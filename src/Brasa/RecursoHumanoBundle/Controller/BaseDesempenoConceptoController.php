<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDesempenoConceptoType;

/**
 * RhuBaseDesempenoConcepto controller.
 *
 */
class BaseDesempenoConceptoController extends Controller
{

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arDesempenoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoDesempenoConcepto) {
                    $arDesempenoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto();
                    $arDesempenoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->find($codigoDesempenoConcepto);
                    $em->remove($arDesempenoConcepto);
                    $em->flush();
                }
            }
              
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }
        }
        $arDesempenoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->findAll();
        $arDesempenoConceptos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);

        return $this->render('BrasaRecursoHumanoBundle:Base/DesempenoConcepto:listar.html.twig', array(
                    'arDesempenoConceptos' => $arDesempenoConceptos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoDesempenoConcepto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDesempenoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto();
        if ($codigoDesempenoConcepto != 0)
        {
            $arDesempenoConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->find($codigoDesempenoConcepto);
        }    
        $form = $this->createForm(new RhuDesempenoConceptoType(), $arDesempenoConceptos);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arDesempenoConceptos = $form->getData();
            $em->persist($arDesempenoConceptos);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_desempeno_concepto_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DesempenoConcepto:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'TIPO CONCEPTO')
                    ->setCellValue('C1', 'NOMBRE');
        $i = 2;
        $arDesempenoConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->findAll();

        foreach ($arDesempenoConceptos as $arDesempenoConcepto) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arDesempenoConcepto->getcodigoDesempenoConceptoPk())
                    ->setCellValue('B' . $i, $arDesempenoConcepto->getDesempenoConceptoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arDesempenoConcepto->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('DesempenosConceptos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="DesempenosConceptos.xlsx"');
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
