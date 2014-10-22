<?php

namespace Brasa\TransporteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportesController extends Controller
{
    public function guiasPendientesDespachoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $form = $this->createFormBuilder()
            ->add('TxtCodigoGuia', 'text', array('label'  => 'Codigo'))
            ->add('TxtNumeroGuia', 'text')
            ->add('TxtCodigoTercero', 'text')
            ->add('TxtNombreTercero', 'text')           
            ->add('TxtFechaDesde', 'date', array('widget' => 'single_text', 'label'  => 'Desde:'))
            ->add('TxtFechaHasta', 'date', array('widget' => 'single_text', 'label'  => 'Hasta:'))
            ->add('Buscar', 'submit')
            ->getForm();        
        $form->handleRequest($request);
        $arGuias = new \Brasa\TransporteBundle\Entity\TteGuias();
        $query = $em->getRepository('BrasaTransporteBundle:TteGuias')->GuiasPendientesDespacho();
        $paginator = $this->get('knp_paginator');        
        $arGuias = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);
        return $this->render('BrasaTransporteBundle:Reportes/Guias:pendientesDespacho.html.twig', array(
            'arGuias' => $arGuias,
            'form' => $form->createView()));
    }   
        
}
