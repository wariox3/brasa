<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurFacturaDetalleRepository extends EntityRepository {

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