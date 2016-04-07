<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCapacitacionType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCapacitacionDetalleType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCapacitacionNotaType;
use Doctrine\ORM\EntityRepository;
class CapacitacionesController extends Controller
{
    var $strDqlLista = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
            
            if($form->get('BtnEliminar')->isClicked()) {                           
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCapacitacion) {
                        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);                        
                        $em->remove($arCapacitacion);
                        $em->flush();
                    }
                }                
            }            
            
        }

        $arCapacitaciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:lista.html.twig', array(
            'arCapacitaciones' => $arCapacitaciones, 
            'form' => $form->createView()));
    }

    public function detalleAction($codigoCapacitacion) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();        
        $arCapacitacion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);        
        $form = $this->formularioDetalle($arCapacitacion);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 1){
                    $objFormato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCapacitacion();
                    $objFormato->Generar($this, $codigoCapacitacion);
                }    
            }
            if($form->get('BtnImprimirNotas')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 1){
                    $objFormato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCapacitacionNotas();
                    $objFormato->Generar($this, $codigoCapacitacion);
                }    
            }            
            if($form->get('BtnAutorizar')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    $arCapacitacion->setEstadoAutorizado(1);
                    $em->persist($arCapacitacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));           
                }    
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 1){
                    $arCapacitacion->setEstadoAutorizado(0);
                    $em->persist($arCapacitacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));           
                }    
            }            
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoCapacitacionDetalle) {
                            $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                            $arCapacitacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->find($codigoCapacitacionDetalle);                        
                            $em->remove($arCapacitacionDetalle);                        
                        }
                        $em->flush();                    
                    } 
                    return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));           
                }    
            }  
            if($form->get('BtnEliminarNota')->isClicked()) {
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    $arrSeleccionados = $request->request->get('ChkSeleccionarNota');                                                   
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoCapacitacionNota) {
                            $arCapacitacionNota = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionNota();
                            $arCapacitacionNota = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionNota')->find($codigoCapacitacionNota);                        
                            $em->remove($arCapacitacionNota);                        
                        }
                        $em->flush();                    
                    } 
                    return $this->redirect($this->generateUrl('brs_rhu_capacitacion_detalle', array('codigoCapacitacion' => $codigoCapacitacion)));           
                }    
            }             
        }
        $arCapacitacionesDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
        $arCapacitacionesDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->findBy(array('codigoCapacitacionFk' => $codigoCapacitacion));
        $arCapacitacionesDetalles = $paginator->paginate($arCapacitacionesDetalles, $this->get('request')->query->get('page', 1),50);
        $arCapacitacionesNotas = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionNota();
        $arCapacitacionesNotas = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionNota')->findBy(array('codigoCapacitacionFk' => $codigoCapacitacion));
        $arCapacitacionesNotas = $paginator->paginate($arCapacitacionesNotas, $this->get('request')->query->get('page', 1),50);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:detalle.html.twig', array(
                        'arCapacitacionesDetalles' => $arCapacitacionesDetalles,        
                        'arCapacitacionesNotas' => $arCapacitacionesNotas,        
                        'arCapacitacion' => $arCapacitacion,
                        'form' => $form->createView()
                    ));
    }

    public function detalleNuevoAction($codigoCapacitacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
        $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
        $form = $this->createForm(new RhuCapacitacionDetalleType(), $arCapacitacionDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($arCapacitacion->getEstadoAutorizado() == 0){
                $arCapacitacionDetalle = $form->getData();
                $arCapacitacionDetalle->setCapacitacionRel($arCapacitacion);
                $em->persist($arCapacitacionDetalle);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }    
        }        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:detalleNuevo.html.twig', array(
            'form' => $form->createView()));
    }    
    
    public function detalleNuevoEmpleadoAction($codigoCapacitacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) { 
            if ($form->get('BtnAgregar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arCapacitacion->getEstadoAutorizado() == 0){
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoEmpleado) {                           
                            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);                                
                            $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                            $arCapacitacionDetalle->setCapacitacionRel($arCapacitacion);
                            $arCapacitacionDetalle->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());                        
                            $arCapacitacionDetalle->setNombreCorto($arEmpleado->getNombreCorto());                        
                            $em->persist($arCapacitacionDetalle);
                        }
                        $em->flush();
                    }
                }    
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionalesConcepto();
        $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('estadoActivo' => 1));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:detalleNuevoEmpleado.html.twig', array(
            'arCapacitacion' => $arCapacitacion,
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()));
    }    
    
    public function detalleNuevoNotaAction($codigoCapacitacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCapacitacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find($codigoCapacitacion);
        $arCapacitacionNota = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionNota();
        $form = $this->createForm(new RhuCapacitacionNotaType(), $arCapacitacionNota);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($arCapacitacion->getEstadoAutorizado() == 0){
                $arCapacitacionDetalle = $form->getData();
                $arCapacitacionDetalle->setCapacitacionRel($arCapacitacion);
                $em->persist($arCapacitacionDetalle);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
              echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";  
            }
        }        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:detalleNuevoNota.html.twig', array(
            'form' => $form->createView()));
    }        
    
    public function nuevoAction($codigoCapacitacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arCapacitacion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();                       
        $arCapacitacion->setFecha(new \DateTime('now'));        
        $form = $this->createForm(new RhuCapacitacionType(), $arCapacitacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($arCapacitacion->getEstadoAutorizado() == 0){
                $arCapacitacion = $form->getData();                
                $em->persist($arCapacitacion);            
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }    
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Capacitaciones:nuevo.html.twig', array(
            'arRequisito' => $arCapacitacion,            
            'form' => $form->createView()));
    }    
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->listaDql();
    }

    private function formularioLista() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir asistencia', 'disabled' => true);                        
        $arrBotonImprimirNotas = array('label' => 'Imprimir notas', 'disabled' => true);                        
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);                      
        $arrBotonEliminarNota = array('label' => 'Eliminar', 'disabled' => false);                      
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarDetalle['disabled'] = true;            
            $arrBotonEliminarNota['disabled'] = true;            
            $arrBotonImprimir['disabled'] = false;
            $arrBotonImprimirNotas['disabled'] = false;
        } else {            
            $arrBotonDesAutorizar['disabled'] = true;
        }                        
        $form = $this->createFormBuilder()    
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)                                         
                    ->add('BtnImprimirNotas', 'submit', $arrBotonImprimirNotas)                                         
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)                                         
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                                                             
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)                                         
                    ->add('BtnEliminarNota', 'submit', $arrBotonEliminarNota)                                         
                    ->getForm();  
        return $form;
    }    
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        if($controles['fechaDesde']) {
            $this->fechaDesdeInicia = $controles['fechaDesde'];
        }
        if($controles['fechaHasta']) {
            $this->fechaHastaInicia = $controles['fechaHasta'];
        }
        //$session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);

        /*$session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroContratoActivo', $form->get('estadoActivo')->getData());
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);*/
    }

    private function generarExcel() {
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
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'FECHA')
                            ->setCellValue('C1', 'TIPO')
                            ->setCellValue('D1', 'TEMA');

                $i = 2;
                $query = $em->createQuery($this->strDqlLista);
                $arCapacitaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
                $arCapacitaciones = $query->getResult();

                foreach ($arCapacitaciones as $arCapacitacion) {                   
                    if ($arCapacitacion->getCodigoCapacitacionTipoFk() == null){
                        $strCapacitacionTipo = "";
                    }else{
                        $strCapacitacionTipo = $arCapacitacion->getCapacitacionTipoRel()->getNombre();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCapacitacion->getCodigoCapacitacionPk())
                            ->setCellValue('B' . $i, $arCapacitacion->getFecha())
                            ->setCellValue('C' . $i, $strCapacitacionTipo)
                            ->setCellValue('D' . $i, $arCapacitacion->getTema());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Capacitaciones');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Capacitaciones.xlsx"');
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
