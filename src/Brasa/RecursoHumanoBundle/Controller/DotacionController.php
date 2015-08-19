<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDotacionType;

class DotacionController extends Controller
{

    public function nuevoAction($codigoCentroCosto, $codigoEmpleado) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();       
        if($codigoEmpleado != 0) {            
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        } 
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        
        $arDotacion->setFecha(new \DateTime('now'));
        $arDotacion->setCentroCostoRel($arCentroCosto);                    
        $form = $this->createForm(new RhuDotacionType(), $arDotacion); 
                    
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arLicencia = $form->getData();                                       
            if($codigoEmpleado != 0) { 
                $arLicencia->setEmpleadoRel($arEmpleado);                
            }
            $em->persist($arLicencia);
            $em->flush();                        
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_licencia', array('codigoCentroCosto' => $codigoCentroCosto)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Licencias:nuevo.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }
}
