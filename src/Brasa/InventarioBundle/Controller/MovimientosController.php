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
        //$objMensaje = new GenerarMensajes();
        $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumentos();
        $arDocumentoConfiguracion = new \Brasa\InventarioBundle\Entity\InvDocumentosConfiguracion();
        $arDocumentoConfiguracion = $em->getRepository('BrasaInventarioBundle:InvDocumentosConfiguracion')->find($codigoDocumento);


        if ($request->getMethod() == 'POST') {
            if (($request->request->get('TxtCodigoMovimiento'))) {
                $arMovimientoNuevo = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->find($request->request->get('TxtCodigoMovimiento'));
            }
                
            if (!($request->request->get('TxtCodigoMovimiento'))) {
                $arMovimientoNuevo = new \zikmont\InventarioBundle\Entity\InvMovimientos();
            }
                

            //$arTercero = new \zikmont\FrontEndBundle\Entity\GenTerceros();
            //$arTercero = $em->getRepository('zikmontFrontEndBundle:GenTerceros')->find();
            $arTercero = $this->getDoctrine()->getRepository('zikmontFrontEndBundle:GenTerceros')->find($request->request->get('TxtCodigoTercero'));            
            
            $arDocumento = $em->getRepository('zikmontInventarioBundle:InvDocumentos')->find($request->request->get('iddocumento'));

            $arMovimientoNuevo->setDocumentoRel($arDocumento);
            $arMovimientoNuevo->setDocumentoTipoRel($arDocumento->getDocumentoTipoRel());
            $arMovimientoNuevo->setTerceroRel($arTercero);
            $arMovimientoNuevo->setSoporte($request->request->get('TxtSoporte'));
            $arMovimientoNuevo->setComentarios($request->request->get('TxtComentarios'));
            $arMovimientoNuevo->setFecha(date_create(date('Y-m-d H:i:s')));
            if($request->request->get('TxtVrFletes')) {
                $arMovimientoNuevo->setValorTotalFletes($request->request->get('TxtVrFletes'));                
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
    
}
