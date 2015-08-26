<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ProcesoCierreAnioController extends Controller
{
    var $strSqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($request->request->get('OpCerrar')) {
                $codigoAnioCierre = $request->request->get('OpCerrar');               
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                $arConfiguracion->setAnioActual($arConfiguracion->getAnioActual() + 1);
                $em->persist($arConfiguracion);
                $arCierreAnio = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio();
                $arCierreAnio = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->find($codigoAnioCierre);
                $arCierreAnio->setEstadoCerrado(1);
                $em->persist($arCierreAnio);
                $em->flush();
                //$em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generar($codigoProgramacionPago);
                //return $this->redirect($this->generateUrl('brs_rhu_programaciones_pago_lista'));
            }
        }       
                
        $arCierresAnios = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreAnio:lista.html.twig', array(
            'arCierresAnios' => $arCierresAnios,
            'form' => $form->createView()));
    }               
    
    private function formularioLista() {        
        $form = $this->createFormBuilder()                        
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                        
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->listaDql();  
    }         
        
}
