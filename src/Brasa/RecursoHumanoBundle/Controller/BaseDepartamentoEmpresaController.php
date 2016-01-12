<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDepartamentoEmpresaType;

/**
 * RhuDepartamentoEmpresa controller.
 *
 */
class BaseDepartamentoEmpresaController extends Controller
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
        $arDepartamentosEmpresa = new \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoDepartamentoEmpresaPk) {
                    $arDepartamentosEmpresa = new \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa();
                    $arDepartamentosEmpresa = $em->getRepository('BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa')->find($codigoDepartamentoEmpresaPk);
                    $em->remove($arDepartamentosEmpresa);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_departamento_empresa_listar'));
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
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'NOMBRE');

                $i = 2;
                $arDepartamentosEmpresa = $em->getRepository('BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa')->findAll();
                
                foreach ($arDepartamentosEmpresa as $arDepartamentosEmpresa) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arDepartamentosEmpresa->getCodigoDepartamentoEmpresaPk())
                            ->setCellValue('B' . $i, $arDepartamentosEmpresa->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Departamentos empresa');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="DepartamentosEmpresa.xlsx"');
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
        $arDepartamentosEmpresa = new \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa')->findAll();
        $arDepartamentosEmpresa = $paginator->paginate($query, $this->get('request')->query->get('page', 1),50);

        return $this->render('BrasaRecursoHumanoBundle:Base/DepartamentosEmpresa:listar.html.twig', array(
                    'arDepartamentosEmpresa' => $arDepartamentosEmpresa,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoDepartamentoEmpresa) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDepartamentoEmpresa = new \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa();
        if ($codigoDepartamentoEmpresa != 0)
        {
            $arDepartamentoEmpresa = $em->getRepository('BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa')->find($codigoDepartamentoEmpresa);
        }    
        $formDepartamentoEmpresa = $this->createForm(new RhuDepartamentoEmpresaType(), $arDepartamentoEmpresa);
        $formDepartamentoEmpresa->handleRequest($request);
        if ($formDepartamentoEmpresa->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arDepartamentoEmpresa);
            $arDepartamentoEmpresa = $formDepartamentoEmpresa->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_departamento_empresa_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DepartamentosEmpresa:nuevo.html.twig', array(
            'formDepartamentoEmpresa' => $formDepartamentoEmpresa->createView(),
        ));
    }
}
