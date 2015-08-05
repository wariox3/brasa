<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

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
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }                         
            if($form->get('BtnGenerar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    $boolErrores = 0;
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
                        if($arProgramacionPagoProcesar->getEstadoGenerado() == 0 && $arProgramacionPagoProcesar->getEmpleadosGenerados() == 1 && $arProgramacionPagoProcesar->getInconsistencias() == 0) {
                            $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                            $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $arProgramacionPagoProcesar->getCodigoProgramacionPagoPk()));                                                        
                            foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {                                
                                $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                                $arContratoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                                $arPago->setEmpleadoRel($arProgramacionPagoDetalle->getEmpleadoRel());
                                $arPago->setCentroCostoRel($arCentroCosto);
                                $arPago->setFechaDesde($arProgramacionPagoProcesar->getFechaDesde());
                                $arPago->setFechaHasta($arProgramacionPagoProcesar->getFechaHasta());
                                $arPago->setVrSalarioEmpleado($arProgramacionPagoDetalle->getVrSalario());
                                $arPago->setVrSalarioPeriodo(($arProgramacionPagoDetalle->getVrSalario() / 30) * $arProgramacionPagoDetalle->getDias());
                                $arPago->setProgramacionPagoRel($arProgramacionPagoProcesar);
                                $arPago->setDiasPeriodo($arProgramacionPagoDetalle->getDias());
                                $em->persist($arPago);
                                /*if($arEmpleado->getNumeroIdentificacion() =='1056122069') {
                                    echo "Entro";
                                }*/

                                //Parametros generales                                                                
                                $intHorasLaboradas = $arProgramacionPagoDetalle->getHorasPeriodoReales();
                                $intDiasTransporte = $arProgramacionPagoDetalle->getDiasReales();
                                $douVrDia = $arProgramacionPagoDetalle->getVrSalario() / 30;
                                $douVrHora = $douVrDia / 8;                                
                                $douVrSalarioMinimo = $arConfiguracion->getVrSalario();
                                $douVrHoraSalarioMinimo = ($douVrSalarioMinimo / 30) / 8;
                                $douDevengado = 0;
                                $douIngresoBaseCotizacion = 0;                                

                                //Procesar Incapacidades
                                $arPagoConceptoIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConceptoIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($arConfiguracion->getCodigoIncapacidad());
                                $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                                //$arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoEmpleadoFk' => $arProgramacionPagoDetalle->getCodigoEmpleadoFk()));//linea anterior
                                $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listadoIncapacidadesPendientesEmpleados($arProgramacionPagoDetalle->getCodigoEmpleadoFk());
                                foreach ($arIncapacidades as $arIncapacidad) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arIncapacidad->getPagoAdicionalSubtipoRel()->getPagoConceptoRel());
                                    $douPagoDetalle = 0;
                                    $douIngresoBaseCotizacionIncapacidad = 0;                                    
                                    $intHorasLaboradas = $intHorasLaboradas - $arIncapacidad->getCantidad();
                                    $intHorasProcesarIncapacidad = $arIncapacidad->getCantidad();
                                    $intDiasTransporte = $intDiasTransporte - ($intHorasProcesarIncapacidad / $arProgramacionPagoDetalle->getFactorDia());


                                    if($arIncapacidad->getCodigoPagoAdicionalSubtipoFk() == 28) {
                                        if($arProgramacionPagoDetalle->getVrSalario() <= $douVrSalarioMinimo) {
                                            $douPagoDetalle = $intHorasProcesarIncapacidad * $douVrHora;
                                            $douIngresoBaseCotizacionIncapacidad = $intHorasProcesarIncapacidad * $douVrHora;
                                        }
                                        if($arProgramacionPagoDetalle->getVrSalario() > $douVrSalarioMinimo && $arProgramacionPagoDetalle->getVrSalario() <= $douVrSalarioMinimo * 1.5) {                                            
                                            $douPagoDetalle = $intHorasProcesarIncapacidad * $douVrHoraSalarioMinimo;
                                            $douIngresoBaseCotizacionIncapacidad = $intHorasProcesarIncapacidad * $douVrHora;
                                        }
                                        if($arProgramacionPagoDetalle->getVrSalario() > ($douVrSalarioMinimo * 1.5)) {
                                            $douPagoDetalle = $intHorasProcesarIncapacidad * $douVrHora;
                                            $douPagoDetalle = ($douPagoDetalle * $arIncapacidad->getPagoAdicionalSubtipoRel()->getPorcentaje())/100;
                                            $douIngresoBaseCotizacionIncapacidad = $intHorasProcesarIncapacidad * $douVrHora;
                                        }
                                    } else {
                                        $douPagoDetalle = $intHorasProcesarIncapacidad * $douVrHora;
                                        $douPagoDetalle = ($douPagoDetalle * $arIncapacidad->getPagoAdicionalSubtipoRel()->getPorcentaje())/100;
                                        $douIngresoBaseCotizacionIncapacidad = $intHorasProcesarIncapacidad * $douVrHora;
                                    }
                                    $arPagoDetalle->setNumeroHoras($intHorasProcesarIncapacidad);
                                    $arPagoDetalle->setVrHora($douVrHora);
                                    $arPagoDetalle->setDetalle($arIncapacidad->getIncapacidadDiagnosticoRel()->getNombre());
                                    $arPagoDetalle->setVrDia($douVrDia);
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arIncapacidad->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arIncapacidad->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getOperacion());
                                    $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                                    $arPagoDetalle->setIncapacidadRel($arIncapacidad);
                                    $em->persist($arPagoDetalle);
                                    if($arIncapacidad->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getPrestacional() == 1) {
                                        $douDevengado = $douDevengado + $douPagoDetalle;
                                        $douIngresoBaseCotizacion = $douIngresoBaseCotizacion + $douIngresoBaseCotizacionIncapacidad;
                                        $arPagoDetalle->setVrIngresoBaseCotizacion($douIngresoBaseCotizacionIncapacidad);
                                    }
                                    $arIncapacidadPagadas = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad;
                                    $arIncapacidadPagadas = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($arIncapacidad);
                                    $arIncapacidadPagadas->setCantidadPendiente(0);
                                    $em->persist($arIncapacidadPagadas);
                                }


                                //Procesar Licencias
                                $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
                                $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoEmpleadoFk' => $arProgramacionPagoDetalle->getCodigoEmpleadoFk()));
                                foreach ($arLicencias as $arLicencia) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arLicencia->getPagoAdicionalSubtipoRel()->getPagoConceptoRel());
                                    $arPagoDetalle->setDetalle($arLicencia->getPagoAdicionalSubtipoRel()->getDetalle());

                                    $douPagoDetalle = 0;
                                    $intHorasProcesarLicencia = 0;
                                    if($arLicencia->getCantidad() <= $intHorasLaboradas) {
                                        $intHorasLaboradas = $intHorasLaboradas - $arLicencia->getCantidad();
                                        $intHorasProcesarLicencia = $arLicencia->getCantidad();
                                    }

                                    if($arLicencia->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getPrestacional() == 1) {
                                        $douPagoDetalle = $intHorasProcesarLicencia * $douVrHora;                                                                                
                                        $douIngresoBaseCotizacion = $douIngresoBaseCotizacion + $douPagoDetalle;                                        
                                        $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);                                        
                                        if($arLicencia->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getOperacion() == 0) {
                                            $douPagoDetalle = 0;
                                        }
                                        $douDevengado = $douDevengado + $douPagoDetalle;                                                                                                                        
                                        $arPagoDetalle->setVrPago($douPagoDetalle);
                                        $arPagoDetalle->setOperacion($arLicencia->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getOperacion());
                                        $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arLicencia->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getOperacion());
                                    }
                                    $arPagoDetalle->setNumeroHoras($intHorasProcesarLicencia);
                                    $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                                    $em->persist($arPagoDetalle);
                                    if($arLicencia->getAfectaTransporte() == 1){
                                        $intDiasLicenciaProcesar = intval($intHorasProcesarLicencia / $arProgramacionPagoDetalle->getFactorDia());
                                        $intDiasTransporte = $intDiasTransporte - $intDiasLicenciaProcesar;
                                    }
                                    //Actualizar cantidades licencia
                                    $arLicenciaRegistroPago = new \Brasa\RecursoHumanoBundle\Entity\RhuLicenciaRegistroPago();
                                    $arLicenciaRegistroPago->setProgramacionPagoRel($arProgramacionPagoProcesar);
                                    $arLicenciaRegistroPago->setLicenciaRel($arLicencia);
                                    $arLicenciaRegistroPago->setCantidad($intDiasLicenciaProcesar);
                                }

                                //Procesar los conceptos de pagos adicionales
                                $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $arProgramacionPagoProcesar->getCodigoCentroCostoFk(), 'pagoAplicado' => 0, 'codigoEmpleadoFk' => $arProgramacionPagoDetalle->getCodigoEmpleadoFk()));
                                foreach ($arPagosAdicionales as $arPagoAdicional) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoAdicional->getPagoConceptoRel());
                                    if($arPagoAdicional->getPagoConceptoRel()->getComponePorcentaje() == 1) {
                                        $douVrHoraAdicional = ($douVrHora * $arPagoAdicional->getPagoAdicionalSubtipoRel()->getPorcentaje())/100;
                                        $douPagoDetalle = $douVrHoraAdicional * $arPagoAdicional->getCantidad();
                                        $arPagoDetalle->setVrHora($douVrHoraAdicional);
                                        $arPagoDetalle->setVrDia($douVrDia);
                                        $arPagoDetalle->setNumeroHoras($arPagoAdicional->getCantidad());
                                    }
                                    if($arPagoAdicional->getPagoConceptoRel()->getComponeValor() == 1) {
                                        $douPagoDetalle = $arPagoAdicional->getValor();
                                        $arPagoDetalle->setVrDia($douVrDia);
                                    }
                                    $arPagoDetalle->setDetalle($arPagoAdicional->getDetalle());
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoAdicional->getPagoConceptoRel()->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoAdicional->getPagoConceptoRel()->getOperacion());
                                    $arPagoDetalle->setDetalle($arPagoAdicional->getPagoAdicionalSubtipoRel()->getDetalle());
                                    $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                                    $em->persist($arPagoDetalle);

                                    //Aumentar los dias y las horas ordinarias
                                    if($arPagoAdicional->getPagoConceptoRel()->getComponeSalario() == 1) {
                                        $intHorasProcesarAdicionales = $arPagoAdicional->getCantidad();
                                        $intHorasLaboradas = $intHorasLaboradas + $intHorasProcesarAdicionales;
                                        $intDiasAdicionales = intval($intHorasProcesarAdicionales / 8);
                                        $intDiasTransporte = $intDiasTransporte - $intDiasAdicionales;
                                    }
                                    if($arPagoAdicional->getPagoConceptoRel()->getPrestacional() == 1) {
                                        $douDevengado = $douDevengado + $douPagoDetalle;
                                        $douIngresoBaseCotizacion = $douIngresoBaseCotizacion + $douPagoDetalle;
                                        $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);
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
                                $arPagoConceptoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConceptoCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($arConfiguracion->getCodigoCredito());
                                $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                                $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoEmpleadoFk' => $arProgramacionPagoDetalle->getCodigoEmpleadoFk(), 'codigoCreditoTipoPagoFk' => 1, 'estadoPagado' => 0, 'aprobado' => 1, 'estadoSuspendido' => 0));
                                foreach ($arCreditos as $arCredito) {                                    
                                    if($arCredito->getSaldoTotal() > 0) {
                                        $arCreditoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                                        $arCreditoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($arCredito->getCodigoCreditoPk());
                                        $douCuota = 0;
                                        if ($arCreditoProcesar->getSaldoTotal() >= $arCreditoProcesar->getVrCuota()){
                                            $douCuota = $arCreditoProcesar->getVrCuota();                                        
                                        }
                                        else {
                                            $douCuota = $arCreditoProcesar->getSaldoTotal();                                         
                                        }
                                        $arCreditoProcesar->setVrCuotaTemporal($arCreditoProcesar->getVrCuotaTemporal() + $douCuota);
                                        $arCreditoProcesar->setSaldoTotal($arCreditoProcesar->getSaldo() - $arCreditoProcesar->getVrCuotaTemporal());
                                        $em->persist($arCreditoProcesar);

                                        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                        $arPagoDetalle->setPagoRel($arPago);
                                        $arPagoDetalle->setPagoConceptoRel($arPagoConceptoCredito);
                                        $douPagoDetalle = $douCuota; //Falta afectar credito
                                        $arPagoDetalle->setDetalle($arCredito->getCreditoTipoRel()->getNombre());
                                        $arPagoDetalle->setVrPago($douPagoDetalle);
                                        $arPagoDetalle->setOperacion($arPagoConceptoCredito->getOperacion());
                                        $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConceptoCredito->getOperacion());
                                        $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                                        $arPagoDetalle->setCreditoRel($arCredito);
                                        $em->persist($arPagoDetalle);
                                        if($arCredito->getSeguro() > 0) {
                                            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SEGURO
                                            //$intConceptoCreditos = 27; //Configurar desde configuraciones
                                            $intConceptoCreditos = $arConfiguracion->getCodigoSeguro();
                                            $arPagoConceptoCreditoSeguro = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                            $arPagoConceptoCreditoSeguro = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intConceptoCreditos);                                        
                                            $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                            $arPagoDetalle->setPagoRel($arPago);
                                            $arPagoDetalle->setPagoConceptoRel($arPagoConceptoCreditoSeguro);
                                            $douPagoDetalle = $arCredito->getSeguro(); //Falta afectar credito
                                            $arPagoDetalle->setDetalle("SEGURO DE CREDITO " . $arCredito->getCreditoTipoRel()->getNombre());
                                            $arPagoDetalle->setVrPago($douPagoDetalle);
                                            $arPagoDetalle->setOperacion($arPagoConceptoCredito->getOperacion());
                                            $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConceptoCredito->getOperacion());
                                            $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                                            $arPagoDetalle->setCreditoRel($arCredito);
                                            $em->persist($arPagoDetalle);                                        
                                        }                                        
                                    }                                    
                                }


                                $intPagoConceptoSalario = $arConfiguracion->getCodigoHoraDiurnaTrabajada();                                
                                $intPagoConceptoSalud = $arConfiguracion->getCodigoAporteSalud();                                
                                $intPagoConceptoPension = $arConfiguracion->getCodigoAportePension();                                
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();

                                //Liquidar salario
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoSalario);
                                $douPagoDetalle = $intHorasLaboradas * $douVrHora;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setVrHora($douVrHora);
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setNumeroHoras($intHorasLaboradas);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                                $em->persist($arPagoDetalle);
                                $douDevengado = $douDevengado + $douPagoDetalle;
                                $douIngresoBaseCotizacion = $douIngresoBaseCotizacion + $douPagoDetalle;
                                $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);


                                //Liquidar salud
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoSalud);
                                $douPagoDetalle = ($douIngresoBaseCotizacion * $arPagoConcepto->getPorPorcentaje())/100;;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setPorcentajeAplicado($arPagoConcepto->getPorPorcentaje());
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                                $em->persist($arPagoDetalle);

                                //Liquidar pension
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoPension);
                                $douPorcentaje = $arPagoConcepto->getPorPorcentaje();                                
                                if($douIngresoBaseCotizacion * $arCentroCosto->getPeriodoPagoRel()->getPeriodosMes() > $douVrSalarioMinimo * 4) {                                    
                                    $douPorcentaje = $arConfiguracion->getPorcentajePensionExtra(); //PORCENTAJE PENSION EXTRA DEL 5%
                                }
                                $douPagoDetalle = ($douIngresoBaseCotizacion * $douPorcentaje)/100;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setPorcentajeAplicado($douPorcentaje);
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                                $em->persist($arPagoDetalle);

                                //Subsidio transporte
                                if($intDiasTransporte > 0) {
                                    if($arProgramacionPagoDetalle->getEmpleadoRel()->getAuxilioTransporte() == 1) {                                        
                                        $intPagoConceptoTransporte = $arConfiguracion->getCodigoAuxilioTransporte();
                                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoTransporte);
                                        $duoVrAuxilioTransporte = $arConfiguracion->getVrAuxilioTransporte();
                                        $douVrDiaTransporte = $duoVrAuxilioTransporte / 30;
                                        $douPagoDetalle = $douVrDiaTransporte * $intDiasTransporte;
                                        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                        $arPagoDetalle->setPagoRel($arPago);
                                        $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                        $arPagoDetalle->setNumeroHoras($intDiasTransporte);
                                        $arPagoDetalle->setVrHora($douVrDiaTransporte / 8);
                                        $arPagoDetalle->setVrDia($douVrDiaTransporte);
                                        $arPagoDetalle->setVrPago($douPagoDetalle);
                                        $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                        $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                        $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                                        $em->persist($arPagoDetalle);
                                    }
                                }
                            }
                            $arProgramacionPagoProcesar->setEstadoGenerado(1);
                            $em->persist($arCentroCosto);
                            $em->persist($arProgramacionPagoProcesar);
                            $em->flush();

                            $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->liquidar($codigoProgramacionPago);
                            //$em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generarPagoDetalleSede($codigoProgramacionPago);
                            if($arProgramacionPagoProcesar->getNoGeneraPeriodo() == 0) {
                                $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
                            }
                        } else {
                            $boolErrores = 1;
                        }
                    }
                    if($boolErrores == 1) {
                        $objMensaje->Mensaje("error", "La programacion debe tener los empleados generados y sin inconsistencias", $this);
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));                    
                }

            }                
            if($form->get('BtnEliminarPago')->isClicked()) {                                
                if ($arrSeleccionados > 0 ){
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                    
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->eliminar($codigoProgramacionPago);                    
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($arProgramacionPago->getCodigoCentroCostoFk());                                        
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));                
                }
            }                
            if($form->get('BtnPagar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->pagarSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));                
            }     
            if($form->get('BtnDeshacer')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados > 0 ){
                    foreach ($arrSeleccionados as $codigoProgramacionPago) {
                        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        if($arProgramacionPago->getEstadoGenerado() == 1 && $arProgramacionPago->getEstadoPagado() == 0) {
                            $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->deshacer($codigoProgramacionPago);                        
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));                
                }
            }              
            
        }       
                
        $arProgramacionPago = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPago:lista.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
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
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generarEmpleados($codigoProgramacionPago);        
                $arProgramacionPago->setEmpleadosGenerados(1);
                $em->persist($arProgramacionPago);
                $em->flush(); 
                return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
            }
            
            if($form->get('BtnActualizarEmpleados')->isClicked()) {            
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
            }
            if($form->get('BtnEliminarEmpleados')->isClicked()) {            
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
            }            
        }
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPago->getCodigoCentroCostoFk());
        $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $arProgramacionPago->getCodigoCentroCostoFk(), 'pagoAplicado' => 0));
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listadoIncapacidadesPendientes($arProgramacionPago->getCodigoCentroCostoFk());
        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoCentroCostoFk' => $arProgramacionPago->getCodigoCentroCostoFk()));
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
            ->add('estadoGenerado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'GENERADO', '0' => 'SIN GENERAR'), 'data' => $session->get('filtroEstadoGenerado')))                                            
            ->add('estadoPagado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'PAGADOS', '0' => 'SIN PAGAR'), 'data' => $session->get('filtroEstadoPagado')))                                                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnPagar', 'submit', array('label'  => 'Pagar',))            
            ->add('BtnDeshacer', 'submit', array('label'  => 'Des-hacer',))                                
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))
            ->add('BtnEliminarPago', 'submit', array('label'  => 'Eliminar',))                 
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->getRequest()->getSession();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaDQL(
                    "",
                    "",
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroEstadoGenerado'),
                    $session->get('filtroEstadoPagado')
                    );  
    }         
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroEstadoGenerado', $form->get('estadoGenerado')->getData());        
        $session->set('filtroEstadoPagado', $form->get('estadoPagado')->getData());        
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
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'CODIGO')
                    ->setCellValue('C1', 'CENTRO COSTO')
                    ->setCellValue('D1', 'PERIODO')
                    ->setCellValue('E1', 'DESDE')
                    ->setCellValue('F1', 'HASTA')
                    ->setCellValue('G1', 'DIAS');
        
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoProgramacionPagoPk())
                    ->setCellValue('B' . $i, $arPago->getCodigoCentroCostoFk())
                    ->setCellValue('C' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('D' . $i, $arPago->getCentroCostoRel()->getPeriodoPagoRel()->getNombre())
                    ->setCellValue('E' . $i, $arPago->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arPago->getFechaHasta())
                    ->setCellValue('G' . $i, $arPago->getDias());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ProgramacionPagos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ProgramacionPagos.xlsx"');
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
