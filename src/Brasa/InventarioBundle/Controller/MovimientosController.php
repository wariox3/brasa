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
                        $em->getRepository('zikmontInventarioBundle:InvMovimientos')->Autorizar($codigoMovimiento);
                    break;

                case "OpImprimir";
                    foreach ($arrSeleccionados AS $codigoMovimiento)
                        $em->getRepository('zikmontInventarioBundle:InvMovimientos')->Imprimir($codigoMovimiento);
                    break;

                case "OpEliminar";
                    foreach ($arrSeleccionados AS $codigoMovimiento) {
                        $arMovimiento = new \zikmont\InventarioBundle\Entity\InvMovimientos();
                        $arMovimiento = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->find($codigoMovimiento);
                        if ($arMovimiento->getEstadoAutorizado() == 0) {
                            if ($em->getRepository('zikmontInventarioBundle:InvMovimientosDetalles')->DevNroDetallesMovimiento($codigoMovimiento) <= 0) {
                                $em->remove($arMovimiento);
                                $em->flush();
                            }
                        }
                    }
                    break;
                case "OpBuscar";
                    $arMovimientos = new \zikmont\InventarioBundle\Entity\InvMovimientos();
                    $arMovimientos = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->DevMovimientosFiltro(
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
                $arMovimientoNuevo = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->find($request->request->get('TxtCodigoMovimiento'));
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
                $arDireccion = $em->getRepository('zikmontFrontEndBundle:GenTercerosDirecciones')->find($request->request->get('CboDirecciones'));
                $arMovimientoNuevo->setDireccionRel($arDireccion);
            }

            $em->persist($arMovimientoNuevo);
            $em->flush();                
            return $this->redirect($this->generateUrl('inventario_movimientos_detalle', array('codigoMovimiento' => $arMovimientoNuevo->getCodigoMovimientoPk())));
        }
        
        $arMovimiento = null;
        $arTercerosDirecciones = null;
        if ($codigoMovimiento != null && $codigoMovimiento != "" && $codigoMovimiento != 0) {
            $arMovimiento = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->find($codigoMovimiento);        
            //$arTercerosDirecciones = $em->getRepository('zikmontFrontEndBundle:GenTercerosDirecciones')->findBy(array('codigoTerceroFk' => $arMovimiento->getCodigoTerceroFk()));        
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
                        $strResultado = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->Autorizar($codigoMovimiento);
                        if ($strResultado != "")
                            $objMensaje->Mensaje("error", "No se autorizo el movimiento: " . $strResultado, $this);
                    }
                    break;

                case "OpDesAutorizar";
                    $varDesautorizar = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->DesAutorizar($codigoMovimiento);
                    if ($varDesautorizar != "")
                        $objMensaje->Mensaje("error", "No se desautorizo el movimiento: " . $varDesautorizar, $this);
                    break;

                case "OpAnular";
                    $varAnular = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->Anular($codigoMovimiento);
                    if ($varAnular != "")
                        $objMensaje->Mensaje("error", "No se anulo el movimiento: " . $varAnular, $this);
                    break;

                case "OpImprimir";
                    $strResultado = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->Imprimir($codigoMovimiento);
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
                            $arMovimientoDetalle = new \zikmont\InventarioBundle\Entity\InvMovimientosDetalles();
                            $arMovimientoDetalle = $em->getRepository('zikmontInventarioBundle:InvMovimientosDetalles')->find($codigoMovimientoDetalle);
                            if ($arMovimientoDetalle->getCodigoDetalleMovimientoEnlace() != "") {
                                $arMovimientoDetalleEnlace = new \zikmont\InventarioBundle\Entity\InvMovimientosDetalles();
                                $arMovimientoDetalleEnlace = $em->getRepository('zikmontInventarioBundle:InvMovimientosDetalles')->find($arMovimientoDetalle->getCodigoDetalleMovimientoEnlace());
                                $arMovimientoDetalleEnlace->setCantidadAfectada($arMovimientoDetalleEnlace->getCantidadAfectada() - $arMovimientoDetalle->getCantidad());
                                $em->persist($arMovimientoDetalleEnlace);
                                $em->flush();
                            }
                            $em->remove($arMovimientoDetalle);
                            $em->flush();
                        }
                        $em->getRepository('zikmontInventarioBundle:InvMovimientos')->Liquidar($codigoMovimiento);
                    }
                    break;

                case "OpActualizarDetalles";
                    $strResultado = $this->GuardarCambios($arrControles);
                    if ($strResultado != "")
                        $objMensaje->Mensaje("error", $strResultado, $this);
                    else
                        $em->getRepository('zikmontInventarioBundle:InvMovimientos')->Liquidar($codigoMovimiento);
                    break;

                case "OpCerrarDetalles";
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoMovimientoDetalle) {
                            $arMovimientoDetalle = new \zikmont\InventarioBundle\Entity\InvMovimientosDetalles();
                            $arMovimientoDetalle = $em->getRepository('zikmontInventarioBundle:InvMovimientosDetalles')->find($codigoMovimientoDetalle);
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
                            $arItem = new \zikmont\InventarioBundle\Entity\InvItem();
                            $arItem = $em->getRepository('zikmontInventarioBundle:InvItem')->findBy(array('codigoBarras' => $arrControles['TxtCodigoItem']));
                            //$arItem = $em->getRepository('zikmontInventarioBundle:InvItem')->find($arItem[0]);
                            if (count($arItem) > 0) {
                                $arMovimiento = new \zikmont\InventarioBundle\Entity\InvMovimientos();
                                $arMovimiento = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->find($codigoMovimiento);

                                $arMovimientoDetalle = new \zikmont\InventarioBundle\Entity\InvMovimientosDetalles();
                                $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                                $arMovimientoDetalle->setCantidad(1);

                                if ($arMovimiento->getDocumentoRel()->getTipoValor() == 2)
                                    $arMovimientoDetalle->setPrecio($em->getRepository('zikmontInventarioBundle:InvListasPreciosDetalles')->DevPrecio($arMovimiento->getCodigoTerceroFk(), $arItem[0]->getCodigoItemPk()));

                                if ($arMovimiento->getDocumentoRel()->getTipoValor() == 1)
                                    $arMovimientoDetalle->setPrecio($em->getRepository('zikmontInventarioBundle:InvListasCostosDetalles')->DevCosto($arMovimiento->getCodigoTerceroFk(), $arItem[0]->getCodigoItemPk()));

                                $arMovimientoDetalle->setLoteFk("SL");
                                $arMovimientoDetalle->setFechaVencimiento(date_create('2020/12/30'));
                                $arMovimientoDetalle->setCodigoBodegaFk(1);

                                $arMovimientoDetalle->setItemMD($arItem[0]);
                                $arMovimientoDetalle->setPorcentajeIva($arItem[0]->getPorcentajeIva());
                                $em->persist($arMovimientoDetalle);
                                $em->flush();
                                if ($arMovimiento->getCodigoDocumentoTipoFk() == 4 && $arMovimiento->getDocumentoRel()->getOperacionInventario() == -1)
                                    $em->getRepository('zikmontInventarioBundle:InvMovimientosDetalles')->EstableceLoteMovimientoDetalle($arMovimientoDetalle->getCodigoDetalleMovimientoPk());
                                $em->getRepository('zikmontInventarioBundle:InvMovimientos')->Liquidar($codigoMovimiento);
                            }
                        }                        
                    }
                    break;
                    
                case "OpEliminarDescuentoFinanciero";                    
                    if(count($arrDescuentosFinancierosSeleccionados) > 0) {
                        foreach ($arrDescuentosFinancierosSeleccionados AS $codigoMovimientoDescuentoFinanciero) {
                            $arMovimientoDescuentoFinanciero = new \zikmont\InventarioBundle\Entity\InvDescuentosFinancieros();
                            $arMovimientoDescuentoFinanciero = $em->getRepository('zikmontInventarioBundle:InvMovimientosDescuentosFinancieros')->find($codigoMovimientoDescuentoFinanciero);
                            $em->remove($arMovimientoDescuentoFinanciero);
                            $em->flush();
                        }
                    }                    
                    $em->getRepository('zikmontInventarioBundle:InvMovimientos')->LiquidarRetenciones($codigoMovimiento);
                    break;                    
            }
        }
        //No mostrar registros de control traslados
        if($arMovimiento->getCodigoDocumentoTipoFk() == 10) {
            $arMovimientosDetalle = $em->getRepository('zikmontInventarioBundle:InvMovimientosDetalles')->findBy(array('codigoMovimientoFk' => $codigoMovimiento, 'operacionInventario' => 0));
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
    
}
