<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class UtilidadesCartaLaboralController extends Controller
{
    var $strDqlLista = "";
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if($request->request->get('OpImprimir')) {
                $codigoContrato = $request->request->get('OpImprimir');
                $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
                if ($arContrato->getEstadoActivo() == 1){
                    $codigoCartaTipo = 5; //vigente
                } else {
                    $codigoCartaTipo = 6; //retirado
                }
                $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCarta();
                $objFormatoCarta->Generar($this, $codigoCartaTipo, date('Y-m-d'), "", $codigoContrato);
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            } 
        }       
                
        $arContratos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Cartas/CartaLaboral:lista.html.twig', array(
            'arContratos' => $arContratos,
            'form' => $form->createView()));
    }         
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();        
        $session = $this->get('session');
        $arrayPropiedadesCentroCosto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                            
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                                            
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->get('session');
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaContratosCartaLaboralDQL(
            $session->get('filtroCodigoCentroCosto'),
            $session->get('filtroIdentificacion')
            );  
    }         
    
    private function filtrarLista($form, Request $request) {
        $session = $this->get('session');        
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);                
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
    }         
    
}
