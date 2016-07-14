<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuZonaRepository extends EntityRepository {
    
    public function listaDQL($strNombre = "") {        
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuCargo c WHERE c.codigoCargoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND c.nombre LIKE '%" . $strNombre . "%'";
        }
        $dql .= " ORDER BY c.codigoCargoPk";
        return $dql;
    }
}