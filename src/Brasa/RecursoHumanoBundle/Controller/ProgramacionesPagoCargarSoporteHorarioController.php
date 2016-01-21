<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ProgramacionesPagoCargarSoporteHorarioController extends Controller
{
    public function cargarAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();                
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $form = $this->createFormBuilder()            
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->listaDql());                                
                $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();                       
                $arSoportesPago = $query->getResult();
                foreach ($arSoportesPago as $arSoportePago) {
                    
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $arSoportePago->getEmpleadoRel();
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
                    $floVrDia = $arContrato->getVrSalario() / 30;
                    $floVrHora = $floVrDia / 8;
                    $intHoras = $arSoportePago->getHorasDiurnas();
                    $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                    $arProgramacionPagoDetalle->setEmpleadoRel($arEmpleado);
                    $arProgramacionPagoDetalle->setProgramacionPagoRel($arProgramacionPago);
                    $arProgramacionPagoDetalle->setContratoRel($arContrato);
                    $arProgramacionPagoDetalle->setVrSalario($arContrato->getVrSalario());
                    $arProgramacionPagoDetalle->setFechaDesde($arSoportePago->getFechaDesde());
                    $arProgramacionPagoDetalle->setFechaHasta($arSoportePago->getFechaHasta());
                    $arProgramacionPagoDetalle->setFechaDesdePago($arSoportePago->getFechaDesde());
                    $arProgramacionPagoDetalle->setFechaHastaPago($arSoportePago->getFechaHasta());
                    $arProgramacionPagoDetalle->setHorasPeriodo($intHoras);
                    $arProgramacionPagoDetalle->setHorasPeriodoReales($intHoras);
                    $arProgramacionPagoDetalle->setDias($arSoportePago->getDias());
                    $arProgramacionPagoDetalle->setDiasReales($arSoportePago->getDias());    
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
                $em->flush();                
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }                                   
        }         
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:cargarSoporteHorario.html.twig', array(
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
