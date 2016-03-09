<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCostoRecursoRepository extends EntityRepository {

    public function listaDql() {
        $dql   = "SELECT cr FROM BrasaTurnoBundle:TurCostoRecurso cr";
        return $dql;
    }    

}