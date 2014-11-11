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
    
    public function novedadesPendientesAction() {
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
        $arNovedades = new \Brasa\TransporteBundle\Entity\TteNovedades();
        $query = $em->getRepository('BrasaTransporteBundle:TteNovedades')->NovedadesPendientes();
        $paginator = $this->get('knp_paginator');        
        $arNovedades = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);
        return $this->render('BrasaTransporteBundle:Reportes/Guias:novedadesPendientes.html.twig', array(
            'arNovedades' => $arNovedades,
            'form' => $form->createView()));
    }           
    
    public function recibosCajaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $form = $this->createFormBuilder()         
            ->add('TxtFechaDesde', 'date', array('widget' => 'single_text', 'label'  => 'Desde:'))
            ->add('TxtFechaHasta', 'date', array('widget' => 'single_text', 'label'  => 'Hasta:'))
            ->add('Buscar', 'submit')
            ->getForm();        
        $form->handleRequest($request);
        $arRecibosCaja = new \Brasa\TransporteBundle\Entity\TteRecibosCaja();
        $query = $em->getRepository('BrasaTransporteBundle:TteRecibosCaja')->ListaRecibosCaja(date_create(date('Y-m-d')), date_create(date('Y-m-d')));
        $paginator = $this->get('knp_paginator');        
        $arRecibosCaja = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);
        return $this->render('BrasaTransporteBundle:Reportes/Guias:recibosCaja.html.twig', array(
            'arRecibosCaja' => $arRecibosCaja,
            'form' => $form->createView()));
    }               
}
