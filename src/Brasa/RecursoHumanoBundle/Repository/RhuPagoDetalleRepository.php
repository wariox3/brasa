<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoDetalleRepository extends EntityRepository {
    public function pagosDetallesProgramacionPago($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.codigoProgramacionPagoFk = " . $codigoProgramacionPago;
        $query = $em->createQuery($dql);
        $arPagosDetalles = $query->getResult();                
        return $arPagosDetalles;
    }
}