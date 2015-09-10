<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class SeguridadSocialPeriodosController extends Controller
{
    var $strDqlLista = "";
    var $strDqlListaDetalle = "";
    var $strDqlListaEmpleados = "";
    var $strDqlListaDetalleAportes = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);        
        $this->listar();
        if($form->isValid()) { 
            if($request->request->get('OpGenerar')) {
                $codigoPeriodo = $request->request->get('OpGenerar');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->generar($codigoPeriodo);                
            }                                   
            if($request->request->get('OpDesgenerar')) {
                $codigoPeriodo = $request->request->get('OpDesgenerar');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->desgenerar($codigoPeriodo);                
            }                                               
        }                            
        $arSsoPeriodos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:lista.html.twig', array(
            'arSsoPeriodos' => $arSsoPeriodos,
            'form' => $form->createView()));
    } 
    
    public function detalleAction($codigoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);        
        $this->listarDetalle($codigoPeriodo);
        if($form->isValid()) { 
            if($request->request->get('OpGenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpGenerar');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->generar($codigoPeriodoDetalle);                
            } 
            if($request->request->get('OpDesgenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpDesgenerar');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->desgenerar($codigoPeriodoDetalle);                
            }             
        }                            
        $arSsoPeriodoDetalles = $paginator->paginate($em->createQuery($this->strDqlListaDetalle), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalle.html.twig', array(
            'arSsoPeriodoDetalles' => $arSsoPeriodoDetalles,
            'form' => $form->createView()));
    }     
    
    public function detalleEmpleadosAction($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);        
        $this->listarEmpleados($arPeriodoDetalle->getCodigoPeriodoFk(), $arPeriodoDetalle->getCodigoSucursalFk());
        if($form->isValid()) {            
            if($form->get('BtnGenerar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalle) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->generar($codigoPeriodoDetalle);
                    }
                }                
            }            
        }                            
        $arSsoPeriodoEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaEmpleados), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:empleados.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arPeriodoEmpleados' => $arSsoPeriodoEmpleados,
            'form' => $form->createView()));
    }         
    
    public function detalleAportesAction($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');        
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);        
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $this->listarDetalleAportes($codigoPeriodoDetalle);
        if($form->isValid()) { 
            if($request->request->get('OpGenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpGenerar');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->generar($codigoPeriodoDetalle);                
            } 
            if($request->request->get('OpDesgenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpDesgenerar');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->desgenerar($codigoPeriodoDetalle);                
            }             
        }                            
        $arSsoAportes = $paginator->paginate($em->createQuery($this->strDqlListaDetalleAportes), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalleAportes.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arSsoAportes' => $arSsoAportes,
            'form' => $form->createView()));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->listaDQL();  
    }   
    
    private function listarDetalle($codigoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlListaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->listaDQL($codigoPeriodo);  
    }
    
    private function listarEmpleados($codigoPeriodo, $codigoSucursal) {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlListaEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->listaDql($codigoPeriodo, $codigoSucursal);  
    }    

    private function listarDetalleAportes($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlListaDetalleAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->listaDQL($codigoPeriodoDetalle);  
    }    
    
}
