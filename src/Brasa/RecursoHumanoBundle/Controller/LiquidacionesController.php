<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LiquidacionesController extends Controller
{    
    var $strSqlLista = "";
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
        return $this->render('BrasaRecursoHumanoBundle:Liquidaciones:lista.html.twig', array('arLiquidaciones' => $arLiquidaciones, 'form' => $form->createView()));
    }               
    
    public function detalleAction($codigoLiquidacion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $mensaje = 0;
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnLiquidar', 'submit', array('label'  => 'Liquidar',))
            ->getForm();
        $form->handleRequest($request);
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        if($form->isValid()) {           
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoContrato();
                $objFormatoContrato->Generar($this, $codigoContrato);
            }
            if($form->get('BtnLiquidar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigoLiquidacion);
                return $this->redirect($this->generateUrl('brs_rhu_liquidaciones_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));                                                
            }            
        }
        return $this->render('BrasaRecursoHumanoBundle:Liquidaciones:detalle.html.twig', array(
                    'arLiquidacion' => $arLiquidacion,
                    'form' => $form->createView()
                    ));
    }        
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->listaDQL(                
                "");  
    }     
    
    private function formularioLista() {
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()                        
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                            
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
        $objPHPExcel->getProperties()->setCreator("JG Efectivos")
            ->setLastModifiedBy("JG Efectivos")
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
