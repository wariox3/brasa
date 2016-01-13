<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuRequisitoCargoType;

/**
 * RhuRequisitoCargo controller.
 *
 */
class BaseRequisitoCargoController extends Controller
{
    var $strDqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista(); 
        $form->handleRequest($request);     
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnEliminar')->isClicked()) {                           
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoRequisitoCargo) {
                        $arRequisitoCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoCargo')->find($codigoRequisitoCargo);
                        $em->remove($arRequisitoCargo);
                        $em->flush();
                    }
                }                
            }
            
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }            
        }                
        $arRequisitosCargos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/RequisitoCargo:lista.html.twig', array(
                    'arRequisitosCargos' => $arRequisitosCargos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoRequisitoCargo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arRequisitoCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo();
        if ($codigoRequisitoCargo != 0) {
            $arRequisitoCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoCargo')->find($codigoRequisitoCargo);
        }    
        $form = $this->createForm(new RhuRequisitoCargoType(), $arRequisitoCargo);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arRequisitoCargo = $form->getData();
            $em->persist($arRequisitoCargo);            
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_requisito_cargo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/RequisitoCargo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function formularioLista() {
        $form = $this->createFormBuilder()                        
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))                
            ->getForm();        
        return $form;
    }     
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoCargo')->listaDql();         
    }    
    
    private function generarExcel() {
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CARGO')
                    ->setCellValue('C1', 'REQUISITO');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arRequisitosCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arRequisitosCargos = $query->getResult();
        foreach ($arRequisitosCargos as $arRequisitoCargo) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRequisitoCargo->getCodigoRequisitoCargoPk())
                    ->setCellValue('B' . $i, $arRequisitoCargo->getCargoRel()->getNombre())
                    ->setCellValue('C' . $i, $arRequisitoCargo->getRequisitoConceptoRel()->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('RequistosCargos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="RequisitosCargos.xlsx"');
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
