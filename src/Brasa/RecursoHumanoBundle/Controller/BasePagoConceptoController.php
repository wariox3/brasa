<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPagoConceptoType;

/**
 * RhuPagoConcepto controller.
 *
 */
class BasePagoConceptoController extends Controller
{

    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoPagoConceptoPk) {
                    $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                    $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($codigoPagoConceptoPk);
                    $em->remove($arPagoConcepto);
                    $em->flush();
                }
            }
            
        if($form->get('BtnExcel')->isClicked()) {
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
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Código')
                            ->setCellValue('B1', 'Nombre')
                            ->setCellValue('C1', 'Compone Salario')
                            ->setCellValue('D1', 'Compone Porcentaje')
                            ->setCellValue('E1', 'Compone Valor')
                            ->setCellValue('F1', 'Por Porcentaje')
                            ->setCellValue('G1', 'Prestacional')
                            ->setCellValue('H1', 'Operación')
                            ->setCellValue('I1', 'Concepto Adición')
                            ->setCellValue('J1', 'Concepto Incapacidad')
                            ->setCellValue('K1', 'Concepto Auxilio Transporte')
                            ->setCellValue('L1', 'Código Cuenta')
                            ->setCellValue('M1', 'Tipo Cuenta')
                            ->setCellValue('N1', 'Concepto Pensión')
                            ->setCellValue('O1', 'Concepto Salud')
                            ->setCellValue('P1', 'Tipo Adicional');

                $i = 2;
                $arPagoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto;
                $arPagoConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findAll();
                
                foreach ($arPagoConceptos as $arPagoConcepto) {
                    if($arPagoConcepto->getComponeSalario() == 1){
                       $componeSalario = "SI";
                    }else{
                       $componeSalario = "NO"; 
                    }
                    if($arPagoConcepto->getComponePorcentaje() == 1){
                       $componePorcentaje = "SI";
                    }else{
                       $componePorcentaje = "NO"; 
                    }
                    if($arPagoConcepto->getComponeValor() == 1){
                       $componeValor = "SI";
                    }else{
                       $componeValor = "NO"; 
                    }
                    if($arPagoConcepto->getPrestacional() == 1){
                       $prestacional = "SI";
                    }else{
                       $prestacional = "NO"; 
                    }
                    if($arPagoConcepto->getConceptoAdicion() == 1){
                       $conceptoAdicion = "SI";
                    }else{
                       $conceptoAdicion = "NO"; 
                    }
                    if($arPagoConcepto->getConceptoIncapacidad() == 1){
                       $conceptoIncapacidad = "SI";
                    }else{
                       $conceptoIncapacidad = "NO"; 
                    }
                    if($arPagoConcepto->getConceptoAuxilioTransporte() == 1){
                       $conceptoAuxTransporte = "SI";
                    }else{
                       $conceptoAuxTransporte = "NO"; 
                    }
                    if($arPagoConcepto->getConceptoPension() == 1){
                       $conceptoPension = "SI";
                    }else{
                       $conceptoPension = "NO"; 
                    }
                    if($arPagoConcepto->getConceptoSalud() == 1){
                       $conceptoSalud = "SI";
                    }else{
                       $conceptoSalud = "NO"; 
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arPagoConcepto->getCodigoPagoConceptoPk())
                            ->setCellValue('B' . $i, $arPagoConcepto->getNombre())
                            ->setCellValue('C' . $i, $componeSalario)
                            ->setCellValue('D' . $i, $componePorcentaje)
                            ->setCellValue('E' . $i, $componeValor)
                            ->setCellValue('F' . $i, $arPagoConcepto->getPorPorcentaje())
                            ->setCellValue('G' . $i, $prestacional)
                            ->setCellValue('H' . $i, $arPagoConcepto->getOperacion())
                            ->setCellValue('I' . $i, $conceptoAdicion)
                            ->setCellValue('J' . $i, $conceptoIncapacidad)
                            ->setCellValue('K' . $i, $conceptoAuxTransporte)
                            ->setCellValue('L' . $i, $arPagoConcepto->getCodigoCuentaFk())
                            ->setCellValue('M' . $i, $arPagoConcepto->getTipoCuenta())
                            ->setCellValue('N' . $i, $conceptoPension)
                            ->setCellValue('O' . $i, $conceptoSalud)
                            ->setCellValue('P' . $i, $arPagoConcepto->getTipoAdicional());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('PagoConcepto');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="PagoConcepto.xlsx"');
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
        $arPagoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findAll();
        $arPagoConceptos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);

        return $this->render('BrasaRecursoHumanoBundle:Base/PagoConcepto:listar.html.twig', array(
                    'arPagoConceptos' => $arPagoConceptos,
                    'form'=> $form->createView()
        ));
    }
    
    public function nuevoAction($codigoPagoConcepto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        if ($codigoPagoConcepto != 0)
        {
            $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($codigoPagoConcepto);
        }    
        $form = $this->createForm(new RhuPagoConceptoType(), $arPagoConcepto);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arPagoConcepto = $form->getData();
            $em->persist($arPagoConcepto);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_pago_concepto_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/PagoConcepto:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
}
