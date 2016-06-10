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
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoInconsistencia')->eliminarProgramacionPago($codigoProgramacionPago);
                $arrInconsistencias = array();                
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
                    //$arProgramacionPagoDetalle->set
                    //Pregunta por el tipo de pension, si es pensionado no le retiene pension (PABLO ARANZAZU 27/04/2016)
                    if ($arContrato->getCodigoTipoPensionFk() == 5){
                        $arProgramacionPagoDetalle->setDescuentoPension(0);
                    }   
                    //dias vacaciones
                    $intDiasVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->dias($arContrato->getCodigoEmpleadoFk(), $arContrato->getCodigoContratoPk(), $arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta());                
                    if($intDiasVacaciones > 0) {                                        
                        $arProgramacionPagoDetalle->setDiasVacaciones($intDiasVacaciones);
                    }           
                    //if($arSoportePago->getCodigoSoportePagoPk() == 3125) {
                    //    echo "hola";
                    //}
                    //dias licencia
                    $intDiasLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicenciaPeriodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta(), $arContrato->getCodigoEmpleadoFk());                
                    if($intDiasLicencia > 0) {                                        
                        $arProgramacionPagoDetalle->setDiasLicencia($intDiasLicencia);
                    }     

                    //dias incapacidad
                    $intDiasIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidadPeriodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta(), $arContrato->getCodigoEmpleadoFk());                
                    if($intDiasIncapacidad > 0) {                                        
                        $arProgramacionPagoDetalle->setDiasIncapacidad($intDiasIncapacidad);
                    }                    
                    
                    if($intDiasVacaciones != $arSoportePago->getVacacion()) {
                        $arrInconsistencias[] = array('inconsistencia' => "El empleado " . $arEmpleado->getNombreCorto() . " tiene vacaciones de " . $arSoportePago->getVacacion() . " dias en turnos y de " . $intDiasVacaciones . " en recurso humano");
                    }
                    
                    if($intDiasLicencia != $arSoportePago->getLicencia()) {
                        $arrInconsistencias[] = array('inconsistencia' => "El empleado " . $arEmpleado->getNombreCorto() . " tiene licencias de " . $arSoportePago->getLicencia() . " dias en turnos y de " . $intDiasLicencia . " en recurso humano");
                    }
                    
                    if($intDiasIncapacidad != $arSoportePago->getIncapacidad()) {
                        $arrInconsistencias[] = array('inconsistencia' => "El empleado " . $arEmpleado->getNombreCorto() . " tiene incapacidades de " . $arSoportePago->getIncapacidad() . " dias en turnos y de " . $intDiasIncapacidad . " en recurso humano");
                    }                    
                    $comentarios = "Diurnas[" . $arSoportePago->getHorasDiurnas() . "] Nocturnas[" . $arSoportePago->getHorasNocturnas() . "], Descanso[" . $arSoportePago->getHorasDescanso() . "]";
                    $arProgramacionPagoDetalle->setComentarios($comentarios);                    
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
                        $this->insertarAdicionalPago($arProgramacionPago, 42, $arSoportePago->getHorasExtrasFestivasDiurnas(), $arEmpleado);
                    }
                    if($arSoportePago->getHorasExtrasFestivasNocturnas() > 0) {
                        $this->insertarAdicionalPago($arProgramacionPago, 43, $arSoportePago->getHorasExtrasFestivasNocturnas(), $arEmpleado);
                    }                    
                }                
                $arProgramacionPago->setInconsistencias(0);
                if(count($arrInconsistencias) > 0) {
                    $arProgramacionPago->setInconsistencias(1);
                    foreach ($arrInconsistencias as $arrInconsistencia) {
                        $arProgramacionPagoInconsistencia = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoInconsistencia();
                        $arProgramacionPagoInconsistencia->setProgramacionPagoRel($arProgramacionPago);
                        $arProgramacionPagoInconsistencia->setInconsistencia($arrInconsistencia['inconsistencia']);
                        $em->persist($arProgramacionPagoInconsistencia);                        
                    }
                }
                $arProgramacionPago->setEmpleadosGenerados(1);
                $arProgramacionPago->setNumeroEmpleados(count($arSoportesPago));
                
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
