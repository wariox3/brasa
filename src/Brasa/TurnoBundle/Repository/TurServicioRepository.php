<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioRepository extends EntityRepository {
    
    public function listaDql($codigoServicio = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoCerrado = "") {
        $dql   = "SELECT s FROM BrasaTurnoBundle:TurServicio s WHERE s.codigoServicioPk <> 0 ";
        if($codigoCliente != "") {
            $dql .= " AND s.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND s.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND s.estadoAutorizado = 0";
        }      
        
        if($boolEstadoCerrado == 1 ) {
            $dql .= " AND s.estadoCerrado = 1";
        }
        if($boolEstadoCerrado == "0") {
            $dql .= " AND s.estadoCerrado = 0";
        }         
        return $dql;
    }
    
    public function pedidoMaestroDql() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurServicio p WHERE p.codigoServicioTipoFk = 2";
        return $dql;
    }    
    
    public function pedidoSinProgramarDql($strFechaDesde = '', $strFechaHasta = '') {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurServicio p WHERE p.codigoServicioTipoFk = 1 "
                . "AND p.estadoProgramado = 0 AND p.estadoAutorizado = 1";

        if($strFechaDesde != '') {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . "'";  
        }
        if($strFechaHasta != '') {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . "'";  
        }        
        return $dql;
    }        
    
    public function liquidar($codigoServicio) {        
        $em = $this->getEntityManager();        
        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();        
        $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio); 
        $intCantidad = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $douTotalServicio = 0;
        $douTotalMinimoServicio = 0;
        $douTotalCostoCalculado = 0;
        $arServiciosDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();        
        $arServiciosDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigoServicio));         
        foreach ($arServiciosDetalle as $arServicioDetalle) {
            if($arServicioDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 2) {
                $intDias = $arServicioDetalle->getDiaHasta() - $arServicioDetalle->getDiaDesde();
                $intDias += 1;
            } else {
                $intDias = 30;
            }

            $intHorasRealesDiurnas = 0;
            $intHorasRealesNocturnas = 0;            
            $intDiasOrdinarios = 0;
            $intDiasSabados = 0;
            $intDiasDominicales = 0;
            $intDiasFestivos = 0;
            if($arServicioDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 1) {                
                if($arServicioDetalle->getLunes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arServicioDetalle->getMartes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arServicioDetalle->getMiercoles() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arServicioDetalle->getJueves() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arServicioDetalle->getViernes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arServicioDetalle->getSabado() == 1) {
                    $intDiasSabados = 4;    
                }
                if($arServicioDetalle->getDomingo() == 1) {
                    $intDiasDominicales = 4;    
                }                
                if($arServicioDetalle->getFestivo() == 1) {
                    $intDiasFestivos = 2;    
                }                               
                $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                $intHorasRealesDiurnas = $arServicioDetalle->getConceptoServicioRel()->getHorasDiurnas() * $intTotalDias;
                $intHorasRealesNocturnas = $arServicioDetalle->getConceptoServicioRel()->getHorasNocturnas() * $intTotalDias;                            
            } else {
                $intDiaInicial = $arServicioDetalle->getDiaDesde();
                $intDiaFinal = $arServicioDetalle->getDiaHasta();
                for($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {
                    $intHorasRealesDiurnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasDiurnas();
                    $intHorasRealesNocturnas +=  $arServicioDetalle->getConceptoServicioRel()->getHorasNocturnas();                                                                                              
                }                                                                                                 
            }                                    
            $douCostoCalculado = $arServicioDetalle->getCantidad() * $arServicioDetalle->getConceptoServicioRel()->getVrCosto();
            $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas ) * $arServicioDetalle->getCantidad();            
            $arServicioDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();        
            $arServicioDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($arServicioDetalle->getCodigoServicioDetallePk());                         
            $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1); 
            $floValorBaseServicio = $arConfiguracionNomina->getVrSalario() * $arServicio->getSectorRel()->getPorcentaje();
            $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $arServicioDetalle->getModalidadServicioRel()->getPorcentaje() / 100);                        
            $floVrHoraDiurna = ((($floValorBaseServicioMes * 59.7) / 100)/30)/16;            
            $floVrHoraNocturna = ((($floValorBaseServicioMes * 40.3) / 100)/30)/8;                                  
            $floVrMinimoServicio = (($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna)) * $arServicioDetalle->getCantidad();                        
            $floVrServicio = 0;            
            if($arServicioDetalleActualizar->getVrPrecioAjustado() != 0) {
                $floVrServicio = $arServicioDetalleActualizar->getVrPrecioAjustado();
            } else {
                $floVrServicio = $floVrMinimoServicio;
            }            
            $arServicioDetalleActualizar->setVrTotalDetalle($floVrServicio);
            $arServicioDetalleActualizar->setVrPrecioMinimo($floVrMinimoServicio);
            $arServicioDetalleActualizar->setVrCosto($douCostoCalculado);
            
            $arServicioDetalleActualizar->setHoras($douHoras);
            $arServicioDetalleActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arServicioDetalleActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arServicioDetalleActualizar->setDias($intDias);
            
            $em->persist($arServicioDetalleActualizar);            
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalMinimoServicio += $floVrMinimoServicio;
            $douTotalCostoCalculado += $douCostoCalculado;
            $douTotalServicio += $floVrServicio;
            $intCantidad++;
        }
        $arServicio->setHoras($douTotalHoras);
        $arServicio->setHorasDiurnas($douTotalHorasDiurnas);
        $arServicio->setHorasNocturnas($douTotalHorasNocturnas);
        $arServicio->setVrTotal($douTotalServicio);
        $arServicio->setVrTotalPrecioMinimo($douTotalMinimoServicio);
        $arServicio->setVrTotalCosto($douTotalCostoCalculado);
        $em->persist($arServicio);
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
                $ar = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }     
}