<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;

class UtilidadesProgramacionPagoComprobanteMasivoCorreoController extends Controller
{
    var $strDqlLista = "";
    
    public function listaAction($codigoProgramacionPago = "") {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista($codigoProgramacionPago);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnEnviar')->isClicked()) {
                $codigoProgramacionPago = $form->get('numero')->getData();
                $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                
                if(count($arProgramacionPago) > 0) {
                    if($arProgramacionPago->getEstadoPagado() == 1) {
                        echo "hola";
                    }                     
                }   
            }            
        }                    
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/ProgramacionesPago:comprobanteMasivoCorreo.html.twig', array(            
            'form' => $form->createView()));
    }              
    
    private function formularioLista($codigoProgramacionPago = "") {                

        $form = $this->createFormBuilder()                        
            ->add('numero','text', array('required'  => true, 'data' => $codigoProgramacionPago))                
            ->add('BtnVer', 'submit', array('label'  => 'Ver'))    
            ->add('BtnEnviar', 'submit', array('label'  => 'Enviar'))    
            ->getForm();        
        return $form;
    }                     

}
