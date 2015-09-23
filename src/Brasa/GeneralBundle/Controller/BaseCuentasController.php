<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenCuentaType;



class BaseCuentasController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoBanco) {
                    $arBanco = new \Brasa\GeneralBundle\Entity\GenBanco();
                    $arBanco = $em->getRepository('BrasaGeneralBundle:GenBanco')->find($codigoBanco);
                    $em->remove($arBanco);
                    $em->flush();
                }
            }            
            
        }
        $arCuentas = new \Brasa\GeneralBundle\Entity\GenCuenta();
        $query = $em->getRepository('BrasaGeneralBundle:GenCuenta')->findAll();
        $arCuentas = $paginator->paginate($query, $this->get('request')->query->get('page', 1),50);

        return $this->render('BrasaGeneralBundle:Base/Cuentas:lista.html.twig', array(
                    'arCuentas' => $arCuentas,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoBanco) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arBanco = new \Brasa\GeneralBundle\Entity\GenBanco();
        if ($codigoBanco != 0)
        {
            $arBanco = $em->getRepository('BrasaGeneralBundle:GenBanco')->find($codigoBanco);
        }    
        $form = $this->createForm(new GenBancoType(), $arBanco);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arBanco = $form->getData();
            $em->persist($arBanco);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_gen_base_bancos'));
        }
        return $this->render('BrasaGeneralBundle:Base/Bancos:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
        
}
