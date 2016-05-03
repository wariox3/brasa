<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiFacturaRepository extends EntityRepository {  
    
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT f FROM BrasaAfiliacionBundle:AfiFactura f WHERE f.codigoFacturaPk <> 0";
        $dql .= " ORDER BY f.codigoFacturaPk";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }             

    public function liquidar($codigoFactura) {        
        $em = $this->getEntityManager();        
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();        
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);                 
        $floSubTotal = 0;        
        $arFacturasDetalle = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle();        
        $arFacturasDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalle as $arFacturaDetalle) {
            $floSubTotal +=  $arFacturaDetalle->getPrecio();
        }                           
        $arFactura->setTotal($floSubTotal);
        $em->persist($arFactura);
        $em->flush();
        return true;
    }
    
    public function autorizar($codigoFactura) {
        $em = $this->getEntityManager();                
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);            
        $strResultado = "";        
        if($arFactura->getEstadoAutorizado() == 0) {
            if($em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->numeroRegistros($codigoFactura) > 0) {                            
                if($strResultado == "") {
                    $arFactura->setEstadoAutorizado(1);
                    $em->persist($arFactura);
                    $em->flush();                              
                }
              
            } else {
                $strResultado = "Debe adicionar detalles";
            }            
        } else {
            $strResultado = "Ya esta autorizado";
        }        
        return $strResultado;
    } 
    
    public function desAutorizar($codigoFactura) {
        $em = $this->getEntityManager();                
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);            
        $strResultado = "";        
        if($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAnulado() == 0 && $arFactura->getNumero() == 0) {                                            
            $arFactura->setEstadoAutorizado(0);
            $em->persist($arFactura);
            $em->flush();                                                        
        } else {
            $strResultado = "El factura debe estas autorizado y no puede estar anulada o impresa";
        }        
        return $strResultado;
    }    
    
    public function imprimir($codigoFactura) {
        $em = $this->getEntityManager();        
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();        
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);        
        if($arFactura->getEstadoAutorizado() == 1) {
            if($arFactura->getNumero() == 0) {            
                $intNumero = $em->getRepository('BrasaAfiliacionBundle:AfiConsecutivo')->consecutivo(1);
                $arFactura->setNumero($intNumero);
                $arServicio = new \Brasa\AfiliacionBundle\Entity\AfiServicio();
                $arServicio->setClienteRel($arFactura->getClienteRel());
                $arServicio->setVrFactura($arFactura->getTotal());
                $em->persist($arServicio);                
            }   
            $em->persist($arFactura);
            $em->flush();
        } else {
            $strResultado = "Debe autorizar el factura para imprimir";
        }
        return $strResultado;
    }            
}