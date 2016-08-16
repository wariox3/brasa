<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class PagosController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();  
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 2, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnExcelDetalle')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
                $this->generarExcelDetalle();
            } 
            if($form->get('BtnExcelResumen')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
                $this->generarExcelResumen();
            }            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            } 
            
            if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
                $objFormatoPagos = new \Brasa\RecursoHumanoBundle\Formatos\FormatoListaPagos();
                $objFormatoPagos->Generar($this, $this->strDqlLista);
            }
        }       
                
        $arPagos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Pagos:lista.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()));
    }       
    
    public function detalleAction($codigoPago, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');        
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDql($codigoPago);
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();        
        $arPagoDetalles = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 50);                                       
        $arPagoDetallesSede = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede();
        $arPagoDetallesSede = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalleSede')->findBy(array('codigoPagoFk' => $codigoPago));        
        $form = $this->createFormBuilder()            
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))           
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPagoMasivo();
                $objFormatoPago->Generar($this, "", "", $codigoPago);
            }
        }        
        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Pagos:detalle.html.twig', array(
                    'arPago' => $arPago,
                    'arPagoDetalles' => $arPagoDetalles,                    
                    'arPagoDetallesSede' => $arPagoDetallesSede,                    
                    'form' => $form->createView()
                    ));
    }    
    
    /**
     * @Route("/rhu/movimiento/pago/resumen/turno/{codigoPago}", name="brs_rhu_movimiento_pago_resumen_turno")
     */    
    public function verResumenTurnosAction($codigoPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);                
        $form = $this->createFormBuilder()                        
            ->getForm(); 
        $form->handleRequest($request);
        if ($form->isValid()) {
        }
       
        $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        if($arPago->getCodigoSoportePagoFk()) {
            $arSoportePago =  $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arPago->getCodigoSoportePagoFk());                                
            if($arSoportePago) {
                $strAnio = $arSoportePago->getFechaDesde()->format('Y');
                $strMes = $arSoportePago->getFechaDesde()->format('m');        
                $arProgramacionDetalle =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $strAnio, 'mes' => $strMes, 'codigoRecursoFk' => $arSoportePago->getCodigoRecursoFk()));                                                    
            }
        }        
        $strAnioMes = $arPago->getFechaDesde()->format('Y/m');
        $arrDiaSemana = array();
        for($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $objFunciones->devuelveDiaSemanaEspaniol($dateFecha);
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana);
        }        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Pagos:verResumenTurno.html.twig', array(                                    
            'arProgramacionDetalle' => $arProgramacionDetalle,  
            'arSoportePago' => $arSoportePago,
            'arPago' => $arPago,
            'arrDiaSemana' => $arrDiaSemana,
            'form' => $form->createView()));
    }    
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();        
        $session = $this->get('session');
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
        $arrayPropiedadesTipo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoPagoTipo')) {
            $arrayPropiedadesTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoTipo", $session->get('filtroCodigoPagoTipo'));
        }
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)
            ->add('pagoTipoRel', 'entity', $arrayPropiedadesTipo)
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))    
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                            
            ->add('TxtNumero', 'text', array('label'  => 'Numero','data' => $session->get('filtroPagoNumero')))                                                   
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                                            
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnExcelDetalle', 'submit', array('label'  => 'Excel detalle',))
            //->add('BtnExcelResumen', 'submit', array('label'  => 'Excel resumen',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->get('session');
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaDql(
                    $this->intNumero,
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroCodigoPagoTipo'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );  
    }         
    
    private function filtrarLista($form, Request $request) {
        $session = $this->get('session');        
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);                
        $session->set('filtroCodigoPagoTipo', $controles['pagoTipoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $this->intNumero = $form->get('TxtNumero')->getData();
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }         
    
    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();        
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
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'TIPO')
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'EMPLEADO')
                    ->setCellValue('F1', 'CENTRO COSTO')
                    ->setCellValue('G1', 'PERIODO PAGO')
                    ->setCellValue('H1', 'FECHA PAGO DESDE')
                    ->setCellValue('I1', 'FECHA PAGO HASTA')
                    ->setCellValue('J1', 'DÍAS PERIODO')
                    ->setCellValue('K1', 'VR SALARIO EMPLEADO')
                    ->setCellValue('L1', 'VR SALARIO PERIODO')
                    ->setCellValue('M1', 'VR AUX TRANSPORTE')
                    ->setCellValue('N1', 'VR EPS')
                    ->setCellValue('O1', 'VR PENSIÓN')
                    ->setCellValue('P1', 'VR DEDUCCIONES')    
                    ->setCellValue('Q1', 'VR DEVENGADO')
                    ->setCellValue('R1', 'VR INGRESO BASE COTIZACIÓN')
                    ->setCellValue('S1', 'VR INGRESO BASE PRESTACIONAL')
                    ->setCellValue('T1', 'VE NETO PAGAR');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getNumero())
                    ->setCellValue('C' . $i, $arPago->getPagoTipoRel()->getNombre())
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getFechaDesde()->format('Y-m-d'). " - " .$arPago->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arPago->getFechaDesdePago()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arPago->getFechaHastaPago()->format('Y-m-d'))
                    ->setCellValue('J' . $i, $arPago->getDiasPeriodo())
                    ->setCellValue('K' . $i, $arPago->getVrSalarioEmpleado())
                    ->setCellValue('L' . $i, $arPago->getVrSalarioPeriodo())
                    ->setCellValue('M' . $i, $arPago->getVrAuxilioTransporte())
                    ->setCellValue('N' . $i, $arPago->getVrEps())
                    ->setCellValue('O' . $i, $arPago->getVrPension())
                    ->setCellValue('P' . $i, $arPago->getVrDeducciones())
                    ->setCellValue('Q' . $i, $arPago->getVrDevengado())
                    ->setCellValue('R' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('S' . $i, $arPago->getVrIngresoBasePrestacion())
                    ->setCellValue('T' . $i, $arPago->getVrNeto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('pagos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pagos.xlsx"');
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
    
    private function generarExcelResumen() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();        
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
        for($col = 'A'; $col !== 'G'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }     
        for($col = 'D'; $col !== 'G'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CONCEPTO')
                    ->setCellValue('C1', 'HORAS')
                    ->setCellValue('D1', 'DEVENGADO')
                    ->setCellValue('E1', 'DEDUCCION')
                    ->setCellValue('F1', 'NETO');

        $i = 2;
        $strSql = "SELECT  
                            codigo_pago_concepto_fk, pagoConcepto, operacion, sum(numero_horas) as numero_horas, sum(vr_pago) as vr_pago 
                    FROM
                            sql_rhu_pago_detalle  
                    GROUP BY codigo_pago_concepto_fk, operacion "; 
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $resultados = $statement->fetchAll();
        foreach($resultados as $detalle) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $detalle['codigo_pago_concepto_fk'])
                    ->setCellValue('B' . $i, $detalle['pagoConcepto'])
                    ->setCellValue('C' . $i, $detalle['numero_horas']);
            if($detalle['operacion'] == "-1") {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, $detalle['vr_pago']);                
            }
            if($detalle['operacion'] == "1") {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, $detalle['vr_pago']);                                
            }            
            $i++;
        }
        /*$query = $em->createQuery($this->strDqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk());
            $i++;
        }
         * 
         */

        $objPHPExcel->getActiveSheet()->setTitle('pagosResumen');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pagos.xlsx"');
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

    private function generarExcelDetalle() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager(); 
        $session = $this->get('session');
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        for($col = 'A'; $col !== 'O'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
                } 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'NUMERO')
                    ->setCellValue('B1', 'CODIGO')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CODIGO')
                    ->setCellValue('F1', 'CONCEPTO')
                    ->setCellValue('G1', 'CENTRO COSTO')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')
                    ->setCellValue('J1', 'VR PAGO')
                    ->setCellValue('K1', 'HORAS')
                    ->setCellValue('L1', 'DÍAS')
                    ->setCellValue('M1', '%')
                    ->setCellValue('N1', 'VR IBC')    
                    ->setCellValue('O1', 'VR IBP')
                    ->setCellValue('P1', 'N. CRED');

        $i = 2;
        /*$query = $em->createQuery($this->strDqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();*/
        $arPagosDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagosDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDetalleDql(
                    $this->intNumero,
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroCodigoPagoTipo'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta'),
                    ""
                );
        
        $arPagosDetalle = $em->createQuery($arPagosDetalle);
        $arPagosDetalle = $arPagosDetalle->getResult();
        foreach ($arPagosDetalle as $arPagoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoDetalle->getPagoRel()->getNumero())
                    ->setCellValue('B' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoEmpleadoPk())
                    ->setCellValue('C' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arPagoDetalle->getCodigoPagoConceptoFk())
                    ->setCellValue('F' . $i, $arPagoDetalle->getPagoConceptoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPagoDetalle->getPagoRel()->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arPagoDetalle->getPagoRel()->getFechaDesdePago()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arPagoDetalle->getPagoRel()->getFechaHastaPago()->format('Y-m-d'))
                    ->setCellValue('J' . $i, round($arPagoDetalle->getVrPagoOperado()))
                    ->setCellValue('K' . $i, $arPagoDetalle->getNumeroHoras())
                    ->setCellValue('L' . $i, $arPagoDetalle->getNumeroDias())
                    ->setCellValue('M' . $i, $arPagoDetalle->getPorcentajeAplicado())
                    ->setCellValue('N' . $i, round($arPagoDetalle->getVrIngresoBaseCotizacion()))
                    ->setCellValue('O' . $i, round($arPagoDetalle->getVrIngresoBasePrestacion()))
                    ->setCellValue('P' . $i, $arPagoDetalle->getCodigoCreditoFk());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('pagosDetalle');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pagos.xlsx"');
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
