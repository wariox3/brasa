<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuVacacionRepository extends EntityRepository {
    
    public function dias($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $dql   = "SELECT v FROM BrasaRecursoHumanoBundle:RhuVacacion v "
                . "WHERE v.codigoEmpleadoFk = " . $codigoEmpleado
                . " AND v.fechaDesde <= '" . $fechaHasta->format('Y-m-d') . "' "
                . " AND v.fechaHasta >= '" . $fechaDesde->format('Y-m-d') . "' ";
        $query = $em->createQuery($dql);
        $arVacaciones = $query->getResult();
        $intDiasDevolver = 0;
        foreach ($arVacaciones as $arVacacion) {
            $dateFechaDesde =  "";
            $dateFechaHasta =  "";
            
            if($arVacacion->getFechaDesde() <  $fechaDesde == true) {
                $dateFechaDesde = $fechaDesde;
            } else {
                $dateFechaDesde = $arVacacion->getFechaDesde();
            }

            if($arVacacion->getFechaHasta() >  $fechaHasta == true) {
                $dateFechaHasta = $fechaHasta;
            } else {
                $dateFechaHasta = $arVacacion->getFechaHasta();
            }
            if($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');
                $intDiasDevolver += $intDias + 1;
            }
        }
        return $intDiasDevolver;
    }
    
    public function listaVacacionesDQL($strCodigoCentroCosto = "", $strIdentificacion = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT v, e FROM BrasaRecursoHumanoBundle:RhuVacacion v JOIN v.empleadoRel e WHERE v.codigoVacacionPk <> 0";
        
        if($strCodigoCentroCosto != "") {
            $dql .= " AND e.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        return $dql;
    }
}

