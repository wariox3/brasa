<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioDetalleRecursoRepository extends EntityRepository {
    public function listaDql($codigoServicioDetalle) {
        $dql   = "SELECT pdr FROM BrasaTurnoBundle:TurServicioDetalleRecurso pdr WHERE pdr.codigoServicioDetalleFk = " . $codigoServicioDetalle;
        $dql .= " ORDER BY pdr.posicion";
        return $dql;
    }    
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $ar = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->find($codigo);                
                $em->remove($ar);                  
            }                                         
            $em->flush();       
        }
        
    }
    
    public function listaConsultaDql() {
        $dql   = "SELECT sdr FROM BrasaTurnoBundle:TurServicioDetalleRecurso sdr WHERE sdr.codigoServicioDetalleRecursoPk <> 0 ";

        /*if($strFechaDesde != '') {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . "'";  
        }
        if($strFechaHasta != '') {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . "'";  
        } 
         * 
         */
        $dql .= " ORDER BY sdr.codigoServicioDetalleFk";
        return $dql;
    }      
}