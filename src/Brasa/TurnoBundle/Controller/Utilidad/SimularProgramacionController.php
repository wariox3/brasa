<?php

namespace Brasa\TurnoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;


class SimularProgramacionController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/utilidad/simular/programacion/{codigoServicio}/{codigoServicioDetalle}", name="brs_tur_utilidad_simular_programacion")
     */    
    public function listaAction($codigoServicio, $codigoServicioDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        $fechaProgramacion = $form->get('fecha')->getData();
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {      
                $strSql = "DELETE FROM tur_simulacion_detalle WHERE 1 ";           
                $em->getConnection()->executeQuery($strSql);                
                $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
                $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio); 
                $arServicioDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                if($codigoServicioDetalle == 0) {                    
                    $arServicioDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigoServicio));                                    
                } else {
                    $arServicioDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigoServicio, 'codigoServicioDetallePk' => $codigoServicioDetalle));                                    
                }                
                foreach ($arServicioDetalles as $arServicioDetalle) {            
                    $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->nuevo($arServicioDetalle->getCodigoServicioDetallePk(), $fechaProgramacion);
                }     
                $fechaProgramacion = $form->get('fecha')->getData();
                $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
                $arConfiguracion->setFechaUltimaSimulacion($fechaProgramacion);
                $em->persist($arConfiguracion);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_utilidad_simular_programacion', array('codigoServicio' => $codigoServicio, 'codigoServicioDetalle' => $codigoServicioDetalle))); 
            } 
            
            if($form->get('BtnExcel')->isClicked()) {            
                $this->generarExcel();
            }
        }                 
        $strAnioMes = $fechaProgramacion->format('Y/m');
        $arrDiaSemana = array();
        for($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $boolFestivo = 0;
            if($diaSemana == 'd') {
                $boolFestivo = 1;
            }
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana, 'festivo' => $boolFestivo);
        }        
        //$dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionInconsistencia')->listaDql();
        //$arProgramacionInconsistencias = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        $arSimulacionDetalle = $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->findAll();
        return $this->render('BrasaTurnoBundle:Utilidades/Simular:programacion.html.twig', array(            
            'arSimulacionDetalle' => $arSimulacionDetalle,
            'arrDiaSemana' => $arrDiaSemana,
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {                
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);         
        $form = $this->createFormBuilder()                        
            ->add('fecha', 'date', array('data' => $arConfiguracion->getFechaUltimaSimulacion(), 'format' => 'yyyyMMdd'))                            
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))       
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))       
            ->add('BtnPdf', 'submit', array('label'  => 'PDF'))       
            ->getForm();        
        return $form;
    }           

    private function devuelveDiaSemanaEspaniol ($dateFecha) {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "l";
                break;
            case 2:
                $strDia = "m";
                break;
            case 3:
                $strDia = "i";
                break;
            case 4:
                $strDia = "j";
                break;
            case 5:
                $strDia = "v";
                break;
            case 6:
                $strDia = "s";
                break;
            case 7:
                $strDia = "d";
                break;
        }

        return $strDia;
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
        for($col = 'A'; $col !== 'AN'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }                   
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ANIO')
                    ->setCellValue('B1', 'MES')
                    ->setCellValue('C1', 'PUESTO')
                    ->setCellValue('D1', 'RECURSO')
                    ->setCellValue('E1', 'D1')
                    ->setCellValue('F1', 'D2')
                    ->setCellValue('G1', 'D3')
                    ->setCellValue('H1', 'D4')
                    ->setCellValue('I1', 'D5')
                    ->setCellValue('J1', 'D6')
                    ->setCellValue('K1', 'D7')
                    ->setCellValue('L1', 'D8')
                    ->setCellValue('M1', 'D9')
                    ->setCellValue('N1', 'D10')
                    ->setCellValue('O1', 'D11')
                    ->setCellValue('P1', 'D12')
                    ->setCellValue('Q1', 'D13')
                    ->setCellValue('R1', 'D14')
                    ->setCellValue('S1', 'D15')
                    ->setCellValue('T1', 'D16')
                    ->setCellValue('U1', 'D17')
                    ->setCellValue('V1', 'D18')
                    ->setCellValue('W1', 'D19')
                    ->setCellValue('X1', 'D20')
                    ->setCellValue('Y1', 'D21')
                    ->setCellValue('Z1', 'D22')
                    ->setCellValue('AA1', 'D23')
                    ->setCellValue('AB1', 'D24')
                    ->setCellValue('AC1', 'D25')
                    ->setCellValue('AD1', 'D26')
                    ->setCellValue('AE1', 'D27')
                    ->setCellValue('AF1', 'D28')
                    ->setCellValue('AG1', 'D29')
                    ->setCellValue('AH1', 'D30')
                    ->setCellValue('AI1', 'D31');
        
        $i = 2;        
        $dql = $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->listaDql();
        $query = $em->createQuery($dql);
        $arSimulacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arSimulacionDetalles = $query->getResult();        
        foreach ($arSimulacionDetalles as $arSimulacionDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSimulacionDetalle->getAnio())
                    ->setCellValue('B' . $i, $arSimulacionDetalle->getMes())
                    ->setCellValue('E' . $i, $arSimulacionDetalle->getDia1())
                    ->setCellValue('F' . $i, $arSimulacionDetalle->getDia2())
                    ->setCellValue('G' . $i, $arSimulacionDetalle->getDia3())
                    ->setCellValue('H' . $i, $arSimulacionDetalle->getDia4())
                    ->setCellValue('I' . $i, $arSimulacionDetalle->getDia5())
                    ->setCellValue('J' . $i, $arSimulacionDetalle->getDia6())
                    ->setCellValue('K' . $i, $arSimulacionDetalle->getDia7())
                    ->setCellValue('L' . $i, $arSimulacionDetalle->getDia8())
                    ->setCellValue('M' . $i, $arSimulacionDetalle->getDia9())
                    ->setCellValue('N' . $i, $arSimulacionDetalle->getDia10())
                    ->setCellValue('O' . $i, $arSimulacionDetalle->getDia11())
                    ->setCellValue('P' . $i, $arSimulacionDetalle->getDia12())
                    ->setCellValue('Q' . $i, $arSimulacionDetalle->getDia13())
                    ->setCellValue('R' . $i, $arSimulacionDetalle->getDia14())
                    ->setCellValue('S' . $i, $arSimulacionDetalle->getDia15())
                    ->setCellValue('T' . $i, $arSimulacionDetalle->getDia16())
                    ->setCellValue('U' . $i, $arSimulacionDetalle->getDia17())
                    ->setCellValue('V' . $i, $arSimulacionDetalle->getDia18())
                    ->setCellValue('W' . $i, $arSimulacionDetalle->getDia19())
                    ->setCellValue('X' . $i, $arSimulacionDetalle->getDia20())
                    ->setCellValue('Y' . $i, $arSimulacionDetalle->getDia21())
                    ->setCellValue('Z' . $i, $arSimulacionDetalle->getDia22())
                    ->setCellValue('AA' . $i, $arSimulacionDetalle->getDia23())
                    ->setCellValue('AB' . $i, $arSimulacionDetalle->getDia24())
                    ->setCellValue('AC' . $i, $arSimulacionDetalle->getDia25())
                    ->setCellValue('AD' . $i, $arSimulacionDetalle->getDia26())
                    ->setCellValue('AE' . $i, $arSimulacionDetalle->getDia27())
                    ->setCellValue('AF' . $i, $arSimulacionDetalle->getDia28())
                    ->setCellValue('AG' . $i, $arSimulacionDetalle->getDia29())
                    ->setCellValue('AH' . $i, $arSimulacionDetalle->getDia30())
                    ->setCellValue('AI' . $i, $arSimulacionDetalle->getDia31());  
            
            if($arSimulacionDetalle->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C' . $i, $arSimulacionDetalle->getPuestoRel()->getNombre());
            }
            if($arSimulacionDetalle->getRecursoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arSimulacionDetalle->getRecursoRel()->getNombreCorto());
            }
            
            $i++;
        }
        $intNum = count($arSimulacionDetalles);
        $intNum += 1;                
        //$objPHPExcel->getActiveSheet()->getStyle('A1:AL1')->getFont()->setBold(true);        
        
        //$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        
        $objPHPExcel->getActiveSheet()->setTitle('SimulacionDetalle');
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
