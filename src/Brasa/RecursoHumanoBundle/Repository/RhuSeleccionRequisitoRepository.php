<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuSeleccionRequisitoRepository extends EntityRepository {                   
    
     
    
    public function eliminarSeleccionRequisitos($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccionRequisito) {                
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($codigoSeleccionRequisito);                     
                if($em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->devuelveNumeroDetalleRequisito($codigoSeleccionRequisito) <= 0){
                   $em->remove($arSeleccion);  
                }                                            
            }
            $em->flush();       
        }     
    }     
    
    public function listaDQL($strNombre = "", $boolAbierto = 2, $strCargo = "", $strDesde = "", $strHasta= "") {                
        $dql   = "SELECT sq FROM BrasaRecursoHumanoBundle:RhuSeleccionRequisito sq WHERE sq.codigoSeleccionRequisitoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND sq.nombre LIKE '%" . $strNombre . "%'";
        }   
        if($boolAbierto != null) {
            if($boolAbierto == 1 ) {
                $dql .= " AND sq.estadoAbierto = 1";
            } elseif($boolAbierto == 0) {
                $dql .= " AND sq.estadoAbierto = 0";
            }            
        }
        if($strCargo != "") {
            $dql .= " AND sq.codigoCargoFk = " . $strCargo;
        }
        if($strDesde != "" || $strDesde != 0){
            $dql .= " AND sq.fecha >='" . date_format($strDesde, ('Y-m-d')) . "'";
        }
        if($strHasta != "" || $strHasta != 0) {
            $dql .= " AND sq.fecha <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
         
        $dql .= " ORDER BY sq.codigoSeleccionRequisitoPk";
        return $dql;
    }   
    // Esta funcion cambiar el estado abierto del requisito (Abierto / Cerrado)
    public function estadoAbiertoSeleccionRequisitos($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccion) {                
                $arSeleccionRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
                $arSeleccionRequisito = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($codigoSeleccion);
                $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('codigoSeleccionRequisitoFk' => $codigoSeleccion));
                if ($arSeleccionRequisito->getEstadoAbierto() == 1){
                    $arSeleccionRequisito->setEstadoAbierto(0);
                    if (count($arSeleccion) > 0){
                        foreach ($arSeleccion AS $arSeleccion) {
                            $arSeleccion->setEstadoCerrado(0);
                        }
                        $em->persist($arSeleccion);
                    }
                } 
                $em->persist($arSeleccionRequisito);
            }
            $em->flush();       
        }     
    }
    
    public function devuelveNumeroDetalleRequisito($codigoSeleccionRequisito) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionPk) FROM BrasaRecursoHumanoBundle:RhuSeleccion s WHERE s.codigoSeleccionRequisitoFk = " . $codigoSeleccionRequisito;
        $query = $em->createQuery($dql);
        $douNumeroDetalleRequisito = $query->getSingleScalarResult();
        return $douNumeroDetalleRequisito;
    }
}
