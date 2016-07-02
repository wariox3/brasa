<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurFacturaDetalleRepository extends EntityRepository {

    public function listaDql($codigoFactura = "") {
        $dql   = "SELECT fd FROM BrasaTurnoBundle:TurFacturaDetalle fd WHERE fd.codigoFacturaDetallePk <> 0 ";
        
        if($codigoFactura != '') {
            $dql .= "AND fd.codigoFacturaFk = " . $codigoFactura . " ";  
        }        
        $dql .= " ORDER BY fd.codigoGrupoFacturacionFk, fd.codigoPuestoFk";
        return $dql;
    }     
    
    public function listaCliente($codigoCliente, $codigoFactura = "") {
        $dql   = "SELECT fd FROM BrasaTurnoBundle:TurFacturaDetalle fd JOIN fd.facturaRel f WHERE f.codigoClienteFk =  " . $codigoCliente . " ";
        
        if($codigoFactura != '') {
            $dql .= "AND fd.codigoFacturaFk = " . $codigoFactura . " ";  
        }        
        $dql .= " ORDER BY fd.codigoFacturaDetallePk DESC";
        return $dql;
    }     
    
    public function pendientesCliente($codigoTercero) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoTerceroFk = " . $codigoTercero . " AND p.codigoPedidoTipoFk = 1";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }
    
    public function eliminar($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                                
                $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigo);  
                /*$arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arFacturaDetalle->getCodigoPedidoDetalleFk());  
                $arPedidoDetalle->setEstadoFacturado(0);
                $em->persist($arPedidoDetalle);*/
                if($arFacturaDetalle->getCodigoPedidoDetalleConceptoFk()) {
                    $arPedidoDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto();
                    $arPedidoDetalleConcepto = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->find($arFacturaDetalle->getCodigoPedidoDetalleConceptoFk());  
                    $arPedidoDetalleConcepto->setEstadoFacturado(0);
                    $em->persist($arPedidoDetalleConcepto);
                }
                $em->remove($arFacturaDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(fd.codigoFacturaDetallePk) as numeroRegistros FROM BrasaTurnoBundle:TurFacturaDetalle fd "
                . "WHERE fd.codigoFacturaFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrFacturaDetalles = $query->getSingleResult(); 
        if($arrFacturaDetalles) {
            $intNumeroRegistros = $arrFacturaDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }          
    
}