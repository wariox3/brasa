<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCierreMesServicioRepository extends EntityRepository {

    public function listaDql($codigoCliente = "", $mes = "") {
        $dql   = "SELECT cms FROM BrasaTurnoBundle:TurCierreMesServicio cms WHERE cms.codigoCierreMesServicioPk <> 0 ";
        if($codigoCliente != "") {
            $dql .= " AND cms.codigoClienteFk = " . $codigoCliente;  
        }
        if($mes != "") {
            $dql .= " AND cms.mes = " . $mes;  
        }        
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {                                
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigo);                    
                if($arSoportePagoPeriodo->getEstadoGenerado() == 0) {
                    $em->remove($arSoportePagoPeriodo);                    
                }                                     
            }
            $em->flush();
        }
    }     

}