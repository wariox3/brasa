<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCargoType;

/**
 * RhuCargo controller.
 *
 */
class BaseCargoController extends Controller
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
        $arCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuCargo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoCargoPk) {
                    $arCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuCargo();
                    $arCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCargo')->find($codigoCargoPk);
                    $em->remove($arCargo);
                    $em->flush();
                }
            }
            
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoCargo = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCargo();
                $objFormatoCargo->Generar($this);
        }    
        
        if($form->get('BtnExcel')->isClicked()) {
                $objPHPExcel = new \PHPExcel();
                // Set document properties
                $objPHPExcel->getProperties()->setCreator("JG Efectivos")
                    ->setLastModifiedBy("JG Efectivos")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Nombre');

                $i = 2;
                $arCargos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCargo')->findAll();
                
                foreach ($arCargos as $arCargos) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCargos->getCodigoCargoPk())
                            ->setCellValue('B' . $i, $arCargos->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Cargos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Cargos.xlsx"');
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
        $arCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuCargo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuCargo')->findAll();
        $arCargos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/Cargo:listar.html.twig', array(
                    'arCargos' => $arCargos,
                    'form'=> $form->createView()
           
        ));
    }
    public function nuevoAction($codigoCargoPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuCargo();
        if ($codigoCargoPk != 0)
        {
            $arCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCargo')->find($codigoCargoPk);
        }    
        $formCargo = $this->createForm(new RhuCargoType(), $arCargo);
        $formCargo->handleRequest($request);
        if ($formCargo->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arCargo);
            $arCargo = $formCargo->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_cargo_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Cargo:nuevo.html.twig', array(
            'formCargo' => $formCargo->createView(),
        ));
    }
    
}
