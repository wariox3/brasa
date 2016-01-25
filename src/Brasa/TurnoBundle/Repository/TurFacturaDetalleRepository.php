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
                $ar = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigo);                
                $em->remove($ar);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigo));
        return count($arDetalles);
    }          
    
}