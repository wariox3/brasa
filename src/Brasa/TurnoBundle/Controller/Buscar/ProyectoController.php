<?php

namespace Brasa\TurnoBundle\Controller\Buscar;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class ProyectoController extends Controller
{
    var $strDqlLista = "";     
    var $strCodigo = "";
    var $strNombre = "";
    
    /**
     * @Route("/tur/burcar/proyecto/{campoCodigo}/{codigoCliente}", name="brs_tur_buscar_proyecto")
     */    
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
        $arProyectos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaTurnoBundle:Buscar:proyecto.html.twig', array(
            'arProyectos' => $arProyectos,
            'campoCodigo' => $campoCodigo,            
            'form' => $form->createView()
            ));
    }        
    
    private function lista($codigoCliente) {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurProyecto')->listaDQL(
                $this->strCodigo,                
                $this->strNombre,                
                $codigoCliente
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
