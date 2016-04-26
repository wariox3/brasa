<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiEmpleadoRepository extends EntityRepository {    
    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT e FROM BrasaAfiliacionBundle:AfiEmpleado e WHERE e.codigoEmpleadoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND e.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND e.codigoClientePk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY e.nombreCorto";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}