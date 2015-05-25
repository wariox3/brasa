<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoType;
use Doctrine\ORM\EntityRepository;

class BuscquedaController extends Controller
{
    public function terceroAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();

        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();

        if($form->isValid()) {
            if($form->get('BtnBuscar')->isClicked()) {
                $objCentroCosto = $form->get('centroCostoRel')->getData();
                if($objCentroCosto != null) {
                    $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
                } else {
                    $codigoCentroCosto = "";
                }
                $session->set('dqlEmpleado', $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaDQL(
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
                $arEmpleados = $query->getResult();
                foreach ($arEmpleados as $arEmpleado) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arEmpleado->getCodigoEmpleadoPk())
                            ->setCellValue('B' . $i, $arEmpleado->getNumeroIdentificacion())
                            ->setCellValue('C' . $i, $arEmpleado->getNombreCorto());
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

            if($form->get('BtnInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoEmpleado) {
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
                        if($arEmpleado->getEstadoActivo() == 1) {
                            $arEmpleado->setEstadoActivo(0);
                        } else {
                            $arEmpleado->setEstadoActivo(1);
                        }
                        $em->persist($arEmpleado);
                    }
                    $em->flush();
                }
            }
        } else {
           $session->set('dqlEmpleado', $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaDQL(
                   $session->get('filtroNombre'),
                   $session->get('filtroCentroCosto'),
                   $session->get('filtroActivos'),
                   $session->get('filtroIdentificacion'),
                   ""
                   ));
        }

        $query = $em->createQuery($session->get('dqlEmpleado'));
        $arEmpleados = $paginator->paginate($query, $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Busquedas:buscarTercero.html.twig', array(
            'arEmpleados' => $arEmpleados,

            ));
    }

}
