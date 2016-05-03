<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiFacturaDetalleRepository extends EntityRepository {  
    
    public function ListaDql($codigoFactura) {
        $em = $this->getEntityManager();
        $dql   = "SELECT fd FROM BrasaAfiliacionBundle:AfiFacturaDetalle fd WHERE fd.codigoFacturaFk = " . $codigoFactura;
        $dql .= " ORDER BY fd.codigoFacturaDetallePk";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }             
 
}