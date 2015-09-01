<?php

namespace Brasa\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Brasa\SeguridadBundle\Form\Type\UserType;

class SegUsuariosController extends Controller
{
    var $strDqlLista = "";
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $arUsuarios = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaSeguridadBundle:Usuarios:lista.html.twig', array(
            'form' => $form->createView(),
            'arUsuarios' => $arUsuarios
            ));
    }

    public function nuevoAction($codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        //El error es que se debe colocar el eslas entes de Brasa solo con eso toma la clase
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        if($codigoUsuario != 0) {
            $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($arUsuario->getPassword(), $arUsuario->getSalt());
        }
        $form = $this->createForm(new UserType(), $arUsuario);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $form->getData();
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($arUsuario->getPassword(), $arUsuario->getSalt());
            if($codigoUsuario == 0) {
                $arUsuario->setPassword($password);
            }

            $em->persist($arUsuario);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_seg_admin_usuario_lista'));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function recuperarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->formularioRecuperar();
        $form->handleRequest($request);
        if ($form->isValid()) {            
            return $this->redirect($this->generateUrl('login'));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:recuperar.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaSeguridadBundle:User')->listaDql();
    }

    private function formularioRecuperar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('username', 'text', array('label'  => 'Numero', 'data' => ""))            
            ->add('BtnRecuperar', 'submit', array('label'  => 'Recuperar'))
            ->getForm();
        return $form;
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtNumero', 'text', array('label'  => 'Numero','data' => ""))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

}