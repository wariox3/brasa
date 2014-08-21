<?php

namespace Brasa\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocumentosController extends Controller
{
    public function listaAction($codigoTipoDocumento)
    {
        $em = $this->getDoctrine()->getManager();
        $arDocumentos = new \Brasa\InventarioBundle\Entity\InvDocumentos();
        $arDocumentos = $em->getRepository('BrasaInventarioBundle:InvDocumentos')->findBy(array('codigoDocumentoTipoFk' => $codigoTipoDocumento));        
        return $this->render('BrasaInventarioBundle:Documentos:lista.html.twig', array('arDocumentos'=> $arDocumentos));
    }
    
    public function listaBaseAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $arDocumentos = new \Brasa\InventarioBundle\Entity\InvDocumentos();
        $arDocumentos = $em->getRepository('BrasaInventarioBundle:InvDocumentos')->findAll();
        return $this->render('BrasaInventarioBundle:Base/Documentos:listado.html.twig', array('arDocumentos' => $arDocumentos));
    }     
    
    public function nuevoAction($codigoDocumentoPk = null) {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();          
        $arDocumentosTipos = new \Brasa\InventarioBundle\Entity\InvDocumentosTipos();
        $arDocumentosSubTipos = new \Brasa\InventarioBundle\Entity\InvDocumentosSubtipos();
        $arComprobantesContables = new \Brasa\ContabilidadBundle\Entity\CtbComprobantesContables();
        //$objFunciones = new \Brasa\ExternasBundle\FuncionesZikmont\FuncionesZikmont();
        if ($request->getMethod() == 'POST') {
            if (($request->request->get('TxtCodigoDocumento'))) {
                $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumentos')->find($request->request->get('TxtCodigoDocumento'));
                $arDocumentoConfiguracion = $em->getRepository('BrasaInventarioBundle:InvDocumentosConfiguracion')->find($request->request->get('TxtCodigoDocumento'));
            }
            else {
                $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumentos();
                $arDocumentoConfiguracion = new \Brasa\InventarioBundle\Entity\InvDocumentosConfiguracion();
            }                
            $arDocumento->setNombre($request->request->get('TxtNombreDocumento'));
            $arDocumento->setAbreviatura($request->request->get('TxtAbreviatura'));
            $arDocumento->setConsecutivo($request->request->get('TxtConsecutivo'));
            $arDocumento->setOperacionInventario($request->request->get('TxtOperacionInventario'));
            if ($request->request->get('CboDocumentosTipos') != "") {                
                $arDocumentosTipos = $em->getRepository('BrasaInventarioBundle:InvDocumentosTipos')->find($request->request->get('CboDocumentosTipos'));   
                $arDocumento->setDocumentoTipoRel($arDocumentosTipos);
            }
            if ($request->request->get('CboDocumentosSubTipos') != "") {                
                $arDocumentosSubTipos = $em->getRepository('BrasaInventarioBundle:InvDocumentosSubTipos')->find($request->request->get('CboDocumentosSubTipos'));   
                $arDocumento->setDocumentoSubtipoRel($arDocumentosSubTipos);                
            }            
            $arCuenta = new \Brasa\ContabilidadBundle\Entity\CtbCuentasContables();            
            
            $arDocumento->setTipoCuentaIngreso($request->request->get('CboTipoRegistroCuentaIngreso'));
            $arDocumento->setTipoCuentaCosto($request->request->get('CboTipoRegistroCuentaCosto'));
            //Iva
            if($request->request->get('TxtCuentaIva') != "") {
                $intCuenta = $objFunciones->DevCodigoCuenta($request->request->get('TxtCuentaIva'));
                if($intCuenta != "") {                    
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuentasContables')->find($intCuenta);                    
                    if(count($arCuenta) > 0) 
                        $arDocumento->setCuentaIvaRel ($arCuenta);                    
                    else
                        $arDocumento->setCodigoCuentaIvaFk(null);
                } else
                    $arDocumento->setCodigoCuentaIvaFk(null);
            } else
                $arDocumento->setCodigoCuentaIvaFk(null);                                
            $arDocumento->setTipoCuentaIva($request->request->get('CboTipoRegistroCuentaIva'));
            //Retencion fuente
            if($request->request->get('TxtCuentaRetencionFuente') != "") {
                $intCuenta = $objFunciones->DevCodigoCuenta($request->request->get('TxtCuentaRetencionFuente'));
                if($intCuenta != "") {                    
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuentasContables')->find($intCuenta);                    
                    if(count($arCuenta) > 0) 
                        $arDocumento->setCuentaRetencionFuenteRel($arCuenta);                    
                    else
                        $arDocumento->setCodigoCuentaRetencionFuenteFk(null);
                } else
                    $arDocumento->setCodigoCuentaRetencionFuenteFk(null);
            } else
                $arDocumento->setCodigoCuentaRetencionFuenteFk(null);            
            $arDocumento->setTipoCuentaRetencionFuente($request->request->get('CboTipoRegistroCuentaRetencionFuente'));
            //Retencion CREE
            if($request->request->get('TxtCuentaRetencionCREE') != "") {
                $intCuenta = $objFunciones->DevCodigoCuenta($request->request->get('TxtCuentaRetencionCREE'));
                if($intCuenta != "") {                    
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuentasContables')->find($intCuenta);                    
                    if(count($arCuenta) > 0) 
                        $arDocumento->setCuentaRetencionCREERel($arCuenta);                    
                    else
                        $arDocumento->setCodigoCuentaRetencionCREEFk(null);
                } else
                    $arDocumento->setCodigoCuentaRetencionCREEFk(null);
            } else
                $arDocumento->setCodigoCuentaRetencionCREEFk(null);            
            $arDocumento->setTipoCuentaRetencionCREE($request->request->get('CboTipoRegistroCuentaRetencionCREE'));  
            //Retencion Iva
            if($request->request->get('TxtCuentaRetencionIva') != "") {
                $intCuenta = $objFunciones->DevCodigoCuenta($request->request->get('TxtCuentaRetencionIva'));
                if($intCuenta != "") {                    
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuentasContables')->find($intCuenta);                    
                    if(count($arCuenta) > 0) 
                        $arDocumento->setCuentaRetencionIvaRel($arCuenta);                    
                    else
                        $arDocumento->setCodigoCuentaRetencionIvaFk(null);
                } else
                    $arDocumento->setCodigoCuentaRetencionIvaFk(null);
            }else
                $arDocumento->setCodigoCuentaRetencionIvaFk(null);            
            $arDocumento->setTipoCuentaRetencionIva($request->request->get('CboTipoRegistroCuentaRetencionIva'));            
            //Tesoreria
            if($request->request->get('TxtCuentaTesoreria') != "") {
                $intCuenta = $objFunciones->DevCodigoCuenta($request->request->get('TxtCuentaTesoreria'));
                if($intCuenta != "") {                    
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuentasContables')->find($intCuenta);                    
                    if(count($arCuenta) > 0) 
                        $arDocumento->setCuentaTesoreriaRel($arCuenta);                    
                    else
                        $arDocumento->setCodigoCuentaTesoreriaFk(null);
                } else
                    $arDocumento->setCodigoCuentaTesoreriaFk(null);
            }else
                $arDocumento->setCodigoCuentaTesoreriaFk(null);            
            $arDocumento->setTipoCuentaTesoreria($request->request->get('CboTipoRegistroCuentaTesoreria')); 
            //Cartera
            if($request->request->get('TxtCuentaCartera') != "") {
                $intCuenta = $objFunciones->DevCodigoCuenta($request->request->get('TxtCuentaCartera'));
                if($intCuenta != "") {                    
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuentasContables')->find($intCuenta);                    
                    if(count($arCuenta) > 0) 
                        $arDocumento->setCuentaCarteraRel($arCuenta);                    
                    else
                        $arDocumento->setCodigoCuentaCarteraFk(null);
                } else
                    $arDocumento->setCodigoCuentaCarteraFk(null);
            }else
                $arDocumento->setCodigoCuentaCarteraFk(null);            
            $arDocumento->setTipoCuentaCartera($request->request->get('CboTipoRegistroCuentaCartera'));            
            
            if ($request->request->get('CboComprobanteContable') != "") {                
                $arComprobantesContables = $em->getRepository('BrasaContabilidadBundle:CtbComprobantesContables')->find($request->request->get('CboComprobanteContable'));   
                $arDocumento->setComprobanteContableRel($arComprobantesContables);
            }            
            
            if(($request->request->get('ChkAgregarItem') == 'on') && $request->request->get('ChkAgregarItem'))
                $arDocumentoConfiguracion->setAgregarItem(1);
            else
                $arDocumentoConfiguracion->setAgregarItem(0);
            
            if(($request->request->get('ChkAgregarItemDocumentoControl') == 'on') && $request->request->get('ChkAgregarItemDocumentoControl'))
                $arDocumentoConfiguracion->setAgregarItemDocumentoControl (1);
            else
                $arDocumentoConfiguracion->setAgregarItemDocumentoControl (0);
            
            if(($request->request->get('ChkExigeTerceroDocumentoControl') == 'on') && $request->request->get('ChkExigeTerceroDocumentoControl'))
                $arDocumentoConfiguracion->setExigeTerceroDocumentoControl (1);
            else
                $arDocumentoConfiguracion->setExigeTerceroDocumentoControl (0);   
            
            if(($request->request->get('ChkEditarLote') == 'on') && $request->request->get('ChkEditarLote'))
                $arDocumentoConfiguracion->setEditarLote(1);
            else
                $arDocumentoConfiguracion->setEditarLote(0);

            if(($request->request->get('ChkEditarCantidad') == 'on') && $request->request->get('ChkEditarCantidad'))
                $arDocumentoConfiguracion->setEditarCantidad(1);
            else
                $arDocumentoConfiguracion->setEditarCantidad(0);            

            if(($request->request->get('ChkEditarDescuento') == 'on') && $request->request->get('ChkEditarDescuento'))
                $arDocumentoConfiguracion->setEditarDescuento(1);
            else
                $arDocumentoConfiguracion->setEditarDescuento(0);            

            if(($request->request->get('ChkEditarPrecio') == 'on') && $request->request->get('ChkEditarPrecio'))
                $arDocumentoConfiguracion->setEditarPrecio(1);
            else
                $arDocumentoConfiguracion->setEditarPrecio(0);            
            
            if(($request->request->get('ChkManejaLote') == 'on') && $request->request->get('ChkManejaLote'))
                $arDocumentoConfiguracion->setManejaLote (1);
            else
                $arDocumentoConfiguracion->setManejaLote (0);

            if(($request->request->get('ChkManejaBodega') == 'on') && $request->request->get('ChkManejaBodega'))
                $arDocumentoConfiguracion->setManejaBodega (1);
            else
                $arDocumentoConfiguracion->setManejaBodega (0);            

            if(($request->request->get('ChkRequiereFecha1') == 'on') && $request->request->get('ChkRequiereFecha1'))
                $arDocumentoConfiguracion->setRequiereFecha1 (1);
            else
                $arDocumentoConfiguracion->setRequiereFecha1 (0);                        

            if(($request->request->get('ChkRequiereFecha2') == 'on') && $request->request->get('ChkRequiereFecha2'))
                $arDocumentoConfiguracion->setRequiereFecha2(1);
            else
                $arDocumentoConfiguracion->setRequiereFecha2(0);            
            
            $arDocumento->setTipoValor($request->request->get('CboTipoPrecio'));
            
            if(($request->request->get('ChkRequiereDireccion') == 'on') && $request->request->get('ChkRequiereDireccion'))
                $arDocumentoConfiguracion->setRequiereDireccion(1);
            else
                $arDocumentoConfiguracion->setRequiereDireccion(0);

            if(($request->request->get('ChkManejaFletes') == 'on') && $request->request->get('ChkManejaFletes'))
                $arDocumentoConfiguracion->setManejaFletes(1);
            else
                $arDocumentoConfiguracion->setManejaFletes(0);            
            
            $arDocumentoConfiguracion->setNombreFecha1($request->request->get('TxtNombreFecha1'));
            $arDocumentoConfiguracion->setNombreFecha2($request->request->get('TxtNombreFecha2'));
            $em->persist($arDocumento);
            $em->flush();
            $arDocumentoConfiguracion->setCodigoDocumentoConfiguracionPk($arDocumento->getCodigoDocumentoPk());
            $em->persist($arDocumentoConfiguracion);
            $em->flush();
            return $this->redirect($this->generateUrl('maestros_inventario_documentos_lista'));            
        }        
        $arDocumento = null;
        $arDocumentoConfiguracion = null;
        if ($codigoDocumentoPk != null && $codigoDocumentoPk != "" && $codigoDocumentoPk != 0) {
            $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumentos')->find($codigoDocumentoPk);   
            $arDocumentoConfiguracion = $em->getRepository('BrasaInventarioBundle:InvDocumentosConfiguracion')->find($codigoDocumentoPk);   
        }
        $arDocumentosTipos = $em->getRepository('BrasaInventarioBundle:InvDocumentosTipos')->findAll();   
        $arDocumentosSubTipos = $em->getRepository('BrasaInventarioBundle:InvDocumentosSubTipos')->findAll();   
        $arComprobantesContables = $em->getRepository('BrasaContabilidadBundle:CtbComprobantesContables')->findAll();   
        
        return $this->render('BrasaInventarioBundle:Base/Documentos:nuevo.html.twig', array(
            'arDocumento' => $arDocumento,
            'arDocumentoConfiguracion' => $arDocumentoConfiguracion,
            'arDocumentosTipos' => $arDocumentosTipos,
            'arDocumentosSubTipos' => $arDocumentosSubTipos,
            'arComprobantesContables' => $arComprobantesContables));
    }    
}
