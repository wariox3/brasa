<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPlantillaDetalleRepository extends EntityRepository {
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
}