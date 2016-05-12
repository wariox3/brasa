<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurFacturaRepository extends EntityRepository {
    
    public function listaDql($numeroFactura = "", $codigoCliente = "", $boolEstadoAutorizado = "", $strFechaDesde = "", $strFechaHasta = "", $boolEstadoAnulado = "") {
        $dql   = "SELECT f FROM BrasaTurnoBundle:TurFactura f WHERE f.codigoFacturaPk <> 0";
        if($numeroFactura != "") {
            $dql .= " AND f.numero = " . $numeroFactura;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND f.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND f.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND f.estadoAutorizado = 0";
        } 
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND f.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND f.estadoAnulado = 0";
        }        
        if($strFechaDesde != "") {
            $dql .= " AND f.fecha >= '" . $strFechaDesde . " 00:00:00'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND f.fecha <= '" . $strFechaHasta . " 23:59:59'";
        }    
        $dql .= " ORDER BY f.fecha DESC, f.numero DESC";
        return $dql;
    }           
    
    public function pedidoMaestroDql() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoTipoFk = 2";
        return $dql;
    }    
    
    public function pedidoSinProgramarDql($strFechaDesde = '', $strFechaHasta = '') {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoTipoFk = 1 "
                . "AND p.programado = 0 ";

        if($strFechaDesde != '') {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . "'";  
        }
        if($strFechaHasta != '') {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . "'";  
        }        
        return $dql;
    }        
    
    public function liquidar($codigoFactura) {        
        $em = $this->getEntityManager();        
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();        
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura); 
        $floSubTotal = 0;
        $floSubTotalConceptos = 0;
        $floBaseAIU = 0;
        $floIva = 0;
        $floIvaConceptos = 0;
        $floRetencionFuente = 0;
        $floTotal = 0;
        $arFacturasDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();        
        $arFacturasDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));                 
        foreach ($arFacturasDetalle as $arFacturaDetalle) {
            $floSubTotal +=  $arFacturaDetalle->getVrPrecio();
        }
        $arFacturasDetalleConceptos = new \Brasa\TurnoBundle\Entity\TurFacturaDetalleConcepto();        
        $arFacturasDetalleConceptos = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalleConcepto')->findBy(array('codigoFacturaFk' => $codigoFactura));                         
        foreach ($arFacturasDetalleConceptos as $arFacturasDetalleConcepto) {            
            $floSubTotalConceptos += $arFacturasDetalleConcepto->getSubtotal();
            $floIvaConceptos += $arFacturasDetalleConcepto->getIva();
        }
        
        $floBaseAIU = ($floSubTotal * 10) / 100;
        $floIva = (($floBaseAIU * 16 ) / 100) + $floIvaConceptos;
        $floBaseRetencionFuente = $arConfiguracion->getBaseRetencionFuente();
        if(($floBaseAIU+$floSubTotalConceptos) >= $floBaseRetencionFuente) {
            $floRetencionFuente = (($floBaseAIU+$floSubTotalConceptos) * 2 ) / 100;
        }                

        $floSubTotal += $floSubTotalConceptos;
        $floIva += $floIvaConceptos;
        $floTotal = $floSubTotal + $floIva - $floRetencionFuente;
        $arFactura->setVrBaseAIU($floBaseAIU);
        $arFactura->setVrSubtotal($floSubTotal);
        $arFactura->setVrRetencionFuente($floRetencionFuente);
        $arFactura->setVrIva($floIva);
        $arFactura->setvrTotal($floTotal);
        $em->persist($arFactura);
        $em->flush();
        return true;
    }
    
    public function festivo($arFestivos, $dateFecha) {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }    

    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {                
                if($em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->numeroRegistros($codigo) <= 0) {
                    $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigo);                    
                    if($arFactura->getEstadoAutorizado() == 0 && $arFactura->getNumero() == 0) {
                        $em->remove($arFactura);                    
                    }                     
                }               
            }
            $em->flush();
        }
    }  
    
    public function autorizar($codigoFactura) {
        $em = $this->getEntityManager();                
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);            
        $strResultado = "";        
        if($arFactura->getEstadoAutorizado() == 0) {
            if($em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->numeroRegistros($codigoFactura) > 0) {            
                // Validar valor pendiente
                $dql   = "SELECT fd.codigoPedidoDetalleFk, SUM(fd.vrPrecio) as vrPrecio FROM BrasaTurnoBundle:TurFacturaDetalle fd "
                        . "WHERE fd.codigoFacturaFk = " . $codigoFactura . " "
                        . "GROUP BY fd.codigoPedidoDetalleFk";
                $query = $em->createQuery($dql);
                $arrFacturaDetalles = $query->getResult();
                foreach ($arrFacturaDetalles as $arrFacturaDetalle) {
                    $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arrFacturaDetalle['codigoPedidoDetalleFk']);                
                    $floPrecio = $arrFacturaDetalle['vrPrecio'];
                    if(round($arPedidoDetalle->getVrTotalDetallePendiente()) < round($floPrecio)) {
                        $strResultado .= "Para el detalle de pedido " . $arrFacturaDetalle['codigoPedidoDetalleFk'] . " no puede facturar mas de lo pendiente valor a facturar = " . $floPrecio . " valor pendiente = " . $arPedidoDetalle->getVrTotalDetallePendiente();
                    }
                }

                
                if($strResultado == "") {
                    $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                    $arFacturaDetalles = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));                
                    foreach ($arFacturaDetalles as $arFacturaDetalle) {
                        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arFacturaDetalle->getCodigoPedidoDetalleFk());                
                        $floValorTotalPendiente = $arPedidoDetalle->getVrTotalDetallePendiente() - $arFacturaDetalle->getVrPrecio();
                        $arPedidoDetalle->setVrTotalDetallePendiente($floValorTotalPendiente);
                        if($floValorTotalPendiente <= 0) {
                            $arPedidoDetalle->setEstadoFacturado(1);
                        }
                        $em->persist($arPedidoDetalle);
                    }
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
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);            
        $strResultado = "";        
        if($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAnulado() == 0 && $arFactura->getNumero() == 0) {                                            
            $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
            $arFacturaDetalles = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));                
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arFacturaDetalle->getCodigoPedidoDetalleFk());                
                $floValorTotalPendiente = $arPedidoDetalle->getVrTotalDetallePendiente() + $arFacturaDetalle->getVrPrecio();
                $arPedidoDetalle->setVrTotalDetallePendiente($floValorTotalPendiente);                
                $arPedidoDetalle->setEstadoFacturado(0);                
                $em->persist($arPedidoDetalle);
            }
            $arFactura->setEstadoAutorizado(0);
            $em->persist($arFactura);
            $em->flush();                                                        
        } else {
            $strResultado = "La factura debe estas autorizada y no puede estar anulada o impresa";
        }        
        return $strResultado;
    }     
    
    public function imprimir($codigoFactura) {
        $em = $this->getEntityManager();
        
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();        
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);        
        if($arFactura->getEstadoAutorizado() == 1) {
            if($arFactura->getNumero() == 0) {            
                $intNumero = $em->getRepository('BrasaTurnoBundle:TurConsecutivo')->consecutivo(2);
                $arFactura->setNumero($intNumero);
                $arFactura->setFecha(new \DateTime('now'));                
                $dateFechaVence = $objFunciones->sumarDiasFecha($arFactura->getClienteRel()->getPlazoPago(), $arFactura->getFecha());
                $arFactura->setFechaVence($dateFechaVence);
                $arClienteTurno = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arClienteTurno = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($arFactura->getCodigoClienteFk()); 
                $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                $arClienteCartera = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arClienteTurno->getNit())); 
                if ($arClienteCartera == null){
                    $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                    $arClienteCartera->setAsesorRel($arClienteTurno->getAsesorRel());
                    $arClienteCartera->setFormaPagoRel($arClienteTurno->getFormaPagoRel());
                    $arClienteCartera->setCiudadRel($arClienteTurno->getCiudadRel());
                    $arClienteCartera->setNit($arClienteTurno->getNit());
                    $arClienteCartera->setDigitoVerificacion($arClienteTurno->getDigitoVerificacion());
                    $arClienteCartera->setNombreCorto($arClienteTurno->getNombreCorto());
                    $arClienteCartera->setPlazoPago($arClienteTurno->getPlazoPago());
                    $arClienteCartera->setDireccion($arClienteTurno->getDireccion());
                    $arClienteCartera->setTelefono($arClienteTurno->getTelefono());
                    $arClienteCartera->setCelular($arClienteTurno->getCelular());
                    $arClienteCartera->setFax($arClienteTurno->getFax());
                    $arClienteCartera->setEmail($arClienteTurno->getEmail());
                    $arClienteCartera->setUsuario($arFactura->getUsuario());
                    $em->persist($arClienteCartera);                                    
                }
                    $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                    $arCuentaCobrarTipo = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->find(2);
                    $arCuentaCobrar->setClienteRel($arClienteCartera);
                    $arCuentaCobrar->setAsesorRel($arClienteTurno->getAsesorRel());
                    $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
                    $arCuentaCobrar->setFecha($arFactura->getFecha());
                    $arCuentaCobrar->setFechaVence($arFactura->getFechaVence());
                    $arCuentaCobrar->setNumeroDocumento($arFactura->getNumero());
                    $arCuentaCobrar->setValorOriginal($arFactura->getVrTotal());
                    $arCuentaCobrar->setSaldo($arFactura->getVrTotal());
                    $arCuentaCobrar->setPlazo($arClienteTurno->getPlazoPago());
                    $arCuentaCobrar->setAbono(0);
                    $em->persist($arCuentaCobrar);             
            }   
            $em->persist($arFactura);
            $em->flush();
        } else {
            $strResultado = "Debe autorizar la factura para imprimirla";
        }
        return $strResultado;
    }
    
    public function anular($codigoFactura) {
        $em = $this->getEntityManager();   
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();        
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);            
        $strResultado = "";        
        if($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAnulado() == 0 && $arFactura->getNumero() != 0) {
            $boolAnular = TRUE;
            $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
            $arFacturaDetalles = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));      
            //Devolver saldo a los pedidos
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                $arPedidoDetalleAct = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                $arPedidoDetalleAct = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arFacturaDetalle->getCodigoPedidoDetalleFk());                                                    
                $floValorTotalPendiente = $arPedidoDetalleAct->getVrTotalDetallePendiente() + $arFacturaDetalle->getVrPrecio();
                $arPedidoDetalleAct->setVrTotalDetallePendiente($floValorTotalPendiente);                
                $arPedidoDetalleAct->setEstadoFacturado(0);                
                $em->persist($arPedidoDetalleAct);                
            } 
            //Actualizar los detalles de la factura a cero
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                $arFacturaDetalleAct = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                $arFacturaDetalleAct = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($arFacturaDetalle->getCodigoFacturaDetallePk());                                        
                $arFacturaDetalle->setVrPrecio(0);
                $arFacturaDetalle->setCantidad(0);
                $em->persist($arFacturaDetalle);
            }
            $arFactura->setVrSubtotal(0);
            $arFactura->setVrRetencionFuente(0);
            $arFactura->setVrBaseAIU(0);
            $arFactura->setVrIva(0);
            $arFactura->setVrTotal(0);
            $arFactura->setEstadoAnulado(1);
            $em->persist($arFactura);
            $em->flush();      
                           
        } else {
            $strResultado = "La factura debe estar autorizada, no puede estar previamente anulada, debe estar impresa";
        }        
        return $strResultado;
    }        
}