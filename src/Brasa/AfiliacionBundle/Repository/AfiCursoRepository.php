<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiCursoRepository extends EntityRepository {  
    
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiCurso c WHERE c.codigoCursoPk <> 0";
        $dql .= " ORDER BY c.codigoCursoPk DESC";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
}