<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCotizacionDetalleRepository extends EntityRepository {

    public function pendientesCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente;
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
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
    
}