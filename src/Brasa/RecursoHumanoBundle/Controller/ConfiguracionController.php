<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuConfiguracionType;

/**
 * RhuConfiguracion controller.
 *
 */
class ConfiguracionController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->getForm(); 
        $form->handleRequest($request);
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->findAll();
        $arConfiguracion = $paginator->paginate($query, $this->get('request')->query->get('page', 1),30);
        return $this->render('BrasaRecursoHumanoBundle:Configuracion:lista.html.twig', array(
                    'arConfiguracion' => $arConfiguracion,
                    'form'=> $form->createView()
        ));
    }
    public function nuevoAction($codigoConfiguracionPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find($codigoConfiguracionPk);            
        $formConfiguracion = $this->createForm(new RhuConfiguracionType(), $arConfiguracion);
        $formConfiguracion->handleRequest($request);
        if ($formConfiguracion->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arConfiguracion);
            $arConfiguracion = $formConfiguracion->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_configuracion_nomina_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Configuracion:nuevo.html.twig', array(
            'formConfiguracion' => $formConfiguracion->createView(),
        ));
    }
    
}
