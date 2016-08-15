<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;


class PagoDetalleController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    /**
     * @Route("/rhu/consulta/pago/detalle", name="brs_rhu_consulta_pago_detalle")
     */    
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
                $this->filtrarLista($form, $request);
                $this->listar();
                $this->generarExcel();
            }           
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            }

        }
        $arPagosDetalle = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/PagoDetalle:PagoDetalle.html.twig', array(
            'arPagosDetalle' => $arPagosDetalle,
            'form' => $form->createView()
            ));
    }     
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDetalleDql(
                    $this->intNumero,
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroCodigoPagoTipo'),
                    $strFechaDesde = $session->get('filtroDesde'),
                    $strFechaHasta = $session->get('filtroHasta'),
                    $session->get('filtroCodigoPagoConcepto')
                    );
    }  

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();        
        $session = $this->get('session');
        $arrayPropiedadesPagoConcepto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pc')
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoPagoConcepto')) {
            $arrayPropiedadesPagoConcepto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $session->get('filtroCodigoPagoConcepto'));
        }
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
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroDesde') != "") {
            $strFechaDesde = $session->get('filtroDesde');
        }
        if($session->get('filtroHasta') != "") {
            $strFechaHasta = $session->get('filtroHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)
            ->add('pagoConceptoRel', 'entity', $arrayPropiedadesPagoConcepto)
            ->add('pagoTipoRel', 'entity', $arrayPropiedadesTipo)
            //->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))    
            //->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))    
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                            
            ->add('TxtNumero', 'text', array('label'  => 'Numero','data' => $session->get('filtroPagoNumero')))                                                   
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                                            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();        
        return $form;
    }

    private function filtrarLista($form, Request $request) {
        $session = $this->get('session');        
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);                
        $session->set('filtroCodigoPagoTipo', $controles['pagoTipoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $this->intNumero = $form->get('TxtNumero')->getData();
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroCodigoPagoConcepto', $controles['pagoConceptoRel']);
    }

    private function generarExcel() {
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
                    ->setCellValue('A1', 'IDENTIFICACIÓN')
                    ->setCellValue('B1', 'EMPLEADO')
                    ->setCellValue('C1', 'CONCEPTO PAGO')
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'FECHA DESDE')
                    ->setCellValue('F1', 'FECHA HASTA')
                    ->setCellValue('G1', 'VR PAGO')
                    ->setCellValue('H1', 'VR HORA')
                    ->setCellValue('I1', 'VR DÍA')
                    ->setCellValue('J1', 'HORAS')
                    ->setCellValue('K1', 'DÍAS')
                    ->setCellValue('L1', '% APLICADO')
                    ->setCellValue('M1', 'VR IBC')    
                    ->setCellValue('N1', 'VR IBP')
                    ->setCellValue('O1', 'CÓDIGO PROGRAMACION')
                    ->setCellValue('P1', 'CÓDIGO CRÉDITO');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPagosDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagosDetalle = $query->getResult();
        
        foreach ($arPagosDetalle as $arPagoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('B' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arPagoDetalle->getPagoConceptoRel()->getNombre())
                    ->setCellValue('D' . $i, $arPagoDetalle->getPagoRel()->getCentroCostoRel()->getNombre())
                    ->setCellValue('E' . $i, $arPagoDetalle->getPagoRel()->getFechaDesdePago()->format('Y-m-d'))
                    ->setCellValue('F' . $i, $arPagoDetalle->getPagoRel()->getFechaHastaPago()->format('Y-m-d'))
                    ->setCellValue('G' . $i, round($arPagoDetalle->getVrPago()))
                    ->setCellValue('H' . $i, round($arPagoDetalle->getVrHora()))
                    ->setCellValue('I' . $i, round($arPagoDetalle->getVrDia()))
                    ->setCellValue('J' . $i, $arPagoDetalle->getNumeroHoras())
                    ->setCellValue('K' . $i, $arPagoDetalle->getNumeroDias())
                    ->setCellValue('L' . $i, $arPagoDetalle->getPorcentajeAplicado())
                    ->setCellValue('M' . $i, round($arPagoDetalle->getVrIngresoBaseCotizacion()))
                    ->setCellValue('N' . $i, round($arPagoDetalle->getVrIngresoBasePrestacion()))
                    ->setCellValue('O' . $i, $arPagoDetalle->getCodigoProgramacionPagoDetalleFk())
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
