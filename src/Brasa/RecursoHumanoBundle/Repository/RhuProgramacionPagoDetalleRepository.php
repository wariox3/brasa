<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuProgramacionPagoDetalleRepository extends EntityRepository {
    
    public function listaDQL($codigoProgramacionPago = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle pd WHERE pd.codigoProgramacionPagoDetallePk <> 0" ;
        if($codigoProgramacionPago != "" ) {
            $dql .= " AND pd.codigoProgramacionPagoFk = " . $codigoProgramacionPago;
        }             

        return $dql;
    }      
    
    public function generarPago($arProgramacionPagoDetalle, $arProgramacionPagoProcesar, $arCentroCosto, $arConfiguracion) {                
        $em = $this->getEntityManager();
        $arContrato = $arProgramacionPagoDetalle->getContratoRel();
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago->setPagoTipoRel($arProgramacionPagoProcesar->getPagoTipoRel());                        
        $arPago->setEmpleadoRel($arProgramacionPagoDetalle->getEmpleadoRel());
        $arPago->setCentroCostoRel($arCentroCosto);
        $arPago->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
        $arPago->setFechaDesde($arProgramacionPagoProcesar->getFechaDesde());
        $arPago->setFechaHasta($arProgramacionPagoProcesar->getFechaHasta());
        $arPago->setFechaDesdePago($arProgramacionPagoDetalle->getFechaDesdePago());
        $arPago->setFechaHastaPago($arProgramacionPagoDetalle->getFechaHastaPago());
        $arPago->setVrSalarioEmpleado($arProgramacionPagoDetalle->getVrSalario());
        $arPago->setVrSalarioPeriodo($arProgramacionPagoDetalle->getVrDevengado());
        $arPago->setProgramacionPagoRel($arProgramacionPagoProcesar);
        $arPago->setContratoRel($arContrato);                        
        $arPago->setDiasPeriodo($arProgramacionPagoDetalle->getDias());
        $arPago->setCodigoUsuario($arProgramacionPagoProcesar->getCodigoUsuario());
        $arPago->setComentarios($arProgramacionPagoDetalle->getComentarios());

        //Parametros generales
        $intHorasLaboradas = $arProgramacionPagoDetalle->getHorasPeriodoReales();
        $horasDiurnas = $arProgramacionPagoDetalle->getHorasDiurnas();
        $intDiasTransporte = $arProgramacionPagoDetalle->getDiasReales(); 
        $intFactorDia = $arProgramacionPagoDetalle->getFactorDia();
        $douVrDia = $arProgramacionPagoDetalle->getVrDia();
        $douVrHora = $arProgramacionPagoDetalle->getVrHora();
        $douVrSalarioMinimo = $arConfiguracion->getVrSalario();
        $douVrHoraSalarioMinimo = ($douVrSalarioMinimo / 30) / 8;
        $douIngresoBasePrestacional = 0;
        $douIngresoBaseCotizacion = 0;                        
        //Procesar vacaciones
        $intDiasVacaciones = $arProgramacionPagoDetalle->getDiasVacaciones();
        $intHorasVacaciones = $intDiasVacaciones * $intFactorDia;
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
            $arPagoDetalle->setNumeroDias($intHorasVacaciones / $intFactorDia);
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
                $intHorasProcesarIncapacidad = $intDias * $intFactorDia;                                                                                                
                $intHorasLaboradas = $intHorasLaboradas - $intHorasProcesarIncapacidad;                                
                $douPagoDetalle = 0;
                $douIngresoBaseCotizacionIncapacidad = 0;
                $intDiasTransporte = $intDiasTransporte - ($intHorasProcesarIncapacidad / $intFactorDia);

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
                $intHorasProcesarLicencia = $intDias * $intFactorDia;

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
                $arPagoDetalle->setVrPago($douPagoDetalle);
                $arPagoDetalle->setOperacion($arLicencia->getLicenciaTipoRel()->getPagoConceptoRel()->getOperacion());
                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arLicencia->getLicenciaTipoRel()->getPagoConceptoRel()->getOperacion());                               
                $arPagoDetalle->setNumeroHoras($intHorasProcesarLicencia);
                $arPagoDetalle->setNumeroDias($intDias);
                $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);                                    
                $em->persist($arPagoDetalle);
                if($arLicencia->getAfectaTransporte() == 1){
                    $intDiasLicenciaProcesar = intval($intHorasProcesarLicencia / $intFactorDia);
                    $intDiasTransporte = $intDiasTransporte - $intDiasLicenciaProcesar;
                }
            }
        }

        //Esta condicion se debe quitar, se agrega para no permitir un descuento en vacaciones
        if($intHorasLaboradas > 0) {
            //Procesar los conceptos de pagos adicionales
            $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
            $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->programacionPago($arProgramacionPagoDetalle->getCodigoEmpleadoFk(), $arProgramacionPagoDetalle->getCodigoProgramacionPagoFk());
            foreach ($arPagosAdicionales as $arPagoAdicional) {
                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                $arPagoDetalle->setPagoRel($arPago);
                $arPagoDetalle->setPagoConceptoRel($arPagoAdicional->getPagoConceptoRel());
                $arPagoDetalle->setAdicional(1);
                $douPagoDetalle = 0;
                if($arPagoAdicional->getPagoConceptoRel()->getComponePorcentaje() == 1) {
                    $douVrHoraAdicional = ($douVrHora * $arPagoAdicional->getPagoConceptoRel()->getPorPorcentaje())/100;
                    $douPagoDetalle = $douVrHoraAdicional * $arPagoAdicional->getCantidad();
                    $arPagoDetalle->setPorcentajeAplicado($arPagoAdicional->getPagoConceptoRel()->getPorPorcentaje());
                    $arPagoDetalle->setVrHora($douVrHoraAdicional);
                    $arPagoDetalle->setVrDia($douVrDia);
                    $arPagoDetalle->setNumeroHoras($arPagoAdicional->getCantidad());
                }
                if($arPagoAdicional->getPagoConceptoRel()->getComponeValor() == 1) {
                    $douPagoDetalle = $arPagoAdicional->getValor();
                    if($arPagoAdicional->getAplicaDiaLaborado() == 1) {
                        $douPagoDetalle = $arPagoAdicional->getValor() * ($intHorasLaboradas / $intFactorDia);
                    }
                    $arPagoDetalle->setVrDia($douVrDia);
                }
                $arPagoDetalle->setDetalle($arPagoAdicional->getDetalle());
                $arPagoDetalle->setVrPago($douPagoDetalle);
                $arPagoDetalle->setOperacion($arPagoAdicional->getPagoConceptoRel()->getOperacion());
                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoAdicional->getPagoConceptoRel()->getOperacion());
                $arPagoDetalle->setDetalle($arPagoAdicional->getDetalle());
                $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);                            

                if($arPagoAdicional->getPagoConceptoRel()->getPrestacional() == 1) {
                    if($arPagoAdicional->getPagoConceptoRel()->getGeneraIngresoBaseCotizacion() == 1) {
                        $douIngresoBaseCotizacion += $douPagoDetalle;    
                        $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);
                        $arPagoDetalle->setCotizacion(1);
                    }
                    if($arPagoAdicional->getPagoConceptoRel()->getGeneraIngresoBasePrestacion() == 1) {
                        $douIngresoBasePrestacional += $douPagoDetalle;    
                        $arPagoDetalle->setVrIngresoBasePrestacion($douPagoDetalle);
                    }                                                                                                                                                                
                    $arPagoDetalle->setPrestacional(1);
                }

                $em->persist($arPagoDetalle);                            
            }  

            //Horas extra
            $arrHorasExtras = $this->horasExtra($arProgramacionPagoDetalle, $arConfiguracion);
            foreach($arrHorasExtras as $arrHorasExtra) {
                //$arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($arrHorasExtra['concepto']);                                
                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                $arPagoDetalle->setPagoRel($arPago);
                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                $arPagoDetalle->setAdicional(1);                                

                $douVrHoraAdicional = ($douVrHora * $arPagoConcepto->getPorPorcentaje())/100;
                $douPagoDetalle = $douVrHoraAdicional * $arrHorasExtra['horas'];
                $arPagoDetalle->setPorcentajeAplicado($arPagoConcepto->getPorPorcentaje());
                $arPagoDetalle->setVrHora($douVrHoraAdicional);
                $arPagoDetalle->setVrDia(0);
                $arPagoDetalle->setNumeroHoras($arrHorasExtra['horas']);

                $arPagoDetalle->setVrPago($douPagoDetalle);
                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());                                
                $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);                            

                if($arPagoConcepto->getPrestacional() == 1) {
                    if($arPagoConcepto->getGeneraIngresoBaseCotizacion() == 1) {
                        $douIngresoBaseCotizacion += $douPagoDetalle;    
                        $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);
                        $arPagoDetalle->setCotizacion(1);
                    }
                    if($arPagoConcepto->getGeneraIngresoBasePrestacion() == 1) {
                        $douIngresoBasePrestacional += $douPagoDetalle;    
                        $arPagoDetalle->setVrIngresoBasePrestacion($douPagoDetalle);
                    }                                                                                                                                                                
                    $arPagoDetalle->setPrestacional(1);
                }

                $em->persist($arPagoDetalle);                                
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

        //Liquidar salario
        $intPagoConceptoSalario = $arConfiguracion->getCodigoHoraDiurnaTrabajada();
        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoSalario);
        $douPagoDetalle = $horasDiurnas * $douVrHora;
        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalle->setPagoRel($arPago);
        $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
        $arPagoDetalle->setVrHora($douVrHora);
        $arPagoDetalle->setVrDia($douVrDia);
        $arPagoDetalle->setPorcentajeAplicado($arPagoConcepto->getPorPorcentaje());
        $arPagoDetalle->setNumeroHoras($horasDiurnas);
        $arPagoDetalle->setNumeroDias(0);
        $arPagoDetalle->setVrPago($douPagoDetalle);
        $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
        $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
        $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
        $em->persist($arPagoDetalle);
        //El ingreso base cotizacion del salacion integral es el 70% del salario                        
        if($arProgramacionPagoDetalle->getSalarioIntegral() == 1) {
            $douPagoDetalleCotizacion = ($douPagoDetalle * 70 / 100);    
        } else {
            $douPagoDetalleCotizacion = $douPagoDetalle;
        }                        
        $douIngresoBasePrestacional += $douPagoDetalle;
        $douIngresoBaseCotizacion += $douPagoDetalleCotizacion;                        
        $arPagoDetalle->setVrIngresoBasePrestacion($douPagoDetalle);
        $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalleCotizacion);

        //Liquidar salud
        if($arProgramacionPagoDetalle->getDescuentoSalud() == 1) {                            
            $intDias = $intHorasLaboradas / 8;
            $floPorcentaje = $arContrato->getTipoSaludRel()->getPorcentajeEmpleado();
            $intOperacion = -1;
            if($floPorcentaje > 0) {
                $douPagoDetalle = ($douIngresoBaseCotizacion * $floPorcentaje)/100;                                                                                            
                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                $arPagoDetalle->setPagoRel($arPago);
                $arPagoDetalle->setPagoConceptoRel($arContrato->getTipoSaludRel()->getPagoConceptoRel());
                $arPagoDetalle->setPorcentajeAplicado($floPorcentaje);
                $arPagoDetalle->setVrDia($douVrDia);
                $arPagoDetalle->setVrPago($douPagoDetalle);
                $arPagoDetalle->setOperacion($intOperacion);
                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $intOperacion);
                $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                $em->persist($arPagoDetalle);                                
            }                                                        
        }                        

        //Liquidar pension
        if($arProgramacionPagoDetalle->getDescuentoPension() == 1) {                            
            $douPorcentaje = $arContrato->getTipoPensionRel()->getPorcentajeEmpleado();
            $intOperacion = -1;
            if($douPorcentaje > 0) {
                $douPagoDetalle = ($douIngresoBaseCotizacion * $douPorcentaje)/100;                                
                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                $arPagoDetalle->setPagoRel($arPago);
                $arPagoDetalle->setPagoConceptoRel($arContrato->getTipoPensionRel()->getPagoConceptoRel());
                $arPagoDetalle->setPorcentajeAplicado($douPorcentaje);
                $arPagoDetalle->setVrDia($douVrDia);
                $arPagoDetalle->setVrPago($douPagoDetalle);
                $arPagoDetalle->setOperacion($intOperacion);
                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $intOperacion);
                $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                $em->persist($arPagoDetalle);   

                //Fondo de solidaridad pensional
                if($intHorasLaboradas > 0) {                                
                    $douValorHoraMinimo = ($douVrSalarioMinimo / 240) * 4;
                    if($douVrHora > $douValorHoraMinimo) {
                        $douPorcentaje = 1;
                        $douPagoDetalle = ($douIngresoBaseCotizacion * $douPorcentaje)/100;                                        
                        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                        $arPagoDetalle->setPagoRel($arPago);
                        $arPagoDetalle->setPagoConceptoRel($arContrato->getTipoPensionRel()->getPagoConceptoFondoRel());
                        $arPagoDetalle->setPorcentajeAplicado($douPorcentaje);
                        $arPagoDetalle->setVrDia($douVrDia);
                        $arPagoDetalle->setVrPago($douPagoDetalle);
                        $arPagoDetalle->setOperacion($intOperacion);
                        $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $intOperacion);
                        $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                        $em->persist($arPagoDetalle);                                         
                    }                                        
                }

            }                            
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

        //Retencion en la fuente
        /*if($douIngresoBaseCotizacion > 0){
            $intPagoConcepto = $arConfiguracion->getCodigoRetencionFuente();
            $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConcepto);                                                                                    
            $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
            $arPagoDetalle->setPagoRel($arPago);
            $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
            $arPagoDetalle->setVrHora(0);
            $arPagoDetalle->setVrDia(0);
            $arPagoDetalle->setPorcentajeAplicado($arPagoConcepto->getPorPorcentaje());
            $arPagoDetalle->setNumeroHoras();
            $arPagoDetalle->setNumeroDias(0);
            $arPagoDetalle->setVrPago($douPagoDetalle);
            $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
            $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
            $arPagoDetalle->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
            $em->persist($arPagoDetalle);
            $douIngresoBasePrestacional += $douPagoDetalle;                        
            $douIngresoBaseCotizacion += $douPagoDetalle;                        
            $arPagoDetalle->setVrIngresoBasePrestacion($douPagoDetalle);
            $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);                            
        }*/

        $intDiasLaborados = $intHorasLaboradas / $intFactorDia;                        
        $douAuxilioTransporteCotizacion = $arProgramacionPagoDetalle->getDiasReales() * ($arConfiguracion->getVrAuxilioTransporte() / 30);
        $arPago->setVrAuxilioTransporteCotizacion($douAuxilioTransporteCotizacion);
        $arPago->setDiasLaborados($intDiasLaborados);
        $em->persist($arPago);                                                             
        
        return true;
    }
    
    public function generarProgramacionPagoDetallePorSede($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
            $intHoras = 0;
            $arProgramacionPagoDetalleProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
            $arProgramacionPagoDetalleProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($arProgramacionPagoDetalle->getCodigoProgramacionPagoDetallePk());            
            $arProgramacionPagoDetallesSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
            $arProgramacionPagoDetallesSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->findBy(array('codigoProgramacionPagoDetalleFk' => $arProgramacionPagoDetalle->getCodigoProgramacionPagoDetallePk(), 'codigoEmpleadoFk' => $arProgramacionPagoDetalle->getCodigoEmpleadoFk()));                        
            foreach ($arProgramacionPagoDetallesSedes as $arProgramacionPagoDetalleSede) {
                $intHoras = $intHoras + $arProgramacionPagoDetalleSede->getHorasPeriodo();
            }
            foreach ($arProgramacionPagoDetallesSedes as $arProgramacionPagoDetalleSede) {
                $arProgramacionPagoDetalleSedeProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
                $arProgramacionPagoDetalleSedeProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->find($arProgramacionPagoDetalleSede->getCodigoProgramacionPagoDetalleSedePk());                                                        
                $arProgramacionPagoDetalleSedeProcesar->setPorcentajeParticipacion(($arProgramacionPagoDetalleSedeProcesar->getHorasPeriodo() / $intHoras) * 100);
                $em->persist($arProgramacionPagoDetalleSedeProcesar);
            }            
            $arProgramacionPagoDetalleProcesar->setHorasPeriodoReales($intHoras);
            $em->persist($arProgramacionPagoDetalleProcesar);
        }
        $em->flush();
    } 
    
    public function listaDQLDetalleArchivo($codigoProgramacionPago = "") {        
        $em = $this->getEntityManager();
        $strSql = "SELECT ppd,e FROM BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle ppd JOIN ppd.empleadoRel e WHERE
                  ppd.codigoProgramacionPagoFk = ".$codigoProgramacionPago."";
    
        //$strSql .= " ORDER BY e.nombreCorto";
        $query = $em->createQuery($strSql);
        $arRegistros = $query->getResult();
        return $arRegistros;        
    }
    
    public function fechaPrimerPago($codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT MIN(pp.fechaDesde) FROM BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle ppd JOIN ppd.programacionPagoRel pp "
                . "WHERE ppd.codigoContratoFk = " . $codigoContrato
                . " AND pp.estadoPagado = 1";                
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getSingleScalarResult();
        return $arrayResultado;
    }
    
    public function eliminarTodoEmpleados($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
        if ($arProgramacionPagoDetalle <> null){
            $strSql = "DELETE FROM rhu_programacion_pago_detalle WHERE codigo_programacion_pago_fk = " . $codigoProgramacionPago;
            $em->getConnection()->executeQuery($strSql);
            //$em->persist($arProgramacionPagoDetalle);
            //$em->flush();
            return true;
        }    
    }
    
    private function horasExtra($arProgramacionPagoDetalle, $arConfiguracion) {
        //$arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        //$arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arrExtra = array();
        if($arProgramacionPagoDetalle->getHorasDescanso() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraDescanso(), 'horas' => $arProgramacionPagoDetalle->getHorasDescanso());
        }        
        if($arProgramacionPagoDetalle->getHorasNocturnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraNocturna(), 'horas' => $arProgramacionPagoDetalle->getHorasNocturnas());
        }
        if($arProgramacionPagoDetalle->getHorasFestivasDiurnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraFestivaDiurna(), 'horas' => $arProgramacionPagoDetalle->getHorasFestivasDiurnas());
        }
        if($arProgramacionPagoDetalle->getHorasFestivasNocturnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraFestivaNocturna(), 'horas' => $arProgramacionPagoDetalle->getHorasFestivasNocturnas());
        }        
        if($arProgramacionPagoDetalle->getHorasExtrasOrdinariasDiurnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraExtraOrdinariaDiurna(), 'horas' => $arProgramacionPagoDetalle->getHorasExtrasOrdinariasDiurnas());
        }
        if($arProgramacionPagoDetalle->getHorasExtrasOrdinariasNocturnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraExtraOrdinariaNocturna(), 'horas' => $arProgramacionPagoDetalle->getHorasExtrasOrdinariasNocturnas());
        }        
        if($arProgramacionPagoDetalle->getHorasExtrasFestivasDiurnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraExtraFestivaDiurna(), 'horas' => $arProgramacionPagoDetalle->getHorasExtrasFestivasDiurnas());
        }
        if($arProgramacionPagoDetalle->getHorasExtrasFestivasNocturnas() > 0) {
            $arrExtra[] = array('concepto' => $arConfiguracion->getCodigoHoraExtraFestivaNocturna(), 'horas' => $arProgramacionPagoDetalle->getHorasExtrasFestivasNocturnas());
        }        
        return $arrExtra;
    }    
    
}