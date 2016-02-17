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
                    $objFormatoCartaLaboral = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCartaLaboralVigente();
                    $objFormatoCartaLaboral->Generar($this, $codigoContrato);
                } else {
                    $objFormatoCartaLaboral = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCartaLaboralRetirado();
                    $objFormatoCartaLaboral->Generar($this, $codigoContrato);                   
                }
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            } 
            /*if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
                $objFormatoPagos = new \Brasa\RecursoHumanoBundle\Formatos\FormatoListaPagos();
                $objFormatoPagos->Generar($this, $this->strDqlLista);
            }*/
        }       
                
        $arContratos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/CartaLaboral:lista.html.twig', array(
            'arContratos' => $arContratos,
            'form' => $form->createView()));
    }       
    
    public function detalleAction($codigoPago, Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $objMensaje = $this->get('mensajes_brasa');        
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago));
        $arPagoDetallesSede = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede();
        $arPagoDetallesSede = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalleSede')->findBy(array('codigoPagoFk' => $codigoPago));        
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))           
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPago();
                $objFormatoPago->Generar($this, $codigoPago);
            }
        }        
        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Pagos:detalle.html.twig', array(
                    'arPago' => $arPago,
                    'arPagoDetalles' => $arPagoDetalles,                    
                    'arPagoDetallesSede' => $arPagoDetallesSede,                    
                    'form' => $form->createView()
                    ));
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
