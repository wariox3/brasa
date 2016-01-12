<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioDetalleRecursoRepository extends EntityRepository {
    public function listaDql($codigoServicioDetalle) {
        $dql   = "SELECT pdr FROM BrasaTurnoBundle:TurServicioDetalleRecurso pdr WHERE pdr.codigoServicioDetalleFk = " . $codigoServicioDetalle;
        $dql .= " ORDER BY pdr.posicion";
        return $dql;
    }    
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $ar = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->find($codigo);                
                $em->remove($ar);                  
            }                                         
            $em->flush();       
        }
        
    }            
}