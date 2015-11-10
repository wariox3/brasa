<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurTurnoRepository extends EntityRepository {
    public function listaDQL() {
        $dql   = "SELECT t FROM BrasaTurnoBundle:TurTurno t WHERE t.codigoTurnoPk <> ''";
        return $dql;
    }
}