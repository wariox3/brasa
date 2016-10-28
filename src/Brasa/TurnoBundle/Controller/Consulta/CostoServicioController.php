<?php
namespace Brasa\TurnoBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class CostoServicioController extends Controller
{
    var $strListaDql = "";
    var $strListaDetalleDql = "";
    /**
     * @Route("/tur/consulta/costo/servicio", name="brs_tur_consulta_costo_servicio")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 49)) {
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

        $arCostoServicio = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Costo:servicio.html.twig', array(
            'arCostoServicio' => $arCostoServicio,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/consulta/costo/servicio/ver/detalle/{codigoCostoServicio}", name="brs_tur_consulta_costo_servicio_ver_detalle")
     */    
    public function verDetalleAction($codigoCostoServicio) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioVerDetalle();
        $form->handleRequest($request);
        $arCostoServicio = new \Brasa\TurnoBundle\Entity\TurCostoServicio();
        $arCostoServicio = $em->getRepository('BrasaTurnoBundle:TurCostoServicio')->find($codigoCostoServicio);
        if ($form->isValid()) {                             

        }
        $dql = $em->getRepository('BrasaTurnoBundle:TurCostoRecursoDetalle')->listaDql("", $arCostoServicio->getAnio(), $arCostoServicio->getMes(), $arCostoServicio->getCodigoPedidoDetalleFk());
        $arCostoRecursoDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Costo:verDetalleServicio.html.twig', array(
            'arCostoRecursoDetalle' => $arCostoRecursoDetalle,                        
            'form' => $form->createView()));
    }    
    
    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurCostoServicio')->listaDql(
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroTurMes') 
                );
        $this->strListaDetalleDql =  $em->getRepository('BrasaTurnoBundle:TurCostoRecursoDetalle')->listaConsultaDql(
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroTurMes') 
                );        
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $session->set('filtroTurMes', $form->get('TxtMes')->getData());
        //$session->set('filtroCodigoRecurso', $form->get('TxtCodigoRecurso')->getData());
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
        /*$strNombreRecurso = "";
        if($session->get('filtroCodigoRecurso')) {
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($session->get('filtroCodigoRecurso'));
            if($arRecurso) {
                $strNombreRecurso = $arRecurso->getNombreCorto();
            }  else {
                $session->set('filtroCodigoRecurso', null);
            }
        }*/

        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))
            //->add('TxtCodigoRecurso', 'text', array('label'  => 'Nit','data' => $session->get('filtroCodigoRecurso')))
            ->add('TxtMes', 'text', array('data' => $session->get('filtroTurMes')))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioVerDetalle() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();      
        $form = $this->createFormBuilder()
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
        for($col = 'A'; $col !== 'AZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        for($col = 'J'; $col !== 'AZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'AÑO')
                    ->setCellValue('B1', 'MES')
                    ->setCellValue('C1', 'CLIENTE')
                    ->setCellValue('D1', 'PUESTO')
                    ->setCellValue('E1', 'CONCEPTO')
                    ->setCellValue('F1', 'MODALIDAD')
                    ->setCellValue('G1', 'PERIODO')
                    ->setCellValue('H1', 'DES')
                    ->setCellValue('I1', 'HAS')
                    ->setCellValue('J1', 'DIAS')
                    ->setCellValue('K1', 'H')
                    ->setCellValue('L1', 'H.P')
                    ->setCellValue('M1', 'CANT')
                    ->setCellValue('N1', 'COSTO')
                    ->setCellValue('O1', 'PRECIO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arCostoServicios = new \Brasa\TurnoBundle\Entity\TurCostoServicio();
        $arCostoServicios = $query->getResult();
        foreach ($arCostoServicios as $arCostoServicio) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCostoServicio->getAnio())
                    ->setCellValue('B' . $i, $arCostoServicio->getMes())
                    ->setCellValue('C' . $i, $arCostoServicio->getClienteRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arCostoServicio->getPuestoRel()->getNombre())
                    ->setCellValue('E' . $i, $arCostoServicio->getConceptoServicioRel()->getNombre())
                    ->setCellValue('F' . $i, $arCostoServicio->getModalidadServicioRel()->getNombre())
                    ->setCellValue('G' . $i, $arCostoServicio->getPeriodoRel()->getNombre())
                    ->setCellValue('H' . $i, $arCostoServicio->getDiaDesde())
                    ->setCellValue('I' . $i, $arCostoServicio->getDiaHasta())
                    ->setCellValue('J' . $i, $arCostoServicio->getDias())
                    ->setCellValue('K' . $i, $arCostoServicio->getHoras())
                    ->setCellValue('L' . $i, $arCostoServicio->getHorasProgramadas())
                    ->setCellValue('M' . $i, $arCostoServicio->getCantidad())
                    ->setCellValue('N' . $i, $arCostoServicio->getVrCostoRecurso())
                    ->setCellValue('O' . $i, $arCostoServicio->getVrTotal());
            $i++;
        }        

        $objPHPExcel->getActiveSheet()->setTitle('CostoServicio');
        
        $objPHPExcel->createSheet(1)->setTitle('CostoServicioDetalle')
                ->setCellValue('A1', 'COD')
                ->setCellValue('B1', 'CLIENTE')
                ->setCellValue('C1', 'COD')
                ->setCellValue('D1', 'PUESTO')
                ->setCellValue('E1', 'C.COSTO')
                ->setCellValue('F1', 'MODALIDAD')
                ->setCellValue('G1', 'IDENTIFICACION')
                ->setCellValue('H1', 'NOMBRE')
                ->setCellValue('I1', 'C.COSTO')
                ->setCellValue('J1', 'COSTO')
                ->setCellValue('K1', 'DS')
                ->setCellValue('L1', 'D')
                ->setCellValue('M1', 'N')
                ->setCellValue('N1', 'FD')
                ->setCellValue('O1', 'FN')
                ->setCellValue('P1', 'EOD')
                ->setCellValue('Q1', 'EON')
                ->setCellValue('R1', 'EFD')
                ->setCellValue('S1', 'EFN')
                ->setCellValue('T1', 'RN')
                ->setCellValue('U1', 'RFD')
                ->setCellValue('V1', 'RFN')
                ->setCellValue('W1', 'C.DS')
                ->setCellValue('X1', 'C.D')
                ->setCellValue('Y1', 'C.N')
                ->setCellValue('Z1', 'C.FD')
                ->setCellValue('AA1', 'C.FN')
                ->setCellValue('AB1', 'C.EOD')
                ->setCellValue('AC1', 'C.EON')
                ->setCellValue('AD1', 'C.EFD')
                ->setCellValue('AE1', 'C.EFN')
                ->setCellValue('AF1', 'C.RN')
                ->setCellValue('AG1', 'C.RFD')
                ->setCellValue('AH1', 'C.RFN');

        $objPHPExcel->setActiveSheetIndex(1); 
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet(1)->getStyle('1')->getFont()->setBold(true);     
        for($col = 'A'; $col !== 'AI'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                  
        }            
        for($col = 'J'; $col !== 'AI'; $col++) { 
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');                 
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }             

        $i = 2;
            
        $query = $em->createQuery($this->strListaDetalleDql);
        $arCostoRecursoDetalles = new \Brasa\TurnoBundle\Entity\TurCostoRecursoDetalle();
        $arCostoRecursoDetalles = $query->getResult();
        foreach ($arCostoRecursoDetalles as $arCostoRecursoDetalle) {
            $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('A' . $i, $arCostoRecursoDetalle->getCodigoClienteFk())
                    ->setCellValue('B' . $i, $arCostoRecursoDetalle->getClienteRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arCostoRecursoDetalle->getCodigoPuestoFk())
                    ->setCellValue('D' . $i, $arCostoRecursoDetalle->getPuestoRel()->getNombre())
                    ->setCellValue('E' . $i, $arCostoRecursoDetalle->getPuestoRel()->getCodigoCentroCostoContabilidadFk())
                    ->setCellValue('F' . $i, $arCostoRecursoDetalle->getPedidoDetalleRel()->getModalidadServicioRel()->getNombre())
                    ->setCellValue('G' . $i, $arCostoRecursoDetalle->getRecursoRel()->getNumeroIdentificacion())
                    ->setCellValue('H' . $i, $arCostoRecursoDetalle->getRecursoRel()->getNombreCorto())
                    ->setCellValue('I' . $i, $arCostoRecursoDetalle->getRecursoRel()->getEmpleadoRel()->getCodigoCentroCostoContabilidadFk())
                    ->setCellValue('J' . $i, $arCostoRecursoDetalle->getCosto())
                    ->setCellValue('K' . $i, $arCostoRecursoDetalle->getHorasDescanso())
                    ->setCellValue('L' . $i, $arCostoRecursoDetalle->getHorasDiurnas())
                    ->setCellValue('M' . $i, $arCostoRecursoDetalle->getHorasNocturnas())
                    ->setCellValue('N' . $i, $arCostoRecursoDetalle->getHorasFestivasDiurnas())
                    ->setCellValue('O' . $i, $arCostoRecursoDetalle->getHorasFestivasNocturnas())
                    ->setCellValue('P' . $i, $arCostoRecursoDetalle->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('Q' . $i, $arCostoRecursoDetalle->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('R' . $i, $arCostoRecursoDetalle->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('S' . $i, $arCostoRecursoDetalle->getHorasExtrasFestivasNocturnas())
                    ->setCellValue('T' . $i, $arCostoRecursoDetalle->getHorasRecargoNocturno())
                    ->setCellValue('U' . $i, $arCostoRecursoDetalle->getHorasRecargoFestivoDiurno())
                    ->setCellValue('V' . $i, $arCostoRecursoDetalle->getHorasRecargoFestivoNocturno())
                    ->setCellValue('W' . $i, $arCostoRecursoDetalle->getHorasDescansoCosto())
                    ->setCellValue('X' . $i, $arCostoRecursoDetalle->getHorasDiurnasCosto())
                    ->setCellValue('Y' . $i, $arCostoRecursoDetalle->getHorasNocturnasCosto())
                    ->setCellValue('Z' . $i, $arCostoRecursoDetalle->getHorasFestivasDiurnasCosto())
                    ->setCellValue('AA' . $i, $arCostoRecursoDetalle->getHorasFestivasNocturnasCosto())
                    ->setCellValue('AB' . $i, $arCostoRecursoDetalle->getHorasExtrasOrdinariasDiurnasCosto())
                    ->setCellValue('AC' . $i, $arCostoRecursoDetalle->getHorasExtrasOrdinariasNocturnasCosto())
                    ->setCellValue('AD' . $i, $arCostoRecursoDetalle->getHorasExtrasFestivasDiurnasCosto())
                    ->setCellValue('AE' . $i, $arCostoRecursoDetalle->getHorasExtrasFestivasNocturnasCosto())
                    ->setCellValue('AF' . $i, $arCostoRecursoDetalle->getHorasRecargoNocturnoCosto())
                    ->setCellValue('AG' . $i, $arCostoRecursoDetalle->getHorasRecargoFestivoDiurnoCosto())
                    ->setCellValue('AH' . $i, $arCostoRecursoDetalle->getHorasRecargoFestivoNocturnoCosto());
            $i++;
        }           
        
        
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CostoServicio.xlsx"');
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