<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuSedeRepository extends EntityRepository {  
    public function ListaDQL($strNombre = "", $strCodigoCentroCosto = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT s FROM BrasaRecursoHumanoBundle:RhuSede s WHERE s.codigoSedePk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND s.nombre LIKE '%" . $strNombre . "%'";
        }     
        if($strCodigoCentroCosto != "") {
            $dql .= " AND s.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }      
        $dql .= " ORDER BY s.nombre";
        return $dql;
    }                    
}