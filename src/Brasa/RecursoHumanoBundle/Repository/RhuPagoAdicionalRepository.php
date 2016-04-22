<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoAdicionalRepository extends EntityRepository {
    
    public function listaDql($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pa FROM BrasaRecursoHumanoBundle:RhuPagoAdicional pa WHERE pa.codigoProgramacionPagoFk =  " . $codigoProgramacionPago . " AND pa.permanente = 0";
        $dql .= " ORDER BY pa.codigoProgramacionPagoFk DESC";
        return $dql;
    } 
    
    public function listaTiempoDql($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pa FROM BrasaRecursoHumanoBundle:RhuPagoAdicional pa WHERE pa.codigoProgramacionPagoFk =  " . $codigoProgramacionPago . " AND pa.permanente = 0 AND pa.tipoAdicional = 4 ";
        $dql .= " ORDER BY pa.codigoProgramacionPagoFk DESC";
        return $dql;
    } 
    
    public function listaValorDql($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pa FROM BrasaRecursoHumanoBundle:RhuPagoAdicional pa WHERE pa.codigoProgramacionPagoFk =  " . $codigoProgramacionPago . " AND pa.permanente = 0 AND pa.tipoAdicional <> 4 ";
        $dql .= " ORDER BY pa.codigoProgramacionPagoFk DESC";
        return $dql;
    } 
    
    public function listaAdicionalesDql($strIdentificacion = "", $aplicarDiaLaborado = "", $codigoCentroCosto = "", $codigoPagoConcepto = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pa,e FROM BrasaRecursoHumanoBundle:RhuPagoAdicional pa JOIN pa.empleadoRel e WHERE pa.codigoPagoAdicionalPk <> 0 AND pa.permanente = 1";   
        
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($aplicarDiaLaborado == 1 ) {
            $dql .= " AND pa.aplicaDiaLaborado = 1";
        }
        if($aplicarDiaLaborado == 0 ) {
            $dql .= " AND pa.aplicaDiaLaborado = 0";
        }
        if($codigoCentroCosto != "" || $codigoCentroCosto != 0 ) {
            $dql .= " AND e.codigoCentroCostoFk = " . $codigoCentroCosto;
        }
        if($codigoPagoConcepto != "" || $codigoPagoConcepto != 0 ) {
            $dql .= " AND pa.codigoPagoConceptoFk = " . $codigoPagoConcepto;
        }
        $dql .= " ORDER BY pa.codigoPagoAdicionalPk DESC";
        return $dql;
    }
    
    public function listaConsultaDql($strNombre = "", $strIdentificacion = "", $codigoCentroCosto = "", $aplicaDiaLaborado) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pa,e FROM BrasaRecursoHumanoBundle:RhuPagoAdicional pa JOIN pa.empleadoRel e WHERE pa.codigoPagoAdicionalPk <> 0 AND pa.permanente = 0";   
        if($strNombre != "" ) {
            $dql .= " AND e.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($codigoCentroCosto != "" || $codigoCentroCosto != 0 ) {
            $dql .= " AND e.codigoCentroCostoFk = " . $codigoCentroCosto;
        }
        
        if($aplicaDiaLaborado == 1 ) {
            $dql .= " AND pa.aplicaDiaLaborado = 1";
        }
        if($aplicaDiaLaborado == 0 ) {
            $dql .= " AND pa.aplicaDiaLaborado = 0";
        }
        return $dql;
    }
    
    public function programacionPago($codigoEmpleado = "", $codigoProgramacionPago = "") {
        $em = $this->getEntityManager();
        $dql = "SELECT pa FROM BrasaRecursoHumanoBundle:RhuPagoAdicional pa "
                . "WHERE (pa.codigoProgramacionPagoFk = $codigoProgramacionPago OR pa.permanente = 1) AND pa.codigoEmpleadoFk = $codigoEmpleado";
        $objQuery = $em->createQuery($dql);  
        $arPagosAdicionales = $objQuery->getResult();         
        return $arPagosAdicionales;
    } 
 
    public function bonificacionNoPrestacional($codigoEmpleado, $codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pa.valor) as valor FROM BrasaRecursoHumanoBundle:RhuPagoAdicional pa "
               . "WHERE (pa.codigoProgramacionPagoFk = $codigoProgramacionPago OR pa.permanente = 1) AND pa.codigoEmpleadoFk = $codigoEmpleado AND pa.prestacional = 0 AND pa.tipoAdicional = 1";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $floValor = $arrayResultado[0]['valor'];
        return $floValor;
    }     
}