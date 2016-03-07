<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSoportePagoPeriodoRepository extends EntityRepository {
    public function listaDql() {
        $dql   = "SELECT spp FROM BrasaTurnoBundle:TurSoportePagoPeriodo spp";
        return $dql;
    }
    
    public function liquidar($codigoSoportePagoPeriodo) {        
        $em = $this->getEntityManager();        
        $intRegistros = 0;
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();        
        $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo); 
        $dql   = "SELECT COUNT(sp.codigoSoportePagoPk) as numeroRegistros "
                . "FROM BrasaTurnoBundle:TurSoportePago sp "
                . "WHERE sp.codigoSoportePagoPeriodoFk =  " . $codigoSoportePagoPeriodo;
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();         
        if($arrayResultado) {
            $intRegistros = $arrayResultado[0]['numeroRegistros'];
        }
        $arSoportePagoPeriodo->setRecursos($intRegistros);
        $em->persist($arSoportePagoPeriodo);
        $em->flush();
        return true;
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