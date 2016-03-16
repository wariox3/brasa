<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CarReciboDetalleRepository extends EntityRepository {
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arReciboDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->find($codigo);                
                $em->remove($arReciboDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(rd.codigoReciboDetallePk) as numeroRegistros FROM BrasaCarteraBundle:CarReciboDetalle rd "
                . "WHERE rd.codigoReciboFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrReciboDetalles = $query->getSingleResult(); 
        if($arrReciboDetalles) {
            $intNumeroRegistros = $arrReciboDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }  

    public function liquidar($codigoRecibo) {        
        $em = $this->getEntityManager();        
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();        
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo); 
        $intCantidad = 0;
        $floValor = 0;
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);         
        $arRecibosDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();        
        $arRecibosDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo));         
        foreach ($arRecibosDetalle as $arReciboDetalle) {                       
            $floValor += $arReciboDetalle->getValor();
        }                 
        $arRecibo->setVrTotal($floValor);
        $em->persist($arRecibo);
        $em->flush();
        return true;
    }
}