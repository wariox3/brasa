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
        $floCurso = 0;        
        $arFacturasDetalle = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle();        
        $arFacturasDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalle as $arFacturaDetalle) {
            $floSubTotal +=  $arFacturaDetalle->getPrecio();
        }                           
        
        $arFacturasDetalleCursos = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso();        
        $arFacturasDetalleCursos = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalleCursos as $arFacturasDetalleCurso) {
            $floSubTotal +=  $arFacturasDetalleCurso->getPrecio();
            $floCurso +=  $arFacturasDetalleCurso->getPrecio();
        }        
        $arFactura->setCurso($floCurso);
        $arFactura->setSubTotal($floSubTotal);
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
            if($strResultado == "") {
                $arFactura->setEstadoAutorizado(1);
                $em->persist($arFactura);
                $em->flush();                              
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
        $strResultado = "";
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();        
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);        
        if($arFactura->getEstadoAutorizado() == 1) {
            if($arFactura->getNumero() == 0) {            
                if($arFactura->getCodigoFacturaTipoFk() == 1) {
                    $intNumero = $em->getRepository('BrasaAfiliacionBundle:AfiConsecutivo')->consecutivo(2);
                    $arCuentaCobrarTipo = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->find(3);
                } 
                if($arFactura->getCodigoFacturaTipoFk() == 2) {
                    $intNumero = $em->getRepository('BrasaAfiliacionBundle:AfiConsecutivo')->consecutivo(3);
                    $arCuentaCobrarTipo = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->find(4);
                }    
                $arFactura->setNumero($intNumero);
                
                $arClienteAfiliacion = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
                $arClienteAfiliacion = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->find($arFactura->getCodigoClienteFk());                 
                $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                $arClienteCartera = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arClienteAfiliacion->getNit()));                 
                if ($arClienteCartera == null){
                    $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                    $arClienteCartera->setFormaPagoRel($arClienteAfiliacion->getFormaPagoRel());
                    $arClienteCartera->setCiudadRel($arClienteAfiliacion->getCiudadRel());
                    $arClienteCartera->setNit($arClienteAfiliacion->getNit());
                    $arClienteCartera->setDigitoVerificacion($arClienteAfiliacion->getDigitoVerificacion());
                    $arClienteCartera->setNombreCorto($arClienteAfiliacion->getNombreCorto());
                    $arClienteCartera->setPlazoPago($arClienteAfiliacion->getPlazoPago());
                    $arClienteCartera->setDireccion($arClienteAfiliacion->getDireccion());
                    $arClienteCartera->setTelefono($arClienteAfiliacion->getTelefono());
                    $arClienteCartera->setCelular($arClienteAfiliacion->getCelular());
                    $arClienteCartera->setFax($arClienteAfiliacion->getFax());
                    $arClienteCartera->setEmail($arClienteAfiliacion->getEmail());                    
                    $em->persist($arClienteCartera);                                    
                }                
                $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();                                
                $arCuentaCobrar->setClienteRel($arClienteCartera);
                $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
                $arCuentaCobrar->setFecha($arFactura->getFecha());
                $arCuentaCobrar->setFechaVence($arFactura->getFechaVence());
                $arCuentaCobrar->setNumeroDocumento($arFactura->getNumero());
                $arCuentaCobrar->setValorOriginal($arFactura->getTotal());
                $arCuentaCobrar->setSaldo($arFactura->getTotal());
                $arCuentaCobrar->setPlazo($arClienteAfiliacion->getPlazoPago());                
                $arCuentaCobrar->setAbono(0);
                $em->persist($arCuentaCobrar);                
            }              
            $em->persist($arFactura);
            $em->flush();
        } else {
            $strResultado = "Debe autorizar la factura para imprimir";
        }
        return $strResultado;
    }            
}