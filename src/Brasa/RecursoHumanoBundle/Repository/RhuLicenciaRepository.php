<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuLicenciaRepository extends EntityRepository {
    
    public function pendientesCentroCosto($strCodigoCentroCosto) {
        $em = $this->getEntityManager();                
        $dql   = "SELECT l FROM BrasaRecursoHumanoBundle:RhuLicencia l "
                . "WHERE l.codigoCentroCostoFk = " . $strCodigoCentroCosto . " "
                . "AND l.cantidadPendiente != 0 ";
        $query = $em->createQuery($dql);
        $arLicenciasPendientes = $query->getResult();
        return $arLicenciasPendientes;        
    }
        
    public function pendientesEmpleado($strCodigoEmpleado) {
        $em = $this->getEntityManager();                
        $dql   = "SELECT l FROM BrasaRecursoHumanoBundle:RhuLicencia l "
                . "WHERE l.codigoEmpleadoFk = " . $strCodigoEmpleado . " "
                . "AND l.cantidadPendiente != 0 ";
        $query = $em->createQuery($dql);
        $arLicenciasPendientesEmpleado = $query->getResult();
        return $arLicenciasPendientesEmpleado;        
    }
    
    public function diasLicencia($fechaDesde, $fechaHasta, $codigoEmpleado, $tipo) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT licencia FROM BrasaRecursoHumanoBundle:RhuLicencia licencia "
                . "WHERE (((licencia.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (licencia.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (licencia.fechaDesde >= '$strFechaDesde' AND licencia.fechaDesde <= '$strFechaHasta') "
                . "OR (licencia.fechaHasta >= '$strFechaHasta' AND licencia.fechaDesde <= '$strFechaDesde')) "
                . "AND licencia.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";

        if($tipo == 1) {
            $dql = $dql . "AND (licencia.codigoPagoAdicionalSubtipoFk = 48 OR licencia.codigoPagoAdicionalSubtipoFk = 43)";       
        } else {
            $dql = $dql . "AND licencia.codigoPagoAdicionalSubtipoFk <> 48 AND licencia.codigoPagoAdicionalSubtipoFk <> 43";       
        }
        $objQuery = $em->createQuery($dql);  
        $arLicencias = $objQuery->getResult();         
        $intDiasLicencia = 0;
        foreach ($arLicencias as $arLicencia) {
            $intDiaInicio = 1;            
            $intDiaFin = 30;
            if($arLicencia->getFechaDesde() <  $fechaDesde) {
                $intDiaInicio = 1;                
            } else {
                $intDiaInicio = $arLicencia->getFechaDesde()->format('j');
            }
            if($arLicencia->getFechaHasta() > $fechaHasta) {
                $intDiaFin = 30;                
            } else {
                $intDiaFin = $arLicencia->getFechaHasta()->format('j');
            }            
            $intDiasLicencia += (($intDiaFin - $intDiaInicio)+1);
        }
        if($intDiasLicencia > 30) {
            $intDiasLicencia = 30;
        }
        return $intDiasLicencia;
    }                
}