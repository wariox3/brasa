<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuProgramacionPagoType;

class ProgramacionesPagoController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($request->request->get('OpGenerarEmpleados')) {
                $codigoProgramacionPago = $request->request->get('OpGenerarEmpleados');
                $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generarEmpleados($codigoProgramacionPago);
                $arProgramacionPago->setEmpleadosGenerados(1);
                $em->persist($arProgramacionPago);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));
            }
            if($request->request->get('OpGenerar')) {
                $codigoProgramacionPago = $request->request->get('OpGenerar');                
                $strResultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generar($codigoProgramacionPago);
                if($strResultado == "") {
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));
                } else {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }                                                
            }
            if($request->request->get('OpDeshacer')) {
                $codigoProgramacionPago = $request->request->get('OpDeshacer');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->deshacer($codigoProgramacionPago);
                return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));
            }            
            if($request->request->get('OpPagar')) {
                $codigoProgramacionPago = $request->request->get('OpPagar');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->pagar($codigoProgramacionPago);
                return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));
            }            

            if($form->get('BtnEliminarPago')->isClicked()) {
                if ($arrSeleccionados > 0 ){
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        if($arProgramacionPago->getEstadoPagado() == 0 && $arProgramacionPago->getEstadoGenerado() == 0) {
                            $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->eliminar($codigoProgramacionPago);                            
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));
                }
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }            

        }

        $arProgramacionPago = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPago:lista.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'form' => $form->createView()));
    }

    public function nuevoAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();    
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();        
        $arProgramacionPago->setFechaDesde(new \DateTime('now'));
        $arProgramacionPago->setFechaHasta(new \DateTime('now'));
        $form = $this->createForm(new RhuProgramacionPagoType(), $arProgramacionPago);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arProgramacionPago = $form->getData();
            $em->persist($arProgramacionPago);
            $em->flush();                       
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                        
        }

        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPago:nuevo.html.twig', array(            
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $form = $this->createFormBuilder()
            ->add('BtnGenerarEmpleados', 'submit', array('label'  => 'Generar empleados',))
            ->add('BtnActualizarEmpleados', 'submit', array('label'  => 'Actualizar',))
            ->add('BtnEliminarEmpleados', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerarEmpleados')->isClicked()) {
                if($arProgramacionPago->getEstadoGenerado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generarEmpleados($codigoProgramacionPago);
                    $arProgramacionPago->setEmpleadosGenerados(1);
                    $em->persist($arProgramacionPago);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));                    
                } else {
                    $objMensaje->Mensaje("error", "No puede generar empleados cuando la programacion esta generada", $this);
                }                
            }

            if($form->get('BtnActualizarEmpleados')->isClicked()) {
                if($arProgramacionPago->getEstadoGenerado() == 0) {
                    $arrControles = $request->request->All();
                    $arEmpleadosDetalleProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findby(array('codigoProgramacionPagoFk' =>$codigoProgramacionPago));
                    $duoRegistrosDetalleEmpleados = count($arEmpleadosDetalleProgramacionPago);
                    $intIndice = 0;
                    if ($duoRegistrosDetalleEmpleados != 0){
                        foreach ($arrControles['LblCodigoDetalle'] as $intCodigo) {
                           if($arrControles['TxtHorasPeriodoReales'][$intIndice] != "") {
                               $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                               $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($intCodigo);
                               $arProgramacionPagoDetalle->setHorasPeriodoReales($arrControles['TxtHorasPeriodoReales'][$intIndice]);
                               $arProgramacionPagoDetalle->setDiasReales($arrControles['TxtDiasReales'][$intIndice]);
                               $em->persist($arProgramacionPagoDetalle);
                           }
                           $intIndice++;
                        }
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "No puede actualizar empleados cuando la programacion esta generada", $this);
                }
            }
            if($form->get('BtnEliminarEmpleados')->isClicked()) {
                if($arProgramacionPago->getEstadoGenerado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarSede');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoProgramacionPagoSede) {
                            $arProgramacionPagoDetalleSede = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
                            $arProgramacionPagoDetalleSede = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->find($codigoProgramacionPagoSede);
                            $em->remove($arProgramacionPagoDetalleSede);
                        }
                    }

                    $arrSeleccionados = $request->request->get('ChkSeleccionarEmpleado');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigo) {
                            $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                            $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($codigo);
                            $em->remove($arProgramacionPagoDetalle);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));                    
                } else {
                    $objMensaje->Mensaje("error", "No puede eliminar empleados cuando la programacion esta generada", $this);
                }
            }
        }
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPago->getCodigoCentroCostoFk());
        
        if($arProgramacionPago->getEstadoGenerado() == 1) {
            $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
            $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
            $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        } else {
            $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
            $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $arProgramacionPago->getCodigoCentroCostoFk(), 'pagoAplicado' => 0));
            $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
            $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->pendientesCentroCosto($arProgramacionPago->getCodigoCentroCostoFk());
            $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
            $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->pendientesCentroCosto($arProgramacionPago->getCodigoCentroCostoFk());            
        }
        
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->listaDQL($codigoProgramacionPago));
        $arProgramacionPagoDetalles = $paginator->paginate($query, $request->query->get('page', 1), 500);
        $arProgramacionPagoDetalleSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
        $arProgramacionPagoDetalleSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->findAll();
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
        }

        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPago:detalle.html.twig', array(
                    'arCentroCosto' => $arCentroCosto,
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'arIncapacidades' => $arIncapacidades,
                    'arLicencias' => $arLicencias,
                    'arProgramacionPagoDetalles' => $arProgramacionPagoDetalles,
                    'arProgramacionPagoDetalleSedes' => $arProgramacionPagoDetalleSedes,
                    'arProgramacionPago' => $arProgramacionPago,
                    'form' => $form->createView()
                    ));
    }

    public function detallePrimaAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $form = $this->createFormBuilder()
            ->add('BtnGenerarEmpleados', 'submit', array('label'  => 'Generar empleados',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerarEmpleados')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generarEmpleados($codigoProgramacionPago);
                $arProgramacionPago->setEmpleadosGenerados(1);
                $em->persist($arProgramacionPago);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle_prima', array('codigoProgramacionPago' => $codigoProgramacionPago)));
            }
        }
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPago->getCodigoCentroCostoFk());
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->listaDQL($codigoProgramacionPago));
        $arProgramacionPagoDetalles = $paginator->paginate($query, $request->query->get('page', 1), 500);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
        }

        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPago:detallePrima.html.twig', array(
                    'arCentroCosto' => $arCentroCosto,
                    'arProgramacionPagoDetalles' => $arProgramacionPagoDetalles,
                    'arProgramacionPago' => $arProgramacionPago,
                    'form' => $form->createView()
                    ));
    }

    public function agregarEmpleadoAction($codigoProgramacionPago) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
            $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('numeroIdentificacion' => $form->getData('numeroIdentificacion')));
            if(count($arEmpleado) > 0) {
                $intCodigoContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->ultimoContrato($arProgramacionPago->getCodigoCentroCostoFk(), $arEmpleado[0]->getCodigoEmpleadoPk());
                $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($intCodigoContrato);
                if(count($arContrato) > 0) {
                    $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                    $arProgramacionPagoDetalle->setEmpleadoRel($arEmpleado[0]);
                    $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                    $arProgramacionPagoDetalle->setFechaDesde($arContrato->getFechaDesde());
                    $arProgramacionPagoDetalle->setFechaHasta($arContrato->getFechaHasta());
                    $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
                    $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());
                    if($arContrato->getCodigoTipoTiempoFk() == 2) {
                        $arProgramacionPagoDetalle->setFactorDia(4);
                    } else {
                        $arProgramacionPagoDetalle->setFactorDia(8);
                    }

                    $em->persist($arProgramacionPagoDetalle);
                    $em->flush();
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";


        }

        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPago:agregarEmpleado.html.twig', array(
            'form' => $form->createView()));
    }

    public function inconsistenciasAction ($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arProgramacionPagoInconsistencias = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoInconsistencia();
        $arProgramacionPagoInconsistencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoInconsistencia')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPago:inconsistencias.html.twig', array(
            'arProgramacionPagoInconsistencias' => $arProgramacionPagoInconsistencias
            ));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedadesCentroCosto = array(
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
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $arrayPropiedadesTipo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoPagoTipo')) {
            $arrayPropiedadesTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoTipo", $session->get('filtroCodigoPagoTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)
            ->add('pagoTipoRel', 'entity', $arrayPropiedadesTipo)
            ->add('estadoGenerado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'GENERADO', '0' => 'SIN GENERAR'), 'data' => $session->get('filtroEstadoGenerado')))
            ->add('estadoPagado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'PAGADOS', '0' => 'SIN PAGAR'), 'data' => $session->get('filtroEstadoPagado')))
            ->add('fechaHasta', 'date', array('required' => true, 'widget' => 'single_text'))                
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminarPago', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaDQL(
                    "",
                    $session->get('filtroFechaHasta'),
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroEstadoGenerado'),
                    $session->get('filtroEstadoPagado'),
                    $session->get('filtroCodigoPagoTipo')
                    );
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroCodigoPagoTipo', $controles['pagoTipoRel']);
        $session->set('filtroEstadoGenerado', $form->get('estadoGenerado')->getData());
        $session->set('filtroEstadoPagado', $form->get('estadoPagado')->getData());
        if($form->get('fechaHasta')->getData()) {
            $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
        } else {
            $session->set('filtroFechaHasta', "");
        }
            
        
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Codigo');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arProgramacionesPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionesPagos = $query->getResult();
        foreach ($arProgramacionesPagos as $arProgramacionPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacionPago->getProgramacionPagoPk());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PrograPago');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PrograPago.xlsx"');
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
