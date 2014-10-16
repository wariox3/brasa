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
            $arDespachoTipo = $em->getRepository ('BrasaLogisticaBundle:LogDespachosTipos')->find($request->request->get('CboDespachosTipos'));
            $arDespachoNuevo->setDespachoTipoRel($arDespachoTipo);
            $arDespachoNuevo->setFecha(date_create(date('Y-m-d H:i:s')));           
            $arCiudadOrigen = $em->getRepository('BrasaGeneralBundle:GenCiudades')->find($request->request->get('TxtCodigoCiudadOrigen'));
            $arDespachoNuevo->setCiudadOrigenRel($arCiudadOrigen);
            $arCiudadDestino = $em->getRepository('BrasaGeneralBundle:GenCiudades')->find($request->request->get('TxtCodigoCiudadDestino'));
            $arDespachoNuevo->setCiudadDestinoRel($arCiudadDestino);
            $arConductor = $em->getRepository('BrasaLogisticaBundle:LogConductores')->find($request->request->get('TxtCodigoConductor'));
            $arDespachoNuevo->setConductorRel($arConductor);
            $arRuta = $em->getRepository('BrasaLogisticaBundle:LogRutas')->find($request->request->get('TxtCodigoRuta'));            
            $arDespachoNuevo->setRutaRel($arRuta);
            $arVehiculo = $em->getRepository('BrasaLogisticaBundle:LogVehiculos')->find($request->request->get('TxtVehiculo'));            
            $arDespachoNuevo->setVehiculoRel($arVehiculo);
            $arDespachoNuevo->setVrFlete($request->request->get('TxtFlete'));
            $arDespachoNuevo->setVrAnticipo($request->request->get('TxtAnticipo'));
            $arDespachoNuevo->setVrNeto($arDespachoNuevo->getVrFlete() - $arDespachoNuevo->getVrAnticipo());
            $arDespachoNuevo->setComentarios($request->request->get('TxtComentarios'));

            $em->persist($arDespachoNuevo);
            $em->flush();                
            return $this->redirect($this->generateUrl('brs_log_despachos_lista'));
        }
        
        $arDespacho = null;  
        $arDespachosTipos = new \Brasa\LogisticaBundle\Entity\LogDespachosTipos();
        $arDespachosTipos = $em->getRepository('BrasaLogisticaBundle:LogDespachosTipos')->findAll();                                
        
        if ($codigoDespacho != null && $codigoDespacho != "" && $codigoDespacho != 0) {
            $arDespacho = $em->getRepository('BrasaLogisticaBundle:LogDespachos')->find($codigoDespacho);                                
        }       
            
        
        return $this->render('BrasaLogisticaBundle:Despachos:nuevo.html.twig', array(
            'arDespacho' => $arDespacho,
            'arDespachosTipos' => $arDespachosTipos));
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
                    if($arDespacho->getEstadoGenerado() == 0) {
                        $reporte = new \Brasa\LogisticaBundle\Formatos\FormatoManifiesto();
                        $reporte->Generar($this);                        
                    }
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
