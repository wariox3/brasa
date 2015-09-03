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

        }

        $arCierresAnios = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreAnio:lista.html.twig', array(
            'arCierresAnios' => $arCierresAnios,
            'form' => $form->createView()));
    }

    public function cerrarAction($codigoCierreAnio) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_proceso_cierre_anio_cerrar', array('codigoCierreAnio' => $codigoCierreAnio)))
            ->add('salarioMinimo', 'number')
            ->add('auxilioTransporte', 'number')
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        $arCierreAnio = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio();
        $arCierreAnio = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->find($codigoCierreAnio);
        if ($form->isValid()) {  
            $floSalarioMinimo = $form->get('salarioMinimo')->getData();
            $floAuxilioTransporte = $form->get('auxilioTransporte')->getData();
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            $arConfiguracion->setAnioActual($arConfiguracion->getAnioActual() + 1);
            $arConfiguracion->setVrSalario($floSalarioMinimo);
            $arConfiguracion->setVrAuxilioTransporte($floAuxilioTransporte);
            $em->persist($arConfiguracion);            
            $arCierreAnio = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio();
            $arCierreAnio = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->find($codigoCierreAnio);
            $arCierreAnio->setEstadoCerrado(1);
            $em->persist($arCierreAnio);
            $em->flush();            
            return $this->redirect($this->generateUrl('brs_rhu_proceso_cierre_anio'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreAnio:cerrar.html.twig', array(
            'arCierreAnio' => $arCierreAnio,
            'form' => $form->createView()
        ));
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
