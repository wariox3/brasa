<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ProgramacionesPagoCargarSoporteTurnoController extends Controller
{
    public function cargarAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();                
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $form = $this->createFormBuilder()                        
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($request->request->get('OpCargar')) {
                $codigoSoportePagoPeriodo = $request->request->get('OpCargar');                
                $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();                       
                $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));
                foreach ($arSoportesPago as $arSoportePago) {                    
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $arSoportePago->getRecursoRel()->getEmpleadoRel();
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arSoportePago->getCodigoContratoFk());
                    $floVrDia = $arContrato->getVrSalario() / 30;
                    $floVrHora = $floVrDia / 8;
                    $intHoras = $arSoportePago->getHoras();
                    $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                    $arProgramacionPagoDetalle->setEmpleadoRel($arEmpleado);
                    $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                    $arProgramacionPagoDetalle->setContratoRel($arContrato);
                    $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
                    $arProgramacionPagoDetalle->setSoporteTurno(TRUE);
                    $arProgramacionPagoDetalle->setFechaDesde($arSoportePago->getFechaDesde());
                    $arProgramacionPagoDetalle->setFechaHasta($arSoportePago->getFechaHasta());
                    $arProgramacionPagoDetalle->setFechaDesdePago($arSoportePago->getFechaDesde());
                    $arProgramacionPagoDetalle->setFechaHastaPago($arSoportePago->getFechaHasta());
                    $arProgramacionPagoDetalle->setHorasPeriodo($intHoras);
                    $arProgramacionPagoDetalle->setHorasPeriodoReales($intHoras);
                    $intDias = $arSoportePago->getDias();
                    $arProgramacionPagoDetalle->setDias($intDias);
                    $arProgramacionPagoDetalle->setDiasReales($intDias);    
                    $arProgramacionPagoDetalle->setFactorDia($arContrato->getFactorHorasDia());
                    $arProgramacionPagoDetalle->setVrDia($floVrDia);
                    $arProgramacionPagoDetalle->setVrHora($floVrHora);
                    $em->persist($arProgramacionPagoDetalle);

                    if($arSoportePago->getHorasNocturnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 48, $arSoportePago->getHorasNocturnas(), $arEmpleado);
                    }
                    if($arSoportePago->getHorasFestivasDiurnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 51, $arSoportePago->getHorasFestivasDiurnas(), $arEmpleado);
                    }
                    if($arSoportePago->getHorasFestivasNocturnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 52, $arSoportePago->getHorasFestivasNocturnas(), $arEmpleado);
                    }                    
                    if($arSoportePago->getHorasExtrasOrdinariasDiurnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 44, $arSoportePago->getHorasExtrasOrdinariasDiurnas(), $arEmpleado);
                    }
                    if($arSoportePago->getHorasExtrasOrdinariasNocturnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 45, $arSoportePago->getHorasExtrasOrdinariasNocturnas(), $arEmpleado);
                    }                    
                    if($arSoportePago->getHorasExtrasFestivasDiurnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 43, $arSoportePago->getHorasExtrasFestivasDiurnas(), $arEmpleado);
                    }
                    if($arSoportePago->getHorasExtrasFestivasNocturnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 45, $arSoportePago->getHorasExtrasFestivasNocturnas(), $arEmpleado);
                    }                    
                }
                $arProgramacionPago->setEmpleadosGenerados(1);
                $em->persist($arProgramacionPago);
                $em->flush();             
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                
            }                                                                             
        }  
        
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->findBy(array('estadoGenerado' => 1, 'estadoCerrado' => 0));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:cargarSoporteTurno.html.twig', array(
            'arSoportePagoPeriodos' => $arSoportePagoPeriodo,
            'form' => $form->createView()
            ));
    }
    
    private function insertarAdicionalPago($arProgramacionPago, $codigoPagoConcepto, $cantidad, $arEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($codigoPagoConcepto);                
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
        $arPagoAdicional->setCantidad($cantidad);
        $arPagoAdicional->setEmpleadoRel($arEmpleado);
        $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
        $arPagoAdicional->setTipoAdicional($arPagoConcepto->getTipoAdicional());
        $em->persist($arPagoAdicional);
        return false;
    }
    
}
