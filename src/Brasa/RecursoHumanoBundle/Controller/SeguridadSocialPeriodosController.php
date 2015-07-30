<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class SeguridadSocialPeriodosController extends Controller
{
    var $strDqlLista = "";
    var $strDqlListaDetalle = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))
            ->getForm();
        $form->handleRequest($request);        
        $this->listar();
        if($form->isValid()) {            
            if($form->get('BtnGenerar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodo) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->generar($codigoPeriodo);
                    }
                }                
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
        $form = $this->createFormBuilder()
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))
            ->getForm();
        $form->handleRequest($request);        
        $this->listarDetalle($codigoPeriodo);
        if($form->isValid()) {            
            if($form->get('BtnGenerar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodo) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->generar($codigoPeriodo);
                    }
                }                
            }            
        }                            
        $arSsoPeriodoDetalles = $paginator->paginate($em->createQuery($this->strDqlListaDetalle), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalle.html.twig', array(
            'arSsoPeriodoDetalles' => $arSsoPeriodoDetalles,
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

}
