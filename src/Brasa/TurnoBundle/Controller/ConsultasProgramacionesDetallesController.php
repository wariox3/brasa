<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ConsultasProgramacionesDetallesController extends Controller
{
    var $strListaDql = "";
    var $codigoCliente = "";
    var $codigoRecurso = "";
    var $codigoCentroCosto = "";
    var $fechaDesde = "";
    var $fechaHasta = "";
    var $filtrarFecha = "";    
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        if ($form->isValid()) {            
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {                
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));
                if($arCliente) {
                    $this->codigoCliente = $arCliente->getCodigoClientePk();
                }
            } 
            if($arrControles['txtNumeroIdentificacion'] != '') {                
                $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
                if($arRecurso) {
                    $this->codigoRecurso = $arRecurso->getCodigoRecursoPk();
                }
            }       
            $arCentroCosto = $form->get('centroCostoRel')->getData();
            if($arCentroCosto) {
                $this->codigoCentroCosto = $arCentroCosto->getCodigoCentroCostoPk();
            }
            if ($form->get('BtnFiltrar')->isClicked()) {                
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->lista();
                $this->generarExcel();
            }
        }

        $arProgramacionDetalle = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Programacion:detalle.html.twig', array(
            'arProgramacionDetalle' => $arProgramacionDetalle,
            'arCliente' => $arCliente,
            'arRecurso' => $arRecurso,
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->consultaDetalleDql(
                $this->codigoCliente,
                $this->codigoRecurso,
                $this->codigoCentroCosto);
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($this->fechaDesde != "") {
            $strFechaDesde = $this->fechaDesde;
        }
        if($this->fechaHasta != "") {
            $strFechaDesde = $this->fechaHasta;
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        
        $arrayPropiedadesCentroCosto = array(
                'class' => 'BrasaTurnoBundle:TurCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($this->codigoCentroCosto != "") {
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaTurnoBundle:TurCentroCosto", $this->codigoCentroCosto);
        }        
        $form = $this->createFormBuilder()  
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMMMdd', 'data' => $dateFechaDesde)) 
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMMMdd', 'data' => $dateFechaHasta))  
            ->add('filtrarFecha', 'checkbox', array('required'  => false))                                 
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)                
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
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
        for($col = 'A'; $col !== 'AN'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }                   
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'PROG')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'PUESTO')
                    ->setCellValue('F1', 'RECURSO')
                    ->setCellValue('G1', 'TIPO')
                    ->setCellValue('H1', 'C.COSTO')
                    ->setCellValue('I1', 'D1')
                    ->setCellValue('J1', 'D2')
                    ->setCellValue('K1', 'D3')
                    ->setCellValue('L1', 'D4')
                    ->setCellValue('M1', 'D5')
                    ->setCellValue('N1', 'D6')
                    ->setCellValue('O1', 'D7')
                    ->setCellValue('P1', 'D8')
                    ->setCellValue('Q1', 'D9')
                    ->setCellValue('R1', 'D10')
                    ->setCellValue('S1', 'D11')
                    ->setCellValue('T1', 'D12')
                    ->setCellValue('U1', 'D13')
                    ->setCellValue('V1', 'D14')
                    ->setCellValue('W1', 'D15')
                    ->setCellValue('X1', 'D16')
                    ->setCellValue('Y1', 'D17')
                    ->setCellValue('Z1', 'D18')
                    ->setCellValue('AA1', 'D19')
                    ->setCellValue('AB1', 'D20')
                    ->setCellValue('AC1', 'D21')
                    ->setCellValue('AD1', 'D22')
                    ->setCellValue('AE1', 'D23')
                    ->setCellValue('AF1', 'D24')
                    ->setCellValue('AG1', 'D25')
                    ->setCellValue('AH1', 'D26')
                    ->setCellValue('AI1', 'D27')
                    ->setCellValue('AJ1', 'D28')
                    ->setCellValue('AK1', 'D29')
                    ->setCellValue('AL1', 'D30')
                    ->setCellValue('AM1', 'D31');
        
        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalles = $query->getResult();
        foreach ($arProgramacionDetalles as $arProgramacionDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacionDetalle->getCodigoProgramacionDetallePk())
                    ->setCellValue('B' . $i, $arProgramacionDetalle->getProgramacionRel()->getCodigoProgramacionPk())
                    ->setCellValue('C' . $i, $arProgramacionDetalle->getProgramacionRel()->getFecha()->format('Y-m'))
                    ->setCellValue('D' . $i, $arProgramacionDetalle->getProgramacionRel()->getClienteRel()->getNombreCorto())                                        
                    ->setCellValue('I' . $i, $arProgramacionDetalle->getDia1())
                    ->setCellValue('J' . $i, $arProgramacionDetalle->getDia2())
                    ->setCellValue('K' . $i, $arProgramacionDetalle->getDia3())
                    ->setCellValue('L' . $i, $arProgramacionDetalle->getDia4())
                    ->setCellValue('M' . $i, $arProgramacionDetalle->getDia5())
                    ->setCellValue('N' . $i, $arProgramacionDetalle->getDia6())
                    ->setCellValue('O' . $i, $arProgramacionDetalle->getDia7())
                    ->setCellValue('P' . $i, $arProgramacionDetalle->getDia8())
                    ->setCellValue('Q' . $i, $arProgramacionDetalle->getDia9())
                    ->setCellValue('R' . $i, $arProgramacionDetalle->getDia10())
                    ->setCellValue('S' . $i, $arProgramacionDetalle->getDia11())
                    ->setCellValue('T' . $i, $arProgramacionDetalle->getDia12())
                    ->setCellValue('U' . $i, $arProgramacionDetalle->getDia13())
                    ->setCellValue('V' . $i, $arProgramacionDetalle->getDia14())
                    ->setCellValue('W' . $i, $arProgramacionDetalle->getDia15())
                    ->setCellValue('X' . $i, $arProgramacionDetalle->getDia16())
                    ->setCellValue('Y' . $i, $arProgramacionDetalle->getDia17())
                    ->setCellValue('Z' . $i, $arProgramacionDetalle->getDia18())
                    ->setCellValue('AA' . $i, $arProgramacionDetalle->getDia19())
                    ->setCellValue('AB' . $i, $arProgramacionDetalle->getDia20())
                    ->setCellValue('AC' . $i, $arProgramacionDetalle->getDia21())
                    ->setCellValue('AD' . $i, $arProgramacionDetalle->getDia22())
                    ->setCellValue('AE' . $i, $arProgramacionDetalle->getDia23())
                    ->setCellValue('AF' . $i, $arProgramacionDetalle->getDia24())
                    ->setCellValue('AG' . $i, $arProgramacionDetalle->getDia25())
                    ->setCellValue('AH' . $i, $arProgramacionDetalle->getDia26())
                    ->setCellValue('AI' . $i, $arProgramacionDetalle->getDia27())
                    ->setCellValue('AJ' . $i, $arProgramacionDetalle->getDia28())
                    ->setCellValue('AK' . $i, $arProgramacionDetalle->getDia29())
                    ->setCellValue('AL' . $i, $arProgramacionDetalle->getDia30())
                    ->setCellValue('AM' . $i, $arProgramacionDetalle->getDia31());  
            
            if($arProgramacionDetalle->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('E' . $i, $arProgramacionDetalle->getPuestoRel()->getNombre());
            }
            if($arProgramacionDetalle->getRecursoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F' . $i, $arProgramacionDetalle->getRecursoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arProgramacionDetalle->getRecursoRel()->getRecursoTipoRel()->getNombre())                
                    ->setCellValue('H' . $i, $arProgramacionDetalle->getRecursoRel()->getCentroCostoRel()->getNombre());
            }
            
            $i++;
        }
        $intNum = count($arProgramacionDetalles);
        $intNum += 1;                
        //$objPHPExcel->getActiveSheet()->getStyle('A1:AL1')->getFont()->setBold(true);        
        
        //$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        
        $objPHPExcel->getActiveSheet()->setTitle('ProgramacionDetalle');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ProgramacionDetalle.xlsx"');
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