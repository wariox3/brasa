<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurFacturaRepository extends EntityRepository {
    
    public function listaDql() {
        $dql   = "SELECT f FROM BrasaTurnoBundle:TurFactura f WHERE f.codigoFacturaPk <> 0";
        return $dql;
    }
    
    public function pedidoMaestroDql() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoTipoFk = 2";
        return $dql;
    }    
    
    public function pedidoSinProgramarDql($strFechaDesde = '', $strFechaHasta = '') {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoTipoFk = 1 "
                . "AND p.programado = 0 ";

        if($strFechaDesde != '') {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . "'";  
        }
        if($strFechaHasta != '') {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . "'";  
        }        
        return $dql;
    }        
    
    public function liquidar($codigoFactura) {        
        $em = $this->getEntityManager();        
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();        
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura); 
        $floSubTotal = 0;
        $floBaseAIU = 0;
        $floIva = 0;
        $floRetencionFuente = 0;
        $floTotal = 0;
        $arFacturasDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();        
        $arFacturasDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalle as $arFacturaDetalle) {
            $floSubTotal +=  $arFacturaDetalle->getVrPrecio();
        }
        $floBaseAIU = ($floSubTotal * 10) / 100;
        $floIva = ($floBaseAIU * 16 ) / 100;
        $floRetencionFuente = ($floBaseAIU * 2 ) / 100;
        $floTotal = $floSubTotal + $floIva + $floRetencionFuente;
        $arFactura->setVrBaseAIU($floBaseAIU);
        $arFactura->setVrSubtotal($floSubTotal);
        $arFactura->setVrRetencionFuente($floRetencionFuente);
        $arFactura->setVrIva($floIva);
        $arFactura->setvrTotal($floTotal);
        $em->persist($arFactura);
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
                $ar = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }     
}