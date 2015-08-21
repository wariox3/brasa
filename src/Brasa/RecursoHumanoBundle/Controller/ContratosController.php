<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuContratoType;
class ContratosController extends Controller
{
    var $fechaDesdeInicia;
    var $fechaHastaInicia;
    
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
        
        $arContratos = $paginator->paginate($em->createQuery($session->get('dqlContratoLista')), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:lista.html.twig', array('arContratos' => $arContratos, 'form' => $form->createView()));
    }    
    
    public function detalleAction($codigoContrato) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $mensaje = 0;
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if($form->isValid()) {           
            if($form->get('BtnImprimir')->isClicked()) {
                if ($arContrato->getCodigoContratoTipoFk() == 1){
                    $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoContratoObraLabor();
                    $objFormatoContrato->Generar($this, $codigoContrato);
                }
                if ($arContrato->getCodigoContratoTipoFk() == 2){
                    $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoContratoFijo();
                    $objFormatoContrato->Generar($this, $codigoContrato);
                }
                if ($arContrato->getCodigoContratoTipoFk() == 3){
                    $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoContratoIndefinido();
                    $objFormatoContrato->Generar($this, $codigoContrato);
                }
                if ($arContrato->getCodigoContratoTipoFk() == 4){
                    $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoContratoAprendiz();
                    $objFormatoContrato->Generar($this, $codigoContrato);
                }
                if ($arContrato->getCodigoContratoTipoFk() == 5){
                    $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoContratoPracticante();
                    $objFormatoContrato->Generar($this, $codigoContrato);
                }
                
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:detalle.html.twig', array(
                    'arContrato' => $arContrato,
                    'form' => $form->createView()
                    ));
    }    
    
    public function nuevoAction($codigoContrato, $codigoEmpleado) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $intEstado = 0;
        if($codigoContrato != 0) {
            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        } else {
            $arContrato->setFechaDesde(new \DateTime('now'));
            $arContrato->setFechaHasta(new \DateTime('now'));
            $arContrato->setIndefinido(1);
            $arContrato->setEstadoActivo(1);
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
            $douSalarioMinimo = $arConfiguracion->getVrSalario();
            $arContrato->setVrSalario($douSalarioMinimo); //se Parametrizó con configuracion salario minimo
            $douValidarEmpleadoContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->validarEmpleadoContrato($codigoEmpleado);
            if ($douValidarEmpleadoContrato >= 1){
                $objMensaje->Mensaje("error", "El empleado tiene contrato abierto, no se puede generar otro contrato", $this);                
                $intEstado = 1;
            }
            
        }        
        $form = $this->createForm(new RhuContratoType(), $arContrato);
        $form->handleRequest($request);
        if ($form->isValid()) {                  
            $arContrato = $form->getData();
            if($arContrato->getCentroCostoRel()->getFechaUltimoPago() < $arContrato->getFechaDesde()) {
                $arContrato->setFecha(date_create(date('Y-m-d H:i:s')));
                $arContrato->setEmpleadoRel($arEmpleado);  
                $arContrato->setFechaUltimoPago($arContrato->getCentroCostoRel()->getFechaUltimoPago());
                $arContrato->setFechaUltimoPagoCesantias($arContrato->getFechaDesde());
                $arContrato->setFechaUltimoPagoPrimas($arContrato->getFechaDesde());
                $arContrato->setFechaUltimoPagoVacaciones($arContrato->getFechaDesde());
                $em->persist($arContrato);
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
                $douSalarioMinimo = $arConfiguracion->getVrSalario();
                //$douSalarioMinimo = 644350;
                if($codigoContrato == 0 && $arContrato->getVrSalario() <= $douSalarioMinimo * 2) {
                    $arEmpleado->setAuxilioTransporte(1);
                }
                $arEmpleado->setCentroCostoRel($arContrato->getCentroCostoRel());
                $arEmpleado->setTipoTiempoRel($arContrato->getTipoTiempoRel());
                $arEmpleado->setVrSalario($arContrato->getVrSalario());
                $arEmpleado->setFechaContrato($arContrato->getFechaDesde());
                $arEmpleado->setFechaFinalizaContrato($arContrato->getFechaHasta());
                $arEmpleado->setClasificacionRiesgoRel($arContrato->getClasificacionRiesgoRel());
                $arEmpleado->setCargoRel($arContrato->getCargoRel());
                $arEmpleado->setCargoDescripcion($arContrato->getCargoDescripcion());
                $arEmpleado->setTipoPensionRel($arContrato->getTipoPensionRel());
                $em->persist($arEmpleado);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
            } else {
                $objMensaje->Mensaje("error", "La fecha de inicio del contrato debe ser mayor a la ultima fecha de pago del periodo " . $arContrato->getCentroCostoRel()->getFechaUltimoPago()->format('Y-m-d'), $this);
            }                        
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:nuevo.html.twig', array(
            'arContrato' => $arContrato,
            'arEmpleado' => $arEmpleado,            
            'intEstado' => $intEstado,
            'form' => $form->createView()));
    }
    
    public function terminarAction($codigoContrato) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $formContrato = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_contratos_terminar', array('codigoContrato' => $codigoContrato)))
            ->add('fechaTerminacion', 'date', array('label'  => 'Terminacion', 'data' => new \DateTime('now')))                            
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $formContrato->handleRequest($request);        
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);        
        //$arContrato->setFechaHasta(new \DateTime('now'));        
        if ($formContrato->isValid()) {
            $fechaHasta = $formContrato->get('fechaTerminacion')->getData()->format('Y-m-d');                        
            $arContrato->setFechaHasta(date_create($fechaHasta));            
            $arContrato->setIndefinido(0);
            $arContrato->setEstadoActivo(0);
            $arContrato->setEstadoLiquidado(1);
            $em->persist($arContrato);
            //Generar liquidacion
            $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
            $arLiquidacion->setCentroCostoRel($arContrato->getCentroCostoRel());
            $arLiquidacion->setEmpleadoRel($arContrato->getEmpleadoRel());
            $arLiquidacion->setContratoRel($arContrato);
            $arLiquidacion->setFechaDesde($arContrato->getFechaDesde());
            $arLiquidacion->setFechaHasta($arContrato->getFechaHasta());
            $arLiquidacion->setLiquidarCesantias(1);
            $arLiquidacion->setLiquidarPrima(1);
            $arLiquidacion->setLiquidarVacaciones(1);
            $em->persist($arLiquidacion);            
            //Verificar creditos
            $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
            $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arContrato->getCodigoEmpleadoFk());        
            foreach ($arCreditos as $arCredito) {
                $arLiquidacionDeduccion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion();
                $arLiquidacionDeduccion->setCreditoRel($arCredito);
                $arLiquidacionDeduccion->setLiquidacionRel($arLiquidacion);
                $arLiquidacionDeduccion->setVrDeduccion($arCredito->getSaldoTotal());
                $em->persist($arLiquidacionDeduccion);
            }

            $em->flush();            
            return $this->redirect($this->generateUrl('brs_rhu_base_contratos_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:terminar.html.twig', array(
            'arContrato' => $arContrato,
            'formContrato' => $formContrato->createView()
        ));
    }   
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $session->set('dqlContratoLista', $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaDQL(                
                $session->get('filtroIdentificacion'),
                $this->fechaDesdeInicia,
                $this->fechaHastaInicia
                ));  
    }     
    
    private function formularioLista() {
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()                        
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                            
            ->add('fechaDesdeInicia', 'date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                            
            ->add('fechaHastaInicia', 'date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',)) 
            ->getForm();        
        return $form;
    }    
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        if($controles['fechaDesdeInicia']) {            
            $this->fechaDesdeInicia = $controles['fechaDesdeInicia'];            
        }
        if($controles['fechaHastaInicia']) {            
            $this->fechaHastaInicia = $controles['fechaHastaInicia'];            
        }        
        //$session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
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
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'NUMERO')
                    ->setCellValue('E1', 'CENTRO COSTOS')
                    ->setCellValue('F1', 'TIEMPO')
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')                    
                    ->setCellValue('I1', 'SALARIO')
                    ->setCellValue('J1', 'CARGO')
                    ->setCellValue('K1', 'CARGO DESCRIPCION')
                    ->setCellValue('L1', 'CLASIFICACION RIESGO');
        $i = 2;
        $query = $em->createQuery($session->get('dqlContratoLista'));
        //$arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = $query->getResult();
        foreach ($arContratos as $arContrato) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arContrato->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arContrato->getContratoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arContrato->getFecha()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arContrato->getNumero())
                    ->setCellValue('E' . $i, $arContrato->getCentroCostoRel()->getNombre())
                    ->setCellValue('F' . $i, $arContrato->getTipoTiempoRel()->getNombre())
                    ->setCellValue('G' . $i, $arContrato->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('H' . $i, $arContrato->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('I' . $i, $arContrato->getVrSalario())
                    ->setCellValue('J' . $i, $arContrato->getCargoRel()->getNombre())
                    ->setCellValue('K' . $i, $arContrato->getCargoDescripcion())
                    ->setCellValue('L' . $i, $arContrato->getClasificacionRiesgoRel()->getNombre());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('contratos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Contratos.xlsx"');
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
