<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuSeleccionGrupoRepository extends EntityRepository {                   
    public function eliminarSeleccionGrupos($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccionGrupo) {
                if($em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->devuelveNumeroSelecciones($codigoSeleccionGrupo) <= 0) {
                    $arSeleccionGrupo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->find($codigoSeleccionGrupo);                     
                    $em->remove($arSeleccionGrupo);                            
                }        
            }
            $em->flush();       
        }     
    } 
    
    public function listaDQL($strNombre = "", $boolAbierto = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT sg FROM BrasaRecursoHumanoBundle:RhuSeleccionGrupo sg WHERE sg.codigoSeleccionGrupoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND sg.nombre LIKE '%" . $strNombre . "%'";
        }      
        if($boolAbierto == 1 ) {
            $dql .= " AND sg.estadoAbierto = 1";
        } elseif($boolAbierto == 0) {
            $dql .= " AND sg.estadoAbierto = 0";
        }         
        $dql .= " ORDER BY sg.nombre";
        return $dql;
    }     
}
