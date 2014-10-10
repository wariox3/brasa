<?php

namespace Brasa\LogisticaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DespachosController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();                
        $arDespachos = new \Brasa\LogisticaBundle\Entity\LogDespachos();            
        
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
            $arDespachos = $em->getRepository('BrasaLogisticaBundle:LogDespachos')->findAll();
        }                    

        return $this->render('BrasaLogisticaBundle:Despachos:lista.html.twig', array(
            'arDespachos' => $arDespachos));
    }
    
    /**
     * Crear un nuevo movimiento
     * @return type
     */
    public function nuevoAction($codigoDespacho = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();                
        if ($request->getMethod() == 'POST') {            
            if (($request->request->get('TxtCodigoDespacho'))) {
                $arDespachoNuevo = $em->getRepository('BrasaLogisticaBundle:LogDespachos')->find($request->request->get('TxtCodigoDespacho'));
            } else {
                $arDespachoNuevo = new \Brasa\LogisticaBundle\Entity\LogDespachos();
            }                
            $arDespachoNuevo->setFecha(date_create(date('Y-m-d H:i:s')));                                                                            
            $arDespachoNuevo->setComentarios($request->request->get('TxtComentarios'));

            $em->persist($arDespachoNuevo);
            $em->flush();                
            return $this->redirect($this->generateUrl('brs_log_despachos_lista'));
        }
        
        $arDespacho = null;        
        if ($codigoDespacho != null && $codigoDespacho != "" && $codigoDespacho != 0) {
            $arDespacho = $em->getRepository('BrasaLogisticaBundle:LogDespachos')->find($codigoDespacho);                                
        }       
            
        
        return $this->render('BrasaLogisticaBundle:Despachos:nuevo.html.twig', array(
            'arDespacho' => $arDespacho));
    }    
    
    /**
     * Lista los movimientos detalle (Detalles) segun encabezado - Filtro
     */
    public function detalleAction($codigoDespacho) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');
        
        $arDespacho = new \Brasa\LogisticaBundle\Entity\LogDespachos();
        $arDespacho = $em->getRepository('BrasaLogisticaBundle:LogDespachos')->find($codigoDespacho);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrDescuentosFinancierosSeleccionados = $request->request->get('ChkSeleccionarDescuentoFinanciero');
            switch ($request->request->get('OpSubmit')) {
                case "OpGenerar";
                    $strResultado = $em->getRepository('BrasaLogisticaBundle:LogDespachos')->Generar($codigoDespacho);
                    if ($strResultado != "") {
                        $objMensaje->Mensaje("error", "No se genero el despacho: " . $strResultado, $this);
                    }                        
                    break;

                case "OpAnular";
                    $varAnular = $em->getRepository('BrasaLogisticaBundle:LogDespachos')->Anular($codigoDespacho);
                    if ($varAnular != "") {
                        $objMensaje->Mensaje("error", "No se anulo el despacho: " . $varAnular, $this);
                    }                        
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

                case "OpRetirar";
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\LogisticaBundle\Entity\LogGuias();
                            $arGuia = $em->getRepository('BrasaLogisticaBundle:LogGuias')->find($codigoGuia);
                            if($arGuia->getCodigoDespachoFk() == NULL) {
                                $arGuia->setCodigoDespachoFk(NULL);
                                $em->persist($arGuia);
                                $em->flush();                                
                            }
                        }                        
                    }
                    break;

                case "OpActualizarDetalles";
                    $strResultado = $this->GuardarCambios($arrControles);
                    if ($strResultado != "")
                        $objMensaje->Mensaje("error", $strResultado, $this);
                    else
                        $em->getRepository('BrasaInventarioBundle:InvMovimientos')->Liquidar($codigoMovimiento);
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
                    
                   
            }
        }
        
        $query = $em->getRepository('BrasaLogisticaBundle:LogGuias')->GuiasDespachoDetalle($codigoDespacho);
        $paginator = $this->get('knp_paginator');        
        $arGuias = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);                
        return $this->render('BrasaLogisticaBundle:Despachos:detalle.html.twig', array(
                    'arDespacho' => $arDespacho,
                    'arGuias' => $arGuias,
                    'paginator' => $paginator,));
    }    
        
}
