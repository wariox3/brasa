<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuSeleccionRepository extends EntityRepository {                   
    public function listaDQL($strNombre = "", $strIdentificacion = "", $boolAbierto = 2, $boolAprobado = 2, $codigoCentroCosto = "") {        
        $dql   = "SELECT s FROM BrasaRecursoHumanoBundle:RhuSeleccion s WHERE s.codigoSeleccionPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND s.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strIdentificacion != "" ) {
            $dql .= " AND s.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($boolAbierto != null) {
            if($boolAbierto == 1 ) {
                $dql .= " AND s.estadoAbierto = 1";
            } elseif($boolAbierto == 0) {
                $dql .= " AND s.estadoAbierto = 0";
            }            
        }
        if($boolAprobado != null) {
            if($boolAprobado == 1 ) {
                $dql .= " AND s.estadoAprobado = 1";
            } elseif($boolAprobado == 0) {
                $dql .= " AND s.estadoAprobado = 0";
            }            
        }                     
        if($codigoCentroCosto != "" ) {
            $dql .= " AND s.codigoCentroCostoFk = " . $codigoCentroCosto;
        }
        $dql .= " ORDER BY s.fecha";
        return $dql;
    }                            
    
    public function devuelveNumeroSelecciones($codigoSeleccionGrupo) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionPk) FROM BrasaRecursoHumanoBundle:RhuSeleccion s WHERE s.codigoSeleccionGrupoFk = " . $codigoSeleccionGrupo;
        $query = $em->createQuery($dql);
        $douNumeroSelecciones = $query->getSingleScalarResult();
        return $douNumeroSelecciones;
    }
    
    public function devuelveNumeroReferencias($id) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionReferenciaPk) FROM BrasaRecursoHumanoBundle:RhuSeleccionReferencia s WHERE s.codigoSeleccionFk = " . $id;
        $query = $em->createQuery($dql);
        $douNumeroReferencias = $query->getSingleScalarResult();
        return $douNumeroReferencias;
    }        
    
    public function devuelveNumeroReferenciasSinVerificar($codigoSeleccion) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionReferenciaPk) FROM BrasaRecursoHumanoBundle:RhuSeleccionReferencia s WHERE s.estadoVerificada = 0  and s.codigoSeleccionFk = " . $codigoSeleccion;
        $query = $em->createQuery($dql);        
        return $query->getSingleScalarResult();
    }
    
    public function presentaPruebasSelecciones($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccion) {
                $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                if ($arSelecciones->getPresentaPruebas() == 0){
                    $arSelecciones->setPresentaPruebas(1);
                }
                $em->persist($arSelecciones);                
            }
            $em->flush();              
        }        
    }     
    
    public function referenciasVerificadsSelecciones($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccion) {                
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                if($arSeleccion->getReferenciasVerificadas() == 0) {                    
                    if($em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->devuelveNumeroReferenciasSinVerificar($codigoSeleccion) <= 0) {                        
                        $arSeleccion->setReferenciasVerificadas(1);                                           
                    }                    
                }
                $em->persist($arSeleccion);
            }
            $em->flush();
            
        }        
        return false;
    }         

    public function estadoAprobadoSelecciones($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccion) {
                $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                if ($arSelecciones->getEstadoAprobado() == 0){
                    $arSelecciones->setEstadoAprobado(1);
                }
                $em->persist($arSelecciones);                
            }
            $em->flush();              
        }        
    }         

    public function estadoAbiertoSelecciones($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccion) {
                $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                if ($arSelecciones->getEstadoAbierto() == 1){
                    $arSelecciones->setEstadoAbierto(0);
                }
                $em->persist($arSelecciones);                
            }
            $em->flush();              
        }        
    }             
    
}