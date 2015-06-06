<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuSeleccionRepository extends EntityRepository {                   
    public function listaDQL($strNombre = "", $strIdentificacion = "", $boolAbierto = "", $boolAprobado = "") {        
        $dql   = "SELECT s FROM BrasaRecursoHumanoBundle:RhuSeleccion s WHERE s.codigoSeleccionPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND s.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strIdentificacion != "" ) {
            $dql .= " AND s.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if ($boolAbierto != ""){
            if($boolAbierto == 1 ) {
                $dql .= " AND s.estadoAbierto = 1";
            } elseif($boolAbierto == 0) {
                $dql .= " AND s.estadoAbierto = 0";
            }             
        }
        if ($boolAprobado != ""){
            if($boolAprobado == 1 ) {
                $dql .= " AND s.estadoAprobado = 1";
            } elseif($boolAprobado == 0) {
                $dql .= " AND s.estadoAprobado = 0";
            }             
        }
     
        $dql .= " ORDER BY s.nombreCorto";
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
    
    public function devuelveNumeroReferenciasVerificadas($id) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionReferenciaPk) FROM BrasaRecursoHumanoBundle:RhuSeleccionReferencia s WHERE s.estadoVerificada = 1  and s.codigoSeleccionFk = " . $id;
        $query = $em->createQuery($dql);
        $douNumeroSeleccionesVerificadas = $query->getSingleScalarResult();
        return $douNumeroSeleccionesVerificadas;
    }
}