<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuSupervigilanciaParafiscalesRepository extends EntityRepository {   
    
    public function listaDql() {        
            $em = $this->getEntityManager();
            $dql   = "SELECT sp FROM BrasaRecursoHumanoBundle:RhuSupervigilanciaParafiscales sp WHERE sp.codigoSupervigilanciaParafiscalesPk <> 0";            
            return $dql;
        }             
}