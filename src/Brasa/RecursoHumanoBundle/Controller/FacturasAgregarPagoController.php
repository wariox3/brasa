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
                    $douBaseAIU = 0;
                    $douAdministracion = 0;
                    $douIngresoMision = 0;
                    foreach ($arrSeleccionados AS $codigoPago) {
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
                        $arFacturaDetallePago = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetallePago();
                        $arFacturaDetallePago->setFacturaRel($arFactura);
                        $arFacturaDetallePago->setVrSalario($arPago->getVrSalario());
                        $arFacturaDetallePago->setVrIngresoBaseCotizacion($arPago->getVrIngresoBaseCotizacion());
                        $arFacturaDetallePago->setVrAdicionalTiempo($arPago->getVrAdicionalTiempo());
                        $arFacturaDetallePago->setVrAdicionalValor($arPago->getVrAdicionalValor());
                        $arFacturaDetallePago->setVrAuxilioTransporte($arPago->getVrAuxilioTransporte());
                        $arFacturaDetallePago->setVrArp($arPago->getVrArp());
                        $arFacturaDetallePago->setVrEps($arPago->getVrEps());
                        $arFacturaDetallePago->setVrPension($arPago->getVrPension());
                        $arFacturaDetallePago->setVrCaja($arPago->getVrCaja());
                        $arFacturaDetallePago->setVrCesantias($arPago->getVrCesantias());
                        $arFacturaDetallePago->setVrVacaciones($arPago->getVrVacaciones());
                        $arFacturaDetallePago->setVrAdministracion($arPago->getVrAdministracion());
                        $em->persist($arFacturaDetallePago); 
                        $douAdministracion = $douAdministracion + $arPago->getVrAdministracion();
                        $douIngresoMision = $douIngresoMision + $arPago->getVrCosto();
                        
                    }                    
                    $arFactura->setVrTotalAdministracion($douAdministracion);
                    $arFactura->setVrIngresoMision($douIngresoMision);                    
                    $em->flush();                    
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
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
