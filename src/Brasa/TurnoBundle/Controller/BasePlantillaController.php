<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurPlantillaType;
class BasePlantillaController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                //$em->getRepository('BrasaTurnoBundle:TurPlantilla')->eliminarExamen($arrSeleccionados);
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
        if($codigoPlantilla != 0) {
            $arPlantilla = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->find($codigoPlantilla);
        }        
        $form = $this->createForm(new TurPlantillaType, $arPlantilla);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPlantilla = $form->getData();            
            $em->persist($arPlantilla);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_nuevo', array('codigoPlantilla' => 0 )));
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
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arPlantilla->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->numeroRegistros($codigoPlantilla) > 0) {
                        $arPlantilla->setEstadoAutorizado(1);
                        $em->persist($arPlantilla);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoPlantilla)));                                                                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al examen', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoPlantilla)));                                                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arPlantilla->getEstadoAutorizado() == 1) {
                    $arPlantilla->setEstadoAutorizado(0);
                    $em->persist($arPlantilla);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoPlantilla)));                                                
                }
            }
            if ($form->get('BtnAprobar')->isClicked()) {                
                $strRespuesta = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->aprobarExamen($codigoPlantilla);
                if($strRespuesta == ''){
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoPlantilla)));                                                
                }else {
                  $objMensaje->Mensaje('error', $strRespuesta, $this);
                }                 
            }      
            
            if($form->get('BtnImprimir')->isClicked()) {
                if($arPlantilla->getEstadoAutorizado() == 1) {
                    $objExamen = new \Brasa\TurnoBundle\Formatos\FormatoExamen();
                    $objExamen->Generar($this, $codigoPlantilla);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una orden de examen sin estar autorizada", $this);
                }
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurPlantilla')->liquidar($codigoPlantilla);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoPlantilla)));
            }
            if($form->get('BtnAprobarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->aprobarDetallesSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoPlantilla)));
            }
            if($form->get('BtnCerrarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->cerrarDetallesSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoPlantilla)));
            }            
            if ($form->get('BtnActualizarDetalle')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {                
                    if($arrControles['TxtPrecio'.$intCodigo] != "" && $arrControles['TxtPrecio'.$intCodigo] != 0) {
                        $arPlantillaDetalle = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
                        $arPlantillaDetalle = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->find($intCodigo);                                        
                        $floPrecio = $arrControles['TxtPrecio'.$intCodigo];
                        $arPlantillaDetalle->setValidarVencimiento($arrControles['cboValidarVencimiento'.$intCodigo]);
                        $arPlantillaDetalle->setFechaVence(date_create($arrControles['TxtVence'.$intCodigo]));
                        $arPlantillaDetalle->setVrPrecio($floPrecio);
                        $em->persist($arPlantillaDetalle);                        
                    }
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurPlantilla')->liquidar($codigoPlantilla);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoPlantilla)));
            }            
        }

        $arPlantillaDetalle = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
        $arPlantillaDetalle = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array ('codigoPlantillaFk' => $codigoPlantilla));
        return $this->render('BrasaTurnoBundle:Base/Plantilla:detalle.html.twig', array(
                    'arPlantilla' => $arPlantilla,
                    'arPlantillaDetalle' => $arPlantillaDetalle,
                    'form' => $form->createView()
                    ));
    }

    public function detalleNuevoAction($codigoPlantilla, $codigoPlantillaDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arPlantilla = new \Brasa\TurnoBundle\Entity\TurPlantilla();
        $arPlantilla = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->find($codigoPlantilla);
        $arPlantillaDetalle = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
        if($codigoPlantillaDetalle != 0) {
            $arPlantillaDetalle = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->find($codigoPlantillaDetalle);
        }       
        $form = $this->createForm(new TurPlantillaDetalleType, $arPlantillaDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPlantillaDetalle = $form->getData();            
            $arPlantillaDetalle->setProgramacionRel($arPlantilla);
            $em->persist($arPlantillaDetalle);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle_nuevo', array('codigoProgramacion' => $codigoPlantilla, 'codigoProgramacionDetalle' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleNuevo.html.twig', array(
            'arPlantilla' => $arPlantilla,
            'form' => $form->createView()));
    }   
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->listaDQL(
                $this->strNombre,                
                $this->strCodigo   
                ); 
    }

    private function filtrar ($form) {
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->lista();
    }
    
    private function formularioFiltro() {
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->strCodigo))                            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);        
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $form = $this->createFormBuilder()    
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)            
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)    
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)            
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
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
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    

}