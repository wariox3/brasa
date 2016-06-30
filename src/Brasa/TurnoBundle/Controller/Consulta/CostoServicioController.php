<?php
namespace Brasa\TurnoBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class CostoServicioController extends Controller
{
    var $strListaDql = "";    
    /**
     * @Route("/tur/consulta/costo/servicio", name="brs_tur_consulta_costo_servicio")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
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

        $arCierreMesServicio = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Costo:servicio.html.twig', array(
            'arCierreMesServicio' => $arCierreMesServicio,                        
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();        
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurCierreMesServicio')->listaDql();                    
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();        
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $session->set('filtroCodigoRecurso', $form->get('TxtCodigoRecurso')->getData());
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

        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtCodigoRecurso', 'text', array('label'  => 'Nit','data' => $session->get('filtroCodigoRecurso')))
            ->add('TxtNombreRecurso', 'text', array('label'  => 'NombreCliente','data' => $strNombreRecurso))                                                                    
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
        $arCierreMesServicios = new \Brasa\TurnoBundle\Entity\TurCierreMesServicio();
        $arCierreMesServicios = $query->getResult();
        foreach ($arCierreMesServicios as $arCierreMesServicio) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCierreMesServicio->getAnio())
                    ->setCellValue('B' . $i, $arCierreMesServicio->getMes())
                    ->setCellValue('C' . $i, $arCierreMesServicio->getClienteRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arCierreMesServicio->getPuestoRel()->getNombre())
                    ->setCellValue('E' . $i, $arCierreMesServicio->getConceptoServicioRel()->getNombre())
                    ->setCellValue('F' . $i, $arCierreMesServicio->getModalidadServicioRel()->getNombre())
                    ->setCellValue('G' . $i, $arCierreMesServicio->getPeriodoRel()->getNombre())
                    ->setCellValue('H' . $i, $arCierreMesServicio->getDiaDesde())
                    ->setCellValue('I' . $i, $arCierreMesServicio->getDiaHasta())
                    ->setCellValue('J' . $i, $arCierreMesServicio->getDias())
                    ->setCellValue('K' . $i, $arCierreMesServicio->getHoras())
                    ->setCellValue('L' . $i, $arCierreMesServicio->getHorasProgramadas())
                    ->setCellValue('M' . $i, $arCierreMesServicio->getCantidad())
                    ->setCellValue('N' . $i, $arCierreMesServicio->getVrCostoRecurso())
                    ->setCellValue('O' . $i, $arCierreMesServicio->getVrTotal())
;                         
            $i++;
        }                
        //$objPHPExcel->getActiveSheet()->getStyle('A1:AL1')->getFont()->setBold(true);        
        
        //$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        
        $objPHPExcel->getActiveSheet()->setTitle('CostoServicio');
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