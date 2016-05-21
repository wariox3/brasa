<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiPeriodoDetalleRepository extends EntityRepository {  
    
    public function ListaDql($codigoPeriodo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaAfiliacionBundle:AfiPeriodoDetalle pd WHERE pd.codigoPeriodoDetallePk <> 0";
        if($codigoPeriodo != "") {
            $dql .= " AND pd.codigoPeriodoFk =" . $codigoPeriodo;
        }
        $dql .= " ORDER BY pd.codigoPeriodoDetallePk";
        return $dql;
    }            
    
    public function listaConsultaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaAfiliacionBundle:AfiPeriodoDetalle pd WHERE pd.codigoPeriodoDetallePk <> 0";
        $dql .= " ORDER BY pd.codigoPeriodoDetallePk";
        return $dql;
    }    
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }     
        
}