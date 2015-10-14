<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuProgramacionPagoRepository extends EntityRepository {

    /**
     * Elimina toda la programacion de pago y los pagos generados de esta
     *
     * @author		Mario Estrada
     *
     * @param integer	Codigo de la programacion de pago
     */
    public function eliminar($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arPagos as $arPago) {
            $arPagosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
            $arPagosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
            foreach ($arPagosDetalles as $arPagoDetalle) {
                $em->remove($arPagoDetalle);
            }
            $em->remove($arPago);
        }
        //Eliminar detalles de programacion pago
        $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
            $em->remove($arProgramacionPagoDetalle);
        }
        $em->remove($arProgramacionPago);
        $em->flush();
    }

    
    /**
     * Generar una programacion de pago, crea pagos, afecta creditos, licencias e incapacidades
     *
     * @author		Mario Estrada
     *
     * @param integer	Codigo de la programacion de pago
     */    
    public function generar($codigoProgramacionPago) {
        $em = $this->getEntityManager(); 
        $strMensaje = "";
        set_time_limit(0);
        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        if($arProgramacionPagoProcesar->getEstadoGenerado() == 0 && $arProgramacionPagoProcesar->getEmpleadosGenerados() == 1 && $arProgramacionPagoProcesar->getInconsistencias() == 0) {
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            //Validar que este en el año actual
            if($arProgramacionPagoProcesar->getFechaDesde()->format('Y') <= $arConfiguracion->getAnioActual()) {
                $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
                
                if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 1) {
                    $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                    $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $arProgramacionPagoProcesar->getCodigoProgramacionPagoPk()));
                    foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago->setPagoTipoRel($arProgramacionPagoProcesar->getPagoTipoRel());                        
                        $arPago->setEmpleadoRel($arProgramacionPagoDetalle->getEmpleadoRel());
                        $arPago->setCentroCostoRel($arCentroCosto);
                        $arPago->setFechaDesde($arProgramacionPagoProcesar->getFechaDesde());
                        $arPago->setFechaHasta($arProgramacionPagoProcesar->getFechaHasta());
                        $arPago->setFechaDesdePago($arProgramacionPagoDetalle->getFechaDesdePago());
                        $arPago->setFechaHastaPago($arProgramacionPagoDetalle->getFechaHastaPago());
                        $arPago->setVrSalarioEmpleado($arProgramacionPagoDetalle->getVrSalario());
                        $arPago->setVrSalarioPeriodo($arProgramacionPagoDetalle->getVrDevengado());
                        $arPago->setProgramacionPagoRel($arProgramacionPagoProcesar);
                        $arPago->setContratoRel($arProgramacionPagoDetalle->getContratoRel());                        
                        $arPago->setDiasPeriodo($arProgramacionPagoDetalle->getDias());
                        $em->persist($arPago);

                        //Parametros generales
                        $intHorasLaboradas = $arProgramacionPagoDetalle->getHorasPeriodoReales();
                        $intDiasTransporte = $arProgramacionPagoDetalle->getDiasReales();
                        $douVrDia = $arProgramacionPagoDetalle->getVrDia();
                        $douVrHora = $arProgramacionPagoDetalle->getVrHora();
                        $douVrSalarioMinimo = $arConfiguracion->getVrSalario();
                        $douVrHoraSalarioMinimo = ($douVrSalarioMinimo / 30) / 8;
                        $douDevengado = 0;
                        $douIngresoBasePrestacional = 0;
                        $douIngresoBaseCotizacion = 0;                        
                        
                        //Procesar vacaciones
                        $intDiasVacaciones = $arProgramacionPagoDetalle->getDiasVacaciones();
                        $intHorasVacaciones = $intDiasVacaciones * 8;
                        if($intDiasVacaciones > 0) {
                            $intHorasLaboradas = $intHorasLaboradas - $intHorasVacaciones;
                            $intDiasTransporte = $intDiasTransporte - $intDiasVacaciones;                                        
                            $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(31);
                            $douIngresoBaseCotizacionVacaciones = $intHorasVacaciones * $douVrHora;
                            $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                            $arPagoDetalle->setPagoRel($arPago);
                            $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);                                        
                            $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                            $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                            $arPagoDetalle->setNumeroHoras($intHorasVacaciones);
                            $arPagoDetalle->setVrIngresoBasePrestacion($douIngresoBaseCotizacionVacaciones);
                            $arPagoDetalle->setVrIngresoBaseCotizacion($douIngresoBaseCotizacionVacaciones);
                            $em->persist($arPagoDetalle);                                         
                        }                        
                        
                        //Procesar Incapacidades
                        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->periodo($arProgramacionPagoDetalle->getFechaDesdePago(), $arProgramacionPagoDetalle->getFechaHasta(), $arProgramacionPagoDetalle->getCodigoEmpleadoFk());                                                                        
                        foreach ($arIncapacidades as $arIncapacidad) {
                            if($intHorasLaboradas > 0) { 
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arIncapacidad->getIncapacidadTipoRel()->getPagoConceptoRel());
                                    
                                $fechaDesde = $arProgramacionPagoDetalle->getFechaDesdePago();
                                $fechaHasta = $arProgramacionPagoDetalle->getFechaHasta();
                                if($arIncapacidad->getFechaDesde() >  $fechaDesde) {
                                    $fechaDesde = $arIncapacidad->getFechaDesde();
                                }             
                                if($arIncapacidad->getFechaHasta() < $fechaHasta) {
                                    $fechaHasta = $arIncapacidad->getFechaHasta();                
                                }
                                $intDias = $fechaDesde->diff($fechaHasta);
                                $intDias = $intDias->format('%a');   
                                $intDias += 1;
                                $intHorasProcesarIncapacidad = $intDias * 8;                                                                                                
                                $intHorasLaboradas = $intHorasLaboradas - $intHorasProcesarIncapacidad;                                
                                $douPagoDetalle = 0;
                                $douIngresoBaseCotizacionIncapacidad = 0;
                                $intDiasTransporte = $intDiasTransporte - ($intHorasProcesarIncapacidad / $arProgramacionPagoDetalle->getFactorDia());

                                if($arIncapacidad->getCodigoIncapacidadTipoFk() == 1) {
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
                                        $douPagoDetalle = ($douPagoDetalle * $arIncapacidad->getPorcentajePago())/100;
                                        $douIngresoBaseCotizacionIncapacidad = $intHorasProcesarIncapacidad * $douVrHora;
                                    }
                                } else {
                                    $douPagoDetalle = $intHorasProcesarIncapacidad * $douVrHora;
                                    $douPagoDetalle = ($douPagoDetalle * $arIncapacidad->getPorcentajePago())/100;
                                    $douIngresoBaseCotizacionIncapacidad = $intHorasProcesarIncapacidad * $douVrHora;
                                }
                                $arPagoDetalle->setNumeroHoras($intHorasProcesarIncapacidad);
                                $arPagoDetalle->setNumeroDias($intDias);
                                $arPagoDetalle->setVrHora($douVrHora);
                                $arPagoDetalle->setDetalle($arIncapacidad->getIncapacidadDiagnosticoRel()->getNombre());
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion(1);
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * 1);
                                $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                                $douDevengado = $douDevengado + $douPagoDetalle;
                                $douIngresoBasePrestacional += $douIngresoBaseCotizacionIncapacidad;                                        
                                $douIngresoBaseCotizacion  += $douPagoDetalle;                                                                        
                                $arPagoDetalle->setVrIngresoBasePrestacion($douIngresoBaseCotizacionIncapacidad);                                                                            
                                $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);                                                                                                            
                                $em->persist($arPagoDetalle);                                                                                                         
                            }                                                                                                 
                        }
 
                        //Procesar Licencias                                                
                        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
                        $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->periodo($arProgramacionPagoDetalle->getFechaDesdePago(), $arProgramacionPagoDetalle->getFechaHasta(), $arProgramacionPagoDetalle->getCodigoEmpleadoFk());
                        foreach ($arLicencias as $arLicencia) {
                            if($intHorasLaboradas > 0) {                                                                    
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arLicencia->getLicenciaTipoRel()->getPagoConceptoRel());                                
                                $fechaDesde = $arProgramacionPagoDetalle->getFechaDesdePago();
                                $fechaHasta = $arProgramacionPagoDetalle->getFechaHasta();
                                if($arLicencia->getFechaDesde() >  $fechaDesde) {
                                    $fechaDesde = $arLicencia->getFechaDesde();
                                }             
                                if($arLicencia->getFechaHasta() < $fechaHasta) {
                                    $fechaHasta = $arLicencia->getFechaHasta();                
                                }
                                $intDias = $fechaDesde->diff($fechaHasta);
                                $intDias = $intDias->format('%a');
                                $intDias += 1;
                                $intHorasProcesarLicencia = $intDias * 8;

                                $intHorasLaboradas = $intHorasLaboradas - $intHorasProcesarLicencia;                                                                    
                                $douPagoDetalle = $intHorasProcesarLicencia * $douVrHora;
                                $douIngresoBasePrestacional = $douIngresoBasePrestacional + $douPagoDetalle;                                        
                                $arPagoDetalle->setVrIngresoBasePrestacion($douPagoDetalle);                                                                    
                                if($arLicencia->getLicenciaTipoRel()->getAfectaSalud() == 0) {
                                    $douIngresoBaseCotizacion += $douPagoDetalle;                                        
                                    $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);
                                }       
                                if($arLicencia->getLicenciaTipoRel()->getAusentismo() == 1) {
                                    $arPagoDetalle->setDiasAusentismo($intDias);
                                }
                                if($arLicencia->getLicenciaTipoRel()->getPagoConceptoRel()->getOperacion() == 0) {
                                    $douPagoDetalle = 0;
                                }
                                $douDevengado = $douDevengado + $douPagoDetalle;
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arLicencia->getLicenciaTipoRel()->getPagoConceptoRel()->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arLicencia->getLicenciaTipoRel()->getPagoConceptoRel()->getOperacion());                               
                                $arPagoDetalle->setNumeroHoras($intHorasProcesarLicencia);
                                $arPagoDetalle->setNumeroDias($intDias);
                                $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);                                    
                                $em->persist($arPagoDetalle);
                                if($arLicencia->getAfectaTransporte() == 1){
                                    $intDiasLicenciaProcesar = intval($intHorasProcesarLicencia / $arProgramacionPagoDetalle->getFactorDia());
                                    $intDiasTransporte = $intDiasTransporte - $intDiasLicenciaProcesar;
                                }
                            }
                        }

                        //Procesar los conceptos de pagos adicionales
                        $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $arProgramacionPagoProcesar->getCodigoCentroCostoFk(), 'pagoAplicado' => 0, 'codigoEmpleadoFk' => $arProgramacionPagoDetalle->getCodigoEmpleadoFk()));
                        foreach ($arPagosAdicionales as $arPagoAdicional) {
                            $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                            $arPagoDetalle->setPagoRel($arPago);
                            $arPagoDetalle->setPagoConceptoRel($arPagoAdicional->getPagoConceptoRel());
                            $arPagoDetalle->setAdicional(1);
                            if($arPagoAdicional->getPagoConceptoRel()->getComponePorcentaje() == 1) {
                                $douVrHoraAdicional = ($douVrHora * $arPagoAdicional->getPagoAdicionalSubtipoRel()->getPorcentaje())/100;
                                $douPagoDetalle = $douVrHoraAdicional * $arPagoAdicional->getCantidad();
                                $arPagoDetalle->setVrHora($douVrHoraAdicional);
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setNumeroHoras($arPagoAdicional->getCantidad());
                            }
                            if($arPagoAdicional->getPagoConceptoRel()->getComponeValor() == 1) {
                                $douPagoDetalle = $arPagoAdicional->getValor();
                                if($arPagoAdicional->getAplicaDiaLaborado() == 1) {
                                    $douPagoDetalle = $arPagoAdicional->getValor() * ($intHorasLaboradas / 8);
                                }
                                $arPagoDetalle->setVrDia($douVrDia);
                            }
                            $arPagoDetalle->setDetalle($arPagoAdicional->getDetalle());
                            $arPagoDetalle->setVrPago($douPagoDetalle);
                            $arPagoDetalle->setOperacion($arPagoAdicional->getPagoConceptoRel()->getOperacion());
                            $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoAdicional->getPagoConceptoRel()->getOperacion());
                            $arPagoDetalle->setDetalle($arPagoAdicional->getPagoAdicionalSubtipoRel()->getDetalle());
                            $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                            $em->persist($arPagoDetalle);

                            if($arPagoAdicional->getPagoConceptoRel()->getPrestacional() == 1) {
                                $douDevengado = $douDevengado + $douPagoDetalle;
                                $douIngresoBasePrestacional += $douPagoDetalle;
                                $douIngresoBaseCotizacion += $douPagoDetalle;
                                $arPagoDetalle->setVrIngresoBasePrestacion($douPagoDetalle);
                                $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);
                                $arPagoDetalle->setPrestacional(1);
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
                                if ($arCreditoProcesar->getSaldoTotal() <= 0){
                                    $arCreditoProcesar->setEstadoPagado(1);
                                }
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
                        $arPagoDetalle->setNumeroDias($intHorasLaboradas / $arProgramacionPagoDetalle->getFactorDia());
                        $arPagoDetalle->setVrPago($douPagoDetalle);
                        $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                        $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                        $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                        $em->persist($arPagoDetalle);
                        $douDevengado = $douDevengado + $douPagoDetalle;
                        $douIngresoBasePrestacional += $douPagoDetalle;                        
                        $douIngresoBaseCotizacion += $douPagoDetalle;                        
                        $arPagoDetalle->setVrIngresoBasePrestacion($douPagoDetalle);
                        $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);
                        /*if($arProgramacionPagoDetalle->getCodigoEmpleadoFk() == 507) {
                            echo "hola";
                        }*/
                        //Liquidar salud
                        if($arProgramacionPagoDetalle->getDescuentoSalud() == 1) {
                            $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoSalud);
                            $intDias = $intHorasLaboradas / 8;
                            if($arProgramacionPagoDetalle->getSalarioIntegral() == 1) {
                                $douPagoDetalle = (($douIngresoBaseCotizacion / 1.3) * $arPagoConcepto->getPorPorcentaje())/100;                                                                
                            } else {
                                $douPagoDetalle = ($douIngresoBaseCotizacion * $arPagoConcepto->getPorPorcentaje())/100;                                                            
                            }
                            
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
                        }                        

                        //Liquidar pension
                        if($arProgramacionPagoDetalle->getDescuentoPension() == 1) {
                            $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoPension);
                            $douPorcentaje = $arPagoConcepto->getPorPorcentaje();
                            if($intHorasLaboradas > 0) {                                
                                $douValorHoraMinimo = ($douVrSalarioMinimo / 240) * 4;
                                if($douVrHora > $douValorHoraMinimo) {
                                    $douPorcentaje = $arConfiguracion->getPorcentajePensionExtra(); //PORCENTAJE PENSION EXTRA DEL 5%
                                }                                        
                            }
                            if($arProgramacionPagoDetalle->getSalarioIntegral() == 1) {
                                $douPagoDetalle = (($douIngresoBaseCotizacion / 1.3) * $douPorcentaje)/100;
                            } else {
                                $douPagoDetalle = ($douIngresoBaseCotizacion * $douPorcentaje)/100;
                            }
                            
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
                        }

                        //Subsidio transporte
                        if($arProgramacionPagoDetalle->getPagoAuxilioTransporte() == 1) {
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
                                    $arPagoDetalle->setNumeroHoras(0);
                                    $arPagoDetalle->setNumeroDias($intDiasTransporte);
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
                    }
                    $arProgramacionPagoProcesar->setEstadoGenerado(1);
                    $em->persist($arProgramacionPagoProcesar);
                    $em->flush();

                    //$em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->liquidar($codigoProgramacionPago);
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generarPagoDetalleSede($codigoProgramacionPago);
                    if($arProgramacionPagoProcesar->getNoGeneraPeriodo() == 0) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarProgramacionPago($arProgramacionPagoProcesar->getCodigoCentroCostoFk(), 1);
                    }
                }

                if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 2) {
                    $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                    $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $arProgramacionPagoProcesar->getCodigoProgramacionPagoPk()));
                    foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago->setPagoTipoRel($arProgramacionPagoProcesar->getPagoTipoRel());                                    
                        $arPago->setEmpleadoRel($arProgramacionPagoDetalle->getEmpleadoRel());
                        $arPago->setCentroCostoRel($arCentroCosto);
                        $arPago->setFechaDesde($arProgramacionPagoProcesar->getFechaDesde());
                        $arPago->setFechaHasta($arProgramacionPagoProcesar->getFechaHasta());
                        $arPago->setVrSalarioEmpleado($arProgramacionPagoDetalle->getVrSalario());
                        $arPago->setVrSalarioPeriodo($arProgramacionPagoDetalle->getVrDevengado());
                        $arPago->setProgramacionPagoRel($arProgramacionPagoProcesar);
                        $arPago->setDiasPeriodo($arProgramacionPagoDetalle->getDias());
                        if($arProgramacionPagoDetalle->getCodigoContratoFk()) {
                            $arPago->setContratoRel($arProgramacionPagoDetalle->getContratoRel());
                        }                        
                        $em->persist($arPago);
                        $douSalarioMinimo = $arConfiguracion->getVrSalario();
                        $intDias = $arProgramacionPagoDetalle->getDias();
                        $intDiasContinuos = $arProgramacionPagoDetalle->getFechaDesde()->diff($arProgramacionPagoDetalle->getFechaHasta());
                        $intDiasContinuos = $intDiasContinuos->format('%a');
                        $intDiasContinuos += 1;
                        
                        $floIbp = $em->getRepository('BrasaRecursoHumanoBundle:RhuIngresoBase')->devuelveIbpFecha($arProgramacionPagoDetalle->getCodigoEmpleadoFk(), $arProgramacionPagoDetalle->getFechaDesdePago()->format('Y-m-d'), $arProgramacionPagoProcesar->getFechaHastaReal()->format('Y-m-d'), $arProgramacionPagoDetalle->getCodigoContratoFk());
                        
                        //$arrayCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->devuelveCostosFecha($arProgramacionPagoDetalle->getCodigoEmpleadoFk(), $arProgramacionPagoDetalle->getFechaDesdePago()->format('Y-m-d'), $arProgramacionPagoDetalle->getFechaHasta()->format('Y-m-d'), $arProgramacionPagoDetalle->getCodigoContratoFk());
                        //$floIbc = (float)$arrayCostos[0]['IBC'];
                        $dateFechaUltimoPago = $arProgramacionPagoDetalle->getContratoRel()->getFechaUltimoPago();                        
                        if($dateFechaUltimoPago < $arProgramacionPagoProcesar->getFechaHasta()) {
                            $floVrDia = $arProgramacionPagoDetalle->getVrSalario() / 30;                                        
                            $dateFechaDesde =  "";                            
                            if($dateFechaUltimoPago <= $arProgramacionPagoProcesar->getFechaDesde()) {
                                $dateFechaDesde = $arProgramacionPagoProcesar->getFechaDesde();                                
                            } else {
                                $dateFechaDesde = $dateFechaUltimoPago;                                
                            }
                            $intDiasIbcAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($dateFechaDesde, $arProgramacionPagoProcesar->getFechaHasta());                            
                            $intDiasIbcAdicional = $intDiasIbcAdicional - 1;
                            $floIbp +=  $intDiasIbcAdicional * $floVrDia;
                            $arPago->setComentarios("Dado que se pagan las primas antes del periodo, se proyectan (" . $intDiasIbcAdicional . ") dias restantes con el salario");
                        }
                        if($arCentroCosto->getPeriodoPagoRel()->getContinuo() == 1) {
                            $floSalarioPromedio = ($floIbp / $intDiasContinuos) * 30;
                        } else {
                            $floSalarioPromedio = ($floIbp / $intDias) * 30;
                        }
                                                            
                        if(round($floSalarioPromedio) <=  $douSalarioMinimo * 2 ) {
                            $floSalarioPromedio += $arConfiguracion->getVrAuxilioTransporte();
                        }
                        $floTotalPago = ($floSalarioPromedio * $intDias) / 360;

                        //Prima
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(28);
                        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                        $arPagoDetalle->setPagoRel($arPago);
                        $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                        $arPagoDetalle->setDetalle($intDias . " Dias de primas (IBC " . number_format($floIbp, 0, '.', ',') . " )");
                        $arPagoDetalle->setVrPago($floTotalPago);
                        $arPagoDetalle->setNumeroDias($intDias);
                        $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                        $arPagoDetalle->setVrPagoOperado($floTotalPago * $arPagoConcepto->getOperacion());
                        $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                        $em->persist($arPagoDetalle);                                    

                    }
                    $arProgramacionPagoProcesar->setEstadoGenerado(1);                                
                    $em->persist($arProgramacionPagoProcesar);
                    $em->flush();
                    //$em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->liquidar($codigoProgramacionPago);
                }

                if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 3) {
                    $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                    $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $arProgramacionPagoProcesar->getCodigoProgramacionPagoPk()));
                    foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago->setPagoTipoRel($arProgramacionPagoProcesar->getPagoTipoRel());
                        $arContratoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                        $arPago->setEmpleadoRel($arProgramacionPagoDetalle->getEmpleadoRel());
                        $arPago->setCentroCostoRel($arCentroCosto);
                        $arPago->setFechaDesde($arProgramacionPagoProcesar->getFechaDesde());
                        $arPago->setFechaHasta($arProgramacionPagoProcesar->getFechaHasta());
                        $arPago->setVrSalarioEmpleado($arProgramacionPagoDetalle->getVrSalario());
                        $arPago->setVrSalarioPeriodo($arProgramacionPagoDetalle->getVrDevengado());
                        $arPago->setProgramacionPagoRel($arProgramacionPagoProcesar);
                        $arPago->setDiasPeriodo($arProgramacionPagoDetalle->getDias());
                        if($arProgramacionPagoDetalle->getCodigoContratoFk()) {
                            $arPago->setContratoRel($arProgramacionPagoDetalle->getContratoRel());
                        }
                        $em->persist($arPago);
                        $douSalarioMinimo = $arConfiguracion->getVrSalario();
                        $intDias = $arProgramacionPagoDetalle->getDias();
                        $intDiasContinuos = $arProgramacionPagoDetalle->getFechaDesde()->diff($arProgramacionPagoDetalle->getFechaHasta());
                        $intDiasContinuos = $intDiasContinuos->format('%a');
                        $intDiasContinuos += 1;   
                        
                        $floIbp = $em->getRepository('BrasaRecursoHumanoBundle:RhuIngresoBase')->devuelveIbpFecha($arProgramacionPagoDetalle->getCodigoEmpleadoFk(), $arProgramacionPagoDetalle->getFechaDesdePago()->format('Y-m-d'), $arProgramacionPagoProcesar->getFechaHastaReal()->format('Y-m-d'), $arProgramacionPagoDetalle->getCodigoContratoFk());
                        if($arCentroCosto->getPeriodoPagoRel()->getContinuo() == 1) {
                            $floSalarioPromedio = ($floIbp / $intDiasContinuos) * 30;
                        } else {
                            $floSalarioPromedio = ($floIbp / $intDias) * 30;
                        }                           
                        $strMensajeAuxilioTransporte = "";
                        if(round($floSalarioPromedio) <=  $douSalarioMinimo * 2 ) {
                            $floSalarioPromedio += $arConfiguracion->getVrAuxilioTransporte();
                            $strMensajeAuxilioTransporte = " + Aux. transporte (" . $arConfiguracion->getVrAuxilioTransporte() . ")";
                        }
                        $floTotalPago = ($floSalarioPromedio * $intDias) / 360;

                        //Cesantias
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(29);
                        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                        $arPagoDetalle->setPagoRel($arPago);
                        $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                        $arPagoDetalle->setDetalle($intDias . " Dias de cesantias (IBP " . number_format($floIbp, 0, '.', ',') . " )" . $strMensajeAuxilioTransporte);
                        $arPagoDetalle->setNumeroDias($intDias);
                        $arPagoDetalle->setVrPago($floTotalPago);
                        $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                        $arPagoDetalle->setVrPagoOperado($floTotalPago * $arPagoConcepto->getOperacion());
                        $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                        $em->persist($arPagoDetalle);
                        
                        $floPorcentajeIntereses = (($intDias * 12) / 360)/100;
                        $floTotalPagoIntereses = $floTotalPago * $floPorcentajeIntereses;
                        
                        //Intereses cesantias
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(30);
                        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                        $arPagoDetalle->setPagoRel($arPago);
                        $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                        $arPagoDetalle->setNumeroDias($intDias);
                        $arPagoDetalle->setVrPago($floTotalPagoIntereses);
                        $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                        $arPagoDetalle->setVrPagoOperado($floTotalPagoIntereses * $arPagoConcepto->getOperacion());
                        $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                        $em->persist($arPagoDetalle);                                    

                    }
                    $arProgramacionPagoProcesar->setEstadoGenerado(1);                                
                    $em->persist($arProgramacionPagoProcesar);
                    $em->flush();
                    //$em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->liquidar($codigoProgramacionPago);
                }                
            } else {
                $strMensaje = "No se puede generar programacion porque el año es mayor al ultimo año cerrado";
            }                            
        } else {
            $strMensaje = "Solo se pueden generar programaciones que no esten generadas y que tengan empleados";
        }        
        set_time_limit(90);
        return $strMensaje;
    }
    
    /**
     * Elimina los pagos generados de esta programacion y revierte los procesos
     *
     * @author		Mario Estrada
     *
     * @param integer	Codigo de la programacion de pago
     */
    public function deshacer($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        //Devolver pagos adicionales
        $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arPagosAdicionales as $arPagoAdicional) {
            $arPagoAdicionalActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
            $arPagoAdicionalActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($arPagoAdicional->getCodigoPagoAdicionalPk());
            $arPagoAdicionalActualizar->setPagoAplicado(0);
            $arPagoAdicionalActualizar->setProgramacionPagoRel(null);
            $em->persist($arPagoAdicionalActualizar);
        }

        //Eliminar pagos
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arPagos as $arPago) {
            $arPagosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
            $arPagosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
            foreach ($arPagosDetalles as $arPagoDetalle) {
                //Desahacer creditos
                if($arPagoDetalle->getCodigoCreditoFk() != "" && $arPagoDetalle->getCodigoPagoConceptoFk() == 14) {
                    $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                    $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($arPagoDetalle->getCodigoCreditoFk());
                    $arCredito->setVrCuotaTemporal($arCredito->getVrCuotaTemporal() - $arPagoDetalle->getVrPago());
                    $arCredito->setSaldoTotal($arCredito->getSaldo() - $arCredito->getVrCuotaTemporal());
                    $em->persist($arCredito);
                }                 
                $em->remove($arPagoDetalle);
            }
            $em->remove($arPago);
        }
        $arProgramacionPago->setNoGeneraPeriodo(1);
        $arProgramacionPago->setVrNeto(0);
        $arProgramacionPago->setEstadoGenerado(0);
        $em->persist($arProgramacionPago);
        $em->flush();
        return true;
    }

    /**
     * Liquidar toda la programacion de pago
     *
     * @author		Mario Estrada
     *
     * @param integer	Codigo de la programacion de pago
     */
    public function liquidar($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        set_time_limit(0);
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        $douNeto = 0;
        foreach ($arPagos as $arPago) {
            $douNeto += $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->Liquidar($arPago->getCodigoPagoPk());
        }
        $arProgramacionPago->setVrNeto($douNeto);
        $em->persist($arProgramacionPago);
        $em->flush();
        set_time_limit(90);
        return true;
    }

    /**
     * Paga la programacion pago, este proceso no se puede deshacer
     *
     * @author		Mario Estrada
     *
     * @param integer	Codigo de la programacion de pago
     */
    public function pagar($codigoProgramacionPago) {       
        $em = $this->getEntityManager(); 
        set_time_limit(0);
        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPagoProcesar->getCodigoCentroCostoFk());                                
        if($arProgramacionPagoProcesar->getEstadoGenerado() == 1) {
            $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
            $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
            foreach ($arPagos as $arPago) {
                $arPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                $arPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($arPago->getCodigoPagoPk());                        
                $douSalario = 0;
                $douAuxilioTransporte = 0;
                $douAdicionTiempo = 0;
                $douAdicionValor = 0;
                $douPension = 0;
                $douCaja = 0;
                $douCesantias = 0;
                $douVacaciones = 0;
                $douAdministracion = 0;
                $douDeducciones = 0;
                $douDevengado = 0;        
                $douNeto = 0;
                $douIngresoBaseCotizacion = 0;
                $douIngresoBasePrestacion = 0;
                $arPagosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                $arPagosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
                foreach ($arPagosDetalles AS $arPagoDetalle) {
                    if($arPagoDetalle->getCodigoCreditoFk() != "" && $arPagoDetalle->getCodigoPagoConceptoFk() == 14) {
                        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($arPagoDetalle->getCodigoCreditoFk());

                        //Crear credito pago se guarda el pago en la tabla rhu_pago_credito
                        $arPagoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
                        $arPagoCredito->setCreditoRel($arCredito);
                        $arPagoCredito->setPagoRel($arPagoDetalle->getPagoRel());
                        $arPagoCredito->setfechaPago(new \ DateTime("now"));
                        $arPagoCredito->setCreditoTipoPagoRel($arCredito->getCreditoTipoPagoRel());
                        $arPagoCredito->setVrCuota($arPagoDetalle->getVrPago());
                        $em->persist($arPagoCredito);
                        //Actualizar el saldo del credito
                        $arCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual() + 1);
                        $arCredito->setSaldo($arCredito->getSaldo() - $arPagoDetalle->getVrPago());
                        $arCredito->setVrCuotaTemporal($arCredito->getVrCuotaTemporal() - $arPagoDetalle->getVrPago());
                        $arCredito->setSaldoTotal($arCredito->getSaldo() - $arCredito->getVrCuotaTemporal());
                        if($arCredito->getSaldo() <= 0) {
                           $arCredito->setEstadoPagado(1);
                        }
                        $em->persist($arCredito);
                    }
                    //Liquidacion
                    if($arPagoDetalle->getOperacion() == 1) {
                        $douDevengado = $douDevengado + $arPagoDetalle->getVrPago();
                    }
                    if($arPagoDetalle->getOperacion() == -1) {
                        $douDeducciones = $douDeducciones + $arPagoDetalle->getVrPago();
                    }
                    if($arPagoDetalle->getPagoConceptoRel()->getComponeSalario() == 1) {
                        $douSalario = $douSalario + $arPagoDetalle->getVrPago();
                    }            
                    if($arPagoDetalle->getPagoConceptoRel()->getConceptoAuxilioTransporte() == 1) {
                        $douAuxilioTransporte = $douAuxilioTransporte + $arPagoDetalle->getVrPago();
                    }            
                    if($arPagoDetalle->getPagoConceptoRel()->getConceptoAdicion() == 1) {
                        if($arPagoDetalle->getPagoConceptoRel()->getComponeValor() == 1) {
                            $douAdicionValor = $douAdicionValor + $arPagoDetalle->getVrPago();    
                        } else {
                            $douAdicionTiempo = $douAdicionTiempo + $arPagoDetalle->getVrPago();    
                        }                
                    }
                    $douIngresoBaseCotizacion = $douIngresoBaseCotizacion + $arPagoDetalle->getVrIngresoBaseCotizacion();                            
                    $douIngresoBasePrestacion = $douIngresoBasePrestacion + $arPagoDetalle->getVrIngresoBasePrestacion();                                                
                }
                $douSalarioPeriodo = $arPagoProcesar->getVrSalarioPeriodo();
                $douSalarioSeguridadSocial = $douSalarioPeriodo + $douAdicionTiempo + $douAdicionValor;
                $douDiaAuxilioTransporte = 74000 / 30;
                $douAuxilioTransporteCotizacion = $arPagoProcesar->getDiasPeriodo() * $douDiaAuxilioTransporte;
                $douArp = ($douSalarioSeguridadSocial * $arPagoProcesar->getContratoRel()->getClasificacionRiesgoRel()->getPorcentaje())/100;        
                $douPension = ($douSalarioSeguridadSocial * $arPagoProcesar->getContratoRel()->getTipoPensionRel()->getPorcentajeCotizacion()) / 100; 
                $douCaja = ($douSalarioSeguridadSocial * 4) / 100; // este porcentaje debe parametrizarse en configuracion                
                $douCesantias = (($douSalarioSeguridadSocial + $douAuxilioTransporteCotizacion) * 17.66) / 100; // este porcentaje debe parametrizarse en configuracion                
                $douVacaciones = ($douSalarioPeriodo * 4.5) / 100; // este porcentaje debe parametrizarse en configuracion                        
                $douTotalEjercicio = $douSalario+$douAdicionTiempo+$douAdicionValor+$douAuxilioTransporte+$douArp+$douPension+$douCaja+$douCesantias+$douVacaciones;
                if($arPagoProcesar->getCentroCostoRel()->getPorcentajeAdministracion() != 0 ) {
                    $douAdministracion = ($douTotalEjercicio * $arPagoProcesar->getCentroCostoRel()->getPorcentajeAdministracion()) / 100;            
                } else {
                    $douAdministracion = $arPagoProcesar->getCentroCostoRel()->getPorcentajeAdministracion();
                }                        
                $arServicioCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();                        
                $arServicioCobrar->setPagoRel($arPagoProcesar);
                $arServicioCobrar->setCentroCostoRel($arPagoProcesar->getCentroCostoRel());
                $arServicioCobrar->setEmpleadoRel($arPagoProcesar->getEmpleadoRel());
                $arServicioCobrar->setProgramacionPagoRel($arPagoProcesar->getProgramacionPagoRel());
                $arServicioCobrar->setFechaDesde($arPagoProcesar->getFechaDesde());
                $arServicioCobrar->setFechaHasta($arPagoProcesar->getFechaHasta());
                $arServicioCobrar->setVrDevengado($douDevengado);
                $arServicioCobrar->setVrDeducciones($douDeducciones);
                $douNeto = $douDevengado - $douDeducciones;
                $arServicioCobrar->setVrNeto($douNeto);
                $arServicioCobrar->setVrSalario($douSalario);
                $arServicioCobrar->setVrAuxilioTransporte($douAuxilioTransporte);
                $arServicioCobrar->setVrAuxilioTransporteCotizacion($douAuxilioTransporteCotizacion);
                $arServicioCobrar->setVrAdicionalTiempo($douAdicionTiempo);
                $arServicioCobrar->setVrAdicionalValor($douAdicionValor);
                $arServicioCobrar->setVrArp($douArp);
                $arServicioCobrar->setVrPension($douPension);
                $arServicioCobrar->setVrCaja($douCaja);
                $arServicioCobrar->setVrCesantias($douCesantias);
                $arServicioCobrar->setVrVacaciones($douVacaciones);
                $arServicioCobrar->setVrAdministracion($douAdministracion);
                //Tambien llamado total ejercicio
                $arServicioCobrar->setVrCosto($douTotalEjercicio);
                $arServicioCobrar->setVrTotalCobrar($douTotalEjercicio + $douAdministracion);        
                $arServicioCobrar->setVrIngresoBaseCotizacion($douIngresoBaseCotizacion);
                $em->persist($arServicioCobrar);                      

                $arPagoProcesar->setNumero($em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->consecutivo(1));
                $arPagoProcesar->setEstadoPagado(1);
                $em->persist($arPagoProcesar); 
                
                if($arPagoProcesar->getCodigoPagoTipoFk() == 1) {
                    if($arPagoProcesar->getFechaDesdePago()->format('m') == $arPagoProcesar->getFechaHastaPago()->format('m')) {
                        $arIngresoBase = new \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase();
                        $arIngresoBase->setFechaDesde($arPagoProcesar->getFechaDesdePago());                        
                        $arIngresoBase->setFechaHasta($arPagoProcesar->getFechaHastaPago());
                        $arIngresoBase->setContratoRel($arPagoProcesar->getContratoRel());
                        $arIngresoBase->setEmpleadoRel($arPagoProcesar->getEmpleadoRel());
                        $arIngresoBase->setVrIngresoBaseCotizacion($douIngresoBaseCotizacion);
                        $arIngresoBase->setVrIngresoBasePrestacion($douIngresoBasePrestacion);
                        $em->persist($arIngresoBase);
                    } else {
                        $intDiasHasta = $arPagoProcesar->getFechaHastaPago()->format('j');
                        $intDiasDesde = $arPagoProcesar->getDiasPeriodo() - $intDiasHasta;
                        $vrIbcPromedioDia = 0;
                        $vrIbpPromedioDia = 0;
                        if($arPagoProcesar->getDiasPeriodo() > 0) {
                            $vrIbcPromedioDia = $douIngresoBaseCotizacion / $arPagoProcesar->getDiasPeriodo();                        
                            $vrIbpPromedioDia = $douIngresoBasePrestacion / $arPagoProcesar->getDiasPeriodo();                        
                        }                    
                        $strAnio = $arPagoProcesar->getFechaDesdePago()->format('Y');
                        $strMes = $arPagoProcesar->getFechaDesdePago()->format('m');
                        $intUltimoDiaMes = date("d",(mktime(0,0,0,$strMes+1,1,$strAnio)-1));
                        $strFechaHasta = $arPagoProcesar->getFechaDesdePago()->format('Y/m') ."/" . $intUltimoDiaMes;
                        $arIngresoBase = new \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase();                       
                        $arIngresoBase->setFechaDesde($arPagoProcesar->getFechaDesdePago());                                                
                        $arIngresoBase->setFechaHasta(date_create($strFechaHasta));
                        $arIngresoBase->setContratoRel($arPagoProcesar->getContratoRel());
                        $arIngresoBase->setEmpleadoRel($arPagoProcesar->getEmpleadoRel());
                        $arIngresoBase->setVrIngresoBaseCotizacion( $vrIbcPromedioDia * $intDiasDesde);
                        $arIngresoBase->setVrIngresoBasePrestacion( $vrIbpPromedioDia * $intDiasDesde);
                        $em->persist($arIngresoBase);

                        $strFechaDesde = $arPagoProcesar->getFechaHastaPago()->format('Y/m') . "/01";
                        $arIngresoBase = new \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase();
                        $arIngresoBase->setFechaDesde(date_create($strFechaDesde));
                        $arIngresoBase->setFechaHasta($arPagoProcesar->getFechaHastaPago());
                        $arIngresoBase->setContratoRel($arPagoProcesar->getContratoRel());
                        $arIngresoBase->setEmpleadoRel($arPagoProcesar->getEmpleadoRel());
                        $arIngresoBase->setVrIngresoBaseCotizacion($vrIbcPromedioDia * $intDiasHasta);
                        $arIngresoBase->setVrIngresoBasePrestacion($vrIbpPromedioDia * $intDiasHasta);
                        $em->persist($arIngresoBase);                    

                    }                    
                }
            }

            $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
            $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
            foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
                if($arProgramacionPagoDetalle->getCodigoContratoFk()) {
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arProgramacionPagoDetalle->getCodigoContratoFk());                                                                        
                    if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 1) {                            
                        $arContrato->setFechaUltimoPago($arProgramacionPagoProcesar->getFechaHasta());
                    }
                    if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 2) {                        
                        if($arProgramacionPagoProcesar->getFechaHasta()->format('md') == '1230') {
                            $strAnio = $arProgramacionPagoProcesar->getFechaHasta()->format('Y');
                            $strFecha = $strAnio + 1 . "/01/01";
                        } else {
                            $strAnio = $arProgramacionPagoProcesar->getFechaHasta()->format('Y');
                            $strFecha = $strAnio . "/07/01";                            
                        }
                        $arContrato->setFechaUltimoPagoPrimas(date_create($strFecha));
                    }
                    if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 3) { 
                        $strAnio = $arProgramacionPagoProcesar->getFechaHasta()->format('Y');
                        $strFecha = $strAnio + 1 . "/01/01";                        
                        $arContrato->setFechaUltimoPagoCesantias(date_create($strFecha));
                    }   
                    $em->persist($arContrato);                            
                }                        
            }
            if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 1) {
                $arCentroCosto->setFechaUltimoPago($arProgramacionPagoProcesar->getFechaHasta());
            }
            if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 2) {
                $arCentroCosto->setFechaUltimoPagoPrima($arProgramacionPagoProcesar->getFechaHasta());
            }
            if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 3) {
                $arCentroCosto->setFechaUltimoPagoCesantias($arProgramacionPagoProcesar->getFechaHasta());
            }                                            
            $em->persist($arCentroCosto);
            $arProgramacionPagoProcesar->setEstadoPagado(1);
            $em->persist($arProgramacionPagoProcesar);
            $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->liquidar($codigoProgramacionPago);
        }            
        $em->flush();   
        set_time_limit(90);
    }

    /**
     * Listar las programaciones de pago segun parametros
     *
     * @author		Mario Estrada
     *
     * @param string	$strFechaDesde          Fecha desde
     * @param string    $strFechaHasta          Fecha hasta
     * @param integer   $codigoCentroCosto      Codigo del centro de costos
     * @param boolean   $boolMostrarGenerados   Generados
     * @param boolean   $boolMostrarPagados     Pagados
     */
    public function listaDQL($strFechaDesde = "", $strFechaHasta = "", $codigoCentroCosto, $boolMostrarGenerados, $boolMostrarPagados, $intTipo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pp FROM BrasaRecursoHumanoBundle:RhuProgramacionPago pp WHERE pp.codigoProgramacionPagoPk <> 0 ";
        if($strFechaDesde != "" ) {
            $dql .= " AND pp.fechaHasta >='" . $strFechaDesde . "'";
        }

        if($strFechaHasta != "") {
            $dql .= " AND pp.fechaHasta <='" . $strFechaHasta . "'";
        }
        if($codigoCentroCosto != "" && $codigoCentroCosto != 0) {
            $dql .= " AND pp.codigoCentroCostoFk =" . $codigoCentroCosto;
        }
        if($intTipo != "" && $intTipo != 0) {
            $dql .= " AND pp.codigoPagoTipoFk =" . $intTipo;
        }        
        if($boolMostrarGenerados == 1 ) {
            $dql .= " AND pp.estadoGenerado = 1";
        }
        if($boolMostrarGenerados == "0") {
            $dql .= " AND pp.estadoGenerado = 0";
        }
        if($boolMostrarPagados == 1 ) {
            $dql .= " AND pp.estadoPagado = 1";
        }
        if($boolMostrarPagados == "0") {
            $dql .= " AND pp.estadoPagado = 0";
        }
        $dql .= " ORDER BY pp.codigoProgramacionPagoPk DESC";
        return $dql;
    }

    public function listaGenerarPagoDQL($strFechaDesde = "", $strFechaHasta = "", $codigoCentroCosto) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pp FROM BrasaRecursoHumanoBundle:RhuProgramacionPago pp WHERE pp.estadoGenerado = 0 ";
        if($strFechaDesde != "" ) {
            $dql .= " AND pp.fechaHasta >='" . $strFechaDesde . "'";
        }

        if($strFechaHasta != "") {
            $dql .= " AND pp.fechaHasta <='" . $strFechaHasta . "'";
        }
        if($codigoCentroCosto != "" && $codigoCentroCosto != 0) {
            $dql .= " AND pp.codigoCentroCostoFk =" . $codigoCentroCosto;
        }
        return $dql;
    }
    //Enviar los pagos adicionales con los permanentes
    public function listaPagosAdicionalesyPermanentes($codigoProgramacionPago = "") {
        $em = $this->getEntityManager();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $centroCosto = $arProgramacionPago->getCodigoCentroCostoFk();
        $dql   = "SELECT pa FROM BrasaRecursoHumanoBundle:RhuPagoAdicional pa WHERE pa.codigoProgramacionPagoFk = $codigoProgramacionPago and pa.pagoAplicado = 0  or  (pa.permanente = 1 and pa.codigoCentroCostoFk = $centroCosto)";
        $query = $em->createQuery($dql);
        $dql = $query->getResult();
        return $dql;
    }
    //listado nuevo por el cambio de centro de costo por programacion de pago
    public function listaGeneralPagoActivosDQL($strEstado = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pp FROM BrasaRecursoHumanoBundle:RhuProgramacionPago pp WHERE pp.estadoGenerado = 0 order by pp.fechaDesde Desc";
        $query = $em->createQuery($dql);
        $dql = $query->getResult();
        return $dql;
    }

    /*
     * Liquidar todos los pagos de la programacion de pago
     */
    public function generarPagoDetalleSede($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arPagos as $arPago) {
            $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->generarPagoDetalleSede($arPago->getCodigoPagoPk());
        }
        return true;
    }

    public function eliminarEmpleados($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
            $em->remove($arProgramacionPagoDetalle);
        }
        $em->flush();
    }

    public function generarEmpleados($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        set_time_limit(0);
        $intNumeroEmpleados = 0;
        $floNetoTotal = 0;
        $boolInconsistencias = 0;
        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoInconsistencia')->eliminarProgramacionPago($codigoProgramacionPago);
        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->eliminarEmpleados($codigoProgramacionPago);
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);        
        if($arProgramacionPago->getCodigoPagoTipoFk() == 1) {             
            $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                    . "WHERE c.codigoCentroCostoFk = " . $arProgramacionPago->getCodigoCentroCostoFk()
                    . " AND c.fechaUltimoPago < '" . $arProgramacionPago->getFechaHastaReal()->format('Y-m-d') . "' "
                    . " AND c.fechaDesde <= '" . $arProgramacionPago->getFechaHastaReal()->format('Y-m-d') . "' "
                    . " AND (c.fechaHasta >= '" . $arProgramacionPago->getFechaDesde()->format('Y-m-d') . "' "
                    . " OR c.indefinido = 1)";            
            $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $query = $em->createQuery($dql);
            $arContratos = $query->getResult();
            foreach ($arContratos as $arContrato) {                
                $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                $arProgramacionPagoDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arProgramacionPagoDetalle->setContratoRel($arContrato);                
                $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalarioPago());
                $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());
                $arProgramacionPagoDetalle->setSalarioIntegral($arContrato->getSalarioIntegral());
                if($arContrato->getCodigoContratoTipoFk() == 4 || $arContrato->getCodigoContratoTipoFk() == 5) {
                    $arProgramacionPagoDetalle->setDescuentoPension(0);
                    $arProgramacionPagoDetalle->setDescuentoSalud(0);
                    $arProgramacionPagoDetalle->setPagoAuxilioTransporte(0);
                }
                $dateFechaDesde =  "";
                $dateFechaHasta =  "";
                $intDiasDevolver = 0;
                $fechaFinalizaContrato = $arContrato->getFechaHasta();
                if($arContrato->getIndefinido() == 1) {
                    $fecha = date_create(date('Y-m-d'));
                    date_modify($fecha, '+100000 day');
                    $fechaFinalizaContrato = $fecha;
                }
                if($arContrato->getFechaDesde() <  $arProgramacionPago->getFechaDesde() == true) {
                    $dateFechaDesde = $arProgramacionPago->getFechaDesde();
                } else {
                    if($arContrato->getFechaDesde() > $arProgramacionPago->getFechaHasta() == true) {
                        if($arContrato->getFechaDesde() == $arProgramacionPago->getFechaHastaReal()) {
                            $dateFechaDesde = $arProgramacionPago->getFechaHastaReal();
                            $intDiasDevolver = 1;                        
                        } else {
                            $intDiasDevolver = 0;                        
                        }
                        
                    } else {
                        $dateFechaDesde = $arContrato->getFechaDesde();
                    }
                }
                if($fechaFinalizaContrato >  $arProgramacionPago->getFechaHasta() == true) {
                    $dateFechaHasta = $arProgramacionPago->getFechaHasta();
                } else {
                    if($fechaFinalizaContrato < $arProgramacionPago->getFechaDesde() == true) {
                        $intDiasDevolver = 0;
                    } else {
                        $dateFechaHasta = $fechaFinalizaContrato;
                    }
                }
                if($dateFechaDesde != "" && $dateFechaHasta != "") {
                    $intDias = $dateFechaDesde->diff($dateFechaHasta);
                    $intDias = $intDias->format('%a');
                    $intDiasDevolver = $intDias + 1;
                    //Mes de febrero para periodos NO continuos
                    $intDiasInhabilesFebrero = 0;
                    if($arProgramacionPago->getCentroCostoRel()->getPeriodoPagoRel()->getContinuo() == 0) {
                        if($dateFechaHasta->format('md') == '0228' || $dateFechaHasta->format('md') == '0229') {
                            //Verificar si el año es bisiesto

                            if(date('L',mktime(1,1,1,1,1,$dateFechaHasta->format('Y'))) == 1) {
                                $intDiasInhabilesFebrero = 1;
                            } else {
                                $intDiasInhabilesFebrero = 2;
                            }
                        }
                        
                        if($dateFechaDesde->format('d') == "31") {
                            $intDiasDevolver = 1;
                        } else {
                            $intDiasDevolver += $intDiasInhabilesFebrero;
                        }
                    } else {
                        $intDiasDevolver += $intDiasInhabilesFebrero;
                    }                    
                }
                
                $arProgramacionPagoDetalle->setFechaDesdePago($dateFechaDesde);
                $arProgramacionPagoDetalle->setFechaHastaPago($dateFechaHasta);
                $arProgramacionPagoDetalle->setFechaDesde($arContrato->getFechaDesde());
                $arProgramacionPagoDetalle->setFechaHasta($dateFechaHasta);

                $arProgramacionPagoDetalle->setDias($intDiasDevolver);
                $arProgramacionPagoDetalle->setDiasReales($intDiasDevolver);
                $arProgramacionPagoDetalle->setHorasPeriodo($intDiasDevolver * $arContrato->getFactorHorasDia());
                $arProgramacionPagoDetalle->setHorasPeriodoReales($intDiasDevolver * $arContrato->getFactorHorasDia());
                $arProgramacionPagoDetalle->setFactorDia($arContrato->getFactorHorasDia());
                
                $floValorDia = $arContrato->getVrSalarioPago() / 30;       
                $floValorHora = $floValorDia / $arContrato->getFactorHorasDia();   
                $arProgramacionPagoDetalle->setVrDia($floValorDia);
                $arProgramacionPagoDetalle->setVrHora($floValorHora);
                $floDevengado = $arProgramacionPagoDetalle->getDias() * $floValorDia;
                $arProgramacionPagoDetalle->setVrDevengado($floDevengado);
                $floCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->cuotaCreditosNomina($arContrato->getCodigoEmpleadoFk());
                $arProgramacionPagoDetalle->setVrCreditos($floCreditos);
                $floDeducciones = $floCreditos;
                $arProgramacionPagoDetalle->setVrDeducciones($floDeducciones);
                $floNeto = $floDevengado - $floDeducciones;
                $arProgramacionPagoDetalle->setVrNetoPagar($floNeto);
                
                //dias vacaciones
                $intDiasVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->dias($arContrato->getCodigoEmpleadoFk(), $arContrato->getCodigoContratoPk(), $arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta());                
                if($intDiasVacaciones > 0) {                                        
                    $arProgramacionPagoDetalle->setDiasVacaciones($intDiasVacaciones);
                }                
                
                $em->persist($arProgramacionPagoDetalle);
                if($floNeto < 0) {
                    $boolInconsistencias = 1;
                    $arProgramacionPagoInconsistencia = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoInconsistencia();
                    $arProgramacionPagoInconsistencia->setProgramacionPagoRel($arProgramacionPago);
                    $arProgramacionPagoInconsistencia->setInconsistencia("El empleado " . $arContrato->getEmpleadoRel()->getNombreCorto() . " tiene deducciones muy altas");
                    $em->persist($arProgramacionPagoInconsistencia);
                }
                $intNumeroEmpleados++;
                $floNetoTotal += $floNeto;                                
                
            }
            $arProgramacionPago->setNumeroEmpleados($intNumeroEmpleados);
            //$arProgramacionPago->setVrNeto($floNetoTotal);
            $arProgramacionPago->setInconsistencias($boolInconsistencias);
            $em->flush();            
        }
        
        //Primas
        if($arProgramacionPago->getCodigoPagoTipoFk() == 2) {
            $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                . "WHERE c.codigoCentroCostoFk = " . $arProgramacionPago->getCodigoCentroCostoFk()
                . " AND c.fechaUltimoPagoPrimas < '" . $arProgramacionPago->getFechaHasta()->format('Y-m-d') . "' "                    
                . " AND c.fechaDesde <= '" . $arProgramacionPago->getFechaHasta()->format('Y-m-d') . "' "
                . " AND (c.fechaHasta >= '" . $arProgramacionPago->getFechaDesde()->format('Y-m-d') . "' "
                . " OR c.indefinido = 1) "
                . " AND c.estadoLiquidado = 0 AND c.codigoContratoTipoFk <> 4 AND c.codigoContratoTipoFk <> 5";
            $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $query = $em->createQuery($dql);
            $arContratos = $query->getResult();
            foreach ($arContratos as $arContrato) {
                $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                $arProgramacionPagoDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arProgramacionPagoDetalle->setContratoRel($arContrato);
                $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
                $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());
                $dateFechaDesde =  "";
                $dateFechaDesdePago =  "";
                if($arContrato->getFechaUltimoPagoPrimas() <=  $arProgramacionPago->getFechaDesde() == true) {
                    $dateFechaDesde = $arProgramacionPago->getFechaDesde();
                    $dateFechaDesdePago = $arProgramacionPago->getFechaDesde();
                    if($arContrato->getFechaDesde() >= $dateFechaDesde) {
                        $dateFechaDesde = $arContrato->getFechaDesde();
                        $dateFechaDesdePago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->fechaPrimerPago($arContrato->getCodigoContratoPk());
                        if($dateFechaDesdePago) {
                            $dateFechaDesdePago = date_create_from_format('Y-m-d H:i', $dateFechaDesdePago . "00:00");
                        } else {
                            $dateFechaDesdePago = $dateFechaDesde;                            
                        }
                    }
                    
                } else {
                    $dateFechaDesde = $arContrato->getFechaUltimoPagoPrimas();
                    $dateFechaDesdePago = $arContrato->getFechaUltimoPagoPrimas();
                    $dateFechaDesdePago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->fechaPrimerPago($arContrato->getCodigoContratoPk());
                    if($dateFechaDesdePago == "") {
                        $dateFechaDesdePago = $arContrato->getFechaUltimoPagoPrimas()->format('Y-m-d');
                        $dateFechaDesdePago = date_create_from_format('Y-m-d H:i', $dateFechaDesdePago . "00:00");                                            
                    } else {
                        $dateFechaDesdePago = date_create_from_format('Y-m-d H:i', $dateFechaDesdePago . "00:00");                                            
                    }
                    
                }
                $intDia = $dateFechaDesde->format('j');
                $intDiasMes = 31 - $intDia;                
                $intMes = $dateFechaDesde->format('n');                
                $intMesFinal = $arProgramacionPago->getFechaHasta()->format('n');
                $intMeses = $intMesFinal - $intMes;
                $intDias = ($intMeses * 30) + $intDiasMes;
                
                $arProgramacionPagoDetalle->setFechaDesde($dateFechaDesde);
                $arProgramacionPagoDetalle->setFechaHasta($arProgramacionPago->getFechaHasta());
                $arProgramacionPagoDetalle->setFechaDesdePago($dateFechaDesdePago);
                $arProgramacionPagoDetalle->setDias($intDias);
                $arProgramacionPagoDetalle->setDiasReales($intDias);
                $em->persist($arProgramacionPagoDetalle);
                $intNumeroEmpleados++;
                
            }
            $arProgramacionPago->setNumeroEmpleados($intNumeroEmpleados);            
            $em->flush();           
        }
        
        //Cesantias
        if($arProgramacionPago->getCodigoPagoTipoFk() == 3) {
            $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                . "WHERE c.codigoCentroCostoFk = " . $arProgramacionPago->getCodigoCentroCostoFk()
                . " AND c.fechaUltimoPagoCesantias < '" . $arProgramacionPago->getFechaHasta()->format('Y-m-d') . "' "                    
                . " AND c.fechaDesde <= '" . $arProgramacionPago->getFechaHasta()->format('Y-m-d') . "' "
                . " AND (c.fechaHasta >= '" . $arProgramacionPago->getFechaDesde()->format('Y-m-d') . "' "
                . " OR c.indefinido = 1) "
                . " AND c.estadoLiquidado = 0 AND c.codigoContratoTipoFk <> 4 AND c.codigoContratoTipoFk <> 5";            
            $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $query = $em->createQuery($dql);
            $arContratos = $query->getResult();
            foreach ($arContratos as $arContrato) {
                $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                $arProgramacionPagoDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arProgramacionPagoDetalle->setContratoRel($arContrato);
                $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
                $arProgramacionPagoDetalle->setIndefinido($arContrato->getIndefinido());
                $dateFechaDesde =  "";
                $dateFechaDesdePago =  "";
                if($arContrato->getFechaUltimoPagoCesantias() <=  $arProgramacionPago->getFechaDesde() == true) {
                    $dateFechaDesde = $arProgramacionPago->getFechaDesde();
                    $dateFechaDesdePago = $arProgramacionPago->getFechaDesde();
                    if($arContrato->getFechaDesde() >= $dateFechaDesde) {
                        $dateFechaDesde = $arContrato->getFechaDesde();
                        $dateFechaDesdePago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->fechaPrimerPago($arContrato->getCodigoContratoPk());
                        if($dateFechaDesdePago) {
                            $dateFechaDesdePago = date_create_from_format('Y-m-d H:i', $dateFechaDesdePago . "00:00");
                        } else {
                            $dateFechaDesdePago = $dateFechaDesde;                            
                        }
                    }
                    
                } else {
                    $dateFechaDesde = $arContrato->getFechaUltimoPagoCesantias();
                    $dateFechaDesdePago = $arContrato->getFechaUltimoPagoCesantias();
                }
                $intDia = $dateFechaDesde->format('j');
                $intDiasMes = 31 - $intDia;                
                $intMes = $dateFechaDesde->format('n');                
                $intMesFinal = $arProgramacionPago->getFechaHasta()->format('n');
                $intMeses = $intMesFinal - $intMes;
                $intDias = ($intMeses * 30) + $intDiasMes;
                
                $arProgramacionPagoDetalle->setFechaDesde($dateFechaDesde);
                $arProgramacionPagoDetalle->setFechaHasta($arProgramacionPago->getFechaHasta());
                $arProgramacionPagoDetalle->setFechaDesdePago($dateFechaDesdePago);
                $arProgramacionPagoDetalle->setDias($intDias);
                $arProgramacionPagoDetalle->setDiasReales($intDias);
                $em->persist($arProgramacionPagoDetalle);
                $intNumeroEmpleados++;
                
            }
            $arProgramacionPago->setNumeroEmpleados($intNumeroEmpleados);            
            $em->flush();           
        }        
        set_time_limit(0);
        return true;
    }
    
    //listado de programaciones de pago pagadas para generar archivo txt bancolombia
    public function listaDQLArchivoBanco($codigoCentroCosto, $intTipo = "", $boolMostrarGenerados, $strFechaDesde = "", $strFechaHasta = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pp FROM BrasaRecursoHumanoBundle:RhuProgramacionPago pp WHERE pp.estadoPagado = 1 ";
        
        if($codigoCentroCosto != "" && $codigoCentroCosto != 0) {
            $dql .= " AND pp.codigoCentroCostoFk =" . $codigoCentroCosto;
        }
        if($intTipo != "" && $intTipo != 0) {
            $dql .= " AND pp.codigoPagoTipoFk =" . $intTipo;
        }
        if($boolMostrarGenerados == 1 ) {
            $dql .= " AND pp.archivoExportadoBanco = 1";
        }
        if($boolMostrarGenerados == "0") {
            $dql .= " AND pp.archivoExportadoBanco = 0";
        }
        if($strFechaDesde != "" ) {
            $dql .= " AND pp.fechaHasta >='" . date_format($strFechaDesde, ('Y-m-d')). "'";
        }

        if($strFechaHasta != "") {
            $dql .= " AND pp.fechaHasta <='" . date_format($strFechaHasta, ('Y-m-d')). "'";
        }
        $dql .= " ORDER BY pp.codigoProgramacionPagoPk DESC";
        return $dql;
    }
    

}