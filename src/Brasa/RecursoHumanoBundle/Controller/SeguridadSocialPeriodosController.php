<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class SeguridadSocialPeriodosController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))
            ->getForm();
        $form->handleRequest($request);        
        if($form->isValid()) {            
            if($form->get('BtnGenerar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodo) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuSeguridadSocialPeriodo')->generar($codigoPeriodo);
                    }
                }                
            }            
        }
        $session->set('dqlSeguridadSocialPeriodo', $em->getRepository('BrasaRecursoHumanoBundle:RhuSeguridadSocialPeriodo')->listaDQL(
                ));            
        
        $query = $em->createQuery($session->get('dqlSeguridadSocialPeriodo'));        
        $arSeguridadSocialPeriodos = $paginator->paginate($query, $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:SeguridadSocial/Periodos:lista.html.twig', array(
            'arSeguridadSocialPeriodos' => $arSeguridadSocialPeriodos,
            'form' => $form->createView()));
    }       

}
