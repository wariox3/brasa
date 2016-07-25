<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSoportePagoInconsistenciaRepository extends EntityRepository {
    
    public function listaDql($codigoSoportePago = "") {
        $dql   = "SELECT spi FROM BrasaTurnoBundle:TurSoportePagoInconsistencia spi WHERE spi.codigoSoportePagoFk = " . $codigoSoportePago;
        return $dql;
    }
    
}