<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class UtilidadesSupervigilanciaParafiscalesController extends Controller
{
    var $strDqlLista = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listarCostosGeneral();
                $this->generarExcel();
            }
            if($form->get('BtnGenerar')->isClicked()) {                                 
                $fechaDesde = $form->get('fechaDesde')->getData();
                $fechaHasta = $form->get('fechaHasta')->getData();
                if($fechaDesde != null && $fechaHasta != null) {
                    $strSql = "DELETE FROM rhu_proyeccion WHERE 1";
                    $em->getConnection()->executeQuery($strSql);                
                    $douAuxilioTransporte = 74000;
                    $intDias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($fechaDesde, $fechaHasta);
                    $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('indefinido' => 1));
                    foreach($arContratos as $arContrato) {
                        $floSalarioPromedio = $arContrato->getVrSalarioPago();
                        $floIbc = ($arContrato->getVrSalarioPago() / 30) * $intDias;
                        $douBasePrestaciones = ($floIbc / $intDias) * 30;
                        $douBasePrestacionesTotal = $douBasePrestaciones + $douAuxilioTransporte;
                        $douCesantias = ($douBasePrestacionesTotal * $intDias) / 360;          
                        $floPorcentajeIntereses = (($intDias * 12) / 360)/100;   
                        $douInteresesCesantias = $douCesantias * $floPorcentajeIntereses;
                        $douPrima = ($douBasePrestacionesTotal * $intDias) / 360;                         
                        $douVacaciones = ($floSalarioPromedio * $intDias) / 720;                        
                        $arProyeccion = new \Brasa\RecursoHumanoBundle\Entity\RhuProyeccion();
                        $arProyeccion->setContratoRel($arContrato);
                        $arProyeccion->setEmpleadoRel($arContrato->getEmpleadoRel());
                        $arProyeccion->setVrSalario($arContrato->getVrSalario());
                        $arProyeccion->setVrCesantias($douCesantias);                        
                        $arProyeccion->setVrInteresesCesantias($douInteresesCesantias);
                        $arProyeccion->setVrPrimas($douPrima);
                        $arProyeccion->setVrVacaciones($douVacaciones);
                        $arProyeccion->setDias($intDias);
                        $em->persist($arProyeccion);
                    }                
                    $em->flush();                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_utilidades_proyeccion'));           
            }            
            
            if($form->get('BtnPDF')->isClicked()) {
                $this->filtrarLista($form);
                $this->listarCostosGeneral();
                $objReporteCostos = new \Brasa\RecursoHumanoBundle\Reportes\ReporteCostos();
                $objReporteCostos->Generar($this, $this->strSqlLista);
            }            
            if($form->get('BtnFiltrar')->isClicked()) {
                //$this->filtrarLista($form);
                $this->listar();
            }

        }    
        $arProyecciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);        
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Supervigilancia:parafiscales.html.twig', array(
            'form' => $form->createView()
            ));
    }        
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProyeccion')->listaDql(                                        
                    "",
                    "",
                    ""
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
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDF', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }        

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
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
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'BASICO')
                    ->setCellValue('H1', 'TIEMPO EXTRA')
                    ->setCellValue('I1', 'VALORES ADICIONALES')
                    ->setCellValue('J1', 'AUX. TRANSPORTE')
                    ->setCellValue('K1', 'ARP')
                    ->setCellValue('L1', 'EPS')
                    ->setCellValue('M1', 'PENSION')
                    ->setCellValue('N1', 'CAJA')
                    ->setCellValue('O1', 'ICBF')
                    ->setCellValue('P1', 'SENA')
                    ->setCellValue('Q1', 'CESANTIAS')
                    ->setCellValue('R1', 'VACACIONES')
                    ->setCellValue('S1', 'ADMON')
                    ->setCellValue('T1', 'COSTO')
                    ->setCellValue('U1', 'TOTAL')
                    ->setCellValue('W1', 'NETO')
                    ->setCellValue('X1', 'IBC')
                    ->setCellValue('Y1', 'AUX. TRANSPORTE COTIZACION')
                    ->setCellValue('Z1', 'DIAS PERIODO')
                    ->setCellValue('AA1', 'SALARIO PERIODO')
                    ->setCellValue('AB1', 'SALARIO EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('C' . $i, $arPago->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getVrSalario())
                    ->setCellValue('H' . $i, $arPago->getVrAdicionalTiempo())
                    ->setCellValue('I' . $i, $arPago->getVrAdicionalValor())
                    ->setCellValue('J' . $i, $arPago->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arPago->getVrArp())
                    ->setCellValue('L' . $i, $arPago->getVrEps())
                    ->setCellValue('M' . $i, $arPago->getVrPension())
                    ->setCellValue('N' . $i, $arPago->getVrCaja())
                    ->setCellValue('O' . $i, $arPago->getVrIcbf())
                    ->setCellValue('P' . $i, $arPago->getVrSena())
                    ->setCellValue('Q' . $i, $arPago->getVrCesantias())
                    ->setCellValue('R' . $i, $arPago->getVrVacaciones())
                    ->setCellValue('S' . $i, $arPago->getVrAdministracion())
                    ->setCellValue('T' . $i, $arPago->getVrCosto())
                    ->setCellValue('U' . $i, $arPago->getVrTotalCobrar())
                    ->setCellValue('W' . $i, $arPago->getVrNeto())
                    ->setCellValue('X' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('Y' . $i, $arPago->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('Z' . $i, $arPago->getDiasPeriodo())
                    ->setCellValue('AA' . $i, $arPago->getVrSalarioPeriodo())
                    ->setCellValue('AB' . $i, $arPago->getVrSalarioEmpleado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('costos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Costos.xlsx"');
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
