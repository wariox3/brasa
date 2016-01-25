<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoDetalleRepository extends EntityRepository {

    public function listaConsultaDql($codigoCliente = "", $boolEstadoProgramado = "", $boolEstadoFacturado = "") {
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p WHERE pd.codigoPedidoDetallePk <> 0 ";
        if($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;  
        }
        
        if($boolEstadoProgramado == 1 ) {
            $dql .= " AND pd.estadoProgramado = 1";
        }
        if($boolEstadoProgramado == "0") {
            $dql .= " AND pd.estadoProgramado = 0";
        }  
        
        if($boolEstadoFacturado == 1 ) {
            $dql .= " AND pd.estadoFacturado = 1";
        }
        if($boolEstadoFacturado == "0") {
            $dql .= " AND pd.estadoFacturado = 0";
        }        
        return $dql;
    }     
    
    public function pendientesCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente . " AND pd.estadoProgramado = 0";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }
    
    public function listaCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente;
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    
    
    public function pendientesFacturarDql($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente;
        return $dql;                
    }    
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);                
                $em->remove($arPedidoDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigo));
        return count($arDetalles);
    }          
    
}