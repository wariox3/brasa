<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiContratoRepository extends EntityRepository {    
    
    public function listaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiContrato c WHERE c.codigoContratoPk <> 0";
        $dql .= " ORDER BY c.codigoContratoPk";
        return $dql;
    }            
    
    public function listaDetalleDql($codigoEmpleado) {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiContrato c WHERE c.codigoEmpleadoFk = " . $codigoEmpleado;
        return $dql;
    }                
    
    public function eliminar($arrSeleccionados,$codigoEmpleado) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($codigo);
                $em->remove($ar);
                $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigoEmpleado);
                $arEmpleado->setCodigoContratoActivo(null);
                $em->persist($arEmpleado);
            }
            $em->flush();
        }
    }  
    
    public function contratosPeriodo($fechaDesde = "", $fechaHasta = "", $codigoCliente = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiContrato c "
                ." WHERE (c.fechaHasta >= '" . $fechaDesde . "' OR c.indefinido = 1) "
                . "AND c.fechaDesde <= '" . $fechaHasta . "' AND c.codigoClienteFk=" . $codigoCliente;
        $query = $em->createQuery($dql);        
        $arContratos = $query->getResult();        
        return $arContratos;
    }    
}