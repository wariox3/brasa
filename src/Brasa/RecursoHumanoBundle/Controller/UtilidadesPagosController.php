<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UtilidadesPagosController extends Controller
{
    public function generarPeriodoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('Generar', 'submit')
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            foreach ($arrSeleccionados AS $codigoCentroCosto) {
                $arCentroCostoProceso = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                $arCentroCostoProceso = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
                if($arCentroCostoProceso->getPagoAbierto() == 0) {
                    $intDias = $arCentroCostoProceso->getPeriodoPagoRel()->getDias();
                    $dateDesde = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y-m-d');                    
                    if($arCentroCostoProceso->getPeriodoPagoRel()->getContinuo() == 1) {                                                
                        $dateDesde = date("Y/m/d", strtotime("$dateDesde +1 day"));                        
                        $dateHasta = date("Y/m/d", strtotime("$dateDesde +$intDias day"));
                    } else {
                        if($arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('m') == '02') {
                            $year = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y');
                            if(date('L',mktime(1,1,1,1,1,$year)) == 1) {
                                $intDiasInhabilesFebrero = 1;
                            } else {
                                $intDiasInhabilesFebrero = 2;
                            }                            
                        }
                        $strMesDesde = $arCentroCostoProceso->getFechaUltimoPagoProgramado()->format('Y/m');
                        $strMesHasta = date("Y/m", strtotime("$dateDesde +$intDias day"));
                        if($strMesDesde != $strMesHasta) {
                            $dateDesde = $strMesHasta . "/01";
                            $intDias = $intDias - 1;
                            $dateHasta = date("Y/m/d", strtotime("$dateDesde +$intDias day"));
                        } else {
                            $intDias = $intDias - 1;
                            $dateDesde = date("Y/m/d", strtotime("$dateDesde +1 day"));                        
                            $dateHasta = date("Y/m/d", strtotime("$dateDesde +$intDias day"));                            
                        }
                    }
                    $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                    $arProgramacionPago->setFechaDesde(date_create($dateDesde));
                    $arProgramacionPago->setFechaHasta(date_create($dateHasta));
                    $arProgramacionPago->setDias($intDias);
                    $arProgramacionPago->setCentroCostoRel($arCentroCostoProceso);
                    //$em->persist($arProgramacionPago);
                    //$arCentroCostoProceso->setPagoAbierto(1);
                    //$arCentroCostoProceso->setFechaUltimoPagoProgramado(date_create($dateHasta));
                    $em->persist($arCentroCostoProceso);
                    $em->flush();
                }
            }
        }
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
        //$paginator = $this->get('knp_paginator');
        //$arGuias = new \Brasa\TransporteBundle\Entity\TteGuia();
        //$arGuias = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPeriodoPago.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'form' => $form->createView()
            ));
    }
}
