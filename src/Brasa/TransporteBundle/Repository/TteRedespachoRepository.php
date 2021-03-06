<?php

namespace Brasa\TransporteBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MovimientosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TteRedespachoRepository extends EntityRepository {
    public function RedespachosGuiasDetalle($codigoGuia) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT redespachos FROM BrasaTransporteBundle:TteRedespacho redespachos WHERE redespachos.codigoGuiaFk = " . $codigoGuia;
        $query = $em->createQuery($dql);        
        return $query;
    }    
}