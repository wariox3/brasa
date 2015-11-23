<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurProgramacionDetalleRepository extends EntityRepository {
    public function eliminarDetallesSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigo);                
                $em->remove($arProgramacionDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }  
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigo));
        return count($arDetalles);
    }              
}