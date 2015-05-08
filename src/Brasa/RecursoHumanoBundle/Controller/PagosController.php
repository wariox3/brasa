<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PagosController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        $form->handleRequest($request);        
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p";
        $query = $em->createQuery($dql);        
        $arPagos = $paginator->paginate($query, $request->query->get('page', 1), 10);                               
        
        //$arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        //$arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findAll();                       
        return $this->render('BrasaRecursoHumanoBundle:Pagos:lista.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()));
    }       
    
    public function detalleAction($codigoPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');        
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago));
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnReliquidar', 'submit', array('label'  => 'Reliquidar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPago();
                $objFormatoPago->Generar($this, $codigoPago);
            }
            if($form->get('BtnReliquidar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->liquidar($codigoPago);
                return $this->redirect($this->generateUrl('brs_rhu_pagos_detalle', array('codigoPago' => $codigoPago)));
            }
        }        
        
        return $this->render('BrasaRecursoHumanoBundle:Pagos:detalle.html.twig', array(
                    'arPago' => $arPago,
                    'arPagoDetalles' => $arPagoDetalles,
                    'form' => $form->createView()
                    ));
    }        
}
