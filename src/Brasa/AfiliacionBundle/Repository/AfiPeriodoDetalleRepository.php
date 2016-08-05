<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiPeriodoDetalleRepository extends EntityRepository {  
    
    public function listaDql($codigoPeriodo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaAfiliacionBundle:AfiPeriodoDetalle pd WHERE pd.codigoPeriodoDetallePk <> 0";
        if($codigoPeriodo != "") {
            $dql .= " AND pd.codigoPeriodoFk =" . $codigoPeriodo;
        }
        $dql .= " ORDER BY pd.codigoPeriodoDetallePk";
        return $dql;
    }            
    
    public function listaConsultaDql($codigo = '', $codigoCliente = '', $estadoFacturado = '',$strDesde = "", $strHasta = "") {
        //$em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaAfiliacionBundle:AfiPeriodoDetalle pd JOIN pd.periodoRel p WHERE pd.codigoPeriodoDetallePk <> 0";
        if($codigoCliente != '') {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;
        }
        if($estadoFacturado == 1 ) {
            $dql .= " AND p.estadoFacturado = 1";
        }
        if($estadoFacturado == "0") {
            $dql .= " AND p.estadoFacturado = 0";
        } 
        if($strDesde != "") {
            $dql .= " AND p.fechaDesde >='" . date_format($strDesde, ('Y-m-d')) . "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaDesde <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        
        $dql .= " ORDER BY pd.codigoPeriodoDetallePk";
        return $dql;
    }    
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }     
        
}