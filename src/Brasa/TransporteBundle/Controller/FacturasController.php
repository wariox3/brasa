<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FacturasController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();                
        $arFacturas = new \Brasa\TransporteBundle\Entity\TteFacturas();            
        
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
            $arFacturas = $em->getRepository('BrasaTransporteBundle:TteFacturas')->findAll();
        }                    

        return $this->render('BrasaTransporteBundle:Facturas:lista.html.twig', array(
            'arFacturas' => $arFacturas));
    }
    
    /**
     * Crear un nuevo movimiento
     * @return type
     */
    public function nuevoAction($codigoFactura = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); 
        $arFactura = new \Brasa\TransporteBundle\Entity\TteFacturas();
        $form = $this->createFormBuilder($arFactura)
            ->add('comentarios', 'text')
            ->getForm();
        
        if ($request->getMethod() == 'POST') {             
            if (($request->request->get('TxtCodigoDespacho'))) {
                $arDespachoNuevo = $em->getRepository('BrasaTransporteBundle:TteFacturas')->find($request->request->get('TxtCodigoDespacho'));
            } else {
                $arDespachoNuevo = new \Brasa\TransporteBundle\Entity\TteFacturas();
            }  
            $arDespachoTipo = $em->getRepository ('BrasaTransporteBundle:TteFacturasTipos')->find($request->request->get('CboDespachosTipos'));
            $arDespachoNuevo->setDespachoTipoRel($arDespachoTipo);
            $arDespachoNuevo->setFecha(date_create(date('Y-m-d H:i:s')));           
            $arCiudadOrigen = $em->getRepository('BrasaGeneralBundle:GenCiudades')->find($request->request->get('TxtCodigoCiudadOrigen'));
            $arDespachoNuevo->setCiudadOrigenRel($arCiudadOrigen);
            $arCiudadDestino = $em->getRepository('BrasaGeneralBundle:GenCiudades')->find($request->request->get('TxtCodigoCiudadDestino'));
            $arDespachoNuevo->setCiudadDestinoRel($arCiudadDestino);
            $arConductor = $em->getRepository('BrasaTransporteBundle:TteConductores')->find($request->request->get('TxtCodigoConductor'));
            $arDespachoNuevo->setConductorRel($arConductor);
            $arRuta = $em->getRepository('BrasaTransporteBundle:TteRutas')->find($request->request->get('TxtCodigoRuta'));            
            $arDespachoNuevo->setRutaRel($arRuta);
            $arVehiculo = $em->getRepository('BrasaTransporteBundle:TteVehiculos')->find($request->request->get('TxtVehiculo'));            
            $arDespachoNuevo->setVehiculoRel($arVehiculo);
            $arDespachoNuevo->setVrFlete($request->request->get('TxtFlete'));
            $arDespachoNuevo->setVrAnticipo($request->request->get('TxtAnticipo'));
            $arDespachoNuevo->setVrNeto($arDespachoNuevo->getVrFlete() - $arDespachoNuevo->getVrAnticipo());
            $arDespachoNuevo->setComentarios($request->request->get('TxtComentarios'));

            $em->persist($arDespachoNuevo);
            $em->flush();                
            return $this->redirect($this->generateUrl('brs_tte_despachos_lista'));
        }
        
        $arFactura = null;                                         
        if ($codigoFactura != null && $codigoFactura != "" && $codigoFactura != 0) {
            $arFactura = $em->getRepository('BrasaTransporteBundle:TteFacturas')->find($codigoFactura);                                
        }                           
        return $this->render('BrasaTransporteBundle:Facturas:nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form));
    }    
    
    /**
     * Lista los movimientos detalle (Detalles) segun encabezado - Filtro
     */
    public function detalleAction($codigoDespacho) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');
        
        $arDespacho = new \Brasa\TransporteBundle\Entity\TteFacturas();
        $arDespacho = $em->getRepository('BrasaTransporteBundle:TteFacturas')->find($codigoDespacho);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrDescuentosFinancierosSeleccionados = $request->request->get('ChkSeleccionarDescuentoFinanciero');
            switch ($request->request->get('OpSubmit')) {
                case "OpGenerar";
                    $strResultado = $em->getRepository('BrasaTransporteBundle:TteFacturas')->Generar($codigoDespacho);
                    if ($strResultado != "") {
                        $objMensaje->Mensaje("error", "No se genero el despacho: " . $strResultado, $this);
                    }                        
                    break;

                case "OpAnular";
                    $varAnular = $em->getRepository('BrasaTransporteBundle:TteFacturas')->Anular($codigoDespacho);
                    if ($varAnular != "") {
                        $objMensaje->Mensaje("error", "No se anulo el despacho: " . $varAnular, $this);
                    }                        
                    break;

                case "OpImprimir";   
                    if($arDespacho->getEstadoGenerado() == 0) {
                        $reporte = new \Brasa\TransporteBundle\Formatos\FormatoManifiesto();
                        $reporte->Generar($this);                        
                    }
                    break;

                case "OpRetirar";
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
                            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuias')->find($codigoGuia);
                            if($arGuia->getCodigoDespachoFk() == NULL) {
                                $arGuia->setCodigoDespachoFk(NULL);
                                $em->persist($arGuia);
                                $em->flush();                                
                            }
                        }                        
                    }
                    break;                                                          
            }
        }
        
        $query = $em->getRepository('BrasaTransporteBundle:TteGuias')->GuiasDespachoDetalle($codigoDespacho);
        $paginator = $this->get('knp_paginator');        
        $arGuias = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);                
        return $this->render('BrasaTransporteBundle:Despachos:detalle.html.twig', array(
                    'arDespacho' => $arDespacho,
                    'arGuias' => $arGuias,
                    'paginator' => $paginator,));
    }    
        
}
