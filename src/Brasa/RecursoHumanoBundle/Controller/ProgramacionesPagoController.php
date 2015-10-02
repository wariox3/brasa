<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\RecursoHumanoBundle\Form\Type\RhuProgramacionPagoType;

class ProgramacionesPagoController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
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
            if($request->request->get('OpLiquidar')) {
                $codigoProgramacionPago = $request->request->get('OpLiquidar');
                $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                if($arProgramacionPago->getEstadoGenerado() == 1 && $arProgramacionPago->getEstadoPagado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->liquidar($codigoProgramacionPago);                    
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
            if($request->request->get('OpExcelDetalle')) {
                $codigoProgramacionPago = $request->request->get('OpExcelDetalle');
                $this->generarExcelDetalle($codigoProgramacionPago);
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
                $this->filtrarLista($form, $request);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            }

        }

        $arProgramacionPago = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPago:lista.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'form' => $form->createView()));
    }

    public function nuevoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago->setFechaDesde(new \DateTime('now'));
        $arProgramacionPago->setFechaHasta(new \DateTime('now'));
        $form = $this->createForm(new RhuProgramacionPagoType(), $arProgramacionPago);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arProgramacionPago = $form->getData();
            $arProgramacionPago->setFechaHastaReal($arProgramacionPago->getFechaHasta());
            $arProgramacionPago->setNoGeneraPeriodo(1);
            $em->persist($arProgramacionPago);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }

        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPago:nuevo.html.twig', array(
            'form' => $form->createView()));
    }

    public function detalleAction($codigoProgramacionPago, Request $request) {
        $em = $this->getDoctrine()->getManager();
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
            $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->periodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta());                       
            $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
            $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->periodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta());                       
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

    public function detallePrimaAction($codigoProgramacionPago, Request $request) {
        $em = $this->getDoctrine()->getManager();
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

    public function agregarEmpleadoAction($codigoProgramacionPago, Request $request) {
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
        $session = $this->get('session');
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
        $session = $this->get('session');
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaDQL(
                    "",
                    $session->get('filtroFechaHasta'),
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroEstadoGenerado'),
                    $session->get('filtroEstadoPagado'),
                    $session->get('filtroCodigoPagoTipo')
                    );
    }

    private function filtrarLista($form, Request $request) {
        $session = $this->get('session');
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
        ob_clean();
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
                    ->setCellValue('A1', 'Código')
                    ->setCellValue('B1', 'Tipo')
                    ->setCellValue('C1', 'Centro Costos')
                    ->setCellValue('D1', 'Periodo')
                    ->setCellValue('E1', 'Desde')
                    ->setCellValue('F1', 'Hasta')
                    ->setCellValue('G1', 'Días')
                    ->setCellValue('H1', 'Empleados')
                    ->setCellValue('I1', 'Estado Generado')
                    ->setCellValue('J1', 'Estado Pagado')
                    ->setCellValue('K1', 'Exportado Banco')
                    ->setCellValue('L1', 'Neto');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arProgramacionesPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionesPagos = $query->getResult();
        foreach ($arProgramacionesPagos as $arProgramacionPago) {
            if ($arProgramacionPago->getEstadoGenerado() == 1){
                $estadoGenerado = "SI";
            } else {
                $estadoGenerado = "NO";
            }
            if ($arProgramacionPago->getEstadoPagado() == 1){
                $estadoPagado = "SI";
            } else {
                $estadoPagado = "NO";
            }
            if ($arProgramacionPago->getArchivoExportadoBanco() == 1){
                $archivoExportado = "SI";
            } else {
                $archivoExportado = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacionPago->getCodigoProgramacionPagoPk())
                    ->setCellValue('B' . $i, $arProgramacionPago->getPagoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arProgramacionPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('D' . $i, $arProgramacionPago->getCentroCostoRel()->getPeriodoPagoRel()->getNombre())
                    ->setCellValue('E' . $i, $arProgramacionPago->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arProgramacionPago->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arProgramacionPago->getDias())
                    ->setCellValue('H' . $i, $arProgramacionPago->getNumeroEmpleados())
                    ->setCellValue('I' . $i, $estadoGenerado)
                    ->setCellValue('J' . $i, $estadoPagado)
                    ->setCellValue('K' . $i, $archivoExportado)
                    ->setCellValue('L' . $i, $arProgramacionPago->getVrNeto());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('ProgramacionesPago');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ProgramacionesPago.xlsx"');
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

    private function generarExcelDetalle($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        ob_clean();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        if($arProgramacionPago->getEstadoGenerado() == 1) {
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
                        ->setCellValue('B1', 'Identificación')
                        ->setCellValue('C1', 'Nombre')
                        ->setCellValue('D1', 'Desde')
                        ->setCellValue('E1', 'Hasta')
                        ->setCellValue('F1', 'Salario')
                        ->setCellValue('G1', 'Devengado')
                        ->setCellValue('H1', 'Deducciones')
                        ->setCellValue('I1', 'Neto')
                        ->setCellValue('J1', 'IBP')
                        ->setCellValue('K1', 'IBC');
            $i = 2;

            $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
            $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
            foreach ($arPagos as $arPago) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                        ->setCellValue('B' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                        ->setCellValue('C' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                        ->setCellValue('D' . $i, $arPago->getFechaDesde()->format('Y/m/d'))
                        ->setCellValue('E' . $i, $arPago->getFechaHasta()->format('Y/m/d'))
                        ->setCellValue('F' . $i, $arPago->getVrSalario())
                        ->setCellValue('G' . $i, $arPago->getVrDevengado())
                        ->setCellValue('H' . $i, $arPago->getVrDeducciones())
                        ->setCellValue('I' . $i, $arPago->getVrNeto())
                        ->setCellValue('J' . $i, $arPago->getVrIngresoBasePrestacion())
                        ->setCellValue('K' . $i, $arPago->getVrIngresoBaseCotizacion());
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('Pagos');
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->createSheet(2)->setTitle('PagosDetalle')
                    ->setCellValue('A1', 'Codigo')
                    ->setCellValue('B1', 'Codigo')
                    ->setCellValue('C1', 'VrPago')
                    ->setCellValue('D1', 'Op')
                    ->setCellValue('E1', 'Op')
                    ->setCellValue('F1', 'VrHora')
                    ->setCellValue('G1', 'VrDia')
                    ->setCellValue('H1', 'VrTotal')
                    ->setCellValue('I1', 'Horas')
                    ->setCellValue('J1', 'Dias')
                    ->setCellValue('K1', 'Porcentaje')                    
                    ->setCellValue('L1', 'IBC')
                    ->setCellValue('M1', 'IBP');

            $i = 2;
            $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
            $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->pagosDetallesProgramacionPago($codigoProgramacionPago);            
            foreach ($arPagoDetalles as $arPagoDetalle) {
                $objPHPExcel->setActiveSheetIndex(1)
                        ->setCellValue('A' . $i, $arPagoDetalle->getCodigoPagoDetallePk())
                        ->setCellValue('B' . $i, $arPagoDetalle->getPagoConceptoRel()->getNombre())
                        ->setCellValue('C' . $i, $arPagoDetalle->getVrPago())
                        ->setCellValue('D' . $i, $arPagoDetalle->getOperacion())
                        ->setCellValue('E' . $i, $arPagoDetalle->getVrPagoOperado())
                        ->setCellValue('F' . $i, $arPagoDetalle->getVrHora())
                        ->setCellValue('G' . $i, $arPagoDetalle->getVrDia())
                        ->setCellValue('H' . $i, $arPagoDetalle->getVrTotal())
                        ->setCellValue('I' . $i, $arPagoDetalle->getNumeroHoras())
                        ->setCellValue('J' . $i, $arPagoDetalle->getNumeroDias())
                        ->setCellValue('K' . $i, $arPagoDetalle->getPorcentajeAplicado())
                        ->setCellValue('L' . $i, $arPagoDetalle->getVrIngresoBasePrestacion())
                        ->setCellValue('M' . $i, $arPagoDetalle->getVrIngresoBaseCotizacion());
                $i++;
            }            
            
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Pagos.xlsx"');
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
        } else {
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
                        ->setCellValue('B1', 'Identificación')
                        ->setCellValue('C1', 'Nombre')
                        ->setCellValue('D1', 'Desde')
                        ->setCellValue('E1', 'Hasta')
                        ->setCellValue('F1', 'Dias')
                        ->setCellValue('G1', 'Salario')
                        ->setCellValue('H1', 'Devengado')
                        ->setCellValue('I1', 'Deducciones')
                        ->setCellValue('J1', 'Neto');

            $i = 2;

            $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
            $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
            foreach ($arProgramacionPagoDetalle as $arProgramacionPagoDetalle) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $arProgramacionPagoDetalle->getCodigoProgramacionPagoDetallePk())
                        ->setCellValue('B' . $i, $arProgramacionPagoDetalle->getEmpleadoRel()->getNumeroIdentificacion())
                        ->setCellValue('C' . $i, $arProgramacionPagoDetalle->getEmpleadoRel()->getNombreCorto())
                        ->setCellValue('D' . $i, $arProgramacionPagoDetalle->getFechaDesde()->format('Y/m/d'))
                        ->setCellValue('E' . $i, $arProgramacionPagoDetalle->getFechaHasta()->format('Y/m/d'))
                        ->setCellValue('F' . $i, $arProgramacionPagoDetalle->getDias())
                        ->setCellValue('G' . $i, $arProgramacionPagoDetalle->getVrSalario())
                        ->setCellValue('H' . $i, $arProgramacionPagoDetalle->getVrDevengado())
                        ->setCellValue('I' . $i, $arProgramacionPagoDetalle->getVrDeducciones())
                        ->setCellValue('J' . $i, $arProgramacionPagoDetalle->getVrNetoPagar());
                $i++;
            }

            $objPHPExcel->getActiveSheet()->setTitle('ProgramacionPagoDetalle');
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="ProgramacionPagoDetalle.xlsx"');
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
}

