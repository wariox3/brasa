<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UtilidadesNotificacionesController extends Controller
{
    public function cierreProgramacionPagoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');        
        $form = $this->createFormBuilder()
            ->add('BtnEnviar', 'submit', array('label'  => 'Enviar',))
            ->getForm();
        $form->handleRequest($request);        
        if($form->isValid()) {
            $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL('',0, ""));
            if($form->get('BtnEnviar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        echo $codigoCentroCosto;
                    }
                }
                
                $message = \Swift_Message::newInstance()
                    ->setSubject('Prueba email')
                    ->setFrom('maestradaz3@gmail.com')
                    ->setTo('jefedesarrollo@jgefectivo.com')
                    ->setBody(
                    $this->renderView('BrasaRecursoHumanoBundle:Utilidades/Notificaciones:emailCierreProgramacionPago.html.twig',
                          array('name' => "Mario")
                      ),
            'text/html'                            
                                
                    );
                $this->get('mailer')->send($message);
                
                
            }
        }  
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaFechaPagoDQL());
        $arCentrosCostos = $paginator->paginate($query, $this->get('request')->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Notificaciones:cierreProgramacionPago.html.twig', array(
            'arCentrosCostos' => $arCentrosCostos,
            'form' => $form->createView()
            ));
    }    
}
