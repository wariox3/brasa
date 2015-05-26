<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSaludType;

/**
 * RhuEntidadSalud controller.
 *
 */
class BaseSaludController extends Controller
{

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnPdf', 'submit', array('label'  => 'PDF'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoEntidadSaludPk) {
                    $arSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
                    $arSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->find($codigoEntidadSaludPk);
                    $em->remove($arSalud);
                    $em->flush();
                }
            }
        }
        $arEntidadesSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->findAll();
        $arEntidadesSalud = $paginator->paginate($query, $this->get('request')->query->get('page', 1),10);

        return $this->render('BrasaRecursoHumanoBundle:Base/Salud:listar.html.twig', array(
                    'arEntidadesSalud' => $arEntidadesSalud,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function editarAction($codigoEntidadSaludPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        $arSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->find($codigoEntidadSaludPk);
        $formSalud = $this->createForm(new RhuSaludType(), $arSalud);
        $formSalud->handleRequest($request);
        if ($formSalud->isValid()) {
            // guardar la tarea en la base de datos
            $em->persist($arSalud);
            $arSalud = $formSalud->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_salud_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Salud:new.html.twig', array(
            'formSalud' => $formSalud->createView(),
        ));
    }
    
    public function nuevoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        $formSalud = $this->createForm(new RhuSaludType(), $arSalud);
        $formSalud->handleRequest($request);
        if ($formSalud->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arSalud);
            $arSalud = $formSalud->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_salud_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Salud:nuevo.html.twig', array(
            'formSalud' => $formSalud->createView(),
        ));
    }
    
}
