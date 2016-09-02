<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuEmbargoRepository extends EntityRepository {
    
    public function listaDql() {        
        $em = $this->getEntityManager();
        $dql   = "SELECT e FROM BrasaRecursoHumanoBundle:RhuEmbargo e WHERE e.codigoEmbargoPk <> 0";               
        $dql .= " ORDER BY e.codigoEmbargoPk DESC";
        return $dql;
    }                    
        
}