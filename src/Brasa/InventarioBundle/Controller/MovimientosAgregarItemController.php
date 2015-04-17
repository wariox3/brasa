<?php

namespace Brasa\InventarioBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MovimientosAgregarItemController extends Controller
{   
    
    public function listaItemAction($codigoMovimiento) {                
        $request = $this->getRequest();                   
        $em = $this->getDoctrine()->getManager();
        $arItemes = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();     
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            switch ($request->request->get('OpSubmit')) {
                case "OpBuscar";
                    if($request->request->get('TxtDescripcionItem') != "")    
                        $arItemes = $em->getRepository('BrasaInventarioBundle:InvItem')->BuscarDescripcionItem($request->request->get('TxtDescripcionItem'));     

                    if($request->request->get('TxtCodigoItem') != "")    
                        $arItemes = $em->getRepository('BrasaInventarioBundle:InvItem')->find($request->request->get('TxtCodigoItem'));                     
                    break;      
                case "OpAgregar";                    
                    if (isset($arrControles['TxtCantidad'])) {
                        $intIndice = 0;                
                        foreach ($arrControles['LblCodigoItem'] as $intCodigoItem) {  
                            if($arrControles['TxtCantidad'][$intIndice] != "" && $arrControles['TxtCantidad'][$intIndice] != 0) {
                                $arItem = $this->getDoctrine()->getRepository('BrasaInventarioBundle:InvItem')->find($intCodigoItem);            
                                //$arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($intCodigoItem);                    
                                $intCantidad = $arrControles['TxtCantidad'][$intIndice];

                                $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();                    
                                $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                                $arMovimientoDetalle->setCantidad($intCantidad);

                                if($arMovimiento->getDocumentoRel()->getTipoValor() == 2) {
                                    $arMovimientoDetalle->setVrPrecio($em->getRepository('BrasaInventarioBundle:InvListaPrecioDetalle')->DevPrecio($arMovimiento->getCodigoTerceroFk(), $intCodigoItem));                                    
                                }                       
                                    

                                if($arMovimiento->getDocumentoRel()->getTipoValor() == 1)                        
                                    $arMovimientoDetalle->setVrPrecio($em->getRepository('BrasaInventarioBundle:InvListaCostoDetalle')->DevCosto($arMovimiento->getCodigoTerceroFk(), $intCodigoItem));

                                $arMovimientoDetalle->setItemRel($arItem);
                                if($arMovimiento->getTerceroRel()->getCodigoClasificacionTributariaFk() == 1)
                                    $arMovimientoDetalle->setPorcentajeIva(0);
                                else
                                    $arMovimientoDetalle->setPorcentajeIva($arItem->getPorcentajeIva());
                                $arMovimientoDetalle->setLoteFk("SL");
                                $arMovimientoDetalle->setFechaVencimiento(date_create('2020/12/30')); 
                                $arMovimientoDetalle->setCodigoBodegaFk(1);

                                $em->persist($arMovimientoDetalle);
                                $em->flush();                           
                            }
                            $intIndice++;
                        }
                        $em->getRepository('BrasaInventarioBundle:InvMovimiento')->Liquidar($codigoMovimiento);
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
                    }                    
                    break;      
            }            
        }        
        return $this->render('BrasaInventarioBundle:Movimientos:agregarItem.html.twig', array('arItems' => $arItemes, 'arMovimiento' => $arMovimiento));                                        
    }           
    
    /*
     * Lista los items
     */
    public function documentosControlDetalleAction($codigoMovimiento) {                
        $request = $this->getRequest();                   
        $em = $this->getDoctrine()->getManager(); 
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);        
        $arMovimientosDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevMovimientosDetallesPendientesAfectar($arMovimiento->getCodigoDocumentoFk(), $arMovimiento->getCodigoTerceroFk());               
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $intIndice = 0;
            if (isset($arrControles['LblCodigoDetalle'])) {
                if (count($arrControles['LblCodigoDetalle']) > 0) {                    
                    foreach ($arrControles['LblCodigoDetalle'] as $intCodigoDetalle) {
                        if($arrControles['TxtCantidad'][$intIndice] != "" && $arrControles['TxtCantidad'][$intIndice] != 0) {
                            $intCantidad = $arrControles['TxtCantidad'][$intIndice];
                            $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
                            $arMovimientoDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->find($intCodigoDetalle);                            
                            if(($arMovimientoDetalle->getCantidadAfectada() + $intCantidad) <= $arMovimientoDetalle->getCantidad()) {
                                $arMovimientoDetalleAct = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();                
                                $arMovimientoDetalleAct->setMovimientoRel($arMovimiento);                                
                                $arMovimientoDetalleAct->setItemRel($arMovimientoDetalle->getItemRel());
                                $arMovimientoDetalleAct->setCodigoBodegaFk($arMovimientoDetalle->getCodigoBodegaFk());
                                $arMovimientoDetalleAct->setLoteFk($arMovimientoDetalle->getLoteFk());
                                $arMovimientoDetalleAct->setFechaVencimiento($arMovimientoDetalle->getFechaVencimiento());
                                $arMovimientoDetalleAct->setCantidad($intCantidad);
                                $arMovimientoDetalleAct->setCantidadOperada($intCantidad * $arMovimiento->getDocumentoRel()->getOperacionInventario());
                                
                                $arMovimientoDetalleAct->setPorcentajeIva($arMovimientoDetalle->getPorcentajeIva());
                                $arMovimientoDetalleAct->setPorcentajeDescuento($arMovimientoDetalle->getPorcentajeDescuento());                                
                                $arMovimientoDetalleAct->setCodigoDetalleMovimientoEnlace($arMovimientoDetalle->getCodigoDetalleMovimientoPk());                                                                
                                //La operacion de inventario se le asigna al autorizar
                                //$arMovimientoDetalleAct->setOperacionInventario($em->getRepository('BrasaInventarioBundle:InvItem')->DevOperacionInventario($arMovimientoOrigen->getDocumentoRel()->getOperacionInventario(), $arMovimientoDetalle->getItemMD()->getItemServicio()));
                                
                                //Para heredar el precio del documento control
                                $arDocumentoControl = new \Brasa\InventarioBundle\Entity\InvDocumentoControl();
                                $arDocumentoControl = $em->getRepository('BrasaInventarioBundle:InvDocumentoControl')->find(array('codigoDocumentoPadrePk' => $arMovimiento->getCodigoDocumentoFk(), 'codigoDocumentoHijoPk' => $arMovimientoDetalle->getMovimientoRel()->getCodigoDocumentoFk()));
                                if($arDocumentoControl->getHeredaPrecio() == 1) {
                                    $arMovimientoDetalleAct->setPrecio($arMovimientoDetalle->getPrecio());
                                    $arMovimientoDetalleAct->setPorcentajeDescuento($arMovimientoDetalle->getPorcentajeDescuento());                                                                                                        
                                }
                                else {
                                    if($arMovimiento->getDocumentoRel()->getTipoValor() == 2)                        
                                        $arMovimientoDetalleAct->setVrPrecio($em->getRepository('BrasaInventarioBundle:InvListaPrecioDetalle')->DevPrecio($arMovimiento->getCodigoTerceroFk(), $arMovimientoDetalle->getCodigoItemFk()));

                                    if($arMovimiento->getDocumentoRel()->getTipoValor() == 1)                        
                                        $arMovimientoDetalleAct->setVrPrecio($em->getRepository('BrasaInventarioBundle:InvListaCostoDetalle')->DevCosto($arMovimiento->getCodigoTerceroFk(), $arMovimientoDetalle->getCodigoItemFk()));                                    
                                }
                                if($arMovimientoDetalle->getMovimientoRel()->getDocumentoRel()->getCodigoDocumentoTipoFk() == 9) {
                                    $arMovimientoDetalleAct->setAfectarRemision(1);
                                }
                                $em->persist($arMovimientoDetalleAct);
                                $em->flush();
                                $arMovimientoDetalle->setCantidadAfectada($arMovimientoDetalle->getCantidadAfectada() + $intCantidad);
                                $em->persist($arMovimientoDetalle);
                                $em->flush();                                
                            }
                        }                            
                        $intIndice++;
                    }
                    
                    $em->getRepository('BrasaInventarioBundle:InvMovimiento')->Liquidar($codigoMovimiento);
                    
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }                
            }                          
        }
        
        return $this->render('BrasaInventarioBundle:Movimientos:documentosControlDetalle.html.twig', 
                array(
                    'arMovimientosDetalle' => $arMovimientosDetalle,
                    'arMovimiento' => $arMovimiento));                                        
    }    
    
}
