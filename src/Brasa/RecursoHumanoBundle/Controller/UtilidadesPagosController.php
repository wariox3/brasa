<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UtilidadesPagosController extends Controller
{
    public function generarPeriodoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
        //$paginator = $this->get('knp_paginator');        
        //$arGuias = new \Brasa\TransporteBundle\Entity\TteGuia();
        //$arGuias = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);                        
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPeriodoPago.html.twig', array(
            'arCentroCosto' => $arCentroCosto
            ));
    }
}
