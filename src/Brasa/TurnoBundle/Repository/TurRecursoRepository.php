<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurRecursoRepository extends EntityRepository {    
    
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
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }     
}