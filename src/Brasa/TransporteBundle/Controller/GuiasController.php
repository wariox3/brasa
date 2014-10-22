<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\TransporteBundle\Form\Type\TteGuiasType;
use Brasa\TransporteBundle\Form\Type\TteNovedadesType;
use Brasa\TransporteBundle\Form\Type\TteRecibosCajaType;

class GuiasController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arGuias = new \Brasa\TransporteBundle\Entity\TteGuias();

        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $objChkFecha = NULL;
            if (isset($arrControles['ChkFecha']))
                $objChkFecha = $arrControles['ChkFecha'];
            switch ($request->request->get('OpSubmit')) {

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
            $arGuias = $em->getRepository('BrasaTransporteBundle:TteGuias')->findAll();
        }
        
        return $this->render('BrasaTransporteBundle:Guias:lista.html.twig', array(
            'arGuias' => $arGuias));
    }

    /**
     * Crear un nueva guias
     * @return type
     */
    public function nuevoAction($codigoGuia = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
        $form = $this->createForm(new TteGuiasType(), $arGuia);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arGuia = $form->getData();                        
            $arUsuarioConfiguracion = $em->getRepository('BrasaTransporteBundle:TteUsuariosConfiguracion')->find($this->getUser()->getId());            
            $arCiudadDestino = $em->getRepository('BrasaGeneralBundle:GenCiudades')->find($arrControles['form']['ciudadDestinoRel']);
            $arGuia->setFechaIngreso(date_create(date('Y-m-d H:i:s')));
            $arGuia->setPuntoOperacionIngresoRel($arUsuarioConfiguracion->getPuntoOperacionRel());
            $arGuia->setPuntoOperacionActualRel($arUsuarioConfiguracion->getPuntoOperacionRel());
            $arGuia->setCiudadOrigenRel($arUsuarioConfiguracion->getPuntoOperacionRel()->getCiudadOrigenRel());
            $arGuia->setRutaRel($arCiudadDestino->getRutaRel());
            $em->persist($arGuia);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tte_guias_nuevo', array('codigoGuia' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_tte_guias_detalle', array('codigoGuia' => $arGuia->getCodigoGuiaPk())));
            }    
            
        }                
        return $this->render('BrasaTransporteBundle:Guias:nuevo.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * Lista los movimientos detalle (Detalles) segun encabezado - Filtro
     */
    public function detalleAction($codigoGuia) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $request = $this->getRequest();
        $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
        $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuias')->find($codigoGuia);        
        $arNovedad = new \Brasa\TransporteBundle\Entity\TteNovedades();
        $frmNovedad = $this->createForm(new TteNovedadesType(), $arNovedad);
        $frmNovedad->handleRequest($request);
        if ($frmNovedad->isValid()) {
            $arNovedad = $frmNovedad->getData(); 
            $arNovedad->setFechaRegistro(date_create(date('Y-m-d H:i:s')));
            $arNovedad->setGuiaRel($arGuia);
            $em->persist($arNovedad);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tte_guias_detalle', array('codigoGuia' => $codigoGuia)));            
        }

        $arReciboCaja = new \Brasa\TransporteBundle\Entity\TteRecibosCaja();
        $frmReciboCaja = $this->createForm(new TteRecibosCajaType, $arReciboCaja);
        $frmReciboCaja->handleRequest($request);
        if ($frmReciboCaja->isValid()) {
            $arReciboCaja = $frmReciboCaja->getData();             
            $arReciboCaja->setGuiaRel($arGuia);
            $em->persist($arReciboCaja);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tte_guias_detalle', array('codigoGuia' => $codigoGuia)));            
        }        
        
        $form = $this->createFormBuilder()
            ->add('BtnAutorizar', 'submit')
            ->getForm(); 

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
                    $objFormatoGuia = new \Brasa\TransporteBundle\Formatos\FormatoGuia();
                    $objFormatoGuia->Generar($this, $codigoGuia);
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

        $query = $em->getRepository('BrasaTransporteBundle:TteNovedades')->NovedadesGuiasDetalle($codigoGuia);
        $paginator = $this->get('knp_paginator');        
        $arNovedades = new \Brasa\TransporteBundle\Entity\TteNovedades();
        $arNovedades = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);

        $query = $em->getRepository('BrasaTransporteBundle:TteRecibosCaja')->RecibosCajaGuiasDetalle($codigoGuia);
        $paginator = $this->get('knp_paginator');        
        $arRecibosCaja = new \Brasa\TransporteBundle\Entity\TteRecibosCaja();
        $arRecibosCaja = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);        
        
        return $this->render('BrasaTransporteBundle:Guias:detalle.html.twig', array(
            'arGuia' => $arGuia,
            'arNovedades' => $arNovedades,
            'arRecibosCaja' => $arRecibosCaja,
            'form' => $form->createView(),
            'frmNovedad' => $frmNovedad->createView(),
            'frmReciboCaja' => $frmReciboCaja->createView()));
    }

}
