<?php

namespace Brasa\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MovimientosController extends Controller
{
    public function listaAction($codigoDocumento) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();                
        $arMovimientos = new \Brasa\InventarioBundle\Entity\InvMovimientos();                
        $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumentos')->find($codigoDocumento);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $objChkFecha = NULL;
            if (isset($arrControles['ChkFecha']))
                $objChkFecha = $arrControles['ChkFecha'];
            switch ($request->request->get('OpSubmit')) {
                case "OpAutorizar";
                    foreach ($arrSeleccionados AS $codigoMovimiento)
                        $em->getRepository('BrasaInventarioBundle:InvMovimientos')->Autorizar($codigoMovimiento);
                    break;

                case "OpImprimir";
                    foreach ($arrSeleccionados AS $codigoMovimiento)
                        $em->getRepository('BrasaInventarioBundle:InvMovimientos')->Imprimir($codigoMovimiento);
                    break;

                case "OpEliminar";
                    foreach ($arrSeleccionados AS $codigoMovimiento) {
                        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimientos();
                        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->find($codigoMovimiento);
                        if ($arMovimiento->getEstadoAutorizado() == 0) {
                            if ($em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->DevNroDetallesMovimiento($codigoMovimiento) <= 0) {
                                $em->remove($arMovimiento);
                                $em->flush();
                            }
                        }
                    }
                    break;
                case "OpBuscar";
                    $arMovimientos = new \Brasa\InventarioBundle\Entity\InvMovimientos();
                    $arMovimientos = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->DevMovimientosFiltro(
                            $codigoDocumento, 
                            $arrControles['TxtCodigoMovimiento'], 
                            $arrControles['TxtNumeroMovimiento'], 
                            $objFunciones->DevCodigoTercero($arrControles['TxtCodigoTercero']), 
                            $objChkFecha, 
                            $arrControles['TxtFechaDesde'], 
                            $arrControles['TxtFechaHasta'],
                            $arrControles['CboAutorizado'],
                            $arrControles['CboImpreso']);
                    break;
            }
        } else {
            $arMovimientos = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->findBy(array('codigoDocumentoFk' => $codigoDocumento, 'estadoImpreso' => '0'));
        }                    

        return $this->render('BrasaInventarioBundle:Movimientos:lista.html.twig', array(
            'arMovimientos' => $arMovimientos, 
            'arDocumento' => $arDocumento));
    }
    
    /**
     * Crear un nuevo movimiento
     * @return type
     */
    public function nuevoAction($codigoDocumento, $codigoMovimiento = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();        
        $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumentos();
        $arDocumentoConfiguracion = new \Brasa\InventarioBundle\Entity\InvDocumentosConfiguracion();
        $arDocumentoConfiguracion = $em->getRepository('BrasaInventarioBundle:InvDocumentosConfiguracion')->find($codigoDocumento);


        if ($request->getMethod() == 'POST') {
            //$objMensaje = $this->get('mi_mensaje');
            //$objMensaje->Mensaje("error", "Hola mundo", $this);
            
            if (($request->request->get('TxtCodigoMovimiento'))) {
                $arMovimientoNuevo = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->find($request->request->get('TxtCodigoMovimiento'));
            }
                
            if (!($request->request->get('TxtCodigoMovimiento'))) {
                $arMovimientoNuevo = new \Brasa\InventarioBundle\Entity\InvMovimientos();
            }
                
            $arTercero = $this->getDoctrine()->getRepository('BrasaGeneralBundle:GenTerceros')->find($request->request->get('TxtCodigoTercero'));            
            
            $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumentos')->find($request->request->get('iddocumento'));

            $arMovimientoNuevo->setDocumentoRel($arDocumento);
            $arMovimientoNuevo->setDocumentoTipoRel($arDocumento->getDocumentoTipoRel());
            $arMovimientoNuevo->setTerceroRel($arTercero);
            $arMovimientoNuevo->setSoporte($request->request->get('TxtSoporte'));
            $arMovimientoNuevo->setComentarios($request->request->get('TxtComentarios'));
            $arMovimientoNuevo->setFecha(date_create(date('Y-m-d H:i:s')));
            if($request->request->get('TxtVrFletes')) {
                $arMovimientoNuevo->setVrFletes($request->request->get('TxtVrFletes'));                
            }                
            if($request->request->get('TxtFecha1')) {
                $arMovimientoNuevo->setFecha1(date_create($request->request->get('TxtFecha1')));                
            }                
            if($request->request->get('TxtFecha2')) {
                $arMovimientoNuevo->setFecha2(date_create($request->request->get('TxtFecha2')));                
            }                
            
            if($arDocumento->getTipoTercero() == 1) {
                $arMovimientoNuevo->setFormaPagoRel($arTercero->getFormaPagoClienteRel());
            } elseif($arDocumento->getTipoTercero() == 2) {
                $arMovimientoNuevo->setFormaPagoRel($arTercero->getFormaPagoProveedorRel());
            }
                            
            if($request->request->get('CboDirecciones')) {
                $arDireccion = $em->getRepository('BrasaFrontEndBundle:GenTercerosDirecciones')->find($request->request->get('CboDirecciones'));
                $arMovimientoNuevo->setDireccionRel($arDireccion);
            }

            $em->persist($arMovimientoNuevo);
            $em->flush();                
            return $this->redirect($this->generateUrl('brs_inv_movientos_detalle', array('codigoMovimiento' => $arMovimientoNuevo->getCodigoMovimientoPk())));
        }
        
        $arMovimiento = null;
        $arTercerosDirecciones = null;        
        if ($codigoMovimiento != null && $codigoMovimiento != "" && $codigoMovimiento != 0) {
            $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->find($codigoMovimiento);                    
            //$arTercerosDirecciones = $em->getRepository('BrasaFrontEndBundle:GenTercerosDirecciones')->findBy(array('codigoTerceroFk' => $arMovimiento->getCodigoTerceroFk()));        
        }       
            
        
        return $this->render('BrasaInventarioBundle:Movimientos:nuevo.html.twig', array(
            'codigoDocumento' => $codigoDocumento,
            'arDocumentoConfiguracion' => $arDocumentoConfiguracion,
            'arMovimiento' => $arMovimiento,
            'arTercerosDirecciones' => $arTercerosDirecciones));
    }    
    
    /**
     * Lista los movimientos detalle (Detalles) segun encabezado - Filtro
     */
    public function detalleAction($codigoMovimiento) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mi_mensaje');
        $arMovimientosDetallesFrm = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();       
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimientos();
        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->find($codigoMovimiento);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrDescuentosFinancierosSeleccionados = $request->request->get('ChkSeleccionarDescuentoFinanciero');
            switch ($request->request->get('OpSubmit')) {
                case "OpAutorizar";
                    $strResultado = $this->GuardarCambios($arrControles);
                    if ($strResultado != "")
                        $objMensaje->Mensaje("error", $strResultado, $this);
                    else {
                        $strResultado = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->Autorizar($codigoMovimiento);
                        if ($strResultado != "")
                            $objMensaje->Mensaje("error", "No se autorizo el movimiento: " . $strResultado, $this);
                    }
                    break;

                case "OpDesAutorizar";
                    $varDesautorizar = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->DesAutorizar($codigoMovimiento);
                    if ($varDesautorizar != "")
                        $objMensaje->Mensaje("error", "No se desautorizo el movimiento: " . $varDesautorizar, $this);
                    break;

                case "OpAnular";
                    $varAnular = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->Anular($codigoMovimiento);
                    if ($varAnular != "")
                        $objMensaje->Mensaje("error", "No se anulo el movimiento: " . $varAnular, $this);
                    break;

                case "OpImprimir";
                    $strResultado = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->Imprimir($codigoMovimiento);
                    if ($strResultado == "") {
                        $Impresion = new Control_Impresion_Inventario();
                        $Impresion->CounstruirImpresion($em, $arMovimiento);
                    }
                    else
                        $objMensaje->Mensaje("error", "No se pudo imprimir el documento: " . $strResultado, $this);
                    break;

                case "OpEliminar";
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoMovimientoDetalle) {
                            $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
                            $arMovimientoDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->find($codigoMovimientoDetalle);
                            if ($arMovimientoDetalle->getCodigoDetalleMovimientoEnlace() != "") {
                                $arMovimientoDetalleEnlace = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
                                $arMovimientoDetalleEnlace = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->find($arMovimientoDetalle->getCodigoDetalleMovimientoEnlace());
                                $arMovimientoDetalleEnlace->setCantidadAfectada($arMovimientoDetalleEnlace->getCantidadAfectada() - $arMovimientoDetalle->getCantidad());
                                $em->persist($arMovimientoDetalleEnlace);
                                $em->flush();
                            }
                            $em->remove($arMovimientoDetalle);
                            $em->flush();
                        }
                        $em->getRepository('BrasaInventarioBundle:InvMovimientos')->Liquidar($codigoMovimiento);
                    }
                    break;

                case "OpActualizarDetalles";
                    $strResultado = $this->GuardarCambios($arrControles);
                    if ($strResultado != "")
                        $objMensaje->Mensaje("error", $strResultado, $this);
                    else
                        $em->getRepository('BrasaInventarioBundle:InvMovimientos')->Liquidar($codigoMovimiento);
                    break;

                case "OpCerrarDetalles";
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoMovimientoDetalle) {
                            $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
                            $arMovimientoDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->find($codigoMovimientoDetalle);
                            if ($arMovimientoDetalle->getEstadoCerrado() == 0) {
                                $arMovimientoDetalle->setEstadoCerrado(1);
                                $em->persist($arMovimientoDetalle);
                                $em->flush();
                            }
                        }
                    }
                    break;

                case "OpAgregarItem";
                    if(isset($arrControles['TxtCodigoItem'])) {
                        if ($arrControles['TxtCodigoItem'] != "") {
                            $arItem = new \Brasa\InventarioBundle\Entity\InvItem();
                            $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findBy(array('codigoBarras' => $arrControles['TxtCodigoItem']));
                            //$arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($arItem[0]);
                            if (count($arItem) > 0) {
                                $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimientos();
                                $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->find($codigoMovimiento);

                                $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
                                $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                                $arMovimientoDetalle->setCantidad(1);

                                if ($arMovimiento->getDocumentoRel()->getTipoValor() == 2)
                                    $arMovimientoDetalle->setPrecio($em->getRepository('BrasaInventarioBundle:InvListasPreciosDetalles')->DevPrecio($arMovimiento->getCodigoTerceroFk(), $arItem[0]->getCodigoItemPk()));

                                if ($arMovimiento->getDocumentoRel()->getTipoValor() == 1)
                                    $arMovimientoDetalle->setPrecio($em->getRepository('BrasaInventarioBundle:InvListasCostosDetalles')->DevCosto($arMovimiento->getCodigoTerceroFk(), $arItem[0]->getCodigoItemPk()));

                                $arMovimientoDetalle->setLoteFk("SL");
                                $arMovimientoDetalle->setFechaVencimiento(date_create('2020/12/30'));
                                $arMovimientoDetalle->setCodigoBodegaFk(1);

                                $arMovimientoDetalle->setItemMD($arItem[0]);
                                $arMovimientoDetalle->setPorcentajeIva($arItem[0]->getPorcentajeIva());
                                $em->persist($arMovimientoDetalle);
                                $em->flush();
                                if ($arMovimiento->getCodigoDocumentoTipoFk() == 4 && $arMovimiento->getDocumentoRel()->getOperacionInventario() == -1)
                                    $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->EstableceLoteMovimientoDetalle($arMovimientoDetalle->getCodigoDetalleMovimientoPk());
                                $em->getRepository('BrasaInventarioBundle:InvMovimientos')->Liquidar($codigoMovimiento);
                            }
                        }                        
                    }
                    break;
                    
                case "OpEliminarDescuentoFinanciero";                    
                    if(count($arrDescuentosFinancierosSeleccionados) > 0) {
                        foreach ($arrDescuentosFinancierosSeleccionados AS $codigoMovimientoDescuentoFinanciero) {
                            $arMovimientoDescuentoFinanciero = new \Brasa\InventarioBundle\Entity\InvDescuentosFinancieros();
                            $arMovimientoDescuentoFinanciero = $em->getRepository('BrasaInventarioBundle:InvMovimientosDescuentosFinancieros')->find($codigoMovimientoDescuentoFinanciero);
                            $em->remove($arMovimientoDescuentoFinanciero);
                            $em->flush();
                        }
                    }                    
                    $em->getRepository('BrasaInventarioBundle:InvMovimientos')->LiquidarRetenciones($codigoMovimiento);
                    break;                    
            }
        }
        //No mostrar registros de control traslados
        if($arMovimiento->getCodigoDocumentoTipoFk() == 10) {
            $arMovimientosDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->findBy(array('codigoMovimientoFk' => $codigoMovimiento, 'operacionInventario' => 0));
        }
        else {                        
            $dql   = "SELECT md FROM BrasaInventarioBundle:InvMovimientosDetalles md WHERE md.codigoMovimientoFk = " . $codigoMovimiento;
            $query = $em->createQuery($dql);
            $paginator = $this->get('knp_paginator');
            $arMovimientosDetalle = $paginator->paginate($query, $this->get('request')->query->get('page', 1)/*page number*/,3);
        }
        $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumentos();
        $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumentos')->find($arMovimiento->getCodigoDocumentoFk());
        $arDocumentoConfiguracion = new \Brasa\InventarioBundle\Entity\InvDocumentosConfiguracion();
        $arDocumentoConfiguracion = $em->getRepository('BrasaInventarioBundle:InvDocumentosConfiguracion')->find($arMovimiento->getCodigoDocumentoFk());        
        $arMovimientosRetenciones = new \Brasa\InventarioBundle\Entity\InvMovimientosRetenciones();
        $arMovimientosRetenciones = $em->getRepository('BrasaInventarioBundle:InvMovimientosRetenciones')->findBy(array('codigoMovimientoFk' => $codigoMovimiento));
        $arMovimientosDescuentosFinancieros = new \Brasa\InventarioBundle\Entity\InvMovimientosDescuentosFinancieros();
        $arMovimientosDescuentosFinancieros = $em->getRepository('BrasaInventarioBundle:InvMovimientosDescuentosFinancieros')->findBy(array('codigoMovimientoFk' => $codigoMovimiento));
        return $this->render('BrasaInventarioBundle:Movimientos:detalle.html.twig', array('arMovimiento' => $arMovimiento,
                    'arMovimientosDetalle' => $arMovimientosDetalle,
                    'arDocumento' => $arDocumento,
                    'arDocumentoConfiguracion' => $arDocumentoConfiguracion,
                    'arMovimientosRetenciones' => $arMovimientosRetenciones,
                    'arMovimientosDescuentosFinancieros' => $arMovimientosDescuentosFinancieros));
    }    
    
    
    /**
     * Guarda los cambios realizados en la tabla de los detalles de movimiento
     * @param array $arrDetalles Array con los controles de la vista
     */
    public function GuardarCambios($arrDetalles) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        $boolValidado = "";
        if (isset($arrDetalles['LblCodigoDetalle'])) {
            if (count($arrDetalles['LblCodigoDetalle']) > 0) {
                //Validar las cantidades del documento enlace
                foreach ($arrDetalles['LblCodigoDetalle'] as $intCodigoDetalle) {
                    if ($boolValidado == "") {
                        $intNuevaCantidad = $arrDetalles['TxtCantidad'][$intIndice];
                        $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
                        $arMovimientoDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->find($intCodigoDetalle);
                        if ($arMovimientoDetalle->getCodigoDetalleMovimientoEnlace() != "") {
                            $arMovimientoDetalleEnlace = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
                            $arMovimientoDetalleEnlace = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->find($arMovimientoDetalle->getCodigoDetalleMovimientoEnlace());
                            $intDiferenciaCantidades = $intNuevaCantidad - $arMovimientoDetalle->getCantidad();
                            $intCantidadPendienteEnlace = $arMovimientoDetalleEnlace->getCantidad() - $arMovimientoDetalleEnlace->getCantidadAfectada();
                            if ($intDiferenciaCantidades != 0) {
                                if ($intDiferenciaCantidades > $intCantidadPendienteEnlace) {
                                    $boolValidado = "La cantidad [" . $intNuevaCantidad . "] del detalle " . $arMovimientoDetalle->getCodigoDetalleMovimientoPk() . " es mayor a la cantidad pendiente [" . $intCantidadPendienteEnlace . "] del enlace " . $arMovimientoDetalleEnlace->getMovimientoRel()->getDocumentoRel()->getNombre() . " Nro. " . $arMovimientoDetalleEnlace->getMovimientoRel()->getNumeroMovimiento() . " detalle " . $arMovimientoDetalleEnlace->getCodigoDetalleMovimientoPk();
                                }
                            }
                        }
                    }
                    $intIndice++;
                }

                if ($boolValidado == "") {
                    $intIndice = 0;
                    foreach ($arrDetalles['LblCodigoDetalle'] as $intCodigoDetalle) {
                        $intNuevaCantidad = $arrDetalles['TxtCantidad'][$intIndice];
                        $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
                        $arMovimientoDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->find($intCodigoDetalle);
                        $intDiferenciaCantidades = $intNuevaCantidad - $arMovimientoDetalle->getCantidad();

                        if (isset($arrDetalles['TxtLote']))
                            $arMovimientoDetalle->setLoteFk($arrDetalles['TxtLote'][$intIndice]);

                        if (isset($arrDetalles['TxtVencimiento']))
                            $arMovimientoDetalle->setFechaVencimiento(date_create($arrDetalles['TxtVencimiento'][$intIndice]));

                        if (isset($arrDetalles['TxtBodega']))
                            $arMovimientoDetalle->setCodigoBodegaFk($arrDetalles['TxtBodega'][$intIndice]);

                        if (isset($arrDetalles['TxtBodegaDestino']))
                            $arMovimientoDetalle->setCodigoBodegaDestinoFk($arrDetalles['TxtBodegaDestino'][$intIndice]);                        
                        
                        $arMovimientoDetalle->setCantidad($arrDetalles['TxtCantidad'][$intIndice]);
                        $arMovimientoDetalle->setPorcentajeDescuento($arrDetalles['TxtDescuento'][$intIndice]);

                        $arMovimientoDetalle->setVrPrecio($arrDetalles['TxtPrecio'][$intIndice]);
                        $em->persist($arMovimientoDetalle);
                        $em->flush();

                        if ($arMovimientoDetalle->getCodigoDetalleMovimientoEnlace() != "") {
                            $arMovimientoDetalleEnlace = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
                            $arMovimientoDetalleEnlace = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->find($arMovimientoDetalle->getCodigoDetalleMovimientoEnlace());
                            $arMovimientoDetalleEnlace->setCantidadAfectada($arMovimientoDetalleEnlace->getCantidadAfectada() + $intDiferenciaCantidades);
                            $em->persist($arMovimientoDetalleEnlace);
                            $em->flush();
                        }

                        $intIndice++;
                    }
                }
            }
        }
        return $boolValidado;
    }    
}
