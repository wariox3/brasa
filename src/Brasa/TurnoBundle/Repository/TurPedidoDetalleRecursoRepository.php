<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoDetalleRecursoRepository extends EntityRepository {
    public function listaDql($codigoPedidoDetalle) {
        $dql   = "SELECT pdr FROM BrasaTurnoBundle:TurPedidoDetalleRecurso pdr WHERE pdr.codigoPedidoDetalleFk = " . $codigoPedidoDetalle;
        return $dql;
    }    
}