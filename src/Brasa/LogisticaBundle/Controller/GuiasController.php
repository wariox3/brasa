<?php

namespace Brasa\LogisticaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GuiasController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();                
        $arGuias = new \Brasa\LogisticaBundle\Entity\LogGuias();            
        
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
                            $arrControles['TxtCodigoTercero'], 
                            $objChkFecha, 
                            $arrControles['TxtFechaDesde'], 
                            $arrControles['TxtFechaHasta'],
                            "",
                            "");
                    break;
            }
        } else {            
            $arGuias = $em->getRepository('BrasaLogisticaBundle:LogGuias')->findAll();
        }                    

        return $this->render('BrasaLogisticaBundle:Guias:lista.html.twig', array(
            'arGuias' => $arGuias));
    }
    
    /**
     * Crear un nuevo movimiento
     * @return type
     */
    public function nuevoAction($codigoGuia = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();                
        if ($request->getMethod() == 'POST') {            
            $arCiudad = new \Brasa\GeneralBundle\Entity\GenCiudades();
            $arTercero = new \Brasa\GeneralBundle\Entity\GenTerceros();
            if (($request->request->get('TxtCodigoGuia'))) {
                $arGuiaNueva = $em->getRepository('BrasaLogisticaBundle:LogGuias')->find($request->request->get('TxtCodigoGuia'));
            } else {
                $arGuiaNueva = new \Brasa\LogisticaBundle\Entity\LogGuias();
            }            
            $arTercero = $em->getRepository('BrasaGeneralBundle:GenTerceros')->find($request->request->get('TxtCodigoTercero'));
            $arGuiaNueva->setTerceroRel($arTercero);
            $arGuiaNueva->setFechaIngreso(date_create(date('Y-m-d H:i:s')));                                                                            
            $arGuiaNueva->setDocumentoCliente($request->request->get('TxtDocumentoCliente'));            
            $arGuiaNueva->setNombreDestinatario($request->request->get('TxtNombreDestinatario'));            
            $arGuiaNueva->setDireccionDestinatario($request->request->get('TxtDireccionDestinatario'));            
            $arGuiaNueva->setTelefonoDestinatario($request->request->get('TxtTelefonoDestinatario'));            
            $arCiudad = $em->getRepository('BrasaGeneralBundle:GenCiudades')->find($request->request->get('TxtCodigoCiudadDestino'));
            $arGuiaNueva->setCiudadDestinoRel($arCiudad);            
            $arGuiaNueva->setRutaRel($arCiudad->getRutaRel());
            $arGuiaNueva->setComentarios($request->request->get('TxtComentarios'));
            $arGuiaNueva->setCtUnidades($request->request->get('TxtUnidades'));
            $arGuiaNueva->setCtPesoReal($request->request->get('TxtPesoReal'));
            $arGuiaNueva->setCtPesoVolumen($request->request->get('TxtPesoVolumen'));
            $arGuiaNueva->setVrDeclarado($request->request->get('TxtDeclarado'));
            $arGuiaNueva->setVrFlete($request->request->get('TxtFlete'));
            $arGuiaNueva->setVrManejo($request->request->get('TxtManejo'));
            
            $em->persist($arGuiaNueva);
            $em->flush();                
            return $this->redirect($this->generateUrl('brs_log_guias_lista'));
        }
        
        $arGuia = null;        
        if ($codigoGuia != null && $codigoGuia != "" && $codigoGuia != 0) {
            $arGuia = $em->getRepository('BrasaLogisticaBundle:LogGuias')->find($codigoGuia);                                
        }       
            
        
        return $this->render('BrasaLogisticaBundle:Guias:nuevo.html.twig', array(
            'arGuia' => $arGuia));
    }    
    
    /**
     * Lista los movimientos detalle (Detalles) segun encabezado - Filtro
     */
    public function detalleAction($codigoMovimiento) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');
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
                        //$Impresion = new Control_Impresion_Inventario();
                        //$Impresion->CounstruirImpresion($em, $arMovimiento);
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
        
}
