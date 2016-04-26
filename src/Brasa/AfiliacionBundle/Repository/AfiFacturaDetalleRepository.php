<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiFacturaDetalleRepository extends EntityRepository {  
    
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT fd FROM BrasaAfiliacionBundle:AfiFacturaDetalle fd WHERE fd.codigoFacturaDetallePk <> 0";
        $dql .= " ORDER BY fd.codigoFacturaDetallePk";
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