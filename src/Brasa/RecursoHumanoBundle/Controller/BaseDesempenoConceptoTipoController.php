<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDesempenoConceptoTipoType;

/**
 * RhuBaseDesempenoConceptoTipo controller.
 *
 */
class BaseDesempenoConceptoTipoController extends Controller
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
        $arDesempenoConceptoTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConceptoTipo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoDesempenoConceptoTipo) {
                    $arDesempenoConceptoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConceptoTipo();
                    $arDesempenoConceptoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConceptoTipo')->find($codigoDesempenoConceptoTipo);
                    $em->remove($arDesempenoConceptoTipo);
                    $em->flush();
                }
            }
              
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }
        }
        $arDesempenoConceptoTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConceptoTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConceptoTipo')->findAll();
        $arDesempenoConceptoTipos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/DesempenoConceptoTipo:listar.html.twig', array(
                    'arDesempenoConceptoTipos' => $arDesempenoConceptoTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoDesempenoConceptoTipo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDesempenoConceptoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConceptoTipo();
        if ($codigoDesempenoConceptoTipo != 0)
        {
            $arDesempenoConceptoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConceptoTipo')->find($codigoDesempenoConceptoTipo);
        }    
        $form = $this->createForm(new RhuDesempenoConceptoTipoType(), $arDesempenoConceptoTipo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arDesempenoConceptoTipo = $form->getData();
            $em->persist($arDesempenoConceptoTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_desempeno_concepto_tipo_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DesempenoConceptoTipo:nuevo.html.twig', array(
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
                    ->setCellValue('B1', 'TIPO CONCEPTO');
        $i = 2;
        $arDesempenoConceptoTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConceptoTipo')->findAll();

        foreach ($arDesempenoConceptoTipos as $arDesempenoConceptoTipo) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arDesempenoConceptoTipo->getcodigoDesempenoConceptoTipoPk())
                    ->setCellValue('B' . $i, $arDesempenoConceptoTipo->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('DesempenosConceptosTipos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="DesempenosConceptosTipos.xlsx"');
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