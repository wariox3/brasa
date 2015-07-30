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
        //Devolver incapacidades
        //Devolver Licencias
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
                $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
                foreach ($arPagos as $arPago) {
                    $arPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                    $arPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($arPago->getCodigoPagoPk());                        
                    
                }
                
                /*$arPagosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                $arPagosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->pagosDetallesProgramacionPago($codigoProgramacionPago);
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
                }                
                 * 
                 */
                //$arProgramacionPagoProcesar->setEstadoPagado(1);
                $em->persist($arProgramacionPagoProcesar);
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
    public function listaDQL($strFechaDesde = "", $strFechaHasta = "", $codigoCentroCosto, $boolMostrarGenerados, $boolMostrarPagados) {
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
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c "
                . "WHERE c.codigoCentroCostoFk = " . $arProgramacionPago->getCodigoCentroCostoFk()
                . " AND c.fechaDesde <= '" . $arProgramacionPago->getFechaHasta()->format('Y-m-d') . "' "
                . " AND c.fechaHasta >= '" . $arProgramacionPago->getFechaDesde()->format('Y-m-d') . "' "
                . " AND c.indefinido = 1";
        $query = $em->createQuery($dql);
        $arContratos = $query->getResult();
        foreach ($arContratos as $arContrato) {
            $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
            $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
            $arProgramacionPagoDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
            $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
            $arProgramacionPagoDetalle->setFechaDesde($arContrato->getFechaDesde());
            $arProgramacionPagoDetalle->setFechaHasta($arContrato->getFechaHasta());
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
        return true;
    }


}