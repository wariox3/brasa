<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoRepository extends EntityRepository {
    
    public function listaDql($numeroPedido = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoProgramado = "", $boolEstadoFacturado = "", $boolEstadoAnulado = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoPk <> 0";
        if($numeroPedido != "") {
            $dql .= " AND p.numero = " . $numeroPedido;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND p.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND p.estadoAutorizado = 0";
        }        
        if($boolEstadoProgramado == 1 ) {
            $dql .= " AND p.estadoProgramado = 1";
        }
        if($boolEstadoProgramado == "0") {
            $dql .= " AND p.estadoProgramado = 0";
        }    
        if($boolEstadoFacturado == 1 ) {
            $dql .= " AND p.estadoFacturado = 1";
        }
        if($boolEstadoFacturado == "0") {
            $dql .= " AND p.estadoFacturado = 0";
        }
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND p.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND p.estadoAnulado = 0";
        }        
        if($strFechaDesde != "") {
            $dql .= " AND p.fechaProgramacion >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND p.fechaProgramacion <= '" . $strFechaHasta . "'";
        }        
        $dql .= " ORDER BY p.fecha DESC";
        return $dql;
    }
    
    public function pedidoMaestroDql() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoTipoFk = 2";
        return $dql;
    }    
    
    public function pedidoSinProgramarDql($strFechaDesde = '', $strFechaHasta = '') {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.estadoProgramado = 0 AND p.estadoAutorizado = 1 AND p.estadoAnulado = 0 ";

        if($strFechaDesde != '') {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . "'";  
        }
        if($strFechaHasta != '') {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . "'";  
        }        
        return $dql;
    }        
    
    public function liquidar($codigoPedido) {        
        $em = $this->getEntityManager();        
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();        
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido); 
        $intCantidad = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $douTotalServicio = 0;
        $douTotalMinimoServicio = 0;
        $douTotalCostoCalculado = 0;        
        $arPedidosDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));         
        foreach ($arPedidosDetalle as $arPedidoDetalle) {
            if($arPedidoDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 2) {
                $intDias = $arPedidoDetalle->getDiaHasta() - $arPedidoDetalle->getDiaDesde();
                $intDias += 1;
                if($arPedidoDetalle->getDiaHasta() == 0 || $arPedidoDetalle->getDiaDesde() == 0) {
                    $intDias = 0;
                }
            } else {
                $intDias = 30;
            }

            $intHorasRealesDiurnas = 0;
            $intHorasRealesNocturnas = 0;            
            $intDiasOrdinarios = 0;
            $intDiasSabados = 0;
            $intDiasDominicales = 0;
            $intDiasFestivos = 0;
            if($arPedidoDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 1) {                
                if($arPedidoDetalle->getLunes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arPedidoDetalle->getMartes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arPedidoDetalle->getMiercoles() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arPedidoDetalle->getJueves() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arPedidoDetalle->getViernes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arPedidoDetalle->getSabado() == 1) {
                    $intDiasSabados = 4;    
                }
                if($arPedidoDetalle->getDomingo() == 1) {
                    $intDiasDominicales = 4;    
                }                
                if($arPedidoDetalle->getFestivo() == 1) {
                    $intDiasFestivos = 2;    
                }                               
                $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                $intHorasRealesDiurnas = $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas() * $intTotalDias;
                $intHorasRealesNocturnas = $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas() * $intTotalDias;                            
            } else {
                $strFechaDesde = $arPedido->getFechaProgramacion()->format('Y-m') ."-". $arPedidoDetalle->getDiaDesde();
                $strFechaHasta = $arPedido->getFechaProgramacion()->format('Y-m') ."-". $arPedidoDetalle->getDiaHasta();
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($strFechaDesde, $strFechaHasta);
                $fecha = $strFechaDesde;
                for($i = 1; $i <= $intDias; $i++) {
                    $nuevafecha = strtotime ( '+'.$i.' day' , strtotime ( $fecha ) ) ;
                    $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
                    $dateNuevaFecha = date_create($nuevafecha);
                    $diaSemana = $dateNuevaFecha->format('N');
                    if($this->festivo($arFestivos, $dateNuevaFecha) == 1) {
                        $intDiasFestivos += 1;
                    } else {
                        if($diaSemana == 1) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getLunes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        } 
                        if($diaSemana == 2) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getMartes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 3) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getMiercoles() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }    
                        if($diaSemana == 4) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getJueves() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 5) {
                            $intDiasOrdinarios += 1; 
                            if($arPedidoDetalle->getViernes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 6) {
                           $intDiasSabados += 1; 
                            if($arPedidoDetalle->getSabado() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }                   
                        }
                        if($diaSemana == 7) {
                           $intDiasDominicales += 1; 
                            if($arPedidoDetalle->getDomingo() == 1) {                   
                                $intHorasRealesDiurnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arPedidoDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }                   
                        }                    
                    }                                
                }                
            }
                                    
            $douCostoCalculado = $arPedidoDetalle->getCantidad() * $arPedidoDetalle->getConceptoServicioRel()->getVrCosto();
            $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas ) * $arPedidoDetalle->getCantidad();            
            $arPedidoDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
            $arPedidoDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDetalle->getCodigoPedidoDetallePk());                         
            $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1); 
            $floValorBaseServicio = $arConfiguracionNomina->getVrSalario() * $arPedido->getSectorRel()->getPorcentaje();
            $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $arPedidoDetalle->getModalidadServicioRel()->getPorcentaje() / 100);                        
            $floVrHoraDiurna = ((($floValorBaseServicioMes * 59.7) / 100)/30)/16;            
            $floVrHoraNocturna = ((($floValorBaseServicioMes * 40.3) / 100)/30)/8;                                  
            $floVrMinimoServicio = (($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna)) * $arPedidoDetalle->getCantidad();                        
            $floVrServicio = 0;            
            if($arPedidoDetalleActualizar->getVrPrecioAjustado() != 0) {
                $floVrServicio = $arPedidoDetalleActualizar->getVrPrecioAjustado();
            } else {
                $floVrServicio = $floVrMinimoServicio;
            }            
            $arPedidoDetalleActualizar->setVrTotalDetalle($floVrServicio);
            $arPedidoDetalleActualizar->setVrTotalDetallePendiente($floVrServicio);
            $arPedidoDetalleActualizar->setVrPrecioMinimo($floVrMinimoServicio);
            $arPedidoDetalleActualizar->setVrCosto($douCostoCalculado);
            
            $arPedidoDetalleActualizar->setHoras($douHoras);
            $arPedidoDetalleActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arPedidoDetalleActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arPedidoDetalleActualizar->setDias($intDias);
            
            $em->persist($arPedidoDetalleActualizar);            
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalMinimoServicio += $floVrMinimoServicio;
            $douTotalCostoCalculado += $douCostoCalculado;
            $douTotalServicio += $floVrServicio;
            $intCantidad++;
        }
        $arPedido->setHoras($douTotalHoras);
        $arPedido->setHorasDiurnas($douTotalHorasDiurnas);
        $arPedido->setHorasNocturnas($douTotalHorasNocturnas);
        $arPedido->setVrTotal($douTotalServicio);
        $arPedido->setVrTotalPrecioMinimo($douTotalMinimoServicio);
        $arPedido->setVrTotalCosto($douTotalCostoCalculado);
        $em->persist($arPedido);
        $em->flush();
        return true;
    }
    
    public function festivo($arFestivos, $dateFecha) {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }    

    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {                
                $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigo);
                if($arPedido->getNumero() == 0) {
                    $boolEliminar = TRUE;
                    $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigo));                
                    foreach ($arPedidoDetalles as $arPedidoDetalle) {
                        $arProgramacionesDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                        $arProgramacionesDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoPedidoDetalleFk' => $arPedidoDetalle->getCodigoPedidoDetallePk()));                    
                        if(count($arProgramacionesDetalles) > 0) {
                            $boolEliminar = FALSE;
                        }
                    }
                    if($boolEliminar == TRUE) {                    
                        $em->remove($arPedido);                      
                    }                    
                }
            }
            $em->flush();
        }
    }     
    
    public function autorizar($codigoPedido) {
        $em = $this->getEntityManager();                
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);            
        $strResultado = "";        
        if($arPedido->getEstadoAutorizado() == 0) {
            if($em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->numeroRegistros($codigoPedido) > 0) {
                $intSinPuesto = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->validarPuesto($codigoPedido);
                if($intSinPuesto <= 0) {
                    $arPedido->setEstadoAutorizado(1);
                    if($arPedido->getNumero() == 0) {
                        $intNumero = $em->getRepository('BrasaTurnoBundle:TurConsecutivo')->consecutivo(1);
                        $arPedido->setNumero($intNumero);
                    }
                    $em->persist($arPedido);
                    $em->flush();                    
                } else {
                    $strResultado = $intSinPuesto . " servicios no tienen puesto asignado";
                }                        
            } else {
                $strResultado = "Debe adicionar detalles al pedido";
            }            
        } else {
            $strResultado = "El pedido ya esta autorizado";
        }        
        return $strResultado;
    }
    
    public function anular($codigoPedido) {
        $em = $this->getEntityManager();                
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);            
        $strResultado = "";        
        if($arPedido->getEstadoAutorizado() == 1 && $arPedido->getEstadoAnulado() == 0) {
            $boolAnular = TRUE;
            $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
            $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));                
            foreach ($arPedidoDetalles as $arPedidoDetalle) {
                $arProgramacionesDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                $arProgramacionesDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoPedidoDetalleFk' => $arPedidoDetalle->getCodigoPedidoDetallePk()));                    
                if(count($arProgramacionesDetalles) > 0) {
                    $boolAnular = FALSE;
                }
            }
            if($boolAnular == TRUE) {
                foreach ($arPedidoDetalles as $arPedidoDetalle) {
                    $arPedidoDetalleAct = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalleAct = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDetalle->getCodigoPedidoDetallePk());                                        
                    $arPedidoDetalleAct->setVrCosto(0);
                    $arPedidoDetalleAct->setVrPrecioAjustado(0);
                    $arPedidoDetalleAct->setVrPrecioMinimo(0);
                    $arPedidoDetalleAct->setVrTotalDetalle(0);
                    $arPedidoDetalleAct->setHoras(0);
                    $arPedidoDetalleAct->setHorasDiurnas(0);
                    $arPedidoDetalleAct->setHorasNocturnas(0);                    
                    $arPedidoDetalleAct->setDias(0);                    
                    $em->persist($arPedidoDetalleAct);
                }
                $arPedido->setEstadoAnulado(1);
                $arPedido->setVrTotalCosto(0);
                $arPedido->setVrTotalPrecioAjustado(0);
                $arPedido->setVrTotalPrecioMinimo(0);
                $arPedido->setVrTotal(0);
                $arPedido->setHoras(0);
                $arPedido->setHorasDiurnas(0);
                $arPedido->setHorasNocturnas(0);
                $em->persist($arPedido);
                $em->flush();      
            } else {
                $strResultado = "Hay programaciones que dependen de este pedido, por lo tanto no se puede anular";
            }                            
        } else {
            $strResultado = "El pedido debe estar autorizado y no puede estar previamente anulado";
        }        
        return $strResultado;
    }    
}