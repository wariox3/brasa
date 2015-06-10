<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuExamenRepository extends EntityRepository {                   
    
     
    
    public function eliminarExamen($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoExamen) {                
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);                     
                   $em->remove($arSeleccion);                                              
            }
            $em->flush();       
        }     
    }     
    
    public function listaDQL($strNombre = "", $boolAprobado = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT sg FROM BrasaRecursoHumanoBundle:RhuExamen sg WHERE sg.codigoExamenPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND sg.nombre LIKE '%" . $strNombre . "%'";
        }      
        if($boolAprobado == 1 ) {
            $dql .= " AND sg.estadoAprobado = 1";
        } elseif($boolAprobado == 0) {
            $dql .= " AND sg.estadoAprobado = 0";
        }         
        $dql .= " ORDER BY sg.codigoExamenPk";
        return $dql;
    }   
    
}
