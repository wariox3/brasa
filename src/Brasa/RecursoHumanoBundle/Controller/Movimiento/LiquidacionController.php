<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\RecursoHumanoBundle\Form\Type\RhuLiquidacionType;
class LiquidacionController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/movimiento/liquidacion/", name="brs_rhu_movimiento_liquidacion")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
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
        }
        
        $arLiquidaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:lista.html.twig', array('arLiquidaciones' => $arLiquidaciones, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/liquidacion/nuevo/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_nuevo")
     */     
    public function nuevoAction($codigoLiquidacion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();        
        if($codigoLiquidacion != 0) {
            $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        }
        $form = $this->createForm(new RhuLiquidacionType, $arLiquidacion);         
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            
            if($form->get('guardar')->isClicked()) {
                $arLiquidacion = $form->getData();
                $em->persist($arLiquidacion);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));                                                                                               
            }
        }                    
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:nuevo.html.twig', array(
            'arLiquidacion' => $arLiquidacion,            
            'form' => $form->createView()));
    }     
    
    /**
     * @Route("/rhu/movimiento/liquidacion/detalle/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_detalle")
     */    
    public function detalleAction($codigoLiquidacion) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $form = $this->formularioDetalle($arLiquidacion);
        $form->handleRequest($request);

        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                if($arLiquidacion->getEstadoGenerado() == 1) {
                $objFormatoLiquidacion = new \Brasa\RecursoHumanoBundle\Formatos\FormatoLiquidacion();
                $objFormatoLiquidacion->Generar($this, $codigoLiquidacion);
                }
            }
            if($form->get('BtnAutorizar')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 0){
                    $arDotacionPendiente = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->dotacionDevolucion($arLiquidacion->getCodigoEmpleadoFk());
                    $registrosDotacionesPendientes = count($arDotacionPendiente);
                    if ($registrosDotacionesPendientes > 0){
                        $objMensaje->Mensaje("error", "El empleado tiene dotaciones pendientes por entregar, no se puede autorizar la liquidación", $this);
                    }else{
                        if ($arLiquidacion->getEstadoGenerado() == 0){
                            $objMensaje->Mensaje("error", "La liquidacion debe ser liquidada antes de autorizar", $this);
                        } else {
                            $arLiquidacion->setEstadoAutorizado(1);
                            $em->persist($arLiquidacion);
                            $em->flush();
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                        }
                    }                    
                }
                    
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 1){
                    $arLiquidacion->setEstadoAutorizado(0);
                    $em->persist($arLiquidacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                }    
            }            
            if($form->get('BtnLiquidar')->isClicked()) {
                if($arLiquidacion->getEstadoAutorizado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigoLiquidacion);
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                } else {
                    $objMensaje->Mensaje("error", "No puede reliquidar una liquidacion autorizada", $this);
                }

            }
            
            if($form->get('BtnGenerarPago')->isClicked()) {            
                if($arLiquidacion->getEstadoAutorizado() == 1) {
                    
                    $validar = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->pagar($codigoLiquidacion);
                    if ($validar == ''){
                        $arLiquidacion->setEstadoPagoGenerado(1);
                        $em->persist($arLiquidacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                    } else {
                        $objMensaje->Mensaje("error", "Hay saldos en creditos que son inferiores a la deducciones", $this);
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "No esta autorizado, no se puede generar pago", $this);
                }
            }
            
            if($form->get('BtnEliminarAdicional')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 0){
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoLiquidacionAdicional) {
                            $arLiquidacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                            $arLiquidacionAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->find($codigoLiquidacionAdicional);
                            $em->remove($arLiquidacionAdicional);
                        }
                        $em->flush();                        
                    }
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigoLiquidacion);
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                }    
            }
        }
        $arLiquidacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
        $arLiquidacionAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->FindBy(array('codigoLiquidacionFk' => $codigoLiquidacion));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:detalle.html.twig', array(
                    'arLiquidacion' => $arLiquidacion,
                    'arLiquidacionAdicionales' => $arLiquidacionAdicionales,
                    'form' => $form->createView()
                    ));
    }    

    /**
     * @Route("/rhu/movimiento/liquidacion/detalle/descuento/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_detalle_descuento")
     */     
    public function detalleDescuentoAction($codigoLiquidacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $form = $this->createFormBuilder()
            ->add('pagoConceptoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', 2)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))  
            ->add('TxtValor', 'number', array('required' => true))                             
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) { 
            if ($form->get('BtnGuardar')->isClicked()) {
                    $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                    $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                    $arLiquidacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                    $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion); 
                    $arLiquidacionAdicional->setPagoConceptoRel($arPagoConcepto);
                    $arLiquidacionAdicional->setVrDeduccion($form->get('TxtValor')->getData());  
                    $em->persist($arLiquidacionAdicional);                
                    $em->flush();                        
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigoLiquidacion);                    
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                
            }                        
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:detalleDescuentoNuevo.html.twig', array(
            'arLiquidacion' => $arLiquidacion,
            'form' => $form->createView()));
    }      
    
    /**
     * @Route("/rhu/movimiento/liquidacion/detalle/bonificacion/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_detalle_bonificacion")
     */     
    public function detalleBonificacionAction($codigoLiquidacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $arPagoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagoConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('tipoAdicional' => 1));
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) { 
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrControles = $request->request->All();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {                    
                        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($codigo);
                        $valor = 0;
                        if($arrControles['TxtValor'.$codigo] != '') {
                            $valor = $arrControles['TxtValor'.$codigo];                
                        }
                        $arLiquidacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                        $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion);
                        $arLiquidacionAdicional->setVrBonificacion($valor);
                        $arLiquidacionAdicional->setPagoConceptoRel($arPagoConcepto);                                               
                        $em->persist($arLiquidacionAdicional);            
                    }                                                       
                    $em->flush();                        
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigoLiquidacion);                    
                }                
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:detalleBonificacionNuevo.html.twig', array(
            'arPagoConceptos' => $arPagoConceptos,
            'arLiquidacion' => $arLiquidacion,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/movimiento/liquidacion/detalle/credito/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_detalle_credito")
     */     
    public function detalleCreditoAction($codigoLiquidacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arLiquidacion->getCodigoEmpleadoFk());
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) { 
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrControles = $request->request->All();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $floVrDeducciones = 0;
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {                    
                        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        $valor = 0;
                        if($arrControles['TxtValor'.$codigoCredito] != '') {
                            $valor = $arrControles['TxtValor'.$codigoCredito];                
                        }
                        $arLiquidacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                        $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion);
                        $arLiquidacionAdicional->setVrDeduccion($valor);
                        $arLiquidacionAdicional->setPagoConceptoRel($arCredito->getCreditoTipoRel()->getPagoConceptoRel());                                               
                        $arLiquidacionAdicional->setCreditoRel($arCredito);
                        $em->persist($arLiquidacionAdicional);                                                 
                        $floVrDeducciones += $valor;
                    }                                                       
                    $em->flush();                        
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($arLiquidacion->getCodigoLiquidacionPk());                    
                }
                
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:detalleCreditoNuevo.html.twig', array(
            'arCreditos' => $arCreditos,
            'arLiquidacion' => $arLiquidacion,
            'form' => $form->createView()));
    }     
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->listaDql(
               $session->get('filtroIdentificacion'),
               $session->get('filtroGenerado'),
               $session->get('filtroCodigoCentroCosto'));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedadesCentroCosto = array(
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
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)    
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('estadoGenerado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'),'data' => $session->get('filtroGenerado')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonEliminarAdicional = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonLiquidar = array('label' => 'Liquidar', 'disabled' => false);
        $arrBotonGenerarPago = array('label' => 'Generar pago', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarAdicional['disabled'] = true;
            $arrBotonLiquidar['disabled'] = true;
        } else {            
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonGenerarPago['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }
        if($ar->getEstadoPagoGenerado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonGenerarPago['disabled'] = true;
            $arrBotonLiquidar['disabled'] = true;
        }
        $form = $this->createFormBuilder()    
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnLiquidar', 'submit', $arrBotonLiquidar)
                    ->add('BtnEliminarAdicional', 'submit', $arrBotonEliminarAdicional)
                    ->add('BtnGenerarPago', 'submit', $arrBotonGenerarPago)
                    ->getForm();  
        return $form;
    }        
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroGenerado', $controles['estadoGenerado']);
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
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
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'EMPLEADO')
                    ->setCellValue('C1', 'CENTRO COSTOS')
                    ->setCellValue('D1', 'CONTRATO')
                    ->setCellValue('E1', 'FECHA DESDE')
                    ->setCellValue('F1', 'FECHA HASTA')
                    ->setCellValue('G1', 'VR AUX TRANSPORTE')
                    ->setCellValue('H1', 'VR CESANTIAS')
                    ->setCellValue('I1', 'VR INTERESES CESANTIAS')
                    ->setCellValue('J1', 'VR PRIMA')
                    ->setCellValue('K1', 'VR DEDUCCIONES PRIMA')
                    ->setCellValue('L1', 'VR VACACIONES')
                    ->setCellValue('M1', 'DÍAS CESANTIAS')
                    ->setCellValue('N1', 'DÍAS VACACIONES')
                    ->setCellValue('O1', 'DÍAS PRIMA')
                    ->setCellValue('P1', 'FECHA ULTIMO PAGO')
                    ->setCellValue('Q1', 'VR INGRESO BASE PRESTACIONAL')
                    ->setCellValue('R1', 'VR INGRESO BASE PRESTACIONAL TOTAL')
                    ->setCellValue('S1', 'VR BASE PRESTACIONES')
                    ->setCellValue('T1', 'VR BASE PRESTACIONES TOTAL')
                    ->setCellValue('U1', 'VR SALARIO')
                    ->setCellValue('V1', 'VR SALARIO VACACIONES')
                    ->setCellValue('W1', 'FECHA ULTIMA PAGO PRIMAS')
                    ->setCellValue('X1', 'FECHA ULTIMA PAGO VACACIONES')
                    ->setCellValue('Y1', 'FECHA ULTIMA PAGO CESANTIAS')
                    ->setCellValue('Z1', 'VR DEDUCCIONES')
                    ->setCellValue('AA1', 'VR BONIFICACIONES')
                    ->setCellValue('AB1', 'VR TOTAL')
                    ->setCellValue('AC1', 'COMENTARIOS');    
        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arLiquidaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidaciones = $query->getResult();
        foreach ($arLiquidaciones as $arLiquidacion) {
            if ($arLiquidacion->getFechaUltimoPagoPrimas() == null){
                $fechaUltimaPagoPrimas = "SIN FECHA";
            }else{
                $fechaUltimaPagoPrimas = $arLiquidacion->getFechaUltimoPagoPrimas()->format('Y-m-d');
            }
            if ($arLiquidacion->getFechaUltimoPagoVacaciones() == null){
                $fechaUltimaPagoVacaciones = "SIN FECHA";
            }else{
                $fechaUltimaPagoVacaciones = $arLiquidacion->getFechaUltimoPagoVacaciones()->format('Y-m-d');
            }
            if ($arLiquidacion->getFechaUltimoPagoCesantias() == null){
                $fechaUltimaPagoCesantias = "SIN FECHA";
            }else{
                $fechaUltimaPagoCesantias = $arLiquidacion->getFechaUltimoPagoCesantias()->format('Y-m-d');
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arLiquidacion->getCodigoLiquidacionPk())
                    ->setCellValue('B' . $i, $arLiquidacion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arLiquidacion->getCentroCostoRel()->getNombre())
                    ->setCellValue('D' . $i, $arLiquidacion->getCodigoContratoFk())
                    ->setCellValue('E' . $i, $arLiquidacion->getFechaDesde()->format('Y-m-d'))
                    ->setCellValue('F' . $i, $arLiquidacion->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('G' . $i, $arLiquidacion->getVrAuxilioTransporte())
                    ->setCellValue('H' . $i, $arLiquidacion->getVrCesantias())
                    ->setCellValue('I' . $i, $arLiquidacion->getVrInteresesCesantias())
                    ->setCellValue('J' . $i, $arLiquidacion->getVrPrima())
                    ->setCellValue('K' . $i, $arLiquidacion->getVrDeduccionPrima())
                    ->setCellValue('L' . $i, $arLiquidacion->getVrVacaciones())
                    ->setCellValue('M' . $i, $arLiquidacion->getDiasCesantias())
                    ->setCellValue('N' . $i, $arLiquidacion->getDiasVacaciones())
                    ->setCellValue('O' . $i, $arLiquidacion->getDiasPrimas())
                    ->setCellValue('P' . $i, $arLiquidacion->getFechaUltimoPago())
                    ->setCellValue('Q' . $i, $arLiquidacion->getVrIngresoBasePrestacion())
                    ->setCellValue('R' . $i, $arLiquidacion->getVrIngresoBasePrestacionTotal())
                    ->setCellValue('S' . $i, $arLiquidacion->getVrBasePrestaciones())
                    ->setCellValue('T' . $i, $arLiquidacion->getVrBasePrestacionesTotal())
                    ->setCellValue('U' . $i, $arLiquidacion->getVrSalario())
                    ->setCellValue('V' . $i, $arLiquidacion->getVrSalarioVacaciones())
                    ->setCellValue('W' . $i, $fechaUltimaPagoPrimas)
                    ->setCellValue('X' . $i, $fechaUltimaPagoVacaciones)
                    ->setCellValue('Y' . $i, $fechaUltimaPagoCesantias)
                    ->setCellValue('Z' . $i, $arLiquidacion->getVrDeducciones())
                    ->setCellValue('AA' . $i, $arLiquidacion->getVrBonificaciones())
                    ->setCellValue('AB' . $i, $arLiquidacion->getVrTotal())
                    ->setCellValue('AC' . $i, $arLiquidacion->getComentarios());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Liquidaciones');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Liquidaciones.xlsx"');
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
