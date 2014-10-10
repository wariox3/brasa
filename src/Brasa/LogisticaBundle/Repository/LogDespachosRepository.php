<?php

namespace Brasa\LogisticaBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MovimientosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LogDespachosRepository extends EntityRepository {
    public function Generar($codigoDespacho) {        
        $em = $this->getEntityManager();
        $arDespacho = new \Brasa\LogisticaBundle\Entity\LogDespachos();
        $arDespacho = $em->getRepository('BrasaLogisticaBundle:LogDespachos')->find($codigoDespacho);
        $arDespacho->setEstadoGenerado(1);
        $em->persist($arDespacho);
        $em->flush(); 
        return "";
    }    
}