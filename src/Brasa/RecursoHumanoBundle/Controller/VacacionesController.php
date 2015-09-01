<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuVacacionType;
use Doctrine\ORM\EntityRepository;

class VacacionesController extends Controller
{    
    var $strSqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoVacacion) {
                        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
                        if ($arVacaciones->getEstadoPagado() == 1 ) {
                            $objMensaje->Mensaje("error", "No se puede Eliminar el registro, por que ya fue pagada!", $this);
                        }
                        else {    
                            $em->remove($arVacaciones);
                            $em->flush();
                        }
                    }
                }
                $this->filtrarLista($form);
                $this->listar();
            }
            
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            /*if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCredito();
                $objFormatoCredito->Generar($this, $this->strSqlLista);
            }*/
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arVacaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                        
        return $this->render('BrasaRecursoHumanoBundle:Base/Vacaciones:lista.html.twig', array(
            'arVacaciones' => $arVacaciones,
            'form' => $form->createView()
            ));
    } 
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->listaVacacionesDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion')
                    );
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            //->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))    
            ->getForm();
        return $form;
    } 
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
    }
    
    public function nuevoAction($codigoCentroCosto, $codigoEmpleado, $codigoVacacion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();       
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);        
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findOneBy(array('codigoEmpleadoFk' => $codigoEmpleado, 'estadoActivo' => 1));
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        if (count($arContrato) <= 0){
            $objMensaje->Mensaje("error", "El empleado no tiene contrato", $this);
        }else{
            if($codigoVacacion != 0) {
                $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
            } else {
                $arVacacion->setFecha(new \DateTime('now'));                             
                $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();                                
                $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta(360, $fechaDesdePeriodo);                                               
                $arVacacion->setFechaDesdePeriodo($fechaDesdePeriodo);                                                
                $arVacacion->setFechaHastaPeriodo($fechaHastaPeriodo);
                $arVacacion->setFechaDesde(new \DateTime('now'));
                $arVacacion->setFechaHasta(new \DateTime('now'));
                $arVacacion->setCentroCostoRel($arEmpleado->getCentroCostoRel());                                                  
                if ($fechaHastaPeriodo > $arContrato->getFechaUltimoPago()){
                    $objMensaje->Mensaje("error", "El empleado no ha cumplido el periodo para disfrutar las vacaciones", $this);
                }
            }
        }     
        $form = $this->createForm(new RhuVacacionType(), $arVacacion);     
        $form->handleRequest($request);
        if ($form->isValid()) {            
            if (count($arContrato) >= 0){
                $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();                                
                $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta(360, $fechaDesdePeriodo);                                               
                if ($fechaHastaPeriodo > $arContrato->getFechaUltimoPago()){
                    $objMensaje->Mensaje("error", "El empleado no ha cumplido el periodo para disfrutar las vacaciones", $this);
                } else {
                    $arVacacion = $form->getData();   
                    $arVacacion->setEmpleadoRel($arEmpleado); 
                    $arVacacion->setFechaDesdePeriodo($fechaDesdePeriodo);
                    $arVacacion->setFechaHastaPeriodo($fechaHastaPeriodo);
                    $em->persist($arVacacion);
                    //Calcular deducciones credito
                    $floVrDeducciones = 0;
                    $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                    $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($codigoEmpleado);
                    foreach ($arCreditos as $arCredito) {
                        $arVacacionCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
                        $arVacacionCredito->setCreditoRel($arCredito);
                        $arVacacionCredito->setVacacionRel($arVacacion);
                        $arVacacionCredito->setVrDeduccion($arCredito->getSaldoTotal());
                        $em->persist($arVacacionCredito);            
                        $floVrDeducciones += $arCredito->getSaldoTotal();
                    }
                    $arContratoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContratoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arContrato->getCodigoContratoPk());                    
                    $arContratoActualizar->setFechaUltimoPagoVacaciones($fechaHastaPeriodo);
                    $em->persist($arContratoActualizar);                     
                    $em->flush();
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($arVacacion->getCodigoVacacionPk());
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";  
                }
            }  
        }                
        $arCreditosPendientes = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditosPendientes = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($codigoEmpleado);
        return $this->render('BrasaRecursoHumanoBundle:Base/Vacaciones:nuevo.html.twig', array(
            'arEmpleado' => $arEmpleado,
            'arCreditosPendientes' => $arCreditosPendientes,
            'form' => $form->createView()));
    }
    
    public function editarDisfrutadasAction($codigoVacacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);        
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_vacaciones_editar_disfrutadas', array('codigoVacacion' => $codigoVacacion)))
            ->add('fechaHasta', 'date', array('label'  => 'Hasta', 'data' => $arVacacion->getFechaHasta()))                            
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);        
        
        if ($form->isValid()) {
            $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoEmpleadoFk' => $arVacacion->getCodigoEmpleadoFk(), 'estadoActivo' => 1));
            foreach ($arContrato as $arContrato) {
                $fechaUltimoPagoNomina = $arContrato->getFechaUltimoPago();
            }
            $fechaHasta = $form->get('fechaHasta')->getData();
            if ($fechaHasta < $fechaUltimoPagoNomina){
                $objMensaje->Mensaje("error", "La fecha a modificar no puede ser menor al último periodo de pago", $this);
                echo "<script>alert(no pe puede);</script>";
                return $this->redirect($this->generateUrl('brs_rhu_vacaciones_detalle', array('codigoVacacion' => $codigoVacacion)));
            }else {
                $arVacacion->setFechaHasta($form->get('fechaHasta')->getData());
                $em->persist($arVacacion);
                $em->flush();            
            return $this->redirect($this->generateUrl('brs_rhu_vacaciones_detalle', array('codigoVacacion' => $codigoVacacion)));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Vacaciones:editarDisfrutadas.html.twig', array(
            'arVacacion' => $arVacacion,
            'form' => $form->createView()
        ));
    } 
    
    public function detalleAction($codigoVacacion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()    
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnLiquidar', 'submit', array('label'  => 'Liquidar',))
            ->add('BtnEliminarDeduccion', 'submit', array('label'  => 'Eliminar deduccion',))
            ->getForm();
        $form->handleRequest($request);
        
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        if($form->isValid()) {                      
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoDetalleVacaciones = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDetalleVacaciones();
                $objFormatoDetalleVacaciones->Generar($this, $codigoVacacion);
            }
            if($form->get('BtnLiquidar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($codigoVacacion);
                return $this->redirect($this->generateUrl('brs_rhu_vacaciones_detalle', array('codigoVacacion' => $codigoVacacion)));                                                
            }     
            if($form->get('BtnEliminarDeduccion')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoVacacionDeduccion) {
                        $arVacacionDeduccion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
                        $arVacacionDeduccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionCredito')->find($codigoVacacionDeduccion);
                        $em->remove($arVacacionDeduccion);                        
                    }
                    $em->flush();
                }                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($codigoVacacion);
                return $this->redirect($this->generateUrl('brs_rhu_vacaciones_detalle', array('codigoVacacion' => $codigoVacacion)));                                                
            }            
        }
        $arVacacionDeducciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
        $arVacacionDeducciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionCredito')->FindBy(array('codigoVacacionFk' => $codigoVacacion));        
        return $this->render('BrasaRecursoHumanoBundle:Base/Vacaciones:detalle.html.twig', array(
                    'arVacaciones' => $arVacaciones,
                    'arVacacionDeducciones' => $arVacacionDeducciones,
                    'form' => $form->createView()
                    ));
    }
    
    public function detalleNuevoAction($codigoVacacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arVacacion->getCodigoEmpleadoFk());
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) { 
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $floVrDeducciones = 0;
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {                    
                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        $arVacacionCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
                        $arVacacionCredito->setCreditoRel($arCreditos);
                        $arVacacionCredito->setVacacionRel($arVacacion);
                        $arVacacionCredito->setVrDeduccion($arCreditos->getSaldoTotal());
                        $em->persist($arVacacionCredito);            
                        $floVrDeducciones += $arCreditos->getSaldoTotal();
            }                    
            $em->flush();                        
            $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($arVacacion->getCodigoVacacionPk());                    
                }                
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Vacaciones:detallenuevo.html.twig', array(
            'arCreditos' => $arCreditos,
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
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
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Centro Costo')
                            ->setCellValue('C1', 'Desde')
                            ->setCellValue('D1', 'Hasta')
                            ->setCellValue('E1', 'Identificación')
                            ->setCellValue('F1', 'Empleado')
                            ->setCellValue('G1', 'Dias')
                            ->setCellValue('H1', 'Vr Vacaciones')
                            ->setCellValue('I1', 'Pagado');

                $i = 2;
                $query = $em->createQuery($this->strSqlLista);
                $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                $arVacaciones = $query->getResult();
                
                foreach ($arVacaciones as $arVacacion) {
                    if ($arVacacion->getEstadoPagado() == 1)
                    {
                        $Estado = "SI";
                    }
                    else
                    {
                        $Estado = "NO"; 
                    }
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arVacacion->getCodigoVacacionPk())
                            ->setCellValue('B' . $i, $arVacacion->getCentroCostoRel()->getNombre())
                            ->setCellValue('C' . $i, $arVacacion->getFechaDesde())
                            ->setCellValue('D' . $i, $arVacacion->getFechaHasta())
                            ->setCellValue('E' . $i, $arVacacion->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('F' . $i, $arVacacion->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('G' . $i, $arVacacion->getDiasVacaciones())
                            ->setCellValue('H' . $i, round($arVacacion->getVrVacacion()))
                            ->setCellValue('I' . $i, $Estado);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Vacaciones');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Vacaciones.xlsx"');
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
