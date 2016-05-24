<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiNovedadRepository extends EntityRepository {  
    
    public function listaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT n FROM BrasaAfiliacionBundle:AfiNovedad n WHERE n.codigoNovedadPk <> 0";
        $dql .= " ORDER BY n.codigoNovedadPk";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }     
    
}