<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCentroCostoRepository extends EntityRepository {    
    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT cc FROM BrasaTurnoBundle:TurCentroCosto cc WHERE cc.codigoCentroCostoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND cc.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND cc.codigoCentroCostoPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY cc.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurCentroCosto')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}