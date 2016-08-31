<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuProvisionRepository extends EntityRepository {
    public function listaDql($codigoProvisionPeriodo = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuProvision p WHERE p.codigoProvisionPk <> 0";
        if($codigoProvisionPeriodo) {
            $dql .= " AND p.codigoProvisionPeriodoFk = " . $codigoProvisionPeriodo;
        }
        $dql .= " ORDER BY p.codigoProvisionPk ASC";
        return $dql;
    }                            
    
    public function pendientesContabilizarDql() {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuProvision p JOIN p.provisionPeriodoRel pp WHERE pp.estadoGenerado = 1 AND p.estadoContabilizado = 0 ";
        $dql .= " ORDER BY p.codigoProvisionPk ASC";
        return $dql;
    }     
}