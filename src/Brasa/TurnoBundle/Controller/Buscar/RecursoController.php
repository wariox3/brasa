<?php

namespace Brasa\TurnoBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class RecursoController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";


    /**
     * @Route("/tur/burcar/recurso/{campoCodigo}/{campoNombre}", name="brs_tur_buscar_recurso")
     */
    public function buscarAction(Request $request, $campoCodigo, $campoNombre) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->lista();
            }
        }
        $arRecurso = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaTurnoBundle:Buscar:recurso.html.twig', array(
            'arRecursos' => $arRecurso,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/tur/burcar/recurso/{campoCodigo}", name="brs_tur_buscar_recurso2")
     */
    public function buscar2Action(Request $request, $campoCodigo) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->lista();
            }
        }
        $arRecurso = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaTurnoBundle:Buscar:recurso2.html.twig', array(
            'arRecursos' => $arRecurso,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
            ));
    }

    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurRecurso')->buscarDQL(
                $this->strNombre,
                $this->strCodigo,
                "",
                $session->get('filtroTurnoNumeroIdentificacion'),
                "", "", $session->get('filtroTurnoRecursoInactivo')
                );
    }

    private function formularioLista() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->strCodigo))
            ->add('inactivos', 'checkbox', array('required'  => false, 'data' => $session->get('filtroTurnoRecursoInactivo')))
            ->add('TxtNumeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroTurnoNumeroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new Session();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $session->set('filtroTurnoRecursoInactivo', $form->get('inactivos')->getData());
        $session->set('filtroTurnoNumeroIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
    }

}
