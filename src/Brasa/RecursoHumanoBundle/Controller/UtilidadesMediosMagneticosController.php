<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class UtilidadesMediosMagneticosController extends Controller
{
               
    public function InformeAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('BtnGenerarArchivo', 'submit', array('label'  => 'Generar archivo',))
            ->add('fechaProceso', 'choice', array('choices' => array('2014' => '2014', '2013' => '2013','2012' => '2012', '2011' => '2011','2010' => '2010')))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerarArchivo')->isClicked()) {
                if($form->get('formatos')->getData() == 1001) {
                    $this->generarFormato1001Excel();
                }
                if($form->get('formatos')->getData() == 1003) {
                    $this->generarFormato1003Excel();
                }
                if($form->get('formatos')->getData() == 1005) {
                    $this->generarFormato1005Excel();
                }
                if($form->get('formatos')->getData() == 1006) {
                    $this->generarFormato1006Excel();
                }
                if($form->get('formatos')->getData() == 1007) {
                    $this->generarFormato1007Excel();
                }
                if($form->get('formatos')->getData() == 1008) {
                    $this->generarFormato1008Excel();
                }
                if($form->get('formatos')->getData() == 1009) {
                    $this->generarFormato1009Excel();
                }
                if($form->get('formatos')->getData() == 1010) {
                    $this->generarFormato1010Excel();
                }
                if($form->get('formatos')->getData() == 1011) {
                    $this->generarFormato1011Excel();
                }
                if($form->get('formatos')->getData() == 1012) {
                    $this->generarFormato1012Excel();
                }
                
            }   
        }
                
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/MediosMagneticos:Informe.html.twig', array(
                'form' => $form->createView() 
                ));
    } 
    
    private function generarFormato1001Excel() {
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
                            ->setCellValue('A1', 'Concepto')
                            ->setCellValue('B1', 'Tipo de Documento')
                            ->setCellValue('C1', 'Número Identificación')
                            ->setCellValue('D1', 'Primer apellido del informado')
                            ->setCellValue('E1', 'Segundo apellido del informado')
                            ->setCellValue('F1', 'Primer nombre del informado')
                            ->setCellValue('G1', 'Otros nombres del informado')
                            ->setCellValue('H1', 'Razón social informado')
                            ->setCellValue('I1', 'Dirección')
                            ->setCellValue('J1', 'Codigo departamento')
                            ->setCellValue('K1', 'Codigo municipio')
                            ->setCellValue('L1', 'País de Residencia o domicilio')
                            ->setCellValue('M1', 'Pago o abono en cuenta deducible')
                            ->setCellValue('N1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('O1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('P1', 'IVA mayor valor del costo o gasto deducible')
                            ->setCellValue('Q1', 'IVA mayor valor del costo o gasto no deducible')
                            ->setCellValue('R1', 'Retención en la fuente practicada Renta')
                            ->setCellValue('S1', 'Retención en la fuente asumida Renta')
                            ->setCellValue('T1', 'Retención en la fuente practicada Iva Régimen común')
                            ->setCellValue('U1', 'Retención en la fuente asumida IVA Régimen Simplificado')
                            ->setCellValue('V1', 'Retención en la fuente practicada Iva no domiciliados')
                            ->setCellValue('W1', 'Retención en la fuente practicadas  CREE')
                            ->setCellValue('X1', 'Retención en la fuente asumidas CREE');

                $i = 2;
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arEmpleados as $arEmpleados) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "5001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, $arEmpleados->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEmpleados->getApellido1())
                            ->setCellValue('E' . $i, $arEmpleados->getApellido2())
                            ->setCellValue('F' . $i, $arEmpleados->getNombre1())
                            ->setCellValue('G' . $i, $arEmpleados->getNombreCorto())
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arEmpleados->getDireccion())
                            ->setCellValue('J' . $i, $arEmpleados->getCiudadRel()->getCodigoDepartamentoFk())
                            ->setCellValue('K' . $i, $arEmpleados->getCodigoCiudadFk())
                            ->setCellValue('L' . $i, "169")
                            ->setCellValue('M' . $i, $arEmpleados->getCuenta())
                            ->setCellValue('N' . $i, "0")
                            ->setCellValue('O' . $i, "0")
                            ->setCellValue('P' . $i, "0")
                            ->setCellValue('Q' . $i, "0")
                            ->setCellValue('R' . $i, "0")
                            ->setCellValue('S' . $i, "0")
                            ->setCellValue('T' . $i, "0")
                            ->setCellValue('U' . $i, "0")
                            ->setCellValue('V' . $i, "0")
                            ->setCellValue('W' . $i, "0")
                            ->setCellValue('X' . $i, "0");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1001.xlsx"');
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
    
    private function generarFormato1003Excel() {
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
                            ->setCellValue('A1', 'Concepto')
                            ->setCellValue('B1', 'Tipo de Documento')
                            ->setCellValue('C1', 'Número Identificación')
                            ->setCellValue('D1', 'Primer apellido del informado')
                            ->setCellValue('E1', 'Segundo apellido del informado')
                            ->setCellValue('F1', 'Primer nombre del informado')
                            ->setCellValue('G1', 'Otros nombres del informado')
                            ->setCellValue('H1', 'Razón social informado')
                            ->setCellValue('I1', 'Dirección')
                            ->setCellValue('J1', 'Codigo departamento')
                            ->setCellValue('K1', 'Codigo municipio')
                            ->setCellValue('L1', 'País de Residencia o domicilio')
                            ->setCellValue('M1', 'Pago o abono en cuenta deducible')
                            ->setCellValue('N1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('O1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('P1', 'IVA mayor valor del costo o gasto deducible')
                            ->setCellValue('Q1', 'IVA mayor valor del costo o gasto no deducible')
                            ->setCellValue('R1', 'Retención en la fuente practicada Renta')
                            ->setCellValue('S1', 'Retención en la fuente asumida Renta')
                            ->setCellValue('T1', 'Retención en la fuente practicada Iva Régimen común')
                            ->setCellValue('U1', 'Retención en la fuente asumida IVA Régimen Simplificado')
                            ->setCellValue('V1', 'Retención en la fuente practicada Iva no domiciliados')
                            ->setCellValue('W1', 'Retención en la fuente practicadas  CREE')
                            ->setCellValue('X1', 'Retención en la fuente asumidas CREE');

                $i = 2;
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arEmpleados as $arEmpleados) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "5001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, $arEmpleados->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEmpleados->getApellido1())
                            ->setCellValue('E' . $i, $arEmpleados->getApellido2())
                            ->setCellValue('F' . $i, $arEmpleados->getNombre1())
                            ->setCellValue('G' . $i, $arEmpleados->getNombreCorto())
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arEmpleados->getDireccion())
                            ->setCellValue('J' . $i, $arEmpleados->getCiudadRel()->getCodigoDepartamentoFk())
                            ->setCellValue('K' . $i, $arEmpleados->getCodigoCiudadFk())
                            ->setCellValue('L' . $i, "169")
                            ->setCellValue('M' . $i, $arEmpleados->getCuenta())
                            ->setCellValue('N' . $i, "0")
                            ->setCellValue('O' . $i, "0")
                            ->setCellValue('P' . $i, "0")
                            ->setCellValue('Q' . $i, "0")
                            ->setCellValue('R' . $i, "0")
                            ->setCellValue('S' . $i, "0")
                            ->setCellValue('T' . $i, "0")
                            ->setCellValue('U' . $i, "0")
                            ->setCellValue('V' . $i, "0")
                            ->setCellValue('W' . $i, "0")
                            ->setCellValue('X' . $i, "0");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1003.xlsx"');
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
    
    private function generarFormato1005Excel() {
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
                            ->setCellValue('A1', 'Concepto')
                            ->setCellValue('B1', 'Tipo de Documento')
                            ->setCellValue('C1', 'Número Identificación')
                            ->setCellValue('D1', 'Primer apellido del informado')
                            ->setCellValue('E1', 'Segundo apellido del informado')
                            ->setCellValue('F1', 'Primer nombre del informado')
                            ->setCellValue('G1', 'Otros nombres del informado')
                            ->setCellValue('H1', 'Razón social informado')
                            ->setCellValue('I1', 'Dirección')
                            ->setCellValue('J1', 'Codigo departamento')
                            ->setCellValue('K1', 'Codigo municipio')
                            ->setCellValue('L1', 'País de Residencia o domicilio')
                            ->setCellValue('M1', 'Pago o abono en cuenta deducible')
                            ->setCellValue('N1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('O1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('P1', 'IVA mayor valor del costo o gasto deducible')
                            ->setCellValue('Q1', 'IVA mayor valor del costo o gasto no deducible')
                            ->setCellValue('R1', 'Retención en la fuente practicada Renta')
                            ->setCellValue('S1', 'Retención en la fuente asumida Renta')
                            ->setCellValue('T1', 'Retención en la fuente practicada Iva Régimen común')
                            ->setCellValue('U1', 'Retención en la fuente asumida IVA Régimen Simplificado')
                            ->setCellValue('V1', 'Retención en la fuente practicada Iva no domiciliados')
                            ->setCellValue('W1', 'Retención en la fuente practicadas  CREE')
                            ->setCellValue('X1', 'Retención en la fuente asumidas CREE');

                $i = 2;
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arEmpleados as $arEmpleados) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "5001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, $arEmpleados->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEmpleados->getApellido1())
                            ->setCellValue('E' . $i, $arEmpleados->getApellido2())
                            ->setCellValue('F' . $i, $arEmpleados->getNombre1())
                            ->setCellValue('G' . $i, $arEmpleados->getNombreCorto())
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arEmpleados->getDireccion())
                            ->setCellValue('J' . $i, $arEmpleados->getCiudadRel()->getCodigoDepartamentoFk())
                            ->setCellValue('K' . $i, $arEmpleados->getCodigoCiudadFk())
                            ->setCellValue('L' . $i, "169")
                            ->setCellValue('M' . $i, $arEmpleados->getCuenta())
                            ->setCellValue('N' . $i, "0")
                            ->setCellValue('O' . $i, "0")
                            ->setCellValue('P' . $i, "0")
                            ->setCellValue('Q' . $i, "0")
                            ->setCellValue('R' . $i, "0")
                            ->setCellValue('S' . $i, "0")
                            ->setCellValue('T' . $i, "0")
                            ->setCellValue('U' . $i, "0")
                            ->setCellValue('V' . $i, "0")
                            ->setCellValue('W' . $i, "0")
                            ->setCellValue('X' . $i, "0");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1005.xlsx"');
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
    
    private function generarFormato1006Excel() {
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
                            ->setCellValue('A1', 'Concepto')
                            ->setCellValue('B1', 'Tipo de Documento')
                            ->setCellValue('C1', 'Número Identificación')
                            ->setCellValue('D1', 'Primer apellido del informado')
                            ->setCellValue('E1', 'Segundo apellido del informado')
                            ->setCellValue('F1', 'Primer nombre del informado')
                            ->setCellValue('G1', 'Otros nombres del informado')
                            ->setCellValue('H1', 'Razón social informado')
                            ->setCellValue('I1', 'Dirección')
                            ->setCellValue('J1', 'Codigo departamento')
                            ->setCellValue('K1', 'Codigo municipio')
                            ->setCellValue('L1', 'País de Residencia o domicilio')
                            ->setCellValue('M1', 'Pago o abono en cuenta deducible')
                            ->setCellValue('N1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('O1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('P1', 'IVA mayor valor del costo o gasto deducible')
                            ->setCellValue('Q1', 'IVA mayor valor del costo o gasto no deducible')
                            ->setCellValue('R1', 'Retención en la fuente practicada Renta')
                            ->setCellValue('S1', 'Retención en la fuente asumida Renta')
                            ->setCellValue('T1', 'Retención en la fuente practicada Iva Régimen común')
                            ->setCellValue('U1', 'Retención en la fuente asumida IVA Régimen Simplificado')
                            ->setCellValue('V1', 'Retención en la fuente practicada Iva no domiciliados')
                            ->setCellValue('W1', 'Retención en la fuente practicadas  CREE')
                            ->setCellValue('X1', 'Retención en la fuente asumidas CREE');

                $i = 2;
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arEmpleados as $arEmpleados) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "5001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, $arEmpleados->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEmpleados->getApellido1())
                            ->setCellValue('E' . $i, $arEmpleados->getApellido2())
                            ->setCellValue('F' . $i, $arEmpleados->getNombre1())
                            ->setCellValue('G' . $i, $arEmpleados->getNombreCorto())
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arEmpleados->getDireccion())
                            ->setCellValue('J' . $i, $arEmpleados->getCiudadRel()->getCodigoDepartamentoFk())
                            ->setCellValue('K' . $i, $arEmpleados->getCodigoCiudadFk())
                            ->setCellValue('L' . $i, "169")
                            ->setCellValue('M' . $i, $arEmpleados->getCuenta())
                            ->setCellValue('N' . $i, "0")
                            ->setCellValue('O' . $i, "0")
                            ->setCellValue('P' . $i, "0")
                            ->setCellValue('Q' . $i, "0")
                            ->setCellValue('R' . $i, "0")
                            ->setCellValue('S' . $i, "0")
                            ->setCellValue('T' . $i, "0")
                            ->setCellValue('U' . $i, "0")
                            ->setCellValue('V' . $i, "0")
                            ->setCellValue('W' . $i, "0")
                            ->setCellValue('X' . $i, "0");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1006.xlsx"');
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
    
    private function generarFormato1007Excel() {
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
                            ->setCellValue('A1', 'Concepto')
                            ->setCellValue('B1', 'Tipo de Documento')
                            ->setCellValue('C1', 'Número Identificación')
                            ->setCellValue('D1', 'Primer apellido del informado')
                            ->setCellValue('E1', 'Segundo apellido del informado')
                            ->setCellValue('F1', 'Primer nombre del informado')
                            ->setCellValue('G1', 'Otros nombres del informado')
                            ->setCellValue('H1', 'Razón social informado')
                            ->setCellValue('I1', 'Dirección')
                            ->setCellValue('J1', 'Codigo departamento')
                            ->setCellValue('K1', 'Codigo municipio')
                            ->setCellValue('L1', 'País de Residencia o domicilio')
                            ->setCellValue('M1', 'Pago o abono en cuenta deducible')
                            ->setCellValue('N1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('O1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('P1', 'IVA mayor valor del costo o gasto deducible')
                            ->setCellValue('Q1', 'IVA mayor valor del costo o gasto no deducible')
                            ->setCellValue('R1', 'Retención en la fuente practicada Renta')
                            ->setCellValue('S1', 'Retención en la fuente asumida Renta')
                            ->setCellValue('T1', 'Retención en la fuente practicada Iva Régimen común')
                            ->setCellValue('U1', 'Retención en la fuente asumida IVA Régimen Simplificado')
                            ->setCellValue('V1', 'Retención en la fuente practicada Iva no domiciliados')
                            ->setCellValue('W1', 'Retención en la fuente practicadas  CREE')
                            ->setCellValue('X1', 'Retención en la fuente asumidas CREE');

                $i = 2;
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arEmpleados as $arEmpleados) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "5001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, $arEmpleados->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEmpleados->getApellido1())
                            ->setCellValue('E' . $i, $arEmpleados->getApellido2())
                            ->setCellValue('F' . $i, $arEmpleados->getNombre1())
                            ->setCellValue('G' . $i, $arEmpleados->getNombreCorto())
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arEmpleados->getDireccion())
                            ->setCellValue('J' . $i, $arEmpleados->getCiudadRel()->getCodigoDepartamentoFk())
                            ->setCellValue('K' . $i, $arEmpleados->getCodigoCiudadFk())
                            ->setCellValue('L' . $i, "169")
                            ->setCellValue('M' . $i, $arEmpleados->getCuenta())
                            ->setCellValue('N' . $i, "0")
                            ->setCellValue('O' . $i, "0")
                            ->setCellValue('P' . $i, "0")
                            ->setCellValue('Q' . $i, "0")
                            ->setCellValue('R' . $i, "0")
                            ->setCellValue('S' . $i, "0")
                            ->setCellValue('T' . $i, "0")
                            ->setCellValue('U' . $i, "0")
                            ->setCellValue('V' . $i, "0")
                            ->setCellValue('W' . $i, "0")
                            ->setCellValue('X' . $i, "0");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1007.xlsx"');
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
    
    private function generarFormato1008Excel() {
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
                            ->setCellValue('A1', 'Concepto')
                            ->setCellValue('B1', 'Tipo de Documento')
                            ->setCellValue('C1', 'Número Identificación')
                            ->setCellValue('D1', 'Primer apellido del informado')
                            ->setCellValue('E1', 'Segundo apellido del informado')
                            ->setCellValue('F1', 'Primer nombre del informado')
                            ->setCellValue('G1', 'Otros nombres del informado')
                            ->setCellValue('H1', 'Razón social informado')
                            ->setCellValue('I1', 'Dirección')
                            ->setCellValue('J1', 'Codigo departamento')
                            ->setCellValue('K1', 'Codigo municipio')
                            ->setCellValue('L1', 'País de Residencia o domicilio')
                            ->setCellValue('M1', 'Pago o abono en cuenta deducible')
                            ->setCellValue('N1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('O1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('P1', 'IVA mayor valor del costo o gasto deducible')
                            ->setCellValue('Q1', 'IVA mayor valor del costo o gasto no deducible')
                            ->setCellValue('R1', 'Retención en la fuente practicada Renta')
                            ->setCellValue('S1', 'Retención en la fuente asumida Renta')
                            ->setCellValue('T1', 'Retención en la fuente practicada Iva Régimen común')
                            ->setCellValue('U1', 'Retención en la fuente asumida IVA Régimen Simplificado')
                            ->setCellValue('V1', 'Retención en la fuente practicada Iva no domiciliados')
                            ->setCellValue('W1', 'Retención en la fuente practicadas  CREE')
                            ->setCellValue('X1', 'Retención en la fuente asumidas CREE');

                $i = 2;
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arEmpleados as $arEmpleados) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "5001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, $arEmpleados->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEmpleados->getApellido1())
                            ->setCellValue('E' . $i, $arEmpleados->getApellido2())
                            ->setCellValue('F' . $i, $arEmpleados->getNombre1())
                            ->setCellValue('G' . $i, $arEmpleados->getNombreCorto())
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arEmpleados->getDireccion())
                            ->setCellValue('J' . $i, $arEmpleados->getCiudadRel()->getCodigoDepartamentoFk())
                            ->setCellValue('K' . $i, $arEmpleados->getCodigoCiudadFk())
                            ->setCellValue('L' . $i, "169")
                            ->setCellValue('M' . $i, $arEmpleados->getCuenta())
                            ->setCellValue('N' . $i, "0")
                            ->setCellValue('O' . $i, "0")
                            ->setCellValue('P' . $i, "0")
                            ->setCellValue('Q' . $i, "0")
                            ->setCellValue('R' . $i, "0")
                            ->setCellValue('S' . $i, "0")
                            ->setCellValue('T' . $i, "0")
                            ->setCellValue('U' . $i, "0")
                            ->setCellValue('V' . $i, "0")
                            ->setCellValue('W' . $i, "0")
                            ->setCellValue('X' . $i, "0");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1008.xlsx"');
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
    
    private function generarFormato1009Excel() {
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
                            ->setCellValue('A1', 'Concepto')
                            ->setCellValue('B1', 'Tipo de Documento')
                            ->setCellValue('C1', 'Número Identificación')
                            ->setCellValue('D1', 'Primer apellido del informado')
                            ->setCellValue('E1', 'Segundo apellido del informado')
                            ->setCellValue('F1', 'Primer nombre del informado')
                            ->setCellValue('G1', 'Otros nombres del informado')
                            ->setCellValue('H1', 'Razón social informado')
                            ->setCellValue('I1', 'Dirección')
                            ->setCellValue('J1', 'Codigo departamento')
                            ->setCellValue('K1', 'Codigo municipio')
                            ->setCellValue('L1', 'País de Residencia o domicilio')
                            ->setCellValue('M1', 'Pago o abono en cuenta deducible')
                            ->setCellValue('N1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('O1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('P1', 'IVA mayor valor del costo o gasto deducible')
                            ->setCellValue('Q1', 'IVA mayor valor del costo o gasto no deducible')
                            ->setCellValue('R1', 'Retención en la fuente practicada Renta')
                            ->setCellValue('S1', 'Retención en la fuente asumida Renta')
                            ->setCellValue('T1', 'Retención en la fuente practicada Iva Régimen común')
                            ->setCellValue('U1', 'Retención en la fuente asumida IVA Régimen Simplificado')
                            ->setCellValue('V1', 'Retención en la fuente practicada Iva no domiciliados')
                            ->setCellValue('W1', 'Retención en la fuente practicadas  CREE')
                            ->setCellValue('X1', 'Retención en la fuente asumidas CREE');

                $i = 2;
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arEmpleados as $arEmpleados) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "5001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, $arEmpleados->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEmpleados->getApellido1())
                            ->setCellValue('E' . $i, $arEmpleados->getApellido2())
                            ->setCellValue('F' . $i, $arEmpleados->getNombre1())
                            ->setCellValue('G' . $i, $arEmpleados->getNombreCorto())
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arEmpleados->getDireccion())
                            ->setCellValue('J' . $i, $arEmpleados->getCiudadRel()->getCodigoDepartamentoFk())
                            ->setCellValue('K' . $i, $arEmpleados->getCodigoCiudadFk())
                            ->setCellValue('L' . $i, "169")
                            ->setCellValue('M' . $i, $arEmpleados->getCuenta())
                            ->setCellValue('N' . $i, "0")
                            ->setCellValue('O' . $i, "0")
                            ->setCellValue('P' . $i, "0")
                            ->setCellValue('Q' . $i, "0")
                            ->setCellValue('R' . $i, "0")
                            ->setCellValue('S' . $i, "0")
                            ->setCellValue('T' . $i, "0")
                            ->setCellValue('U' . $i, "0")
                            ->setCellValue('V' . $i, "0")
                            ->setCellValue('W' . $i, "0")
                            ->setCellValue('X' . $i, "0");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1009.xlsx"');
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
    
    private function generarFormato1010Excel() {
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
                            ->setCellValue('A1', 'Concepto')
                            ->setCellValue('B1', 'Tipo de Documento')
                            ->setCellValue('C1', 'Número Identificación')
                            ->setCellValue('D1', 'Primer apellido del informado')
                            ->setCellValue('E1', 'Segundo apellido del informado')
                            ->setCellValue('F1', 'Primer nombre del informado')
                            ->setCellValue('G1', 'Otros nombres del informado')
                            ->setCellValue('H1', 'Razón social informado')
                            ->setCellValue('I1', 'Dirección')
                            ->setCellValue('J1', 'Codigo departamento')
                            ->setCellValue('K1', 'Codigo municipio')
                            ->setCellValue('L1', 'País de Residencia o domicilio')
                            ->setCellValue('M1', 'Pago o abono en cuenta deducible')
                            ->setCellValue('N1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('O1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('P1', 'IVA mayor valor del costo o gasto deducible')
                            ->setCellValue('Q1', 'IVA mayor valor del costo o gasto no deducible')
                            ->setCellValue('R1', 'Retención en la fuente practicada Renta')
                            ->setCellValue('S1', 'Retención en la fuente asumida Renta')
                            ->setCellValue('T1', 'Retención en la fuente practicada Iva Régimen común')
                            ->setCellValue('U1', 'Retención en la fuente asumida IVA Régimen Simplificado')
                            ->setCellValue('V1', 'Retención en la fuente practicada Iva no domiciliados')
                            ->setCellValue('W1', 'Retención en la fuente practicadas  CREE')
                            ->setCellValue('X1', 'Retención en la fuente asumidas CREE');

                $i = 2;
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arEmpleados as $arEmpleados) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "5001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, $arEmpleados->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEmpleados->getApellido1())
                            ->setCellValue('E' . $i, $arEmpleados->getApellido2())
                            ->setCellValue('F' . $i, $arEmpleados->getNombre1())
                            ->setCellValue('G' . $i, $arEmpleados->getNombreCorto())
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arEmpleados->getDireccion())
                            ->setCellValue('J' . $i, $arEmpleados->getCiudadRel()->getCodigoDepartamentoFk())
                            ->setCellValue('K' . $i, $arEmpleados->getCodigoCiudadFk())
                            ->setCellValue('L' . $i, "169")
                            ->setCellValue('M' . $i, $arEmpleados->getCuenta())
                            ->setCellValue('N' . $i, "0")
                            ->setCellValue('O' . $i, "0")
                            ->setCellValue('P' . $i, "0")
                            ->setCellValue('Q' . $i, "0")
                            ->setCellValue('R' . $i, "0")
                            ->setCellValue('S' . $i, "0")
                            ->setCellValue('T' . $i, "0")
                            ->setCellValue('U' . $i, "0")
                            ->setCellValue('V' . $i, "0")
                            ->setCellValue('W' . $i, "0")
                            ->setCellValue('X' . $i, "0");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1010.xlsx"');
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
    
    private function generarFormato1011Excel() {
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
                            ->setCellValue('A1', 'Concepto')
                            ->setCellValue('B1', 'Tipo de Documento')
                            ->setCellValue('C1', 'Número Identificación')
                            ->setCellValue('D1', 'Primer apellido del informado')
                            ->setCellValue('E1', 'Segundo apellido del informado')
                            ->setCellValue('F1', 'Primer nombre del informado')
                            ->setCellValue('G1', 'Otros nombres del informado')
                            ->setCellValue('H1', 'Razón social informado')
                            ->setCellValue('I1', 'Dirección')
                            ->setCellValue('J1', 'Codigo departamento')
                            ->setCellValue('K1', 'Codigo municipio')
                            ->setCellValue('L1', 'País de Residencia o domicilio')
                            ->setCellValue('M1', 'Pago o abono en cuenta deducible')
                            ->setCellValue('N1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('O1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('P1', 'IVA mayor valor del costo o gasto deducible')
                            ->setCellValue('Q1', 'IVA mayor valor del costo o gasto no deducible')
                            ->setCellValue('R1', 'Retención en la fuente practicada Renta')
                            ->setCellValue('S1', 'Retención en la fuente asumida Renta')
                            ->setCellValue('T1', 'Retención en la fuente practicada Iva Régimen común')
                            ->setCellValue('U1', 'Retención en la fuente asumida IVA Régimen Simplificado')
                            ->setCellValue('V1', 'Retención en la fuente practicada Iva no domiciliados')
                            ->setCellValue('W1', 'Retención en la fuente practicadas  CREE')
                            ->setCellValue('X1', 'Retención en la fuente asumidas CREE');

                $i = 2;
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arEmpleados as $arEmpleados) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "5001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, $arEmpleados->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEmpleados->getApellido1())
                            ->setCellValue('E' . $i, $arEmpleados->getApellido2())
                            ->setCellValue('F' . $i, $arEmpleados->getNombre1())
                            ->setCellValue('G' . $i, $arEmpleados->getNombreCorto())
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arEmpleados->getDireccion())
                            ->setCellValue('J' . $i, $arEmpleados->getCiudadRel()->getCodigoDepartamentoFk())
                            ->setCellValue('K' . $i, $arEmpleados->getCodigoCiudadFk())
                            ->setCellValue('L' . $i, "169")
                            ->setCellValue('M' . $i, $arEmpleados->getCuenta())
                            ->setCellValue('N' . $i, "0")
                            ->setCellValue('O' . $i, "0")
                            ->setCellValue('P' . $i, "0")
                            ->setCellValue('Q' . $i, "0")
                            ->setCellValue('R' . $i, "0")
                            ->setCellValue('S' . $i, "0")
                            ->setCellValue('T' . $i, "0")
                            ->setCellValue('U' . $i, "0")
                            ->setCellValue('V' . $i, "0")
                            ->setCellValue('W' . $i, "0")
                            ->setCellValue('X' . $i, "0");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1011.xlsx"');
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
    
    private function generarFormato1012Excel() {
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
                            ->setCellValue('A1', 'Concepto')
                            ->setCellValue('B1', 'Tipo de Documento')
                            ->setCellValue('C1', 'Número Identificación')
                            ->setCellValue('D1', 'Primer apellido del informado')
                            ->setCellValue('E1', 'Segundo apellido del informado')
                            ->setCellValue('F1', 'Primer nombre del informado')
                            ->setCellValue('G1', 'Otros nombres del informado')
                            ->setCellValue('H1', 'Razón social informado')
                            ->setCellValue('I1', 'Dirección')
                            ->setCellValue('J1', 'Codigo departamento')
                            ->setCellValue('K1', 'Codigo municipio')
                            ->setCellValue('L1', 'País de Residencia o domicilio')
                            ->setCellValue('M1', 'Pago o abono en cuenta deducible')
                            ->setCellValue('N1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('O1', 'Pago o abono en cuenta NO deducible')
                            ->setCellValue('P1', 'IVA mayor valor del costo o gasto deducible')
                            ->setCellValue('Q1', 'IVA mayor valor del costo o gasto no deducible')
                            ->setCellValue('R1', 'Retención en la fuente practicada Renta')
                            ->setCellValue('S1', 'Retención en la fuente asumida Renta')
                            ->setCellValue('T1', 'Retención en la fuente practicada Iva Régimen común')
                            ->setCellValue('U1', 'Retención en la fuente asumida IVA Régimen Simplificado')
                            ->setCellValue('V1', 'Retención en la fuente practicada Iva no domiciliados')
                            ->setCellValue('W1', 'Retención en la fuente practicadas  CREE')
                            ->setCellValue('X1', 'Retención en la fuente asumidas CREE');

                $i = 2;
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findAll();
                $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold();
                foreach ($arEmpleados as $arEmpleados) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, "5001")
                            ->setCellValue('B' . $i, "13")
                            ->setCellValue('C' . $i, $arEmpleados->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEmpleados->getApellido1())
                            ->setCellValue('E' . $i, $arEmpleados->getApellido2())
                            ->setCellValue('F' . $i, $arEmpleados->getNombre1())
                            ->setCellValue('G' . $i, $arEmpleados->getNombreCorto())
                            ->setCellValue('H' . $i, "")
                            ->setCellValue('I' . $i, $arEmpleados->getDireccion())
                            ->setCellValue('J' . $i, $arEmpleados->getCiudadRel()->getCodigoDepartamentoFk())
                            ->setCellValue('K' . $i, $arEmpleados->getCodigoCiudadFk())
                            ->setCellValue('L' . $i, "169")
                            ->setCellValue('M' . $i, $arEmpleados->getCuenta())
                            ->setCellValue('N' . $i, "0")
                            ->setCellValue('O' . $i, "0")
                            ->setCellValue('P' . $i, "0")
                            ->setCellValue('Q' . $i, "0")
                            ->setCellValue('R' . $i, "0")
                            ->setCellValue('S' . $i, "0")
                            ->setCellValue('T' . $i, "0")
                            ->setCellValue('U' . $i, "0")
                            ->setCellValue('V' . $i, "0")
                            ->setCellValue('W' . $i, "0")
                            ->setCellValue('X' . $i, "0");
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MediosMagneticos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="F1012.xlsx"');
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
