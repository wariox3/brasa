<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuEncabezadoPagoExamenRepository extends EntityRepository {                   
    
     
    
   /* public function eliminarExamen($arrSeleccionados) {
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
    } */    
    
    public function listaDQL($strNombre = "", $strCodigoEntidadExamen = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT sg FROM BrasaRecursoHumanoBundle:RhuEncabezadoPagoExamen sg WHERE sg.codigoEncabezadoPagoExamenPk <> 0";
        if($strCodigoEntidadExamen != "") {
            $dql .= " AND e.codigoEntidadExamenFk = " . $strCodigoEntidadExamen;
        }      
                
        $dql .= " ORDER BY sg.codigoEncabezadoPagoExamenPk";
        return $dql;
    } 
    
   /* public function aprobarExamen($arrSeleccionados) {
        $em = $this->getEntityManager();
        $var = 0;
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoExamen) {                
                $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);                     
                $arExamenDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                $arExamenDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => $codigoExamen));
                foreach ($arExamenDetalles as $arExamenDetalle){
                    if ($arExamenDetalle->getEstadoAprobado()== 1){
                        $var += 1;
                    }
                }
                $var2 = count($arExamenDetalles);
                if ($var != 0 or $var2 != 0){
                    if ($var2 == $var){
                        $arExamen->setEstadoAprobado(1);

                    }
                    else{
                        $arExamen->setEstadoAprobado(0);
                    }
                     $em->persist($arExamen);
                }     
            }
            $em->flush();       
        }     
    } 
    
   /* public function devuelveNumeroDetalleExamen($codigoSeleccionGrupo) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoExamenDetallePk) FROM BrasaRecursoHumanoBundle:RhuExamenDetalle s WHERE s.codigoExamenFk = " . $codigoSeleccionGrupo;
        $query = $em->createQuery($dql);
        $douNumeroDetalleExamen = $query->getSingleScalarResult();
        return $douNumeroDetalleExamen;
    }*/
    
}
