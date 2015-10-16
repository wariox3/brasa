<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoEstudioTipoType;

/**
 * RhuBaseEmpleadoEstudioTipo controller.
 *
 */
class BaseEmpleadoEstudioTipoController extends Controller
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
        $arTipoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoTipoEstudio) {
                    $arTipoEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo();
                    $arTipoEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo')->find($codigoTipoEstudio);
                    $em->remove($arTipoEstudio);
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

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Código')
                            ->setCellValue('B1', 'Estudio');
                $i = 2;
                $arTipoEstudios = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo')->findAll();
                
                foreach ($arTipoEstudios as $arTipoEstudio) {
                      
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arTipoEstudio->getCodigoEmpleadoEstudioTipoPk())
                            ->setCellValue('B' . $i, $arTipoEstudio->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('TiposEstudios');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="TiposEstudios.xlsx"');
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
        $arTipoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo')->findAll();
        $arTipoEstudios = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoEstudioTipo:listar.html.twig', array(
                    'arTipoEstudios' => $arTipoEstudios,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoTipoEstudio) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuBanco();
        if ($codigoBancoPk != 0)
        {
            $arBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuBanco')->find($codigoBancoPk);
        }    
        $formBanco = $this->createForm(new RhuBancoType(), $arBanco);
        $formBanco->handleRequest($request);
        if ($formBanco->isValid())
        {
            // guardar la tarea en la base de datos
            $arBanco = $formBanco->getData();
            $em->persist($arBanco);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_banco_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Banco:nuevo.html.twig', array(
            'formBanco' => $formBanco->createView(),
        ));
    }
    
}