<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UtilidadesPagosController extends Controller
{
    public function generarPeriodoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('ChkMostrarInactivos', 'checkbox', array('label'=> '', 'required'  => false,))
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => ''))
            ->add('BtnBuscar', 'submit', array('label'  => 'Filtrar'))            
            ->add('BtnGenerarNomina', 'submit', array('label'  => 'Generar nomina',))
            ->add('BtnGenerarPrima', 'submit', array('label'  => 'Generar prima',))            
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL('',0, ""));
            if($form->get('BtnGenerarNomina')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarProgramacionPago($codigoCentroCosto, 1);
                    }
                }
            }
            if($form->get('BtnGenerarPrima')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarProgramacionPago($codigoCentroCosto, 2);
                    }
                }
            }            
            if($form->get('BtnBuscar')->isClicked()) {
                $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL($form->get('TxtNombre')->getData(), $form->get('ChkMostrarInactivos')->getData(), ""));
            }
        } else {
            $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL($form->get('TxtNombre')->getData(), 1, 0));
        }
        $arCentroCosto = $paginator->paginate($query, $request->query->get('page', 1), 100);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPeriodoPago.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'form' => $form->createView()
            ));
    }  
}
