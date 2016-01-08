<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuRegistroVisitaType;

class UtilidadesRegistroVisitaController extends Controller
{
    public function registroAction() {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arRegistroVisita = new \Brasa\RecursoHumanoBundle\Entity\RhuRegistroVisita();
        $arRegistroVisitas = new \Brasa\RecursoHumanoBundle\Entity\RhuRegistroVisita();
        $fechaHoy = new \DateTime('now');
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuRegistroVisita')->RegistroHoy($fechaHoy->format('Y/m/d'));
        $arRegistroVisitas = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 50);
        $form = $this->createForm(new RhuRegistroVisitaType, $arRegistroVisita);         
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arRegistroVisita = $form->getData();                      
            $arHorarioAcceso->setFecha(new \DateTime('now'));
            $em->persist($arHorarioAcceso);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_utilidades_control_acceso_empleado'));
        }            
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/RegistroVisitas:registro.html.twig', array(
        'arRegistroVisita' => $arRegistroVisita,
        'arRegistroVisitas' => $arRegistroVisitas,
        'form' => $form->createView()));
    }  
        
}
