<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurNovedadRepository extends EntityRepository {
    
    public function listaDql($numero, $codigoCliente = "", $boolEstadoAutorizado = "") {
        $dql   = "SELECT c FROM BrasaTurnoBundle:TurNovedad c WHERE c.codigoNovedadPk <> 0";
        if($numero != "") {
            $dql .= " AND c.numero = " . $numero;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND c.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND c.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND c.estadoAutorizado = 0";
        }        
        $dql .= " ORDER BY c.fecha DESC";
        return $dql;
    }
    
    public function pendientes($codigoCliente = "", $codigoProspecto = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaTurnoBundle:TurNovedad c "
                . "WHERE c.codigoClienteFk = " . $codigoCliente;
        if($codigoProspecto != "") {
            $dql .= " OR c.codigoProspectoFk = " . $codigoProspecto;
        }
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    
    
    public function liquidar($codigoNovedad) {        
        $em = $this->getEntityManager();        
        $arNovedad = new \Brasa\TurnoBundle\Entity\TurNovedad();        
        $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigoNovedad); 
        $intCantidad = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $douTotalServicio = 0;
        $douTotalMinimoServicio = 0;
        $douTotalCostoCalculado = 0;
        $arNovedadesDetalle = new \Brasa\TurnoBundle\Entity\TurNovedadDetalle();        
        $arNovedadesDetalle = $em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->findBy(array('codigoNovedadFk' => $codigoNovedad));         
        foreach ($arNovedadesDetalle as $arNovedadDetalle) {
            if($arNovedadDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 2) {
                $intDias = $arNovedadDetalle->getFechaDesde()->diff($arNovedadDetalle->getFechaHasta());
                $intDias = $intDias->format('%a');                           
                $intDias += 1;
                if($arNovedadDetalle->getFechaHasta()->format('d') == '31') {
                    $intDias = $intDias - 1;
                }
                if($arNovedadDetalle->getDia31() == 1) {
                    if($arNovedadDetalle->getFechaHasta()->format('d') == '31') {
                        $intDias = $intDias + 1;    
                    }                    
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
            if($arNovedadDetalle->getPeriodoRel()->getCodigoPeriodoPk() == 1) {                
                if($arNovedadDetalle->getLunes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arNovedadDetalle->getMartes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arNovedadDetalle->getMiercoles() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arNovedadDetalle->getJueves() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arNovedadDetalle->getViernes() == 1) {
                    $intDiasOrdinarios += 4;
                }
                if($arNovedadDetalle->getSabado() == 1) {
                    $intDiasSabados = 4;    
                }
                if($arNovedadDetalle->getDomingo() == 1) {
                    $intDiasDominicales = 4;    
                }                
                if($arNovedadDetalle->getFestivo() == 1) {
                    $intDiasFestivos = 2;    
                }                               
                $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                $intHorasRealesDiurnas = $arNovedadDetalle->getConceptoServicioRel()->getHorasDiurnas() * $intTotalDias;
                $intHorasRealesNocturnas = $arNovedadDetalle->getConceptoServicioRel()->getHorasNocturnas() * $intTotalDias;                            
            } else {
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arNovedadDetalle->getFechaDesde()->format('Y-m-d'), $arNovedadDetalle->getFechaHasta()->format('Y-m-d'));
                $fecha = $arNovedadDetalle->getFechaDesde()->format('Y-m-j');
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
                            if($arNovedadDetalle->getLunes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        } 
                        if($diaSemana == 2) {
                            $intDiasOrdinarios += 1; 
                            if($arNovedadDetalle->getMartes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 3) {
                            $intDiasOrdinarios += 1; 
                            if($arNovedadDetalle->getMiercoles() == 1) {                   
                                $intHorasRealesDiurnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }    
                        if($diaSemana == 4) {
                            $intDiasOrdinarios += 1; 
                            if($arNovedadDetalle->getJueves() == 1) {                   
                                $intHorasRealesDiurnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 5) {
                            $intDiasOrdinarios += 1; 
                            if($arNovedadDetalle->getViernes() == 1) {                   
                                $intHorasRealesDiurnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }
                        }                
                        if($diaSemana == 6) {
                           $intDiasSabados += 1; 
                            if($arNovedadDetalle->getSabado() == 1) {                   
                                $intHorasRealesDiurnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }                   
                        }
                        if($diaSemana == 7) {
                           $intDiasDominicales += 1; 
                            if($arNovedadDetalle->getDomingo() == 1) {                   
                                $intHorasRealesDiurnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasDiurnas();
                                $intHorasRealesNocturnas +=  $arNovedadDetalle->getConceptoServicioRel()->getHorasNocturnas();                        
                            }                   
                        }                    
                    }                                
                }                
            }
                                    
            $douCostoCalculado = $arNovedadDetalle->getCantidad() * $arNovedadDetalle->getConceptoServicioRel()->getVrCosto();
            $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas ) * $arNovedadDetalle->getCantidad();            
            $arNovedadDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurNovedadDetalle();        
            $arNovedadDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->find($arNovedadDetalle->getCodigoNovedadDetallePk());                         
            $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1); 
            $floValorBaseServicio = $arConfiguracionNomina->getVrSalario() * $arNovedad->getSectorRel()->getPorcentaje();
            $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $arNovedadDetalle->getModalidadServicioRel()->getPorcentaje() / 100);                        
            $floVrHoraDiurna = ((($floValorBaseServicioMes * 59.7) / 100)/30)/16;            
            $floVrHoraNocturna = ((($floValorBaseServicioMes * 40.3) / 100)/30)/8;                                  
            $floVrMinimoServicio = (($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna)) * $arNovedadDetalle->getCantidad();                        
            $floVrServicio = 0;            
            if($arNovedadDetalleActualizar->getVrPrecioAjustado() != 0) {
                $floVrServicio = $arNovedadDetalleActualizar->getVrPrecioAjustado();
            } else {
                $floVrServicio = $floVrMinimoServicio;
            }            
            $arNovedadDetalleActualizar->setVrTotalDetalle($floVrServicio);            
            $arNovedadDetalleActualizar->setVrPrecioMinimo($floVrMinimoServicio);
            $arNovedadDetalleActualizar->setVrCosto($douCostoCalculado);
            
            $arNovedadDetalleActualizar->setHoras($douHoras);
            $arNovedadDetalleActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arNovedadDetalleActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arNovedadDetalleActualizar->setDias($intDias);
            
            $em->persist($arNovedadDetalleActualizar);            
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalMinimoServicio += $floVrMinimoServicio;
            $douTotalCostoCalculado += $douCostoCalculado;
            $douTotalServicio += $floVrServicio;
            $intCantidad++;
        }
        $arNovedad->setHoras($douTotalHoras);
        $arNovedad->setHorasDiurnas($douTotalHorasDiurnas);
        $arNovedad->setHorasNocturnas($douTotalHorasNocturnas);
        $arNovedad->setVrTotal($douTotalServicio);
        $arNovedad->setVrTotalPrecioMinimo($douTotalMinimoServicio);
        $arNovedad->setVrTotalCosto($douTotalCostoCalculado);
        $em->persist($arNovedad);
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
    
    public function imprimir($codigo) {
        $em = $this->getEntityManager();  
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arNovedad = new \Brasa\TurnoBundle\Entity\TurNovedad();        
        $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigo);        
        if($arNovedad->getEstadoAutorizado() == 1) {
            if($arNovedad->getNumero() == 0) {            
                $intNumero = $em->getRepository('BrasaTurnoBundle:TurConsecutivo')->consecutivo(3);
                $arNovedad->setNumero($intNumero);
                $arNovedad->setFecha(new \DateTime('now'));                
            }   
            
            $em->persist($arNovedad);
            $em->flush();
        } else {
            $strResultado = "Debe autorizar la cotizacion para imprimirla";
        }
        return $strResultado;
    }    
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {                
                if($em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->numeroRegistros($codigo) <= 0) {
                    $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigo);                    
                    if($arNovedad->getEstadoAutorizado() == 0 && $arNovedad->getNumero() == 0) {
                        $em->remove($arNovedad);                    
                    }                     
                }               
            }
            $em->flush();
        }
    }    
}