<?php

namespace Brasa\TurnoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class BuscarPuestoController extends Controller
{
    var $strDqlLista = "";     
    var $strCodigo = "";
    var $strNombre = "";
    
    public function listaAction($campoCodigo, $codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista($codigoCliente);
        if ($form->isValid()) {            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->lista();
            }
        }
        $arPuesto = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaTurnoBundle:Buscar:puesto.html.twig', array(
            'arPuestos' => $arPuesto,
            'campoCodigo' => $campoCodigo,            
            'form' => $form->createView()
            ));
    }        
    
    private function lista($codigoCliente) {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurPuesto')->listaDQL(
                $this->strCodigo,
                $codigoCliente,
                $this->strNombre                   
                ); 
    }       
    
    private function formularioLista() {                
        $form = $this->createFormBuilder()                                                
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->strCodigo))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }           

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
    }    
          
}
