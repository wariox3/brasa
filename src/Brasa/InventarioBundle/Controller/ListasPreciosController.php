<?php

namespace Brasa\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\MensajesBundle\GenerarMensajes;
use Symfony\Component\HttpFoundation\Response;

class ListasPreciosController extends Controller {
    
    public function listarAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $arListasPrecios = new \Brasa\InventarioBundle\Entity\InvListaPrecio();
        $arListasPrecios = $em->getRepository('BrasaInventarioBundle:InvListaPrecio')->findAll();
        if ($request->getMethod() == 'POST') {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrControles = $request->request->All();
            switch ($request->request->get('OpSubmit')) {                       
                case "OpInactivarListas"; 
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoLista) {
                            $arListaPrecio = new \Brasa\InventarioBundle\Entity\InvListaPrecio();                            
                            $arListaPrecio = $em->getRepository('BrasaInventarioBundle:InvListaPrecio')->find($codigoLista);                            
                            if($arListaPrecio->getEstadoInactiva() == 1) 
                                $arListaPrecio->setEstadoInactiva (0);
                            else
                                $arListaPrecio->setEstadoInactiva (1);                            
                            
                            $em->persist($arListaPrecio);
                            $em->flush();                                                              
                        }                         
                    }                    
                    break;                     
            }                           
        }         
        return $this->render('BrasaInventarioBundle:Base/ListasPrecios:listar.html.twig', array('arListasPrecios' => $arListasPrecios));
    } 
    
    public function nuevoAction($codigoListaPreciosPk = null) { 
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();                               
        if ($request->getMethod() == 'POST') {
            if (($request->request->get('TxtCodigoListaPrecios')))
                $arListaPrecios = $em->getRepository('BrasaInventarioBundle:InvListaPrecio')->find($request->request->get('TxtCodigoListaPrecios'));
            else
                $arListaPrecios = new \Brasa\InventarioBundle\Entity\InvListaPrecio();                        
            $arListaPrecios->setNombre($request->request->get('TxtNombre'));
            $em->persist($arListaPrecios);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_inv_base_listas_precios_lista'));            
        }        
        $arListaPrecios = null;
        if ($codigoListaPreciosPk != null && $codigoListaPreciosPk != "" && $codigoListaPreciosPk != 0)        
            $arListaPrecios = $em->getRepository('BrasaInventarioBundle:InvListaPrecio')->find($codigoListaPreciosPk);        
        return $this->render('BrasaInventarioBundle:Base/ListasPrecios:nuevo.html.twig', array('arListaPrecios' => $arListaPrecios));               
        
    }    
    
    public function detalleAction($codigoListaPreciosPk = null) {      
        $em = $this->getDoctrine()->getEntityManager();                        
        $request = $this->getRequest(); 
        if ($request->getMethod() == 'POST') {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrControles = $request->request->All();
            switch ($request->request->get('OpSubmit')) {
                case "OpInactivarDetalle"; 
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoLpDetalle) {
                            $arListaPrecioDetalle = new \Brasa\InventarioBundle\Entity\InvListaPrecioDetalle();                            
                            $arListaPrecioDetalle = $em->getRepository('BrasaInventarioBundle:InvListaPrecioDetalle')->find($codigoLpDetalle);                            
                            if($arListaPrecioDetalle->getEstadoInactiva() == 1) 
                                $arListaPrecioDetalle->setEstadoInactiva (0);
                            else
                                $arListaPrecioDetalle->setEstadoInactiva (1); 
                            $em->persist($arListaPrecioDetalle);
                            $em->flush();                                                              
                        }                         
                    }                    
                    break;                        
            }                           
        }                
        $arListaPrecios = new \Brasa\InventarioBundle\Entity\InvListaPrecio();
        $arListaPrecios = $em->getRepository('BrasaInventarioBundle:InvListaPrecio')->find($codigoListaPreciosPk);
        $arListasPreciosDetalles = new \Brasa\InventarioBundle\Entity\InvListaPrecioDetalle();
        $arListasPreciosDetalles = $em->getRepository('BrasaInventarioBundle:InvListaPrecioDetalle')->findBy(array('codigoListaPreciosFk' => $codigoListaPreciosPk));                
        return $this->render('BrasaInventarioBundle:Base/ListasPrecios:detalle.html.twig', array('arListasPreciosDetalles' => $arListasPreciosDetalles, 'arListaPrecios' => $arListaPrecios));
    }  
    
    public function detalleNuevoAction($codigoListaPreciosPk = null, $codigoListaPreciosDetallePk = null) { 
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();    
        
        if ($request->getMethod() == 'POST') {
            if (($request->request->get('TxtCodigoListaPreciosDetalle')))
                $arListaPreciosDetalle = $em->getRepository('BrasaInventarioBundle:InvListaPrecioDetalle')->find($request->request->get('TxtCodigoListaPreciosDetalle'));
            else
                $arListaPreciosDetalle = new \Brasa\InventarioBundle\Entity\InvListaPrecioDetalle();                        
            $arListaPrecios = new \Brasa\InventarioBundle\Entity\InvListaPrecio();
            $arListaPrecios = $em->getRepository('BrasaInventarioBundle:InvListaPrecio')->find($codigoListaPreciosPk);
            $arListaPreciosDetalle->setListaPrecioRel($arListaPrecios);
            $arItem = new \Brasa\InventarioBundle\Entity\InvItem();
            $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($request->request->get('TxtCodigoItem'));
            $arListaPreciosDetalle->setItemRel($arItem);
            $arListaPreciosDetalle->setPrecio($request->request->get('TxtPrecio'));
            $arListaPreciosDetalle->setFactor($request->request->get('TxtFactor'));
            $arListaPreciosDetalle->setPrecioUMM($request->request->get('TxtPrecio')/$request->request->get('TxtFactor'));
            
            $em->persist($arListaPreciosDetalle);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_inv_base_listas_precios_detalle', array('codigoListaPreciosPk' => $codigoListaPreciosPk)));            
        }        
        $arListaPreciosDetalle = null;
        if ($codigoListaPreciosDetallePk != null && $codigoListaPreciosDetallePk != "" && $codigoListaPreciosDetallePk != 0)        
            $arListaPreciosDetalle = $em->getRepository('BrasaInventarioBundle:InvListaPrecioDetalle')->find($codigoListaPreciosDetallePk);        
        return $this->render('BrasaInventarioBundle:Base/ListasPrecios:detalleNuevo.html.twig', array(
            'arListaPreciosDetalle' => $arListaPreciosDetalle,
            'codigoListaPreciosPk' => $codigoListaPreciosPk));                       
    }
                   
}