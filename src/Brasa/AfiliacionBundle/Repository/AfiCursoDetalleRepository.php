<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiCursoDetalleRepository extends EntityRepository {  
    
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT cd FROM BrasaAfiliacionBundle:AfiCursoDetalle cd WHERE cd.codigoCursoDetallePk <> 0";
        $dql .= " ORDER BY cd.codigoCursoDetallePk";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }             
        
}