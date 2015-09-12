<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class FacturasAgregarServicioCobrarController extends Controller
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
                    foreach ($arrSeleccionados AS $codigoServicioCobrar) {
                        $arServicioCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
                        $arServicioCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->find($codigoServicioCobrar);
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($arServicioCobrar->getCodigoPagoFk());
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arServicioCobrar->getCodigoCentroCostoFk());
                        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($arServicioCobrar->getCodigoProgramacionPagoFk());
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arServicioCobrar->getCodigoEmpleadoFk());
                        if($arServicioCobrar->getEstadoCobrado() == 0) {
                            $arFacturaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
                            $arFacturaDetalle->setPagoRel($arPago);
                            $arFacturaDetalle->setEmpleadoRel($arEmpleado);
                            $arFacturaDetalle->setCentroCostoRel($arCentroCosto);
                            $arFacturaDetalle->setProgramacionPagoRel($arProgramacionPago);
                            $arFacturaDetalle->setFacturaRel($arFactura);
                            $arFacturaDetalle->setServicioCobrarRel($arServicioCobrar);
                            $arFacturaDetalle->setFechaDesde($arServicioCobrar->getFechaDesde());
                            $arFacturaDetalle->setFechaHasta($arServicioCobrar->getFechaHasta());
                            $arFacturaDetalle->setVrSalario($arServicioCobrar->getVrSalario());
                            $arFacturaDetalle->setVrSalarioPeriodo($arServicioCobrar->getVrSalarioPeriodo());
                            $arFacturaDetalle->setVrSalarioEmpleado($arServicioCobrar->getVrSalarioEmpleado());
                            $arFacturaDetalle->setVrDevengado($arServicioCobrar->getVrDevengado());
                            $arFacturaDetalle->setVrDeducciones($arServicioCobrar->getVrDeducciones());
                            $arFacturaDetalle->setVrAdicionalTiempo($arServicioCobrar->getVrAdicionalTiempo());
                            $arFacturaDetalle->setVrAdicionalValor($arServicioCobrar->getVrAdicionalValor());
                            $arFacturaDetalle->setVrAuxilioTransporte($arServicioCobrar->getVrAuxilioTransporte());
                            $arFacturaDetalle->setVrAuxilioTransporteCotizacion($arServicioCobrar->getVrAuxilioTransporteCotizacion());
                            $arFacturaDetalle->setVrArp($arServicioCobrar->getVrArp());
                            $arFacturaDetalle->setVrEps($arServicioCobrar->getVrEps());
                            $arFacturaDetalle->setVrPension($arServicioCobrar->getVrPension());
                            $arFacturaDetalle->setVrCaja($arServicioCobrar->getVrCaja());
                            $arFacturaDetalle->setVrSena($arServicioCobrar->getVrSena());
                            $arFacturaDetalle->setVrIcbf($arServicioCobrar->getVrIcbf());
                            $arFacturaDetalle->setVrCesantias($arServicioCobrar->getVrCesantias());
                            $arFacturaDetalle->setVrVacaciones($arServicioCobrar->getVrVacaciones());
                            $arFacturaDetalle->setVrAdministracion($arServicioCobrar->getVrAdministracion());
                            $arFacturaDetalle->setVrNeto($arServicioCobrar->getVrNeto());
                            $arFacturaDetalle->setVrBruto($arServicioCobrar->getVrBruto());
                            $arFacturaDetalle->setVrTotalCobrar($arServicioCobrar->getVrTotalCobrar());
                            $arFacturaDetalle->setVrCosto($arServicioCobrar->getVrCosto());
                            $arFacturaDetalle->setVrIngresoBaseCotizacion($arServicioCobrar->getVrIngresoBaseCotizacion());
                            $arFacturaDetalle->setEstadoCobrado($arServicioCobrar->getEstadoCobrado());
                            $arFacturaDetalle->setDiasPeriodo($arServicioCobrar->getDiasPeriodo());
                            $em->persist($arFacturaDetalle);                                                     
                            $arServicioCobrar->setEstadoCobrado(1);
                            $em->persist($arServicioCobrar);
                        }                        
                    }                    

                    $em->flush();                    
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->pendienteCobrar($arFactura->getCodigoCentroCostoFk()));        
        $arServiciosCobrar = $paginator->paginate($query, $request->query->get('page', 1), 50);                       
        return $this->render('BrasaRecursoHumanoBundle:Facturas:agregarServicioCobrar.html.twig', array(
            'arServiciosCobrar' => $arServiciosCobrar,
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }
}
