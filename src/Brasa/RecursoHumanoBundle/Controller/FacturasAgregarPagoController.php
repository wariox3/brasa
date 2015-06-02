<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class FacturasAgregarPagoController extends Controller
{

    public function listaAction($codigoFactura) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);                
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAgregar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPago) {
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
                        $arFacturaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
                        $arFacturaDetalle->setFacturaRel($arFactura);
                        $arFacturaDetalle->setVrSalario($arPago->getVrSalario());
                        $arFacturaDetalle->setVrIngresoBaseCotizacion($arPago->getVrIngresoBaseCotizacion());
                        $arFacturaDetalle->setVrAdicionalTiempo($arPago->getVrAdicionalTiempo());
                        $arFacturaDetalle->setVrAdicionalValor($arPago->getVrAdicionalValor());
                        $arFacturaDetalle->setVrAuxilioTransporte($arPago->getVrAuxilioTransporte());
                        $arFacturaDetalle->setVrArp($arPago->getVrArp());
                        $arFacturaDetalle->setVrEps($arPago->getVrEps());
                        $arFacturaDetalle->setVrPension($arPago->getVrPension());
                        $arFacturaDetalle->setVrCaja($arPago->getVrCaja());
                        $arFacturaDetalle->setVrCesantias($arPago->getVrCesantias());
                        $arFacturaDetalle->setVrVacaciones($arPago->getVrVacaciones());
                        $arFacturaDetalle->setVrAdministracion($arPago->getVrAdministracion());
                        $em->persist($arFacturaDetalle);                        
                    }                    
                    $em->flush();                    
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->pendienteCobrar($arFactura->getCodigoCentroCostoFk()));        
        $arPagos = $paginator->paginate($query, $request->query->get('page', 1), 50);                       
        return $this->render('BrasaRecursoHumanoBundle:Facturas:agregarPago.html.twig', array(
            'arPagos' => $arPagos,
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }
}
