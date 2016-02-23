<?php

namespace Brasa\TurnoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurPlantillaType;

class BasePlantillaController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurPlantilla')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_lista'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }

        $arPlantillas = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Plantilla:lista.html.twig', array(
                    'arPlantillas' => $arPlantillas,
                    'form' => $form->createView()));
    }

    public function nuevoAction($codigoPlantilla = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPlantilla = new \Brasa\TurnoBundle\Entity\TurPlantilla();
        if ($codigoPlantilla != 0) {
            $arPlantilla = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->find($codigoPlantilla);
        }
        $form = $this->createForm(new TurPlantillaType, $arPlantilla);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPlantilla = $form->getData();
            $arUsuario = $this->getUser();
            $arPlantilla->setUsuario($arUsuario->getUserName());
            $em->persist($arPlantilla);
            $em->flush();

            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_nuevo', array('codigoPlantilla' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_lista'));
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Plantilla:nuevo.html.twig', array(
                    'arPlantilla' => $arPlantilla,
                    'form' => $form->createView()));
    }

    public function detalleAction($codigoPlantilla) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arPlantilla = new \Brasa\TurnoBundle\Entity\TurPlantilla();
        $arPlantilla = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->find($codigoPlantilla);
        $form = $this->formularioDetalle($arPlantilla);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrControles = $request->request->All();
            if ($form->get('BtnAutorizar')->isClicked()) {
                if ($arPlantilla->getEstadoAutorizado() == 0) {
                    if ($em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->numeroRegistros($codigoPlantilla) > 0) {
                        $arPlantilla->setEstadoAutorizado(1);
                        $em->persist($arPlantilla);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al examen', $this);
                    }
                }
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
            }
            if ($form->get('BtnDesAutorizar')->isClicked()) {
                if ($arPlantilla->getEstadoAutorizado() == 1) {
                    $arPlantilla->setEstadoAutorizado(0);
                    $em->persist($arPlantilla);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
                }
            }
            if ($form->get('BtnImprimir')->isClicked()) {
                if ($arPlantilla->getEstadoAutorizado() == 1) {
                    $objExamen = new \Brasa\TurnoBundle\Formatos\FormatoExamen();
                    $objExamen->Generar($this, $codigoPlantilla);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una orden de examen sin estar autorizada", $this);
                }
            }
            if ($form->get('BtnDetalleEliminar')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->eliminarDetalles($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
            }
            if ($form->get('BtnDetalleActualizar')->isClicked()) {
                $this->actualizarDetalle($arrControles);
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
            }
            if ($form->get('BtnDetalleNuevo')->isClicked()) {
                $this->actualizarDetalle($arrControles);
                $arPlantillaDetalleNuevo = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
                $arPlantillaDetalleNuevo->setPlantillaRel($arPlantilla);
                $em->persist($arPlantillaDetalleNuevo);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $codigoPlantilla)));
            }
        }

        $arPlantillaDetalle = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
        $arPlantillaDetalle = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array('codigoPlantillaFk' => $codigoPlantilla));
        return $this->render('BrasaTurnoBundle:Base/Plantilla:detalle.html.twig', array(
                    'arPlantilla' => $arPlantilla,
                    'arPlantillaDetalle' => $arPlantillaDetalle,
                    'form' => $form->createView()
        ));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->listaDQL(
                $this->strNombre, $this->strCodigo
        );
    }

    private function filtrar($form) {
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->lista();
    }

    private function formularioFiltro() {
        $form = $this->createFormBuilder()
                ->add('TxtNombre', 'text', array('label' => 'Nombre', 'data' => $this->strNombre))
                ->add('TxtCodigo', 'text', array('label' => 'Codigo', 'data' => $this->strCodigo))
                ->add('BtnEliminar', 'submit', array('label' => 'Eliminar',))
                ->add('BtnExcel', 'submit', array('label' => 'Excel',))
                ->add('BtnFiltrar', 'submit', array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleNuevo = array('label' => 'Nuevo', 'disabled' => false);
        if ($ar->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonDetalleNuevo['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }

        $form = $this->createFormBuilder()
                ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)
                ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
                ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
                ->add('BtnDetalleNuevo', 'submit', $arrBotonDetalleNuevo)
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
                ->setLastModifiedBy("EMPRESA")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'NOMBRE');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arPlantillas = new \Brasa\TurnoBundle\Entity\TurPlantilla();
        $arPlantillas = $query->getResult();

        foreach ($arPlantillas as $arPlantilla) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPlantilla->getCodigoPlantillaPk())
                    ->setCellValue('B' . $i, $arPlantilla->getNombreCorto());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Plantilla');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Plantillas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    private function actualizarDetalle($arrControles) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arPlantillaDetalle = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
                $arPlantillaDetalle = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->find($intCodigo);
                if ($arrControles['TxtPosicion' . $intCodigo] != '') {
                    $arPlantillaDetalle->setPosicion($arrControles['TxtPosicion' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setPosicion(0);
                }
                if ($arrControles['TxtDia1' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia1($arrControles['TxtDia1' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia1(null);
                }
                if ($arrControles['TxtDia2' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia2($arrControles['TxtDia2' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia2(null);
                }
                if ($arrControles['TxtDia3' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia3($arrControles['TxtDia3' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia3(null);
                }
                if ($arrControles['TxtDia4' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia4($arrControles['TxtDia4' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia4(null);
                }
                if ($arrControles['TxtDia5' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia5($arrControles['TxtDia5' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia5(null);
                }
                if ($arrControles['TxtDia6' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia6($arrControles['TxtDia6' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia6(null);
                }
                if ($arrControles['TxtDia7' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia7($arrControles['TxtDia7' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia7(null);
                }
                if ($arrControles['TxtDia8' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia8($arrControles['TxtDia8' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia8(null);
                }
                if ($arrControles['TxtDia9' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia9($arrControles['TxtDia9' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia9(null);
                }
                if ($arrControles['TxtDia10' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia10($arrControles['TxtDia10' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia10(null);
                }
                if ($arrControles['TxtDia11' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia11($arrControles['TxtDia11' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia11(null);
                }
                if ($arrControles['TxtDia12' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia12($arrControles['TxtDia12' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia12(null);
                }
                if ($arrControles['TxtDia13' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia13($arrControles['TxtDia13' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia13(null);
                }
                if ($arrControles['TxtDia14' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia14($arrControles['TxtDia14' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia14(null);
                }
                if ($arrControles['TxtDia15' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia15($arrControles['TxtDia15' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia15(null);
                }
                if ($arrControles['TxtDia16' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia16($arrControles['TxtDia16' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia16(null);
                }
                if ($arrControles['TxtDia17' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia17($arrControles['TxtDia17' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia17(null);
                }
                if ($arrControles['TxtDia18' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia18($arrControles['TxtDia18' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia18(null);
                }
                if ($arrControles['TxtDia19' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia19($arrControles['TxtDia19' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia19(null);
                }
                if ($arrControles['TxtDia20' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia20($arrControles['TxtDia20' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia20(null);
                }
                if ($arrControles['TxtDia21' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia21($arrControles['TxtDia21' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia21(null);
                }
                if ($arrControles['TxtDia22' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia22($arrControles['TxtDia22' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia22(null);
                }
                if ($arrControles['TxtDia23' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia23($arrControles['TxtDia23' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia23(null);
                }
                if ($arrControles['TxtDia24' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia24($arrControles['TxtDia24' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia24(null);
                }
                if ($arrControles['TxtDia25' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia25($arrControles['TxtDia25' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia25(null);
                }
                if ($arrControles['TxtDia26' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia26($arrControles['TxtDia26' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia26(null);
                }
                if ($arrControles['TxtDia27' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia27($arrControles['TxtDia27' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia27(null);
                }
                if ($arrControles['TxtDia28' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia28($arrControles['TxtDia28' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia28(null);
                }
                if ($arrControles['TxtDia29' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia29($arrControles['TxtDia29' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia29(null);
                }
                if ($arrControles['TxtDia30' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia30($arrControles['TxtDia30' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia30(null);
                }
                if ($arrControles['TxtDia31' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDia31($arrControles['TxtDia31' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDia31(null);
                }
                if ($arrControles['TxtLunes' . $intCodigo] != '') {
                    $arPlantillaDetalle->setLunes($arrControles['TxtLunes' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setLunes(null);
                }
                if ($arrControles['TxtMartes' . $intCodigo] != '') {
                    $arPlantillaDetalle->setMartes($arrControles['TxtMartes' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setMartes(null);
                }
                if ($arrControles['TxtMiercoles' . $intCodigo] != '') {
                    $arPlantillaDetalle->setMiercoles($arrControles['TxtMiercoles' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setMiercoles(null);
                }
                if ($arrControles['TxtJueves' . $intCodigo] != '') {
                    $arPlantillaDetalle->setJueves($arrControles['TxtJueves' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setJueves(null);
                }
                if ($arrControles['TxtViernes' . $intCodigo] != '') {
                    $arPlantillaDetalle->setViernes($arrControles['TxtViernes' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setViernes(null);
                }
                if ($arrControles['TxtSabado' . $intCodigo] != '') {
                    $arPlantillaDetalle->setSabado($arrControles['TxtSabado' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setSabado(null);
                }
                if ($arrControles['TxtDomingo' . $intCodigo] != '') {
                    $arPlantillaDetalle->setDomingo($arrControles['TxtDomingo' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setDomingo(null);
                }
                if ($arrControles['TxtFestivo' . $intCodigo] != '') {
                    $arPlantillaDetalle->setFestivo($arrControles['TxtFestivo' . $intCodigo]);
                } else {
                    $arPlantillaDetalle->setFestivo(null);
                }
                $em->persist($arPlantillaDetalle);
            }            
        }

        $em->flush();
    }
}