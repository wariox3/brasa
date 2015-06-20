<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class SeguridadSocialPeriodosController extends Controller
{
    var $strDqlLista = "";
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
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuSSPeriodo')->generar($codigoPeriodo);
                    }
                }                
            }            
        }                            
        $arSSPeriodos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:lista.html.twig', array(
            'arSSPeriodos' => $arSSPeriodos,
            'form' => $form->createView()));
    } 
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSSPeriodo')->listaDQL();  
    }         

}
