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
        $douTotalHoras = 0;
        $arPedidosDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));         
        foreach ($arPedidosDetalle as $arPedidoDetalle) {
            $douHorasDetalle = 0;
            //$intHorasTurno = 0;
            $intDias = $arPedidoDetalle->getFechaDesde()->diff($arPedidoDetalle->getFechaHasta());
            $intDias = $intDias->format('%a');                           
            $intDias += 1;
            /*if($arPedidoDetalle->getLunes() == 1) {
                $intHorasTurno += $arPedidoDetalle->getTurnoRel()->getHoras();
            }
            if($arPedidoDetalle->getMartes() == 1) {
                $intHorasTurno += $arPedidoDetalle->getTurnoRel()->getHoras();
            }
            if($arPedidoDetalle->getMiercoles() == 1) {
                $intHorasTurno += $arPedidoDetalle->getTurnoRel()->getHoras();
            }
            if($arPedidoDetalle->getJueves() == 1) {
                $intHorasTurno += $arPedidoDetalle->getTurnoRel()->getHoras();
            }
            if($arPedidoDetalle->getViernes() == 1) {
                $intHorasTurno += $arPedidoDetalle->getTurnoRel()->getHoras();
            }
            if($arPedidoDetalle->getSabado() == 1) {
                $intHorasTurno += $arPedidoDetalle->getTurnoRel()->getHoras();
            }
            if($arPedidoDetalle->getDomingo() == 1) {
                $intHorasTurno += $arPedidoDetalle->getTurnoRel()->getHoras();
            }*/                    
            $douHorasDetalle = $arPedidoDetalle->getTurnoRel()->getHoras() * $intDias;
            $arPedidoDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
            $arPedidoDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDetalle->getCodigoPedidoDetallePk());                         
            //$arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            //$arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arPedidoDetalle->getDia15()); 
            $arPedidoDetalleActualizar->setHoras($douHorasDetalle);
            $em->persist($arPedidoDetalleActualizar);            
            $douTotalHoras += $douHorasDetalle;
        }
        $arPedido->setHoras($douTotalHoras);
        $em->persist($arPedido);
        $em->flush();
        return true;
    }        
}