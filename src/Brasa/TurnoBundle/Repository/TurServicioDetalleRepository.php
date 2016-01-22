<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioDetalleRepository extends EntityRepository {

    public function listaConsultaDql($codigoServicio = "", $codigoCliente = "") {
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurServicioDetalle sd JOIN sd.servicioRel s WHERE sd.codigoServicioDetallePk <> 0 ";

        if($codigoCliente != '') {
            $dql .= "AND s.codigoClienteFk = " . $codigoCliente . " ";  
        }
        return $dql;
    }     
    
    public function pendientesCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurServicioDetalle sd JOIN sd.servicioRel s "
                . "WHERE s.codigoClienteFk = " . $codigoCliente;
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);                
                $em->remove($arServicioDetalle);                  
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