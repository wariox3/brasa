<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuIncapacidadType;

class PagosAdicionalesAgregarIncapacidadController extends Controller
{

    public function nuevoAction($codigoCentroCosto) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();       
        $arIncapacidad->setFechaDesde(new \DateTime('now'));
        $arIncapacidad->setFechaHasta(new \DateTime('now'));    
        $arIncapacidad->setCentroCostoRel($arCentroCosto);
        $form = $this->createForm(new RhuIncapacidadType(), $arIncapacidad); 
                    
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arIncapacidad = $form->getData();                          
            $intDias = $arIncapacidad->getFechaDesde()->diff($arIncapacidad->getFechaHasta());
            $intDias = $intDias->format('%a');
            $intDias = $intDias + 1; 
            $arIncapacidad->setCantidad($intDias);
            $arIncapacidad->setCantidadPendiente($intDias);
            $em->persist($arIncapacidad);
            $em->flush();                        
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_incapacidad', array('codigoCentroCosto' => $codigoCentroCosto)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:PagosAdicionales:agregarIncapacidad.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'form' => $form->createView()));
    }
}
