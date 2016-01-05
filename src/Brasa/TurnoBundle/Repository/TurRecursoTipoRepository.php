<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurRecursoTipoRepository extends EntityRepository {    
    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT r FROM BrasaTurnoBundle:TurRecurso r WHERE r.codigoRecursoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND r.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND r.codigoRecursoPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY r.nombreCorto";
        return $dql;
    }        
        
}