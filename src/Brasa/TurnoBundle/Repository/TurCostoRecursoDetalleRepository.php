<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCostoRecursoDetalleRepository extends EntityRepository {

    public function listaDql($codigoRecurso = "", $anio = "", $mes = "") {
        $dql   = "SELECT crd FROM BrasaTurnoBundle:TurCostoRecursoDetalle crd WHERE crd.codigoCostoRecursoDetallePk <> 0";
        if($codigoRecurso != "") {
            $dql .= " AND crd.codigoRecursoFk = " . $codigoRecurso;  
        }         
        if($anio != "") {
            $dql .= " AND crd.anio = " . $anio;  
        }     
        if($mes != "") {
            $dql .= " AND crd.mes = " . $mes;  
        }         
        return $dql;
    }    

}