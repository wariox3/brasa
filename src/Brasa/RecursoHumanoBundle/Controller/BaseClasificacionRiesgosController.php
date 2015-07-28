<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuClasificacionRiesgosType;

/**
 * RhuClasificacionRiesgos controller.
 *
 */
class BaseClasificacionRiesgosController extends Controller
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
        $arCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoClasificacionPk) {
                    $arClasificacion = new \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo();
                    $arClasificacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuClasificacionRiesgo')->find($codigoClasificacionPk);
                    $em->remove($arClasificacion);
                    $em->flush();
                }
            }
            
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoClasificacion = new \Brasa\RecursoHumanoBundle\Formatos\FormatoClasificacion();
                $objFormatoClasificacion->Generar($this);
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
                            ->setCellValue('B1', 'Nombre')
                            ->setCellValue('C1', 'Porcentaje');

                $i = 2;
                $arClasificaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuClasificacionRiesgo')->findAll();
                
                foreach ($arClasificaciones as $arClasificaciones) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arClasificaciones->getCodigoClasificacionRiesgoPk())
                            ->setCellValue('B' . $i, $arClasificaciones->getNombre())
                            ->setCellValue('C' . $i, $arClasificaciones->getPorcentaje());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('ClasificacionRiesgos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="ClasificacionRiesgos.xlsx"');
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
        $arClasificaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuClasificacionRiesgo')->findAll();
        $arClasificaciones = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/Clasificacion:listar.html.twig', array(
                    'arClasificaciones' => $arClasificaciones,
                    'form'=> $form->createView()
           
        ));
    }
    public function nuevoAction($codigoClasificacionPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arClasificacion = new \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo();
        if ($codigoClasificacionPk != 0)
        {
            $arClasificacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuClasificacionRiesgo')->find($codigoClasificacionPk);
        }    
        $formClasificacion = $this->createForm(new RhuClasificacionRiesgosType(), $arClasificacion);
        $formClasificacion->handleRequest($request);
        if ($formClasificacion->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arClasificacion);
            $arClasificacion = $formClasificacion->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_clasificacion_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Clasificacion:nuevo.html.twig', array(
            'formClasificacion' => $formClasificacion->createView(),
        ));
    }
    
}
