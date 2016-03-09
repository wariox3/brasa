<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenCargoType;
use Doctrine\ORM\EntityRepository;

/**
 * RhuExamenCargo controller.
 *
 */
class BaseExamenCargoController extends Controller
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
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoExamenCargo) {
                        $arExamenCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenCargo')->find($codigoExamenCargo);
                        $em->remove($arExamenCargo);
                        $em->flush();
                    }
                }                
            }
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }            
        }                
        $arExamenesCargos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/ExamenCargo:lista.html.twig', array(
                    'arExamenesCargos' => $arExamenesCargos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoExamenCargo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arExamenCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo();
        if ($codigoExamenCargo != 0) {
            $arExamenCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenCargo')->find($codigoExamenCargo);
        }    
        $form = $this->createForm(new RhuExamenCargoType(), $arExamenCargo);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arExamenCargo = $form->getData();
            $em->persist($arExamenCargo);            
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_examen_cargo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/ExamenCargo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function nuevoMultipleAction($codigoExamenCargo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arExamenesTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
        $arExamenesTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->findAll();
        $form = $this->createFormBuilder()
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'property' => 'nombre',
            ))    
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoExamenTipo) {                           
                        $arExamenTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
                        $arExamenTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->find($codigoExamenTipo);                                
                        $arCargo = $form->get('cargoRel')->getData();
                        $arExamenCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo();
                        $arExamenCargo->setCargoRel($arCargo);
                        $arExamenCargo->setExamenTipoRel($arExamenTipo);
                        $em->persist($arExamenCargo); 
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_base_examen_cargo_lista'));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/ExamenCargo:nuevoMultiple.html.twig', array(
            'form' => $form->createView(),
            'arExamenesTipo' => $arExamenesTipo,
        ));
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCargo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCargo", $session->get('filtroCodigoCargo'));
        }
        $form = $this->createFormBuilder()
            ->add('cargoRel', 'entity', $arrayPropiedades)    
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                
            ->getForm();        
        return $form;
    }     
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenCargo')->listaDql(
        $session->get('filtroCodigoCargo'));         
    }    
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCargo', $controles['cargoRel']);
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
                    ->setCellValue('C1', 'EXAMEN');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arExamenesCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo();
        $arExamenesCargos = $query->getResult();
        foreach ($arExamenesCargos as $arExamenCargo) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arExamenCargo->getCodigoExamenCargoPk())
                    ->setCellValue('B' . $i, $arExamenCargo->getCargoRel()->getNombre())
                    ->setCellValue('C' . $i, $arExamenCargo->getExamenTipoRel()->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ExamenesCargos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ExamenesCargos.xlsx"');
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
