<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionType;

class SeleccionController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_value' => "Todos",
                'mapped' => false,
                'data' => '',

            ))            
            ->add('estadoAprobado', 'choice', array('choices'   => array('2' => 'Todos', '1' => 'Aprobados', '0' => 'No aprobados')))                            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
            ->getForm();
        $form->handleRequest($request);

        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();

        if($form->isValid()) {
            if($form->get('BtnBuscar')->isClicked()) {
                $objCentroCosto = $form->get('centroCostoRel')->getData();
                if($objCentroCosto != null) {
                    $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
                } else {
                    $codigoCentroCosto = "";
                }
                $session->set('dqlEmpleado', $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->ListaDQL(
                        $form->get('TxtNombre')->getData(),
                        $codigoCentroCosto,
                        $form->get('estadoActivo')->getData(),
                        $form->get('TxtIdentificacion')->getData(),
                        ""
                        ));
                $session->set('filtroNombre', $form->get('TxtNombre')->getData());
                $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
                $session->set('filtroCentroCosto', $codigoCentroCosto);
                $session->set('filtroActivos', $form->get('estadoActivo')->getData());

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
                            ->setCellValue('B1', 'Identificacion')
                            ->setCellValue('C1', 'Nombre');

                $i = 2;
                $query = $em->createQuery($session->get('dqlEmpleado'));
                $arSeleccions = $query->getResult();
                foreach ($arSeleccions as $arSeleccion) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arSeleccion->getCodigoEmpleadoPk())
                            ->setCellValue('B' . $i, $arSeleccion->getNumeroIdentificacion())
                            ->setCellValue('C' . $i, $arSeleccion->getNombreCorto());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Empleados');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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
        } else {
           $session->set('dqlSeleccion', $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->ListaDQL(
                   $session->get('filtroNombre'),
                   $session->get('filtroCentroCosto'),
                   $session->get('filtroAprobados'),
                   $session->get('filtroIdentificacion')
                   ));
        }

        $query = $em->createQuery($session->get('dqlSeleccion'));
        $arSelecciones = $paginator->paginate($query, $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Seleccion:lista.html.twig', array(
            'arSelecciones' => $arSelecciones,
            'form' => $form->createView()
            ));
    }   

    public function nuevoAction($codigoSeleccion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        if($codigoSeleccion != 0) {
            $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        } else {
            $arSeleccion->setFechaPruebas(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuSeleccionType, $arSeleccion);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arSeleccion = $form->getData();
            $em->persist($arSeleccion);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_nuevo', array('codigoSeleccion' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
            }

        }

        return $this->render('BrasaRecursoHumanoBundle:Seleccion:nuevo.html.twig', array(
            'arSeleccion' => $arSeleccion,
            'form' => $form->createView()));
    }
}
