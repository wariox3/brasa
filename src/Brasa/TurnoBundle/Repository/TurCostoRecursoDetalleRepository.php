<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCostoRecursoDetalleRepository extends EntityRepository {

    public function listaDql($codigoRecurso = "") {
        $dql   = "SELECT cr FROM BrasaTurnoBundle:TurCostoRecurso cr WHERE cr.codigoCostoRecursoPk <> 0";
        if($codigoRecurso != "") {
            $dql .= " AND cr.codigoRecursoFk = " . $codigoRecurso;  
        }         
        return $dql;
    }    

}