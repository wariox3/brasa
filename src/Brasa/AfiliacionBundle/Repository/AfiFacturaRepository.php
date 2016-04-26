<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiFacturaRepository extends EntityRepository {  
    
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT f FROM BrasaAfiliacionBundle:AfiFactura f WHERE f.codigoFacturaPk <> 0";
        $dql .= " ORDER BY f.codigoFacturaPk";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }             
        
}