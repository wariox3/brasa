<?php
namespace Brasa\TurnoBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ProgramacionesDetallesController extends Controller
{
    var $strListaDql = "";
    var $codigoRecurso = "";
    /**
     * @Route("/tur/consulta/programaciones/detalles", name="brs_tur_consulta_programaciones_detalles")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 47)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $this->filtrarFecha = TRUE;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();                
        if ($form->isValid()) {                             
            if ($form->get('BtnFiltrar')->isClicked()) { 
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
        }

        $arProgramacionDetalle = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Programacion:detalle.html.twig', array(
            'arProgramacionDetalle' => $arProgramacionDetalle,                        
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroProgramacionFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroProgramacionFechaDesde');
            $strFechaHasta = $session->get('filtroProgramacionFechaHasta');
        }       
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->consultaDetalleDql(
                $session->get('filtroCodigoCliente'),
                $session->get('filtroCodigoRecurso'),
                $session->get('filtroCodigoCentroCostos'),
                $strFechaDesde,
                $strFechaHasta, 
                $session->get('filtroProgramacionEstadoAutorizado'));                    
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();        
        $session->set('filtroProgramacionCodigo', $form->get('TxtCodigo')->getData());
        $session->set('filtroProgramacionEstadoAutorizado', $form->get('estadoAutorizado')->getData());                  
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $session->set('filtroCodigoRecurso', $form->get('TxtCodigoRecurso')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroProgramacionFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroProgramacionFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroProgramacionFiltrarFecha', $form->get('filtrarFecha')->getData());
    }    
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }       
        $strNombreRecurso = "";
        if($session->get('filtroCodigoRecurso')) {
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($session->get('filtroCodigoRecurso'));
            if($arRecurso) {                
                $strNombreRecurso = $arRecurso->getNombreCorto();
            }  else {
                $session->set('filtroCodigoRecurso', null);
            }          
        }
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroProgramacionFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroProgramacionFechaDesde');
        }
        if($session->get('filtroProgramacionFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroProgramacionFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtCodigoRecurso', 'text', array('label'  => 'Nit','data' => $session->get('filtroCodigoRecurso')))
            ->add('TxtNombreRecurso', 'text', array('label'  => 'NombreCliente','data' => $strNombreRecurso))                                
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $session->get('filtroProgramacionCodigo')))
            ->add('estadoAutorizado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroProgramacionEstadoAutorizado')))                
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', 'checkbox', array('required'  => false, 'data' => $session->get('filtroProgramacionFiltrarFecha')))                             
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }        

    private function generarExcel() {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
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
                    ->setCellValue('H1', 'D1')
                    ->setCellValue('I1', 'D2')
                    ->setCellValue('J1', 'D3')
                    ->setCellValue('K1', 'D4')
                    ->setCellValue('L1', 'D5')
                    ->setCellValue('M1', 'D6')
                    ->setCellValue('N1', 'D7')
                    ->setCellValue('O1', 'D8')
                    ->setCellValue('P1', 'D9')
                    ->setCellValue('Q1', 'D10')
                    ->setCellValue('R1', 'D11')
                    ->setCellValue('S1', 'D12')
                    ->setCellValue('T1', 'D13')
                    ->setCellValue('U1', 'D14')
                    ->setCellValue('V1', 'D15')
                    ->setCellValue('W1', 'D16')
                    ->setCellValue('X1', 'D17')
                    ->setCellValue('Y1', 'D18')
                    ->setCellValue('Z1', 'D19')
                    ->setCellValue('AA1', 'D20')
                    ->setCellValue('AB1', 'D21')
                    ->setCellValue('AC1', 'D22')
                    ->setCellValue('AD1', 'D23')
                    ->setCellValue('AE1', 'D24')
                    ->setCellValue('AF1', 'D25')
                    ->setCellValue('AG1', 'D26')
                    ->setCellValue('AH1', 'D27')
                    ->setCellValue('AI1', 'D28')
                    ->setCellValue('AJ1', 'D29')
                    ->setCellValue('AK1', 'D30')
                    ->setCellValue('AL1', 'D31');
        
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
                    ->setCellValue('H' . $i, $arProgramacionDetalle->getDia1())
                    ->setCellValue('I' . $i, $arProgramacionDetalle->getDia2())
                    ->setCellValue('J' . $i, $arProgramacionDetalle->getDia3())
                    ->setCellValue('K' . $i, $arProgramacionDetalle->getDia4())
                    ->setCellValue('L' . $i, $arProgramacionDetalle->getDia5())
                    ->setCellValue('M' . $i, $arProgramacionDetalle->getDia6())
                    ->setCellValue('N' . $i, $arProgramacionDetalle->getDia7())
                    ->setCellValue('O' . $i, $arProgramacionDetalle->getDia8())
                    ->setCellValue('P' . $i, $arProgramacionDetalle->getDia9())
                    ->setCellValue('Q' . $i, $arProgramacionDetalle->getDia10())
                    ->setCellValue('R' . $i, $arProgramacionDetalle->getDia11())
                    ->setCellValue('S' . $i, $arProgramacionDetalle->getDia12())
                    ->setCellValue('T' . $i, $arProgramacionDetalle->getDia13())
                    ->setCellValue('U' . $i, $arProgramacionDetalle->getDia14())
                    ->setCellValue('V' . $i, $arProgramacionDetalle->getDia15())
                    ->setCellValue('W' . $i, $arProgramacionDetalle->getDia16())
                    ->setCellValue('X' . $i, $arProgramacionDetalle->getDia17())
                    ->setCellValue('Y' . $i, $arProgramacionDetalle->getDia18())
                    ->setCellValue('Z' . $i, $arProgramacionDetalle->getDia19())
                    ->setCellValue('AA' . $i, $arProgramacionDetalle->getDia20())
                    ->setCellValue('AB' . $i, $arProgramacionDetalle->getDia21())
                    ->setCellValue('AC' . $i, $arProgramacionDetalle->getDia22())
                    ->setCellValue('AD' . $i, $arProgramacionDetalle->getDia23())
                    ->setCellValue('AE' . $i, $arProgramacionDetalle->getDia24())
                    ->setCellValue('AF' . $i, $arProgramacionDetalle->getDia25())
                    ->setCellValue('AG' . $i, $arProgramacionDetalle->getDia26())
                    ->setCellValue('AH' . $i, $arProgramacionDetalle->getDia27())
                    ->setCellValue('AI' . $i, $arProgramacionDetalle->getDia28())
                    ->setCellValue('AJ' . $i, $arProgramacionDetalle->getDia29())
                    ->setCellValue('AK' . $i, $arProgramacionDetalle->getDia30())
                    ->setCellValue('AL' . $i, $arProgramacionDetalle->getDia31());  
            
            if($arProgramacionDetalle->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('E' . $i, $arProgramacionDetalle->getPuestoRel()->getNombre());
            }
            if($arProgramacionDetalle->getRecursoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F' . $i, $arProgramacionDetalle->getRecursoRel()->getNombreCorto());
            }
            if($arProgramacionDetalle->getRecursoRel()->getRecursoTipoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('G' . $i, $arProgramacionDetalle->getRecursoRel()->getRecursoTipoRel()->getNombre());
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