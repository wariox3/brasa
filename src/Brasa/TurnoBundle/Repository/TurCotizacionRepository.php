<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCotizacionRepository extends EntityRepository {
    
    public function listaDQL($codigoCotizacion) {
        $dql   = "SELECT c FROM BrasaTurnoBundle:TurCotizacion c WHERE c.codigoCotizacionPk <> 0";
        if($codigoCotizacion != "") {
            $dql = $dql . " AND c.codigoCotizacionPk = " . $codigoCotizacion;
        }
        return $dql;
    }
    
    public function liquidar($codigoCotizacion) {        
        $em = $this->getEntityManager();        
        $arCotizacion = new \Brasa\TurnoBundle\Entity\TurCotizacion();        
        $arCotizacion = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->find($codigoCotizacion); 
        $intCantidad = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $douTotalServicio = 0;
        $arCotizacionesDetalle = new \Brasa\TurnoBundle\Entity\TurCotizacionDetalle();        
        $arCotizacionesDetalle = $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->findBy(array('codigoCotizacionFk' => $codigoCotizacion));         
        foreach ($arCotizacionesDetalle as $arCotizacionDetalle) {
            $intDias = $arCotizacionDetalle->getFechaDesde()->diff($arCotizacionDetalle->getFechaHasta());
            $intDias = $intDias->format('%a');                           
            $intDias += 1; 
            $intHorasRealesDiurnas = 0;
            $intHorasRealesNocturnas = 0;            
            $intDiasOrdinarios = 0;
            $intDiasSabados = 0;
            $intDiasDominicales = 0;
            $intDiasFestivos = 0;
            if($arCotizacionDetalle->getCodigoPeriodoFk() == 1) {                
                if($arCotizacionDetalle->getLunes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arCotizacionDetalle->getMartes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arCotizacionDetalle->getMiercoles() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arCotizacionDetalle->getJueves() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arCotizacionDetalle->getViernes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arCotizacionDetalle->getSabado() == 1) {
                    $intDiasSabados = 4;    
                }
                if($arCotizacionDetalle->getDomingo() == 1) {
                    $intDiasDominicales = 4;    
                }                
                if($arCotizacionDetalle->getFestivo() == 1) {
                    $intDiasFestivos = 2;    
                }                               
                $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                $intHorasRealesDiurnas = $arCotizacionDetalle->getTurnoRel()->getHorasDiurnas() * $intTotalDias;
                $intHorasRealesNocturnas = $arCotizacionDetalle->getTurnoRel()->getHorasNocturnas() * $intTotalDias;                            
            } else {
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arCotizacionDetalle->getFechaDesde()->format('Y-m-d'), $arCotizacionDetalle->getFechaHasta()->format('Y-m-d'));
                $fecha = $arCotizacionDetalle->getFechaDesde()->format('Y-m-j');
                for($i = 0; $i < $intDias; $i++) {
                    $nuevafecha = strtotime ( '+'.$i.' day' , strtotime ( $fecha ) ) ;
                    $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
                    $dateNuevaFecha = date_create($nuevafecha);
                    $diaSemana = $dateNuevaFecha->format('N');
                    if($this->festivo($arFestivos, $dateNuevaFecha) == 1) {
                        $intDiasFestivos += 1;
                    } else {
                        if($diaSemana == 1) {
                            $intDiasOrdinarios += 1; 
                            if($arCotizacionDetalle->getLunes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }
                        } 
                        if($diaSemana == 2) {
                            $intDiasOrdinarios += 1; 
                            if($arCotizacionDetalle->getMartes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 3) {
                            $intDiasOrdinarios += 1; 
                            if($arCotizacionDetalle->getMiercoles() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }
                        }    
                        if($diaSemana == 4) {
                            $intDiasOrdinarios += 1; 
                            if($arCotizacionDetalle->getJueves() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 5) {
                            $intDiasOrdinarios += 1; 
                            if($arCotizacionDetalle->getViernes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 6) {
                           $intDiasSabados += 1; 
                            if($arCotizacionDetalle->getSabado() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }                   
                        }
                        if($diaSemana == 7) {
                           $intDiasDominicales += 1; 
                            if($arCotizacionDetalle->getDomingo() == 1) {                   
                                $intHorasRealesDiurnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arCotizacionDetalle->getTurnoRel()->getHorasNocturnas();                        
                            }                   
                        }                    
                    }                                
                }                
            }
                                    
            
            $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas ) * $arCotizacionDetalle->getCantidad();            
            $arCotizacionDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurCotizacionDetalle();        
            $arCotizacionDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->find($arCotizacionDetalle->getCodigoCotizacionDetallePk());                         
            $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1); 
            $floValorBaseServicio = $arConfiguracionNomina->getVrSalario() * $arCotizacion->getSectorRel()->getPorcentaje();
            $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $arCotizacionDetalle->getModalidadServicioRel()->getPorcentaje() / 100);                        
            $floVrHoraDiurna = ((($floValorBaseServicioMes * 59.7) / 100)/30)/16;            
            $floVrHoraNocturna = ((($floValorBaseServicioMes * 40.3) / 100)/30)/8;                                  
            $floVrServicio = (($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna)) * $arCotizacionDetalle->getCantidad();                        
            $arCotizacionDetalleActualizar->setVrTotal($floVrServicio);
            $arCotizacionDetalleActualizar->setHoras($douHoras);
            $arCotizacionDetalleActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arCotizacionDetalleActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arCotizacionDetalleActualizar->setDias($intDias);
            
            $em->persist($arCotizacionDetalleActualizar);            
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalServicio += $floVrServicio;
            $intCantidad++;
        }
        $arCotizacion->setHoras($douTotalHoras);
        $arCotizacion->setHorasDiurnas($douTotalHorasDiurnas);
        $arCotizacion->setHorasNocturnas($douTotalHorasNocturnas);
        $arCotizacion->setVrTotal($douTotalServicio);
        $em->persist($arCotizacion);
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
                $arCotizacion = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->find($codigo);
                $em->remove($arCotizacion);
            }
            $em->flush();
        }
    }    
}