<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSoportePagoPeriodoRepository extends EntityRepository {
    public function listaDql() {
        $dql   = "SELECT spp FROM BrasaTurnoBundle:TurSoportePagoPeriodo spp";
        return $dql;
    }

}