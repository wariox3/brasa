<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDotacionElementoType;

/**
 * RhuDotacionElemento controller.
 *
 */
class BaseDotacionElementoController extends Controller
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
        $arDotacionElementos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoDotacionElementoPk) {
                    $arDotacionElementos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
                    $arDotacionElementos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->find($codigoDotacionElementoPk);
                    $em->remove($arDotacionElementos);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_empleado_dotacion_lista'));
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

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Nombre');

                $i = 2;
                $arDotacionElementos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->findAll();
                
                foreach ($arDotacionElementos as $arDotacionElementos) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arDotacionElementos->getCodigoDotacionElementoPk())
                            ->setCellValue('B' . $i, $arDotacionElementos->getDotacion());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Dotacion Elementos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="DotacionElementos.xlsx"');
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
        $arDotacionElementos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->findAll();
        $arDotacionElementos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),50);

        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionElementos:listar.html.twig', array(
                    'arDotacionElementos' => $arDotacionElementos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoDotacionElemento) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDotacionElemento = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
        if ($codigoDotacionElemento != 0)
        {
            $arDotacionElemento = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->find($codigoDotacionElemento);
        }    
        $formDotacionElemento = $this->createForm(new RhuDotacionElementoType(), $arDotacionElemento);
        $formDotacionElemento->handleRequest($request);
        if ($formDotacionElemento->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arDotacionElemento);
            $arDotacionElemento = $formDotacionElemento->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_dotacionElemento_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionElementos:nuevo.html.twig', array(
            'formDotacionElemento' => $formDotacionElemento->createView(),
        ));
    }
    
}
