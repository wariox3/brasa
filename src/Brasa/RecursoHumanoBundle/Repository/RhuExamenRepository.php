<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuExamenRepository extends EntityRepository {                   
    
     
    
    public function eliminarExamen($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoExamen) {                
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);                     
                if($em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->devuelveNumeroDetalleExamen($codigoExamen) <= 0){   
                    $em->remove($arSeleccion);
                }
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
    
    public function aprobarExamen($arrSeleccionados) {
        $em = $this->getEntityManager();
        $var = 0;
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoExamen) {                
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);                     
                $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                $arExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => $codigoExamen));
                foreach ($arExamenDetalle as $arExamenDetalle){
                    if ($arExamenDetalle->getEstadoAprobado()== 1){
                        $var = $var + 1;
                    }
                }
                $var2 = count($arExamenDetalle);
                if ($var2 == $var){
                    $arSeleccion->setEstadoAbierto(1);
                    $em->persist($arSeleccion);
                }
            }
            $em->flush();       
        }     
    } 
    
    public function devuelveNumeroDetalleExamen($codigoSeleccionGrupo) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoExamenDetallePk) FROM BrasaRecursoHumanoBundle:RhuExamenDetalle s WHERE s.codigoExamenFk = " . $codigoSeleccionGrupo;
        $query = $em->createQuery($dql);
        $douNumeroDetalleExamen = $query->getSingleScalarResult();
        return $douNumeroDetalleExamen;
    }
    
}
