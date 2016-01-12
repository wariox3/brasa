<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurTurnoType;
use Brasa\TurnoBundle\Form\Type\TurTurnoDetalleType;
class BaseTurnoController extends Controller
{
    var $strListaDql = "";
    
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
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurTurno')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_turno_lista'));                                  
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arTurnos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Turno:lista.html.twig', array(
            'arTurnos' => $arTurnos, 
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoTurno = '') {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        if($codigoTurno != '' && $codigoTurno != '0') {
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
        }        
        $form = $this->createForm(new TurTurnoType, $arTurno);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arTurno = $form->getData();            
            $em->persist($arTurno);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_turno_nuevo', array('codigoTurno' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_turno_lista'));
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Turno:nuevo.html.twig', array(
            'arTurno' => $arTurno,
            'form' => $form->createView()));
    }        

    public function detalleAction($codigoTurno) {
        $em = $this->getDoctrine()->getManager(); 
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
        $form = $this->formularioDetalle($arTurno);
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arTurno->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaTurnoBundle:TurTurnoDetalle')->numeroRegistros($codigoTurno) > 0) {
                        $arTurno->setEstadoAutorizado(1);
                        $em->persist($arTurno);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoTurno)));                                                                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al examen', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoTurno)));                                                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arTurno->getEstadoAutorizado() == 1) {
                    $arTurno->setEstadoAutorizado(0);
                    $em->persist($arTurno);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoTurno)));                                                
                }
            }
            if ($form->get('BtnAprobar')->isClicked()) {                
                $strRespuesta = $em->getRepository('BrasaTurnoBundle:TurTurno')->aprobarExamen($codigoTurno);
                if($strRespuesta == ''){
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoTurno)));                                                
                }else {
                  $objMensaje->Mensaje('error', $strRespuesta, $this);
                }                 
            }      
            
            if($form->get('BtnImprimir')->isClicked()) {
                if($arTurno->getEstadoAutorizado() == 1) {
                    $objExamen = new \Brasa\TurnoBundle\Formatos\FormatoExamen();
                    $objExamen->Generar($this, $codigoTurno);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una orden de examen sin estar autorizada", $this);
                }
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurTurnoDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurTurno')->liquidar($codigoTurno);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoTurno)));
            }
            if($form->get('BtnAprobarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurTurnoDetalle')->aprobarDetallesSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoTurno)));
            }
            if($form->get('BtnCerrarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurTurnoDetalle')->cerrarDetallesSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoTurno)));
            }            
            if ($form->get('BtnActualizarDetalle')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {                
                    if($arrControles['TxtPrecio'.$intCodigo] != "" && $arrControles['TxtPrecio'.$intCodigo] != 0) {
                        $arTurnoDetalle = new \Brasa\TurnoBundle\Entity\TurTurnoDetalle();
                        $arTurnoDetalle = $em->getRepository('BrasaTurnoBundle:TurTurnoDetalle')->find($intCodigo);                                        
                        $floPrecio = $arrControles['TxtPrecio'.$intCodigo];
                        $arTurnoDetalle->setValidarVencimiento($arrControles['cboValidarVencimiento'.$intCodigo]);
                        $arTurnoDetalle->setFechaVence(date_create($arrControles['TxtVence'.$intCodigo]));
                        $arTurnoDetalle->setVrPrecio($floPrecio);
                        $em->persist($arTurnoDetalle);                        
                    }
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurTurno')->liquidar($codigoTurno);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoTurno)));
            }            
        }

        $arTurnoDetalle = new \Brasa\TurnoBundle\Entity\TurTurnoDetalle();
        $arTurnoDetalle = $em->getRepository('BrasaTurnoBundle:TurTurnoDetalle')->findBy(array ('codigoTurnoFk' => $codigoTurno));
        return $this->render('BrasaTurnoBundle:Base/Turno:detalle.html.twig', array(
                    'arTurno' => $arTurno,
                    'arTurnoDetalle' => $arTurnoDetalle,
                    'form' => $form->createView()
                    ));
    }

    public function detalleNuevoAction($codigoTurno, $codigoTurnoDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
        $arTurnoDetalle = new \Brasa\TurnoBundle\Entity\TurTurnoDetalle();
        if($codigoTurnoDetalle != 0) {
            $arTurnoDetalle = $em->getRepository('BrasaTurnoBundle:TurTurnoDetalle')->find($codigoTurnoDetalle);
        }       
        $form = $this->createForm(new TurTurnoDetalleType, $arTurnoDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arTurnoDetalle = $form->getData();            
            $arTurnoDetalle->setTurnoRel($arTurno);
            $em->persist($arTurnoDetalle);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_turno_detalle_nuevo', array('codigoTurno' => $codigoTurno, 'codigoTurnoDetalle' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }            
        }
        return $this->render('BrasaTurnoBundle:Base/Turno:detalleNuevo.html.twig', array(
            'arTurno' => $arTurno,
            'form' => $form->createView()));
    }   
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurTurno')->listaDQL();
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest(); 
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()                        
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);                
        $form = $this->createFormBuilder()    
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)            
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
                    ->setCellValue('B1', 'NOMBRE')
                    ->setCellValue('C1', 'H.DESDE')
                    ->setCellValue('D1', 'H.HASTA')
                    ->setCellValue('E1', 'SERVICIO')
                    ->setCellValue('F1', 'PROGRAMACION')
                    ->setCellValue('G1', 'NOVEDAD')
                    ->setCellValue('H1', 'DESCANSO')
                    ->setCellValue('I1', 'HORAS')
                    ->setCellValue('J1', 'H.DIURNAS')
                    ->setCellValue('K1', 'H.NOCTURNAS');

        $i = 2;
        
        $query = $em->createQuery($this->strListaDql);
        $arTurnos = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurnos = $query->getResult();
                
        foreach ($arTurnos as $arTurno) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arTurno->getCodigoTurnoPk())
                    ->setCellValue('B' . $i, $arTurno->getNombre())
                    ->setCellValue('C' . $i, $arTurno->getHoraDesde()->format('H:i'))
                    ->setCellValue('D' . $i, $arTurno->getHoraHasta()->format('H:i'))
                    ->setCellValue('E' . $i, $arTurno->getServicio())
                    ->setCellValue('F' . $i, $arTurno->getProgramacion()*1)
                    ->setCellValue('G' . $i, $arTurno->getNovedad()*1)
                    ->setCellValue('H' . $i, $arTurno->getDescanso()*1)
                    ->setCellValue('I' . $i, $arTurno->getHoras())
                    ->setCellValue('J' . $i, $arTurno->getHorasDiurnas())
                    ->setCellValue('K' . $i, $arTurno->getHorasNocturnas());
                        
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Turnos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Turnos.xlsx"');
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