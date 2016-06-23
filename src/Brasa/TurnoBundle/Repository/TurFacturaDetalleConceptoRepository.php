<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurFacturaDetalleConceptoRepository extends EntityRepository {

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
                $ar = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalleConcepto')->find($codigo);  
                if($ar->getCodigoPedidoDetalleConceptoFk() != '') {
                    $arPedidoDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto();
                    $arPedidoDetalleConcepto = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->find($ar->getCodigoPedidoDetalleConceptoFk());
                    $arPedidoDetalleConcepto->setEstadoFacturado(0);
                    $em->persist($arPedidoDetalleConcepto);
                }
                $em->remove($ar);                  
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