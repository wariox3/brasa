<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuSeleccionGrupoRepository extends EntityRepository {                   
    
     
    
    public function eliminarSeleccionGrupos($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccionGrupo) {                
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->find($codigoSeleccionGrupo);                     
                if($em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->devuelveNumeroDetalleGrupo($codigoSeleccionGrupo) <= 0){
                   $em->remove($arSeleccion);  
                }                                            
            }
            $em->flush();       
        }     
    }     
    
    public function listaDQL($strNombre = "", $boolAbierto = "") {                
        $dql   = "SELECT sg FROM BrasaRecursoHumanoBundle:RhuSeleccionGrupo sg WHERE sg.codigoSeleccionGrupoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND sg.nombre LIKE '%" . $strNombre . "%'";
        }   
        if($boolAbierto != "") {
            if($boolAbierto == 1 ) {
                $dql .= " AND sg.estadoAbierto = 1";
            } elseif($boolAbierto == 0) {
                $dql .= " AND sg.estadoAbierto = 0";
            }            
        }
         
        $dql .= " ORDER BY sg.codigoSeleccionGrupoPk";
        return $dql;
    }   
    // Esta funcion cambiar el estado abierto del grupo (Abierto / Cerrado)
    public function estadoAbiertoSeleccionGrupos($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccion) {                
                $arSeleccionGrupo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo();
                $arSeleccionGrupo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->find($codigoSeleccion);
                $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('codigoSeleccionGrupoFk' => $codigoSeleccion));
                if ($arSeleccionGrupo->getEstadoAbierto() == 1){
                    $arSeleccionGrupo->setEstadoAbierto(0);
                    if (count($arSeleccion) > 0){
                        foreach ($arSeleccion AS $arSeleccion) {
                            $arSeleccion->setEstadoAbierto(0);
                        }
                        $em->persist($arSeleccion);
                    }
                } 
                $em->persist($arSeleccionGrupo);
            }
            $em->flush();       
        }     
    }
    public function devuelveNumeroDetalleGrupo($codigoSeleccionGrupo) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionPk) FROM BrasaRecursoHumanoBundle:RhuSeleccion s WHERE s.codigoSeleccionGrupoFk = " . $codigoSeleccionGrupo;
        $query = $em->createQuery($dql);
        $douNumeroDetalleGrupo = $query->getSingleScalarResult();
        return $douNumeroDetalleGrupo;
    }
}
