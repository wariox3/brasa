<?php

namespace Brasa\TransporteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConsultasController extends Controller
{
    public function guiasPendientesDespachoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $form = $this->createFormBuilder()            
            ->add('TxtNumeroGuia', 'text')
            ->add('TxtCodigoTercero', 'text')
            ->add('TxtNombreTercero', 'text')           
            ->add('TxtFechaDesde', 'date', array('widget' => 'single_text', 'label'  => 'Desde:'))
            ->add('TxtFechaHasta', 'date', array('widget' => 'single_text', 'label'  => 'Hasta:'))
            ->add('Ruta', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteRuta',
                'property' => 'nombre', 'empty_value' => "Seleccionar"
            ))                
            ->add('Buscar', 'submit')
            ->getForm();        
        $form->handleRequest($request);
        $arGuias = new \Brasa\TransporteBundle\Entity\TteGuia();
        $query = $em->getRepository('BrasaTransporteBundle:TteGuia')->GuiasPendientesDespacho("", "", "", "","");
        if($form->isValid()) {            
            $query = $em->getRepository('BrasaTransporteBundle:TteGuia')->GuiasPendientesDespacho(
                    $form->get('TxtNumeroGuia')->getData(),
                    $form->get('TxtFechaDesde')->getData(),
                    $form->get('TxtFechaHasta')->getData(),
                    $form->get('TxtCodigoTercero')->getData(),
                    $form->get('Ruta')->getData());                        
        }        
        $paginator = $this->get('knp_paginator');        
        $arGuias = $paginator->paginate($query, $this->get('request')->query->get('page', 1),200);
        return $this->render('BrasaTransporteBundle:Consultas/Guias:pendientesDespacho.html.twig', array(
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
        $arNovedades = new \Brasa\TransporteBundle\Entity\TteNovedad();
        $query = $em->getRepository('BrasaTransporteBundle:TteNovedad')->NovedadesPendientes();
        $paginator = $this->get('knp_paginator');        
        $arNovedades = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);
        return $this->render('BrasaTransporteBundle:Consultas/Guias:novedadesPendientes.html.twig', array(
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
        $arRecibosCaja = new \Brasa\TransporteBundle\Entity\TteReciboCaja();
        $query = $em->getRepository('BrasaTransporteBundle:TteReciboCaja')->ListaRecibosCaja(date_create(date('Y-m-d')), date_create(date('Y-m-d')));
        $paginator = $this->get('knp_paginator');        
        $arRecibosCaja = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);
        return $this->render('BrasaTransporteBundle:Consulta/Guias:recibosCaja.html.twig', array(
            'arRecibosCaja' => $arRecibosCaja,
            'form' => $form->createView()));
    }               
}
