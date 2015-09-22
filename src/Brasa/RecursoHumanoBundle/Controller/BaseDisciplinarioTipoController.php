<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDisciplinarioTipoType;

/**
 * RhuDisciplinarioTipo controller.
 *
 */
class BaseDisciplinarioTipoController extends Controller
{
    var $strDqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoDisciplinarioTipo) {
                    $arDisciplinarioTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo();
                    $arDisciplinarioTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioTipo')->find($codigoDisciplinarioTipo);
                    $em->remove($arDisciplinarioTipo);
                    $em->flush();                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_base_disciplinario_tipo_lista'));
            }                        
        }
        
        $arDisciplinarioTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Base/DisciplinarioTipo:listar.html.twig', array(
                    'arDisciplinarioTipos' => $arDisciplinarioTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoDisciplinarioTipo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDisciplinarioTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo();
        if ($codigoDisciplinarioTipo != 0) {
            $arDisciplinarioTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioTipo')->find($codigoDisciplinarioTipo);
        }    
        $form = $this->createForm(new RhuDisciplinarioTipoType(), $arDisciplinarioTipo);
        $form->handleRequest($request);
        if ($form->isValid()) {                        
            $arDisciplinarioTipo = $form->getData();
            $em->persist($arDisciplinarioTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_disciplinario_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DisciplinarioTipo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();        
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioTipo')->listaDql();         
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
                    ->setCellValue('A1', 'Codigo')
                    ->setCellValue('B1', 'Nombre');
        $i = 2;
        $arDisciplinarioTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioTipo')->findAll();

        foreach ($arDisciplinarioTipos as $arDisciplinarioTipos) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arDisciplinarioTipos->getcodigoExamenTipoPk())
                    ->setCellValue('B' . $i, $arDisciplinarioTipos->getnombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Examenes_Tipos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Examen_Tipo.xlsx"');
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
