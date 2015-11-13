<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoRepository extends EntityRepository {
    
    public function listaDQL() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoPk <> 0";
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
        $arPedidosDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));         
        foreach ($arPedidosDetalle as $arPedidoDetalle) {
            $intDias = $arPedidoDetalle->getFechaDesde()->diff($arPedidoDetalle->getFechaHasta());
            $intDias = $intDias->format('%a');                           
            $intDias += 1; 
            $intHorasRealesDiurnas = 0;
            $intHorasRealesNocturnas = 0;            
            $intDiasOrdinarios = 0;
            $intDiasSabados = 0;
            $intDiasDominicales = 0;
            $intDiasFestivos = 0;
            $fecha = $arPedidoDetalle->getFechaDesde()->format('Y-m-j');
            for($i = 0; $i < $intDias; $i++) {
                $nuevafecha = strtotime ( '+'.$i.' day' , strtotime ( $fecha ) ) ;
                $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
                $dateNuevaFecha = date_create($nuevafecha);
                $diaSemana = $dateNuevaFecha->format('N');
                if($diaSemana == 1) {
                    $intDiasOrdinarios += 1; 
                    if($arPedidoDetalle->getLunes() == 1) {                   
                        $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                        $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                    }
                }
                if($diaSemana == 2) {
                    $intDiasOrdinarios += 1; 
                    if($arPedidoDetalle->getMartes() == 1) {                   
                        $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                        $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                    }
                }                
                if($diaSemana == 3) {
                    $intDiasOrdinarios += 1; 
                    if($arPedidoDetalle->getMiercoles() == 1) {                   
                        $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                        $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                    }
                }    
                if($diaSemana == 4) {
                    $intDiasOrdinarios += 1; 
                    if($arPedidoDetalle->getJueves() == 1) {                   
                        $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                        $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                    }
                }                
                if($diaSemana == 5) {
                    $intDiasOrdinarios += 1; 
                    if($arPedidoDetalle->getViernes() == 1) {                   
                        $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                        $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                    }
                }                
                if($diaSemana == 6) {
                   $intDiasSabados += 1; 
                    if($arPedidoDetalle->getSabado() == 1) {                   
                        $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                        $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                    }                   
                }
                if($diaSemana == 7) {
                   $intDiasDominicales += 1; 
                    if($arPedidoDetalle->getDomingo() == 1) {                   
                        $intHorasRealesDiurnas +=  $arPedidoDetalle->getTurnoRel()->getHorasDiurnas();
                        $intHorasRealesNocturnas +=  $arPedidoDetalle->getTurnoRel()->getHorasNocturnas();                        
                    }                   
                }                
            }                                    
            
            $douHoras = ($intHorasRealesDiurnas + $intHorasRealesNocturnas ) * $arPedidoDetalle->getCantidad();            
            $arPedidoDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
            $arPedidoDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDetalle->getCodigoPedidoDetallePk());                         
            $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1); 
            $floValorBaseServicio = $arConfiguracionNomina->getVrSalario() * $arPedido->getClienteRel()->getSectorRel()->getPorcentaje();
            
            $floServicio = ($floValorBaseServicio + ( $floValorBaseServicio * $arPedidoDetalle->getModalidadServicioRel()->getPorcentaje() / 100)) / 24;            
            $floServicio = $floServicio * $arPedidoDetalle->getTurnoRel()->getHoras();
            $floServicio = $floServicio * $arPedidoDetalle->getCantidad();
            $arPedidoDetalleActualizar->setVrTotal($floServicio);
            $arPedidoDetalleActualizar->setHoras($douHoras);
            $arPedidoDetalleActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arPedidoDetalleActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arPedidoDetalleActualizar->setDias($intDias);
            
            $em->persist($arPedidoDetalleActualizar);            
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalServicio += $floServicio;
            $intCantidad++;
        }
        $arPedido->setHoras($douTotalHoras);
        $arPedido->setHorasDiurnas($douTotalHorasDiurnas);
        $arPedido->setHorasNocturnas($douTotalHorasNocturnas);
        $arPedido->setVrTotal($douTotalServicio);
        $em->persist($arPedido);
        $em->flush();
        return true;
    }
    
    public function devolverVariableProporcionalidad($timeDesde, $timeHasta) {
        $floVariableProporcionalidad = 0;
        
        return $floVariableProporcionalidad;
    }    
}