<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioDetalleRepository extends EntityRepository {

    public function listaDql($codigoServicio = "") {
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurServicioDetalle sd WHERE sd.codigoServicioDetallePk <> 0 ";
        
        if($codigoServicio != '') {
            $dql .= "AND sd.codigoServicioFk = " . $codigoServicio . " ";  
        }        
        $dql .= " ORDER BY sd.codigoPuestoFk";
        return $dql;
    }    
    
    public function listaConsultaDql($codigoServicio = "", $codigoCliente = "") {
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurServicioDetalle sd JOIN sd.servicioRel s WHERE sd.codigoServicioDetallePk <> 0 ";                
        if($codigoCliente != '') {
            $dql .= "AND s.codigoClienteFk = " . $codigoCliente;  
        }
        return $dql;
    }     
    
    public function pendientesCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurServicioDetalle sd JOIN sd.servicioRel s "
                . "WHERE s.codigoClienteFk = " . $codigoCliente . " AND s.estadoCerrado = 0";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {
                $intNumeroRegistros = 0;
                $dql   = "SELECT COUNT(pd.codigoPedidoDetallePk) as numeroRegistros FROM BrasaTurnoBundle:TurPedidoDetalle pd "
                        . "WHERE pd.codigoServicioDetalleFk = " . $codigo;
                $query = $em->createQuery($dql);
                $arrPedidoDetalles = $query->getSingleResult(); 
                if($arrPedidoDetalles) {
                    $intNumeroRegistros = $arrPedidoDetalles['numeroRegistros'];
                }
                if($intNumeroRegistros <= 0) {
                    $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);                
                    $em->remove($arServicioDetalle);                                      
                }
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigo));
        return count($arDetalles);
    }          
    
}