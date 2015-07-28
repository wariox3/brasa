<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenTipoType;

/**
 * RhuExamenTipo controller.
 *
 */
class BaseExamenTipoController extends Controller
{

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnPdf', 'submit', array('label'  => 'PDF'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoExamenTipoPk) {
                    $arExamenTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
                    $arExamenTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->find($codigoExamenTipoPk);
                    $em->remove($arExamenTipo);
                    $em->flush();
                }
            }
            
            if($form->get('BtnPdf')->isClicked()) {
                $objFormatoTipoExamen = new \Brasa\RecursoHumanoBundle\Formatos\FormatoTipoExamen();
                $objFormatoTipoExamen->Generar($this);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
            
        }
        $arExamenTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->ordenarExamenTipos();
        $arExamenTipos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/ExamenTipo:listar.html.twig', array(
                    'arExamenTipos' => $arExamenTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoExamenTipoPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arExamenTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
        if ($codigoExamenTipoPk != 0)
        {
            $arExamenTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->find($codigoExamenTipoPk);
        }    
        $formExamenTipo = $this->createForm(new RhuExamenTipoType(), $arExamenTipo);
        $formExamenTipo->handleRequest($request);
        if ($formExamenTipo->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arExamenTipo);
            $arExamenTipo = $formExamenTipo->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_examentipo_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/ExamenTipo:nuevo.html.twig', array(
            'formExamenTipo' => $formExamenTipo->createView(),
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
                    ->setCellValue('A1', 'Codigo')
                    ->setCellValue('B1', 'Nombre');
        $i = 2;
        $arExamenTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->findAll();

        foreach ($arExamenTipos as $arExamenTipos) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arExamenTipos->getcodigoExamenTipoPk())
                    ->setCellValue('B' . $i, $arExamenTipos->getnombre());
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
