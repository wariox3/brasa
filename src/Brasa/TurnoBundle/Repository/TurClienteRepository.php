<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurClienteRepository extends EntityRepository {    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaTurnoBundle:TurCliente c WHERE c.codigoClientePk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND c.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND c.codigoClientePk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY c.nombreCorto";
        return $dql;
    }            
}