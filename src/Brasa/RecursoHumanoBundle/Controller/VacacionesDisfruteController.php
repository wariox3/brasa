<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuVacacionDisfruteType;
use Doctrine\ORM\EntityRepository;

class VacacionesDisfruteController extends Controller
{
    var $strSqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoVacacion) {
                        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
                        if ($arVacaciones->getEstadoPagado() == 1 ) {
                            $objMensaje->Mensaje("error", "No se puede Eliminar el registro, por que ya fue pagada!", $this);
                        }
                        else {
                            $em->remove($arVacaciones);
                            $em->flush();
                        }
                    }
                }
                $this->filtrarLista($form);
                $this->listar();
            }

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            /*if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCredito();
                $objFormatoCredito->Generar($this, $this->strSqlLista);
            }*/
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arVacaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:VacacionesDisfrute:lista.html.twig', array(
            'arVacaciones' => $arVacaciones,
            'form' => $form->createView()
            ));
    }

    public function detalleAction($codigoVacacion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnLiquidar', 'submit', array('label'  => 'Liquidar',))            
            ->getForm();
        $form->handleRequest($request);

        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionDisfrute();
        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionDisfrute')->find($codigoVacacion);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoDetalleVacaciones = new \Brasa\RecursoHumanoBundle\Formatos\FormatoVacacionesDisfrutadas();
                $objFormatoDetalleVacaciones->Generar($this, $codigoVacacion);
            }
            if($form->get('BtnLiquidar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($codigoVacacion);
                return $this->redirect($this->generateUrl('brs_rhu_vacaciones_detalle', array('codigoVacacion' => $codigoVacacion)));
            }
            if($form->get('BtnEliminarDeduccion')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoVacacionDeduccion) {
                        $arVacacionDeduccion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
                        $arVacacionDeduccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionCredito')->find($codigoVacacionDeduccion);
                        $em->remove($arVacacionDeduccion);
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($codigoVacacion);
                return $this->redirect($this->generateUrl('brs_rhu_vacaciones_detalle', array('codigoVacacion' => $codigoVacacion)));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:VacacionesDisfrute:detalle.html.twig', array(
                    'arVacaciones' => $arVacaciones,
                    'form' => $form->createView()
                    ));
    }    
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionDisfrute')->listaDql(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion')
                    );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            //->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
    }

    public function nuevoAction($codigoContrato, $codigoVacacion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);        
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getCodigoEmpleadoFk());
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionDisfrute();
        if($codigoVacacion != 0) {
            $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionDisfrute')->find($codigoVacacion);
        } else {
            $arVacacion->setFecha(new \DateTime('now'));
            $arVacacion->setFechaDesde(new \DateTime('now'));
            $arVacacion->setFechaHasta(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuVacacionDisfruteType(), $arVacacion);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arVacacion = $form->getData();
            $arVacacion->setEmpleadoRel($arEmpleado);
            $arVacacion->setCentroCostoRel($arEmpleado->getCentroCostoRel());
            $arVacacion->setContratoRel($arContrato);
            $intDias = $arVacacion->getFechaDesde()->diff($arVacacion->getFechaHasta());
            $intDias = $intDias->format('%a');
            $intDias += 1;
            $arVacacion->setDias($intDias);
            $em->persist($arVacacion);
            $em->flush();            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                            
        }
        return $this->render('BrasaRecursoHumanoBundle:VacacionesDisfrute:nuevo.html.twig', array(
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }

    public function detalleNuevoAction($codigoVacacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arVacacion->getCodigoEmpleadoFk());
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $floVrDeducciones = 0;
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        $arVacacionCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
                        $arVacacionCredito->setCreditoRel($arCreditos);
                        $arVacacionCredito->setVacacionRel($arVacacion);
                        $arVacacionCredito->setVrDeduccion($arCreditos->getSaldoTotal());
                        $em->persist($arVacacionCredito);
                        $floVrDeducciones += $arCreditos->getSaldoTotal();
            }
            $em->flush();
            $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($arVacacion->getCodigoVacacionPk());
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Vacaciones:detallenuevo.html.twig', array(
            'arCreditos' => $arCreditos,
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
                            ->setCellValue('B1', 'Centro Costo')
                            ->setCellValue('C1', 'Desde')
                            ->setCellValue('D1', 'Hasta')
                            ->setCellValue('E1', 'Identificación')
                            ->setCellValue('F1', 'Empleado')
                            ->setCellValue('G1', 'Dias')
                            ->setCellValue('H1', 'Vr Vacaciones')
                            ->setCellValue('I1', 'Pagado');

                $i = 2;
                $query = $em->createQuery($this->strSqlLista);
                $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                $arVacaciones = $query->getResult();

                foreach ($arVacaciones as $arVacacion) {
                    if ($arVacacion->getEstadoPagado() == 1)
                    {
                        $Estado = "SI";
                    }
                    else
                    {
                        $Estado = "NO";
                    }

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arVacacion->getCodigoVacacionPk())
                            ->setCellValue('B' . $i, $arVacacion->getCentroCostoRel()->getNombre())
                            ->setCellValue('C' . $i, $arVacacion->getFechaDesde())
                            ->setCellValue('D' . $i, $arVacacion->getFechaHasta())
                            ->setCellValue('E' . $i, $arVacacion->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('F' . $i, $arVacacion->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('G' . $i, $arVacacion->getDiasVacaciones())
                            ->setCellValue('H' . $i, round($arVacacion->getVrVacacion()))
                            ->setCellValue('I' . $i, $Estado);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Vacaciones');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Vacaciones.xlsx"');
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
