<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuSeleccionGrupoRepository extends EntityRepository {                   
    public function devuelveNumeroSelecciones($codigoSeleccionGrupo) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionPk) FROM BrasaRecursoHumanoBundle:RhuSeleccion s WHERE s.codigoSeleccionGrupoFk = " . $codigoSeleccionGrupo;
        $query = $em->createQuery($dql);
        $douNumeroSelecciones = $query->getResult();
        return $douNumeroSelecciones;
    }                
}