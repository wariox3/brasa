<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoInformacionInternaTipoType;

/**
 * BaseEmpleadoInformacionInternaTipo  Controller.
 *
 */
class BaseEmpleadoInformacionInternaTipoController extends Controller
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
        $arEmpleadoInformacionInternaTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoEmpleadoInformacionInternaTipo) {
                    $arEmpleadoInformacionInternaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo();
                    $arEmpleadoInformacionInternaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo')->find($codigoEmpleadoInformacionInternaTipo);
                    $em->remove($arEmpleadoInformacionInternaTipo);
                    $em->flush();
                }
            }
              
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }
        }
        $arEmpleadoInformacionInternaTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo')->findAll();
        $arEmpleadoInformacionInternaTipos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoInformacionInternaTipo:listar.html.twig', array(
                    'arEmpleadoInformacionInternaTipos' => $arEmpleadoInformacionInternaTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoInformacionInternaTipo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arEmpleadoInformacionInternaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo();
        if ($codigoInformacionInternaTipo != 0)
        {
            $arEmpleadoInformacionInternaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo')->find($codigoInformacionInternaTipo);
        }    
        $form = $this->createForm(new RhuEmpleadoInformacionInternaTipoType(), $arEmpleadoInformacionInternaTipo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arEmpleadoInformacionInternaTipo = $form->getData();
            $em->persist($arEmpleadoInformacionInternaTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_empleado_informacion_interna_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoInformacionInternaTipo:nuevo.html.twig', array(
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'INFORMACIÓN INTERNA TIPO');
        $i = 2;
        $arEmpleadoInformacionInternaTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo')->findAll();

        foreach ($arEmpleadoInformacionInternaTipos as $arEmpleadoInformacionInternaTipo) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleadoInformacionInternaTipo->getCodigoEmpleadoInformacionInternaTipoPk())
                    ->setCellValue('B' . $i, $arEmpleadoInformacionInternaTipo->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('InformacionInternaTipo');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="InformacionInternaTipo.xlsx"');
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
