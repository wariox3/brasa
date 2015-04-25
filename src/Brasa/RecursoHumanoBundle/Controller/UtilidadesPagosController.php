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
                            $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('codigoCentroCostoFk' => $arProgramacionPagoProcesar->getCodigoCentroCostoFk()));
                            foreach ($arEmpleados as $arEmpleado) {
                                $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                                $arPago->setEmpleadoRel($arEmpleado);
                                $arPago->setFechaDesde($arProgramacionPagoProcesar->getFechaDesde());
                                $arPago->setFechaHasta($arProgramacionPagoProcesar->getFechaHasta());
                                $arPago->setVrSalario($arEmpleado->getVrSalario());
                                $arPago->setProgramacionPagoRel($arProgramacionPagoProcesar);
                                $em->persist($arPago);
                                //Parametros generales
                                $intDiasLaborados = $arProgramacionPagoProcesar->getDias();
                                $intDiasLaborados2 = $this->devolverDiasLaborados($arEmpleado, $arProgramacionPagoProcesar, $arProgramacionPagoProcesar->getDias());
                                echo $intDiasLaborados2 . " ";
                                /*$douVrDia = $arEmpleado->getVrSalario() / 30;
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
                                $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $arProgramacionPagoProcesar->getCodigoCentroCostoFk()));
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
                                        $arPagoDetalle->setVrHora($douVrHora);
                                        $arPagoDetalle->setVrDia($douVrDia);
                                    }
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoAdicional->getPagoConceptoRel()->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoAdicional->getPagoConceptoRel()->getOperacion());
                                    $em->persist($arPagoDetalle);
                                    if($arPagoAdicional->getPagoConceptoRel()->getPrestacional() == 1) {
                                        $douSalarioPrestacional = $douSalarioPrestacional + $douPagoDetalle;
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
                                    $arPagoDetalle->setDetalle("PAGO EMPRESA X");
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
                                $arPagoDetalle->setVrHora($douVrHora);
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $em->persist($arPagoDetalle);

                                //Liquidar pension
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoPension);
                                $douPagoDetalle = ($douSalarioPrestacional * $arPagoConcepto->getPorPorcentaje())/100;;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setVrHora($douVrHora);
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
                                    $arPagoDetalle->setVrHora($douVrDiaTransporte/8);
                                    $arPagoDetalle->setVrDia($douVrDiaTransporte);
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                    $em->persist($arPagoDetalle);                        
                                } 
                                 * 
                                 */                                       
                            }
                            $arProgramacionPagoProcesar->setEstadoGenerado(1);
                            $arCentroCosto->setPagoAbierto(0);
                            //$em->persist($arCentroCosto);
                            //$em->persist($arProgramacionPagoProcesar);
                            //$em->flush();
                            //$em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->Liquidar($codigoProgramacionPago);                             
                            //$this->generarPeriodoPago($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
                            
                        } else {
                            $boolErrores = 1;
                        }
                    }
                    if($boolErrores == 1) {
                        $objMensaje->Mensaje("error", "Algunas programaciones no tienen la verificacion de incapacidades y pagos adicionales", $this);
                    }
                    //return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
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
                        $this->generarPeriodoPago($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
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
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoGenerado' => 0));
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPago.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'form' => $form->createView()
            ));
    }
    
    public function generarPagoResumenAction($codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');
        
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto));
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
    
    public function generarPeriodoPago ($codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();                                                                       
        $arCentroCostoProceso = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCostoProceso = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        if($arCentroCostoProceso->getPagoAbierto() == 0 && $arCentroCostoProceso->getEstadoInactivo() == 0) {
            $intDias = $arCentroCostoProceso->getPeriodoPagoRel()->getDias();
            $dateDesde = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y-m-d');
            if($arCentroCostoProceso->getPeriodoPagoRel()->getContinuo() == 1) {
                $dateDesde = date("Y/m/d", strtotime("$dateDesde +1 day"));
                $dateHasta = date("Y/m/d", strtotime("$dateDesde +$intDias day"));
            } else {
                //Para procesar el mes de febrero
                $intDiasInhabilesFebrero = 0;
                //Si el mes es febrero o el mes es enero 30 de periodos mensuales
                if($arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('m') == '02' || ($arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('m/d') == '01/30' && $intDias == 30)) {
                    $year = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y');
                    //Verificar si el año es bisiesto
                    if(date('L',mktime(1,1,1,1,1,$year)) == 1) {
                        $intDiasInhabilesFebrero = 1;
                    } else {
                        $intDiasInhabilesFebrero = 2;
                    }
                    $intDias = $intDias - $intDiasInhabilesFebrero;
                    $intDiasMes = $intDias+$intDiasInhabilesFebrero;

                    $strMesDesde = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y/m');
                    $strMesHasta = date("Y/m", strtotime("$dateDesde +$intDiasMes day"));
                    if($strMesDesde == $strMesHasta) {
                        $intDias = $intDias + $intDiasInhabilesFebrero;
                    }
                    if(date("m", strtotime("$dateDesde +$intDias day")) == '03') {
                        $intDias = $intDiasMes;
                    }
                }
                $strMesDesde = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y/m');
                $strMesHasta = date("Y/m", strtotime("$dateDesde +$intDias day"));
                if($strMesDesde != $strMesHasta) {
                    $dateDesde = $strMesHasta . "/01";
                    $intDias = $intDias - 1;
                    $dateHasta = date("Y/m/d", strtotime("$dateDesde +$intDias day"));
                } else {
                    $intDias = $intDias - 1;
                    $dateDesde = date("Y/m/d", strtotime("$dateDesde +1 day"));
                    $dateHasta = date("Y/m/d", strtotime("$dateDesde +$intDias day"));
                }
            }
            $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
            $arProgramacionPago->setFechaDesde(date_create($dateDesde));
            $arProgramacionPago->setFechaHasta(date_create($dateHasta));
            $arProgramacionPago->setDias($arCentroCostoProceso->getPeriodoPagoRel()->getDias());
            $arProgramacionPago->setCentroCostoRel($arCentroCostoProceso);
            $em->persist($arProgramacionPago);
            $arCentroCostoProceso->setPagoAbierto(1);
            $arCentroCostoProceso->setFechaUltimoPagoProgramado(date_create($dateHasta));
            $em->persist($arCentroCostoProceso);
            $em->flush();
        }
        return true;
    }
    
    public function devolverDiasLaborados($arEmpleadoProcesar, $arProgramacionPagoProcesar, $intDiasPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $intDiasDevolver = 5;
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $arEmpleadoProcesar;
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $arProgramacionPagoProcesar;
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));                                
        foreach ($arContratos as $arContrato) {
            if($arContrato->getFechaDesde() <  $arProgramacionPago->getFechaDesde() == true && $arContrato->getFechaHasta() >  $arProgramacionPago->getFechaHasta() == true) {
                $intDiasDevolver = $intDiasPeriodo;
            } else {
                if($arContrato->getFechaDesde() >= $arProgramacionPago->getFechaDesde() && $arContrato->getFechaHasta() <= $arProgramacionPago->getFechaHasta()) {
                    $intDiasDevolver = 
                }
                $intDiasDevolver = 0;
            }
        }
        return $intDiasDevolver;
    }
}
