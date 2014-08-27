<?php

namespace Brasa\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\MensajesBundle\GenerarMensajes;
use Symfony\Component\HttpFoundation\Response;

class ListasCostosController extends Controller {
    
    public function listarAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $arListasCostos = new \Brasa\InventarioBundle\Entity\InvListasCostos();
        $arListasCostos = $em->getRepository('BrasaInventarioBundle:InvListasCostos')->findAll();
        if ($request->getMethod() == 'POST') {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrControles = $request->request->All();
            switch ($request->request->get('OpSubmit')) {                       
                case "OpInactivarListas"; 
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoLista) {
                            $arListaCosto = new \Brasa\InventarioBundle\Entity\InvListasCostos();                            
                            $arListaCosto = $em->getRepository('BrasaInventarioBundle:InvListasCostos')->find($codigoLista);                            
                            if($arListaCosto->getEstadoInactiva() == 1) 
                                $arListaCosto->setEstadoInactiva (0);
                            else
                                $arListaCosto->setEstadoInactiva (1);                            
                            
                            $em->persist($arListaCosto);
                            $em->flush();                                                              
                        }                         
                    }                    
                    break;                     
            }                           
        }         
        return $this->render('BrasaInventarioBundle:Base/ListasCostos:listar.html.twig', array('arListasCostos' => $arListasCostos));
    } 
    
    public function nuevoAction($codigoListaCostosPk = null) { 
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();                               
        if ($request->getMethod() == 'POST') {
            if (($request->request->get('TxtCodigoListaCostos')))
                $arListaCostos = $em->getRepository('BrasaInventarioBundle:InvListasCostos')->find($request->request->get('TxtCodigoListaCostos'));
            else
                $arListaCostos = new \Brasa\InventarioBundle\Entity\InvListasCostos();                        
            $arListaCostos->setNombre($request->request->get('TxtNombre'));
            $em->persist($arListaCostos);
            $em->flush();
            return $this->redirect($this->generateUrl('maestros_inventario_listas_costos_lista'));            
        }        
        $arListaCostos = null;
        if ($codigoListaCostosPk != null && $codigoListaCostosPk != "" && $codigoListaCostosPk != 0)        
            $arListaCostos = $em->getRepository('BrasaInventarioBundle:InvListasCostos')->find($codigoListaCostosPk);        
        return $this->render('BrasaInventarioBundle:Maestros/ListasCostos:nuevo.html.twig', array('arListaCostos' => $arListaCostos));               
        
    }    
    
    public function detalleAction($codigoListaCostosPk = null) {      
        $em = $this->getDoctrine()->getEntityManager();                        
        $request = $this->getRequest(); 
        if ($request->getMethod() == 'POST') {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrControles = $request->request->All();
            switch ($request->request->get('OpSubmit')) {
                case "OpInactivarDetalle"; 
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoLpDetalle) {
                            $arListaCostoDetalle = new \Brasa\InventarioBundle\Entity\InvListasCostosDetalles();                            
                            $arListaCostoDetalle = $em->getRepository('BrasaInventarioBundle:InvListasCostosDetalles')->find($codigoLpDetalle);                            
                            if($arListaCostoDetalle->getEstadoInactiva() == 1) 
                                $arListaCostoDetalle->setEstadoInactiva (0);
                            else
                                $arListaCostoDetalle->setEstadoInactiva (1); 
                            $em->persist($arListaCostoDetalle);
                            $em->flush();                                                              
                        }                         
                    }                    
                    break;                        
            }                           
        }                
        $arListaCostos = new \Brasa\InventarioBundle\Entity\InvListasCostos();
        $arListaCostos = $em->getRepository('BrasaInventarioBundle:InvListasCostos')->find($codigoListaCostosPk);
        $arListasCostosDetalles = new \Brasa\InventarioBundle\Entity\InvListasCostosDetalles();
        $arListasCostosDetalles = $em->getRepository('BrasaInventarioBundle:InvListasCostosDetalles')->findBy(array('codigoListaCostosFk' => $codigoListaCostosPk));                
        return $this->render('BrasaInventarioBundle:Maestros/ListasCostos:detalle.html.twig', array('arListasCostosDetalles' => $arListasCostosDetalles, 'arListaCostos' => $arListaCostos));
    }  
    
    public function detalleNuevoAction($codigoListaCostosPk = null, $codigoListaCostosDetallePk = null) { 
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();    
        $objFunciones = new \Brasa\ExternasBundle\FuncionesZikmont\FuncionesZikmont();
        if ($request->getMethod() == 'POST') {
            if (($request->request->get('TxtCodigoListaCostosDetalle')))
                $arListaCostosDetalle = $em->getRepository('BrasaInventarioBundle:InvListasCostosDetalles')->find($request->request->get('TxtCodigoListaCostosDetalle'));
            else
                $arListaCostosDetalle = new \Brasa\InventarioBundle\Entity\InvListasCostosDetalles();                        
            $arListaCostos = new \Brasa\InventarioBundle\Entity\InvListasCostos();
            $arListaCostos = $em->getRepository('BrasaInventarioBundle:InvListasCostos')->find($codigoListaCostosPk);
            $arListaCostosDetalle->setListaCostosRel($arListaCostos);
            $arItem = new \Brasa\InventarioBundle\Entity\InvItem();
            $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($objFunciones->DevCodigoItem($request->request->get('TxtCodigoItem')));
            $arListaCostosDetalle->setItemRel($arItem);
            $arListaCostosDetalle->setCosto($request->request->get('TxtCosto'));
            $arListaCostosDetalle->setCostoUMM($request->request->get('TxtCosto')/$request->request->get('TxtFactor'));
            $arListaCostosDetalle->setFactor($request->request->get('TxtFactor'));
            
            $em->persist($arListaCostosDetalle);
            $em->flush();
            return $this->redirect($this->generateUrl('maestros_inventario_listas_costos_detalle', array('codigoListaCostosPk' => $codigoListaCostosPk)));            
        }        
        $arListaCostosDetalle = null;
        if ($codigoListaCostosDetallePk != null && $codigoListaCostosDetallePk != "" && $codigoListaCostosDetallePk != 0)        
            $arListaCostosDetalle = $em->getRepository('BrasaInventarioBundle:InvListasCostosDetalles')->find($codigoListaCostosDetallePk);        
        return $this->render('BrasaInventarioBundle:Maestros/ListasCostos:detalleNuevo.html.twig', array(
            'arListaCostosDetalle' => $arListaCostosDetalle,
            'codigoListaCostosPk' => $codigoListaCostosPk));               
        
    }        
}