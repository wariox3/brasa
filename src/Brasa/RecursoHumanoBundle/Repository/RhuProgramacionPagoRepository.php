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
                //Devolver incapacidades
                if($arPagoDetalle->getCodigoIncapacidadFk() != "") {
                    $arIncapacidadActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad;
                    $arIncapacidadActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($arPagoDetalle->getCodigoIncapacidadFk());
                    $arIncapacidadActualizar->setCantidadPendiente($arIncapacidadActualizar->getCantidadPendiente() + ($arPagoDetalle->getNumeroHoras() / 8));
                    $em->persist($arIncapacidadActualizar);                                                            
                }
                //Devolver licencias
                if($arPagoDetalle->getCodigoLicenciaFk() != "") {
                    $arLicenciaActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia;
                    $arLicenciaActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->find($arPagoDetalle->getCodigoLicenciaFk());
                    $arLicenciaActualizar->setCantidadPendiente($arLicenciaActualizar->getCantidadPendiente() + ($arPagoDetalle->getNumeroHoras() / 8));
                    $em->persist($arLicenciaActualizar);                                                            
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
        return true;
    }

    /**
     * Paga las programaciones seleccionadas
     *
     * @author		Mario Estrada
     *
     * @param array	Codigo de las programaciones
     */
    public function pagarSeleccionados($arrSeleccionados) {
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigoProgramacionPago) {
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
                        }
                        $douSalarioPeriodo = $arPagoProcesar->getVrSalarioPeriodo();
                        $douSalarioSeguridadSocial = $douSalarioPeriodo + $douAdicionTiempo + $douAdicionValor;
                        $douDiaAuxilioTransporte = 74000 / 30;
                        $douAuxilioTransporteCotizacion = $arPagoProcesar->getDiasPeriodo() * $douDiaAuxilioTransporte;
                        $douArp = ($douSalarioSeguridadSocial * $arPagoProcesar->getEmpleadoRel()->getClasificacionRiesgoRel()->getPorcentaje())/100;        
                        $douPension = ($douSalarioSeguridadSocial * $arPagoProcesar->getEmpleadoRel()->getTipoPensionRel()->getPorcentajeCotizacion()) / 100; 
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
                    }
                    
                    $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                    $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
                    foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
                        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arProgramacionPagoDetalle->getCodigoContratoFk());                                                                        
                        if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 1) {                            
                            $arContrato->setFechaUltimoPago($arProgramacionPagoProcesar->getFechaHasta());
                        }
                        if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 2) {
                            $arContrato->setFechaUltimoPagoPrimas($arProgramacionPagoProcesar->getFechaHasta());
                        }
                        if($arProgramacionPagoProcesar->getCodigoPagoTipoFk() == 3) {
                            $arContrato->setFechaUltimoPagoCesantias($arProgramacionPagoProcesar->getFechaHasta());
                        }   
                        $em->persist($arContrato);                        
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
                }
            }
            $em->flush();
        }
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
        $intNumeroEmpleados = 0;
        $floNetoTotal = 0;
        $boolInconsistencias = 0;
        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoInconsistencia')->eliminarProgramacionPago($codigoProgramacionPago);
        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->eliminarEmpleados($codigoProgramacionPago);
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                . "WHERE c.codigoCentroCostoFk = " . $arProgramacionPago->getCodigoCentroCostoFk()
                . " AND c.fechaDesde <= '" . $arProgramacionPago->getFechaHasta()->format('Y-m-d') . "' "
                . " AND (c.fechaHasta >= '" . $arProgramacionPago->getFechaDesde()->format('Y-m-d') . "' "
                . " OR c.indefinido = 1)";        
        if($arProgramacionPago->getCodigoPagoTipoFk() == 1) {
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
                $dateFechaHasta =  "";
                $intDiasDevolver = 0;
                $fechaFinalizaContrato = $arContrato->getFechaHasta();
                if($arContrato->getIndefinido() == 1) {
                    $fecha = date_create(date('Y-m-d'));
                    date_modify($fecha, '+365 day');
                    $fechaFinalizaContrato = $fecha;
                }
                if($arContrato->getFechaDesde() <  $arProgramacionPago->getFechaDesde() == true) {
                    $dateFechaDesde = $arProgramacionPago->getFechaDesde();
                } else {
                    if($arContrato->getFechaDesde() > $arProgramacionPago->getFechaHasta() == true) {
                        $intDiasDevolver = 0;
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
                    }
                    $intDiasDevolver += $intDiasInhabilesFebrero;
                }
                $arProgramacionPagoDetalle->setFechaDesde($dateFechaDesde);
                $arProgramacionPagoDetalle->setFechaHasta($dateFechaHasta);

                $arProgramacionPagoDetalle->setDias($intDiasDevolver);
                $arProgramacionPagoDetalle->setDiasReales($intDiasDevolver);
                if($arContrato->getCodigoTipoTiempoFk() == 2) {
                    $arProgramacionPagoDetalle->setHorasPeriodo($intDiasDevolver * 4);
                    $arProgramacionPagoDetalle->setHorasPeriodoReales($intDiasDevolver * 4);
                    $arProgramacionPagoDetalle->setFactorDia(4);
                } else {
                    $arProgramacionPagoDetalle->setHorasPeriodo($intDiasDevolver * 8);
                    $arProgramacionPagoDetalle->setHorasPeriodoReales($intDiasDevolver * 8);
                    $arProgramacionPagoDetalle->setFactorDia(8);
                }
                $floValorDia = $arProgramacionPagoDetalle->getVrSalario() / 30;
                $floDevengado = $arProgramacionPagoDetalle->getDias() * $floValorDia;
                $arProgramacionPagoDetalle->setVrDevengado($floDevengado);
                $floCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->cuotaCreditosNomina($arContrato->getCodigoEmpleadoFk());
                $arProgramacionPagoDetalle->setVrCreditos($floCreditos);
                $floDeducciones = $floCreditos;
                $arProgramacionPagoDetalle->setVrDeducciones($floDeducciones);
                $floNeto = $floDevengado - $floDeducciones;
                $arProgramacionPagoDetalle->setVrNetoPagar($floNeto);
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
            $arProgramacionPago->setVrNeto($floNetoTotal);
            $arProgramacionPago->setInconsistencias($boolInconsistencias);
            $em->flush();            
        }
        if($arProgramacionPago->getCodigoPagoTipoFk() == 2) {
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
        if($arProgramacionPago->getCodigoPagoTipoFk() == 3) {
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
        return true;
    }
    
    //listado de programaciones de pago pagadas para generar archivo txt bancolombia
    public function listaDQLArchivo($codigoCentroCosto) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pp FROM BrasaRecursoHumanoBundle:RhuProgramacionPago pp WHERE pp.estadoPagado = 1 ";
        
        if($codigoCentroCosto != "" && $codigoCentroCosto != 0) {
            $dql .= " AND pp.codigoCentroCostoFk =" . $codigoCentroCosto;
        }
        
        $dql .= " ORDER BY pp.codigoProgramacionPagoPk DESC";
        return $dql;
    }
    
    //total registros de la programacion de pago a exportar a txt
    public function totalResgistrosProgramacionPago($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(c.codigoProgramacionPagoDetallePk) FROM BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle c WHERE c.codigoProgramacionPagoFk = " . $codigoProgramacionPago ."AND c.vrNetoPagar > 0";
        $query = $em->createQuery($dql);
        $intRegistros = $query->getSingleScalarResult();
        return $intRegistros;
    }

}