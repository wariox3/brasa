<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuRequisitoCargoRepository extends EntityRepository {       
    public function listaDql() {                
        $dql   = "SELECT rc FROM BrasaRecursoHumanoBundle:RhuRequisitoCargo rc";        
        return $dql;
    }      
}