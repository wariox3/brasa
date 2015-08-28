<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
class UtilidadesInformacionDaneController extends Controller
{
               
    public function InformeAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('BtnGenerarArchivo', 'submit', array('label'  => 'Generar archivo',))
            ->add('fechaProceso', 'choice', array('choices' => array('2014' => '2014', '2013' => '2013','2012' => '2012', '2011' => '2011','2010' => '2010')))
            ->add('formatos', 'choice', array('choices' => array('mts' => 'Muestra trimestral de servicios MTS')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            
            if($form->get('BtnGenerarArchivo')->isClicked()) {
                $objPHPExcel = new \PHPExcel();
                // Set document properties
                 
                $objPHPExcel->getProperties()->setCreator("EMPRESA")
                    ->setLastModifiedBy("EMPRESA")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");
                
                $objPHPExcel->getActiveSheet()->mergeCells('A1:J1')->getStyle()->getAlignment()->setHorizontal('center');
                $objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
                $objPHPExcel->getActiveSheet()->mergeCells('A3:J3');
                $objPHPExcel->getActiveSheet()->mergeCells('A4:J4');
                $objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
                $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
                $objPHPExcel->getActiveSheet()->mergeCells('A7:J7');
                $objPHPExcel->getActiveSheet()->mergeCells('A8:J8')
                    ->setCellValue('A1', 'TIPO CONTRATACION')
                    ->setCellValue('A2', 'Propietarios, socios y familiares (sin remuneracion fija)')
                    ->setCellValue('A3', 'Personal permanente (contrato a termino indefinido)')
                    ->setCellValue('A4', 'Temporal Contratado directamente por la Empresa')
                    ->setCellValue('A5', 'Temporal en Mision en otras empresas (solo para empresas especializadas en suministro de personal')
                    ->setCellValue('A6', 'Temporal suministrado por otras empresas')
                    ->setCellValue('A7', 'Personal Aprendiz o estudiantes por convenio ( Universitario, tecnologo o tecnico)')
                    ->setCellValue('A8', 'TOTAL')
                    ->setCellValue('K1', 'Número de personas promedio trimestre TOTAL NACIONAL')                    
                    ->setCellValue('K2', '0')
                    ->setCellValue('K3', '0')                    
                    ->setCellValue('K4', '0')
                    ->setCellValue('K5', '0')
                    ->setCellValue('K6', '0')
                    ->setCellValue('K7', '0')
                    ->setCellValue('K8', '0')
                    ->setCellValue('L1', 'Número de personas promedio trimestre TOTAL BOGOTA')                    
                    ->setCellValue('L2', '0')
                    ->setCellValue('L3', '0')                    
                    ->setCellValue('L4', '0')
                    ->setCellValue('L5', '0')
                    ->setCellValue('L6', '0')
                    ->setCellValue('L7', '0')
                    ->setCellValue('L8', '0');
                
                
                
                $objPHPExcel->getActiveSheet(0)->setTitle('Dane');
                $objPHPExcel->setActiveSheetIndex(0);
                
                $objPHPExcel->createSheet(2)->setTitle('Dane2')
                    ->setCellValue('A1', 'TIPO CONTRATACION')
                    ->setCellValue('A2', 'Propietarios, socios y familiares (sin remuneracion fija)')
                    ->setCellValue('A3', 'Personal permanente (contrato a termino indefinido)')
                    ->setCellValue('A4', 'Temporal Contratado directamente por la Empresa')
                    ->setCellValue('A5', 'Temporal en Mision en otras empresas (solo para empresas especializadas en suministro de personal')
                    ->setCellValue('A6', 'Temporal suministrado por otras empresas')
                    ->setCellValue('A7', 'Personal Aprendiz o estudiantes por convenio ( Universitario, tecnologo o tecnico)')
                    ->setCellValue('A8', 'TOTAL')
                    ->setCellValue('K1', 'Número de personas promedio trimestre TOTAL NACIONAL')                    
                    ->setCellValue('K2', '0')
                    ->setCellValue('K3', '0')                    
                    ->setCellValue('K4', '0')
                    ->setCellValue('K5', '0')
                    ->setCellValue('K6', '0')
                    ->setCellValue('K7', '0')
                    ->setCellValue('K8', '0')
                    ->setCellValue('L1', 'Número de personas promedio trimestre TOTAL BOGOTA')                    
                    ->setCellValue('L2', '0')
                    ->setCellValue('L3', '0')                    
                    ->setCellValue('L4', '0')
                    ->setCellValue('L5', '0')
                    ->setCellValue('L6', '0')
                    ->setCellValue('L7', '0')
                    ->setCellValue('L8', '0');
                        
                
                
                
                
                
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="InformeDane.xlsx"');
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
                
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/InformacionDane:Informe.html.twig', array(
                'form' => $form->createView() 
                ));
    }            
    
}
