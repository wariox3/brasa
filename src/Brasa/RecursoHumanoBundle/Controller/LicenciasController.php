<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuLicenciaType;

class LicenciasController extends Controller
{

    public function nuevoAction($codigoCentroCosto, $codigoEmpleado) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arLicencia = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();       
        if($codigoEmpleado != 0) {            
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        } 
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $arLicencia->setAfectaTransporte(1);
        $arLicencia->setFechaDesde(new \DateTime('now'));
        $arLicencia->setFechaHasta(new \DateTime('now'));                
        $arLicencia->setCentroCostoRel($arCentroCosto);            
        //$arLicencia->setEstadoCerrada(0);        
        $form = $this->createForm(new RhuLicenciaType(), $arLicencia); 
                    
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arLicencia = $form->getData();                                       
            if($codigoEmpleado != 0) { 
                $arLicencia->setEmpleadoRel($arEmpleado);                
            }
            $intDias = $arLicencia->getFechaDesde()->diff($arLicencia->getFechaHasta());
            $intDias = $intDias->format('%a');
            $intDias = $intDias + 1;
            
            $arLicencia->setCantidad($intDias);
            $arLicencia->setCantidadPendiente($intDias);
            if ($arIncapacidad->getFechaDesde() > $arIncapacidad->getFechaHasta()){
                $objMensaje->Mensaje("error", "La fecha desde no puede ser mayor a la fecha hasta!", $this);
            } else {
                $em->persist($arLicencia);
                $em->flush();                        
                if($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_licencia', array('codigoCentroCosto' => $codigoCentroCosto)));
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }    
            }
        }                

        return $this->render('BrasaRecursoHumanoBundle:Licencias:nuevo.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }
}
