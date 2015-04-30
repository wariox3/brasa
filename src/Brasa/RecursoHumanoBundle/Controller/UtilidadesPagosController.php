<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UtilidadesPagosController extends Controller
{
    public function generarPeriodoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('Actualizar', 'submit')
            ->add('Generar', 'submit')
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('Generar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        $this->generarPeriodoPago($codigoCentroCosto);
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($codigoCentroCosto);
                    }
                }
            }
        }
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPeriodoPago.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'form' => $form->createView()
            ));
    }

    public function generarPagoAction () {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $form = $this->createFormBuilder()
            ->add('BtnVerificarPagosAdicionales', 'submit', array('label'  => 'Pagos adicionales verificados',))
            ->add('BtnVerificarIncapacidades', 'submit', array('label'  => 'Incapacidades verificadas',))
            ->add('BtnNoGenerar', 'submit', array('label'  => 'No generar pago',))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))
            ->getForm();
        $form->handleRequest($request);
        $frmPagar = $this->createFormBuilder()
            ->add('BtnGenerarArchivoBancolombia', 'submit', array('label'  => 'Generar archivo bancolombia',))
            ->add('BtnLiquidar', 'submit', array('label'  => 'Liquidar',))
            ->add('BtnPagar', 'submit', array('label'  => 'Pagar',))
            ->add('BtnAnular', 'submit', array('label'  => 'Anular',))
            ->getForm();
        $frmPagar->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                if($form->get('BtnGenerar')->isClicked()) {
                    $boolErrores = 0;
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
                        if($arProgramacionPagoProcesar->getVerificarIncapacidades() == 1 && $arProgramacionPagoProcesar->getVerificarPagosAdicionales() == 1) {
                            $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                            $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('codigoCentroCostoFk' => $arProgramacionPagoProcesar->getCodigoCentroCostoFk(), 'estadoActivo' => 1));
                            foreach ($arEmpleados as $arEmpleado) {
                                $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                                $arPago->setEmpleadoRel($arEmpleado);
                                $arPago->setCentroCostoRel($arCentroCosto);
                                $arPago->setFechaDesde($arProgramacionPagoProcesar->getFechaDesde());
                                $arPago->setFechaHasta($arProgramacionPagoProcesar->getFechaHasta());
                                $arPago->setVrSalario($arEmpleado->getVrSalario());
                                $arPago->setProgramacionPagoRel($arProgramacionPagoProcesar);
                                $em->persist($arPago);
                                //Parametros generales
                                //$intDiasLaborados = $arProgramacionPagoProcesar->getDias();
                                $intDiasLaborados = $this->devolverDiasLaborados($arEmpleado, $arProgramacionPagoProcesar, $arProgramacionPagoProcesar->getDias());

                                $douVrDia = $arEmpleado->getVrSalario() / 30;
                                $douVrHora = $douVrDia / 8;
                                $douVrSalarioMinimo = 644350; //Configurar desde configuraciones
                                $douSalarioPrestacional = 0;
                                //Procesar Incapacidades
                                $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                                $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));
                                foreach ($arIncapacidades as $arIncapacidad) {
                                    $intConceptoIncapacidad = 0;
                                    if($arIncapacidad->getIncapacidadGeneral() == 1) {
                                        $intConceptoIncapacidad = 16; //Configurar desde configuraciones
                                    } else {
                                        $intConceptoIncapacidad = 17; //Configurar desde configuraciones
                                    }
                                    $arPagoConceptoIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                    $arPagoConceptoIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intConceptoIncapacidad);
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoConceptoIncapacidad);
                                    $douPagoDetalle = 0;
                                    $intDiasProcesarIncapacidad = 0;
                                    if($arIncapacidad->getCantidad() <= $intDiasLaborados) {
                                        $intDiasLaborados = $intDiasLaborados - $arIncapacidad->getCantidad();
                                        $intDiasProcesarIncapacidad = $arIncapacidad->getCantidad();
                                    }
                                    if($arIncapacidad->getIncapacidadGeneral() == 1) {
                                        if($arEmpleado->getVrSalario() <= $douVrSalarioMinimo) {
                                            $douPagoDetalle = $intDiasProcesarIncapacidad * $douVrDia;
                                        }
                                        if($arEmpleado->getVrSalario() > $douVrSalarioMinimo && $arEmpleado->getVrSalario() <= $douVrSalarioMinimo * 1.5) {
                                            $douVrDiaSalarioMinimo = $douVrSalarioMinimo / 30;
                                            $douPagoDetalle = $intDiasProcesarIncapacidad * $douVrDiaSalarioMinimo;
                                        }
                                        if($arEmpleado->getVrSalario() > ($douVrSalarioMinimo * 1.5)) {
                                            $douPagoDetalle = $intDiasProcesarIncapacidad * $douVrDia;
                                            $douPagoDetalle = ($douPagoDetalle * $arPagoConceptoIncapacidad->getPorPorcentaje())/100;
                                        }
                                    } else {
                                        $douPagoDetalle = $intDiasProcesarIncapacidad * $douVrDia;
                                        $douPagoDetalle = ($douPagoDetalle * $arPagoConceptoIncapacidad->getPorPorcentaje())/100;
                                    }
                                    $arPagoDetalle->setVrHora($douVrHora);
                                    $arPagoDetalle->setVrDia($douVrDia);
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoConceptoIncapacidad->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConceptoIncapacidad->getOperacion());
                                    $em->persist($arPagoDetalle);
                                    if($arPagoConceptoIncapacidad->getPrestacional() == 1) {
                                        $douSalarioPrestacional = $douSalarioPrestacional + $douPagoDetalle;
                                    }
                                }

                                //Procesar los conceptos de pagos adicionales
                                $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $arProgramacionPagoProcesar->getCodigoCentroCostoFk(), 'pagoAplicado' => 0, 'codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));
                                foreach ($arPagosAdicionales as $arPagoAdicional) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoAdicional->getPagoConceptoRel());
                                    if($arPagoAdicional->getPagoConceptoRel()->getComponePorcentaje() == 1) {
                                        $douVrHoraAdicional = ($douVrHora * $arPagoAdicional->getPagoConceptoRel()->getPorPorcentaje())/100;
                                        $douPagoDetalle = $douVrHoraAdicional * $arPagoAdicional->getCantidad();
                                        $arPagoDetalle->setVrHora($douVrHoraAdicional);
                                        $arPagoDetalle->setVrDia($douVrDia);
                                    }
                                    if($arPagoAdicional->getPagoConceptoRel()->getComponeValor() == 1) {
                                        $douPagoDetalle = $arPagoAdicional->getValor();
                                        $arPagoDetalle->setVrDia($douVrDia);
                                    }
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoAdicional->getPagoConceptoRel()->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoAdicional->getPagoConceptoRel()->getOperacion());
                                    $em->persist($arPagoDetalle);
                                    if($arPagoAdicional->getPagoConceptoRel()->getPrestacional() == 1) {
                                        $douSalarioPrestacional = $douSalarioPrestacional + $douPagoDetalle;
                                    }
                                    if($arPagoAdicional->getPermanente() == 0) {
                                        $arPagoAdicionalActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                        $arPagoAdicionalActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($arPagoAdicional->getCodigoPagoAdicionalPk());
                                        $arPagoAdicionalActualizar->setPagoAplicado(1);
                                        $arPagoAdicionalActualizar->setProgramacionPagoRel($arProgramacionPagoProcesar);
                                        $em->persist($arPagoAdicionalActualizar);
                                    }
                                }

                                //Procesar creditos
                                $intConceptoCreditos = 14; //Configurar desde configuraciones
                                $arPagoConceptoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConceptoCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intConceptoCreditos);
                                $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                                $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));
                                foreach ($arCreditos as $arCredito) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoConceptoCredito);
                                    $douPagoDetalle = $arCredito->getVrCuota(); //Falta afectar credito
                                    $arPagoDetalle->setDetalle($arCredito->getCreditoTipoRel()->getNombre());
                                    $arPagoDetalle->setVrHora($douVrHora);
                                    $arPagoDetalle->setVrDia($douVrDia);
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoConceptoCredito->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConceptoCredito->getOperacion());
                                    $em->persist($arPagoDetalle);
                                }

                                $intPagoConceptoSalario = 1; //Se debe traer de la base de datos
                                $intPagoConceptoSalud = 3; //Se debe traer de la base de datos
                                $intPagoConceptoPension = 4; //Se debe traer de la base de datos
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();

                                //Liquidar salario
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoSalario);
                                $douPagoDetalle = $intDiasLaborados * $douVrDia;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setVrHora($douVrHora);
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setNumeroHoras($intDiasLaborados * 8);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $em->persist($arPagoDetalle);
                                $douSalarioPrestacional = $douSalarioPrestacional + $douPagoDetalle;

                                //Liquidar salud
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoSalud);
                                $douPagoDetalle = ($douSalarioPrestacional * $arPagoConcepto->getPorPorcentaje())/100;;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setPorcentajeAplicado($arPagoConcepto->getPorPorcentaje());                                
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $em->persist($arPagoDetalle);

                                //Liquidar pension
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoPension);
                                $douPagoDetalle = ($douSalarioPrestacional * $arPagoConcepto->getPorPorcentaje())/100;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();                                
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setPorcentajeAplicado($arPagoConcepto->getPorPorcentaje());
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $em->persist($arPagoDetalle);

                                //Subsidio transporte
                                if($arEmpleado->getAuxilioTransporte() == 1) {
                                    $intPagoConceptoTransporte = 18; //Se debe traer de la base de datos
                                    $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoTransporte);
                                    $douVrDiaTransporte = 74000 / 30;
                                    $douPagoDetalle = $douVrDiaTransporte * $intDiasLaborados;
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);                                    
                                    $arPagoDetalle->setNumeroHoras($intDiasLaborados);
                                    $arPagoDetalle->setVrHora($douVrDiaTransporte/8);
                                    $arPagoDetalle->setVrDia($douVrDiaTransporte);
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                    $em->persist($arPagoDetalle);
                                }
                            }
                            $arProgramacionPagoProcesar->setEstadoGenerado(1);
                            $arCentroCosto->setPagoAbierto(0);
                            $em->persist($arCentroCosto);
                            $em->persist($arProgramacionPagoProcesar);
                            $em->flush();
                            $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->Liquidar($codigoProgramacionPago);
                            $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($arProgramacionPagoProcesar->getCodigoCentroCostoFk());

                        } else {
                            $boolErrores = 1;
                        }
                    }
                    if($boolErrores == 1) {
                        $objMensaje->Mensaje("error", "Algunas programaciones no tienen la verificacion de incapacidades y pagos adicionales", $this);
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
                if($form->get('BtnNoGenerar')->isClicked()) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        $arProgramacionPagoProcesar->setEstadoGenerado(1);
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
                        $arCentroCosto->setPagoAbierto(0);
                        $em->persist($arProgramacionPagoProcesar);
                        $em->persist($arCentroCosto);
                        $em->flush();
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
                if($form->get('BtnVerificarPagosAdicionales')->isClicked()) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        $arProgramacionPagoProcesar->setVerificarPagosAdicionales(1);
                        $em->persist($arProgramacionPagoProcesar);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
                if($form->get('BtnVerificarIncapacidades')->isClicked()) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        $arProgramacionPagoProcesar->setVerificarIncapacidades(1);
                        $em->persist($arProgramacionPagoProcesar);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
            }
        }
        if($frmPagar->isValid()) {
            if($frmPagar->get('BtnPagar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPagar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        $arProgramacionPagoProcesar->setEstadoPagado(1);
                        $em->persist($arProgramacionPagoProcesar);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
            }
            if($frmPagar->get('BtnLiquidar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPagar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
                        foreach($arPagos as $arPago) {
                            $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->liquidar($arPago->getCodigoPagoPk());
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
            }
            if($frmPagar->get('BtnAnular')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPagar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->Anular($codigoProgramacionPago);
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
            }
        }

        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoGenerado' => 0));
        $arProgramacionPagoPendientes = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPagoPendientes = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoGenerado' => 1, 'estadoPagado' => 0, 'estadoAnulado' => 0));
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPago.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'arProgramacionPagoPendientes' => $arProgramacionPagoPendientes,
            'form' => $form->createView(),
            'frmPagar' => $frmPagar->createView()
            ));
    }

    public function generarPagoResumenAction($codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');

        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto, 'pagoAplicado' => 0));
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto));
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto));
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
        }

        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPagoResumen.html.twig', array(
                    'arCentroCosto' => $arCentroCosto,
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'arIncapacidades' => $arIncapacidades,
                    'arEmpleados' => $arEmpleados
                    ));
    }

    public function devolverDiasLaborados($arEmpleadoProcesar, $arProgramacionPagoProcesar, $intDiasPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $intDiasDevolver = $intDiasPeriodo;
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $arEmpleadoProcesar;
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $arProgramacionPagoProcesar;
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(), 'estadoActivo' => 1));
        foreach ($arContratos as $arContrato) {
            $dateFechaDesde =  "";
            $dateFechaHasta =  "";
            if($arContrato->getFechaDesde() <  $arProgramacionPago->getFechaDesde() == true) {
                $dateFechaDesde = $arProgramacionPago->getFechaDesde();
            } else {
                if($arContrato->getFechaDesde() > $arProgramacionPago->getFechaHasta() == true) {
                    $intDiasDevolver = 0;
                } else {
                    $dateFechaDesde = $arContrato->getFechaDesde();
                }
            }
            if($arContrato->getFechaHasta() >  $arProgramacionPago->getFechaHasta() == true) {
                $dateFechaHasta = $arProgramacionPago->getFechaHasta();
            } else {
                if($arContrato->getFechaHasta() < $arProgramacionPago->getFechaDesde() == true) {
                    $intDiasDevolver = 0;
                } else {
                    $dateFechaHasta = $arContrato->getFechaHasta();
                }
            }
            if($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');
                $intDiasDevolver = $intDias + 1;
            }
        }
        return $intDiasDevolver;
    }
}
