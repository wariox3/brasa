<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSoportePagoRepository extends EntityRepository {
    
    public function listaDql($codigoSoportePagoPeriodo = "") {
        $dql   = "SELECT sp FROM BrasaTurnoBundle:TurSoportePago sp WHERE sp.codigoSoportePagoPeriodoFk = " . $codigoSoportePagoPeriodo;
        return $dql;
    }
    
    public function resumen($arSoportePagoPeriodo) {
        $em = $this->getEntityManager();
        $arSoportePagoPeriodoActualizar = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo(); 
        $arSoportePagoPeriodoActualizar = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($arSoportePagoPeriodo->getCodigoSoportePagoPeriodoPk());
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago(); 
        $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $arSoportePagoPeriodo->getCodigoSoportePagoPeriodoPk()));        
        foreach ($arSoportesPago as $arSoportePago) {
            $arSoportePagoAct = new \Brasa\TurnoBundle\Entity\TurSoportePago(); 
            $arSoportePagoAct = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arSoportePago->getCodigoSoportePagoPk());                    
            $dql   = "SELECT spd.codigoRecursoFk, "
                    . "SUM(spd.descanso) as descanso, "                
                    . "SUM(spd.novedad) as novedad, "                
                    . "SUM(spd.incapacidad) as incapacidad, "                
                    . "SUM(spd.licencia) as licencia, "
                    . "SUM(spd.licenciaNoRemunerada) as licenciaNoRemunerada, "
                    . "SUM(spd.vacacion) as vacacion, "
                    . "SUM(spd.ingreso) as ingreso, "
                    . "SUM(spd.retiro) as retiro, "
                    . "SUM(spd.dias) as dias, "
                    . "SUM(spd.horasDescanso) as horasDescanso, "
                    . "SUM(spd.horasNovedad) as horasNovedad, "
                    . "SUM(spd.horasDiurnas) as horasDiurnas, "
                    . "SUM(spd.horasNocturnas) as horasNocturnas, "
                    . "SUM(spd.horasFestivasDiurnas) as horasFestivasDiurnas, "
                    . "SUM(spd.horasFestivasNocturnas) as horasFestivasNocturnas, "                
                    . "SUM(spd.horasExtrasOrdinariasDiurnas) as horasExtrasOrdinariasDiurnas, "
                    . "SUM(spd.horasExtrasOrdinariasNocturnas) as horasExtrasOrdinariasNocturnas, "
                    . "SUM(spd.horasExtrasFestivasDiurnas) as horasExtrasFestivasDiurnas, "
                    . "SUM(spd.horasExtrasFestivasNocturnas) as horasExtrasFestivasNocturnas "
                    . "FROM BrasaTurnoBundle:TurSoportePagoDetalle spd "
                    . "WHERE spd.codigoSoportePagoFk =  " . $arSoportePago->getCodigoSoportePagoPk() . " "
                    . "GROUP BY spd.codigoRecursoFk" ;
            $query = $em->createQuery($dql);
            $arrayResultado = $query->getResult();        
            for($i = 0; $i < count($arrayResultado); $i++){ 
                if($arSoportePagoPeriodo->getDiasAdicionales() > 0) {
                    $arrayResultado[$i]['dias'] += $arSoportePagoPeriodo->getDiasAdicionales();
                    $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodo->getDiasAdicionales() * 8;                
                }
                $intHorasPago = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];

                if($arSoportePagoPeriodoActualizar->getDescansoFestivoFijo()) {
                    $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodoActualizar->getFestivos() * 8;
                    $arrayResultado[$i]['descanso'] += $arSoportePagoPeriodoActualizar->getFestivos();
                }        
                /*if($codigoRecurso == 450) {
                    echo "hola";
                }*/
                $intHoras = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasNovedad'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];                                
                if($arrayResultado[$i]['dias'] > $arSoportePagoPeriodoActualizar->getDiasPeriodo()) {
                    $arrayResultado[$i]['dias'] = $arSoportePagoPeriodoActualizar->getDiasPeriodo(); 
                }
                $arSoportePagoAct->setDias($arrayResultado[$i]['dias']);
                $arSoportePagoAct->setDiasTransporte($arrayResultado[$i]['dias']);
                $arSoportePagoAct->setDescanso($arrayResultado[$i]['descanso']);
                $arSoportePagoAct->setNovedad($arrayResultado[$i]['novedad']);
                $arSoportePagoAct->setIncapacidad($arrayResultado[$i]['incapacidad']);
                $arSoportePagoAct->setLicencia($arrayResultado[$i]['licencia']);
                $arSoportePagoAct->setLicenciaNoRemunerada($arrayResultado[$i]['licenciaNoRemunerada']);
                $arSoportePagoAct->setVacacion($arrayResultado[$i]['vacacion']);
                $arSoportePagoAct->setIngreso($arrayResultado[$i]['ingreso']);
                $arSoportePagoAct->setRetiro($arrayResultado[$i]['retiro']);
                $arSoportePagoAct->setHorasPago($intHorasPago);
                $arSoportePagoAct->setHoras($intHoras);
                $arSoportePagoAct->setHorasDescanso($arrayResultado[$i]['horasDescanso']);
                $arSoportePagoAct->setHorasNovedad($arrayResultado[$i]['horasNovedad']);
                $arSoportePagoAct->setHorasDiurnas($arrayResultado[$i]['horasDiurnas']);
                $arSoportePagoAct->setHorasNocturnas($arrayResultado[$i]['horasNocturnas']);
                $arSoportePagoAct->setHorasFestivasDiurnas($arrayResultado[$i]['horasFestivasDiurnas']);
                $arSoportePagoAct->setHorasFestivasNocturnas($arrayResultado[$i]['horasFestivasNocturnas']);            
                $arSoportePagoAct->setHorasExtrasOrdinariasDiurnas($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
                $arSoportePagoAct->setHorasExtrasOrdinariasNocturnas($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
                $arSoportePagoAct->setHorasExtrasFestivasDiurnas($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
                $arSoportePagoAct->setHorasExtrasFestivasNocturnas($arrayResultado[$i]['horasExtrasFestivasNocturnas']);
                $arSoportePagoAct->setHorasDescansoReales($arrayResultado[$i]['horasDescanso']);
                $arSoportePagoAct->setHorasDiurnasReales($arrayResultado[$i]['horasDiurnas']);
                $arSoportePagoAct->setHorasNocturnasReales($arrayResultado[$i]['horasNocturnas']);
                $arSoportePagoAct->setHorasFestivasDiurnasReales($arrayResultado[$i]['horasFestivasDiurnas']);
                $arSoportePagoAct->setHorasFestivasNocturnasReales($arrayResultado[$i]['horasFestivasNocturnas']);            
                $arSoportePagoAct->setHorasExtrasOrdinariasDiurnasReales($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
                $arSoportePagoAct->setHorasExtrasOrdinariasNocturnasReales($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
                $arSoportePagoAct->setHorasExtrasFestivasDiurnasReales($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
                $arSoportePagoAct->setHorasExtrasFestivasNocturnasReales($arrayResultado[$i]['horasExtrasFestivasNocturnas']);            
                $em->persist($arSoportePagoAct);   

            }
        }
        $arSoportePagoPeriodoActualizar->setRecursos(count($arSoportesPago));
        $em->persist($arSoportePagoPeriodoActualizar);   
        $em->flush();        
    }
    
    public function resumenSoportePago($dateFechaDesde, $dateFechaHasta, $codigoSoportePago) {
        $em = $this->getEntityManager();
        $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago(); 
        $arSoportePago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($codigoSoportePago);
        $dql   = "SELECT spd.codigoRecursoFk, "
                . "SUM(spd.descanso) as descanso, "                
                . "SUM(spd.novedad) as novedad, "                
                . "SUM(spd.incapacidad) as incapacidad, "                
                . "SUM(spd.licencia) as licencia, "
                . "SUM(spd.licenciaNoRemunerada) as licenciaNoRemunerada, "
                . "SUM(spd.vacacion) as vacacion, "
                . "SUM(spd.ingreso) as ingreso, "
                . "SUM(spd.retiro) as retiro, "
                . "SUM(spd.dias) as dias, "
                . "SUM(spd.horasDescanso) as horasDescanso, "
                . "SUM(spd.horasNovedad) as horasNovedad, "
                . "SUM(spd.horasDiurnas) as horasDiurnas, "
                . "SUM(spd.horasNocturnas) as horasNocturnas, "
                . "SUM(spd.horasFestivasDiurnas) as horasFestivasDiurnas, "
                . "SUM(spd.horasFestivasNocturnas) as horasFestivasNocturnas, "                
                . "SUM(spd.horasExtrasOrdinariasDiurnas) as horasExtrasOrdinariasDiurnas, "
                . "SUM(spd.horasExtrasOrdinariasNocturnas) as horasExtrasOrdinariasNocturnas, "
                . "SUM(spd.horasExtrasFestivasDiurnas) as horasExtrasFestivasDiurnas, "
                . "SUM(spd.horasExtrasFestivasNocturnas) as horasExtrasFestivasNocturnas "
                . "FROM BrasaTurnoBundle:TurSoportePagoDetalle spd "
                . "WHERE spd.codigoSoportePagoFk =  " . $codigoSoportePago . " "
                . "GROUP BY spd.codigoRecursoFk" ;
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();        
        for($i = 0; $i < count($arrayResultado); $i++){
            $codigoRecurso = $arrayResultado[$i]['codigoRecursoFk'];
            $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
            
            $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $arRecurso->getEmpleadoRel();
            if($arEmpleado->getEstadoContratoActivo()) {                        
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
            } else {
                if($arEmpleado->getCodigoContratoUltimoFk()) {
                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoUltimoFk());       
                }
            } 
            if($arSoportePago->getSoportePagoPeriodoRel()->getDiasAdicionales() > 0) {
                $arrayResultado[$i]['dias'] += $arSoportePagoPeriodo->getDiasAdicionales();
                $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodo->getDiasAdicionales() * 8;                
            }
            $intHorasPago = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];
                      
            if($arSoportePago->getSoportePagoPeriodoRel()->getDescansoFestivoFijo()) {
                $arrayResultado[$i]['horasDiurnas'] += $arSoportePago->getSoportePagoPeriodoRel()->getFestivos() * 8;
                $arrayResultado[$i]['descanso'] += $arSoportePago->getSoportePagoPeriodoRel()->getFestivos();
            }        
            /*if($codigoRecurso == 450) {
                echo "hola";
            }*/
            $intHoras = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasNovedad'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];                        
            if($arrayResultado[$i]['dias'] > $arSoportePago->getSoportePagoPeriodoRel()->getDiasPeriodo()) {
                $arrayResultado[$i]['dias'] = $arSoportePago->getSoportePagoPeriodoRel()->getDiasPeriodo(); 
            }
            $arSoportePago->setDias($arrayResultado[$i]['dias']);            
            $arSoportePago->setDiasTransporte($arrayResultado[$i]['dias']);
            $arSoportePago->setDescanso($arrayResultado[$i]['descanso']);
            $arSoportePago->setNovedad($arrayResultado[$i]['novedad']);
            $arSoportePago->setIncapacidad($arrayResultado[$i]['incapacidad']);
            $arSoportePago->setLicencia($arrayResultado[$i]['licencia']);
            $arSoportePago->setLicenciaNoRemunerada($arrayResultado[$i]['licenciaNoRemunerada']);
            $arSoportePago->setVacacion($arrayResultado[$i]['vacacion']);
            $arSoportePago->setIngreso($arrayResultado[$i]['ingreso']);
            $arSoportePago->setRetiro($arrayResultado[$i]['retiro']);
            $arSoportePago->setHorasPago($intHorasPago);
            $arSoportePago->setHoras($intHoras);
            $arSoportePago->setHorasDescanso($arrayResultado[$i]['horasDescanso']);
            $arSoportePago->setHorasNovedad($arrayResultado[$i]['horasNovedad']);
            $arSoportePago->setHorasDiurnas($arrayResultado[$i]['horasDiurnas']);
            $arSoportePago->setHorasNocturnas($arrayResultado[$i]['horasNocturnas']);
            $arSoportePago->setHorasFestivasDiurnas($arrayResultado[$i]['horasFestivasDiurnas']);
            $arSoportePago->setHorasFestivasNocturnas($arrayResultado[$i]['horasFestivasNocturnas']);            
            $arSoportePago->setHorasExtrasOrdinariasDiurnas($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
            $arSoportePago->setHorasExtrasOrdinariasNocturnas($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
            $arSoportePago->setHorasExtrasFestivasDiurnas($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
            $arSoportePago->setHorasExtrasFestivasNocturnas($arrayResultado[$i]['horasExtrasFestivasNocturnas']);
            $arSoportePago->setHorasDescansoReales($arrayResultado[$i]['horasDescanso']);
            $arSoportePago->setHorasDiurnasReales($arrayResultado[$i]['horasDiurnas']);
            $arSoportePago->setHorasNocturnasReales($arrayResultado[$i]['horasNocturnas']);
            $arSoportePago->setHorasFestivasDiurnasReales($arrayResultado[$i]['horasFestivasDiurnas']);
            $arSoportePago->setHorasFestivasNocturnasReales($arrayResultado[$i]['horasFestivasNocturnas']);            
            $arSoportePago->setHorasExtrasOrdinariasDiurnasReales($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
            $arSoportePago->setHorasExtrasOrdinariasNocturnasReales($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
            $arSoportePago->setHorasExtrasFestivasDiurnasReales($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
            $arSoportePago->setHorasExtrasFestivasNocturnasReales($arrayResultado[$i]['horasExtrasFestivasNocturnas']);            
            if($arContrato) {
                $arSoportePago->setCodigoContratoFk($arContrato->getCodigoContratoPk());
                $arSoportePago->setVrSalario($arContrato->getVrSalario());
            }
            if($arSoportePago->getRecursoRel()->getCodigoTurnoFijoNominaFk()) {
                $arSoportePago->setTurnoFijo(1);
            }            
            $em->persist($arSoportePago);   
            
        }
        $em->flush();        
        return true;        
    }    
    
    public function generar($arSoportePago = "", $arSoportePagoPeriodo, $intDiaInicial, $intDiaFinal, $arFestivos, $dateFechaDesde, $dateFechaHasta, $codigoRecurso) {
        $em = $this->getEntityManager();
        $turnoFijo = 0;        
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();        
        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);        
        if($arRecurso->getCodigoTurnoFijoNominaFk()) {
            $turnoFijo = 1;
        }   
        $arrTurnoFijo[] = null;
        $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->periodo($dateFechaDesde->format('Y/m/') . "01",$dateFechaHasta->format('Y/m/') . "31", "", $codigoRecurso);
        foreach ($arProgramacionDetalles as $arProgramacionDetalle) { 
            for($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {
                $strFecha = $dateFechaDesde->format('Y/m/') . $i;
                $dateFecha = date_create($strFecha);
                $nuevafecha = strtotime ( '+1 day' , strtotime ( $strFecha ) ) ;
                $dateFecha2 = date ( 'Y/m/j' , $nuevafecha );
                $dateFecha2 = date_create($dateFecha2);
                $boolFestivo = $this->festivo($arFestivos, $dateFecha);
                $boolFestivo2 = $this->festivo($arFestivos, $dateFecha2);
                $strTurno = $this->devuelveTurnoDia($arProgramacionDetalle, $i);                                                                 
                if($turnoFijo == 1) {
                    if(!isset($arrTurnoFijo[$i])) {
                        $arrTurnoFijo[$i] = $strTurno;    
                    } else {
                       if($arrTurnoFijo[$i] == null) {
                          $arrTurnoFijo[$i] = $strTurno; 
                       } else {
                           $strTurno = null;
                       }                       
                    }                    
                }                                                                                
                if($strTurno) {
                    $this->insertarSoportePago($arSoportePago, $arSoportePagoPeriodo, $arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $strTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2);
                }                                   
            }                        
        }        
        $em->flush();
        $em->getRepository('BrasaTurnoBundle:TurSoportePago')->resumenSoportePago($dateFechaDesde, $dateFechaHasta, $arSoportePago->getCodigoSoportePagoPk());                
        $em->getRepository('BrasaTurnoBundle:TurSoportePago')->compensar($arSoportePago->getCodigoSoportePagoPk(), $arSoportePagoPeriodo->getCodigoSoportePagoPeriodoPk());        
        //$em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);                                                
        return true;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $arSoportePago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($codigo);
                $em->remove($arSoportePago);
            }
            $em->flush();
        }
    }     

    public function insertarSoportePago ($arSoportePago, $arSoportePagoPeriodo, $arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $codigoTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2) {        
        $em = $this->getEntityManager();      
        //$arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $strTurnoFijoNomina = $arSoportePagoPeriodo->getRecursoGrupoRel()->getCodigoTurnoFijoNominaFk();
        $strTurnoFijoDescanso = $arSoportePagoPeriodo->getRecursoGrupoRel()->getCodigoTurnoFijoDescansoFk();
        if($arSoportePago->getRecursoRel()->getCodigoTurnoFijoNominaFk()) {
            $strTurnoFijoNomina = $arSoportePago->getRecursoRel()->getCodigoTurnoFijoNominaFk();
        }   
        if($arSoportePago->getRecursoRel()->getCodigoTurnoFijoDescansoFk()) {
            $strTurnoFijoDescanso = $arSoportePago->getRecursoRel()->getCodigoTurnoFijoDescansoFk();
        }        
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
        if($arTurno->getDescanso() == 0 && $arTurno->getNovedad() == 0) {                
            if($strTurnoFijoNomina) {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurnoFijoNomina);
            }                
        }     
        if($arTurno->getDescanso() == 1) {
            if($strTurnoFijoDescanso) {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurnoFijoDescanso);
            }
        }
        
        $intDias = 0;                       
        $intMinutoInicio = (($arTurno->getHoraDesde()->format('i') * 100)/60)/100;
        $intHoraInicio = $arTurno->getHoraDesde()->format('G');        
        $intHoraInicio += $intMinutoInicio;
        $intMinutoFinal = (($arTurno->getHoraHasta()->format('i') * 100)/60)/100;
        $intHoraFinal = $arTurno->getHoraHasta()->format('G');
        $intHoraFinal += $intMinutoFinal;
        $diaSemana = $dateFecha->format('N');
        $diaSemana2 = $dateFecha2->format('N');
        if($arTurno->getNovedad() == 0) {
            $intDias += 1;
        }                    
        if($diaSemana == 7) {
            $boolFestivo = 1;
        }
        if($diaSemana2 == 7) {
            $boolFestivo2 = 1;
        }        
        $arrHoras1 = null;
        if(($intHoraInicio + $intMinutoInicio) <= $intHoraFinal){  
            $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, 0, $arTurno->getNovedad(), $arTurno->getDescanso());
        } else {
            $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, 24, $boolFestivo, 0, $arTurno->getNovedad(), $arTurno->getDescanso());
            $arrHoras1 = $this->turnoHoras(0, 0, $intHoraFinal, $boolFestivo2, $arrHoras['horas'], $arTurno->getNovedad(), $arTurno->getDescanso());                 
        }
        $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
        $arSoportePagoDetalle->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
        $arSoportePagoDetalle->setSoportePagoRel($arSoportePago);
        $arSoportePagoDetalle->setRecursoRel($arProgramacionDetalle->getRecursoRel());
        $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
        $arSoportePagoDetalle->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());            
        $arSoportePagoDetalle->setFecha($dateFecha);
        $arSoportePagoDetalle->setTurnoRel($arTurno);
        $arSoportePagoDetalle->setDescanso($arTurno->getDescanso());
        $arSoportePagoDetalle->setNovedad($arTurno->getNovedad());
        $arSoportePagoDetalle->setIncapacidad($arTurno->getIncapacidad());
        $arSoportePagoDetalle->setLicencia($arTurno->getLicencia());
        $arSoportePagoDetalle->setLicenciaNoRemunerada($arTurno->getLicenciaNoRemunerada());
        $arSoportePagoDetalle->setVacacion($arTurno->getVacacion());
        $arSoportePagoDetalle->setIngreso($arTurno->getIngreso());
        $arSoportePagoDetalle->setRetiro($arTurno->getRetiro());
        $arSoportePagoDetalle->setDias($intDias);
        $arSoportePagoDetalle->setHoras($arTurno->getHorasNomina());        
        $arSoportePagoDetalle->setHorasDiurnas($arrHoras['horasDiurnas']);
        $arSoportePagoDetalle->setHorasNocturnas($arrHoras['horasNocturnas']);
        $arSoportePagoDetalle->setHorasExtrasOrdinariasDiurnas($arrHoras['horasExtrasDiurnas']);
        $arSoportePagoDetalle->setHorasExtrasOrdinariasNocturnas($arrHoras['horasExtrasNocturnas']);
        $arSoportePagoDetalle->setHorasFestivasDiurnas($arrHoras['horasFestivasDiurnas']);
        $arSoportePagoDetalle->setHorasFestivasNocturnas($arrHoras['horasFestivasNocturnas']);        
        $arSoportePagoDetalle->setHorasExtrasFestivasDiurnas($arrHoras['horasExtrasFestivasDiurnas']);
        $arSoportePagoDetalle->setHorasExtrasFestivasNocturnas($arrHoras['horasExtrasFestivasNocturnas']);
        $arSoportePagoDetalle->setHorasDescanso($arrHoras['horasDescanso']);
        $arSoportePagoDetalle->setHorasNovedad($arrHoras['horasNovedad']);
        if($strTurnoFijoNomina) {
            $arSoportePagoDetalle->setHorasDiurnas($arrHoras['horasDiurnas'] + $arrHoras['horasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasFestivasDiurnas(0);
        }
        $em->persist($arSoportePagoDetalle);

        if($arrHoras1) {
            $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
            $arSoportePagoDetalle->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
            $arSoportePagoDetalle->setSoportePagoRel($arSoportePago);
            $arSoportePagoDetalle->setRecursoRel($arProgramacionDetalle->getRecursoRel());
            $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
            $arSoportePagoDetalle->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());
            $arSoportePagoDetalle->setFecha($dateFecha2);
            $arSoportePagoDetalle->setTurnoRel($arTurno);
            $arSoportePagoDetalle->setDescanso($arTurno->getDescanso());
            $arSoportePagoDetalle->setNovedad(0);
            $arSoportePagoDetalle->setDias(0);
            $arSoportePagoDetalle->setHoras($arTurno->getHorasNomina());        
            $arSoportePagoDetalle->setHorasDiurnas($arrHoras1['horasDiurnas']);
            $arSoportePagoDetalle->setHorasNocturnas($arrHoras1['horasNocturnas']);
            $arSoportePagoDetalle->setHorasExtrasOrdinariasDiurnas($arrHoras1['horasExtrasDiurnas']);
            $arSoportePagoDetalle->setHorasExtrasOrdinariasNocturnas($arrHoras1['horasExtrasNocturnas']);
            $arSoportePagoDetalle->setHorasFestivasDiurnas($arrHoras1['horasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasFestivasNocturnas($arrHoras1['horasFestivasNocturnas']);        
            $arSoportePagoDetalle->setHorasExtrasFestivasDiurnas($arrHoras1['horasExtrasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasExtrasFestivasNocturnas($arrHoras1['horasExtrasFestivasNocturnas']);
            $arSoportePagoDetalle->setHorasDescanso($arrHoras1['horasDescanso']);
            $arSoportePagoDetalle->setHorasNovedad($arrHoras1['horasNovedad']);
            $em->persist($arSoportePagoDetalle);            
        }                    
    }    
    
    private function devuelveTurnoDia($arProgramacionDetalle, $intDia) {        
        $strTurno = NULL;
        if($intDia == 1) {
            $strTurno = $arProgramacionDetalle->getDia1();
        }
        if($intDia == 2) {
            $strTurno = $arProgramacionDetalle->getDia2();
        }
        if($intDia == 3) {
            $strTurno = $arProgramacionDetalle->getDia3();
        }
        if($intDia == 4) {
            $strTurno = $arProgramacionDetalle->getDia4();
        }
        if($intDia == 5) {
            $strTurno = $arProgramacionDetalle->getDia5();
        }
        if($intDia == 6) {
            $strTurno = $arProgramacionDetalle->getDia6();
        }
        if($intDia == 7) {
            $strTurno = $arProgramacionDetalle->getDia7();
        }
        if($intDia == 8) {
            $strTurno = $arProgramacionDetalle->getDia8();
        }
        if($intDia == 9) {
            $strTurno = $arProgramacionDetalle->getDia9();
        }
        if($intDia == 10) {
            $strTurno = $arProgramacionDetalle->getDia10();
        }
        if($intDia == 11) {
            $strTurno = $arProgramacionDetalle->getDia11();
        }
        if($intDia == 12) {
            $strTurno = $arProgramacionDetalle->getDia12();
        }
        if($intDia == 13) {
            $strTurno = $arProgramacionDetalle->getDia13();
        }
        if($intDia == 14) {
            $strTurno = $arProgramacionDetalle->getDia14();
        }
        if($intDia == 15) {
            $strTurno = $arProgramacionDetalle->getDia15();
        }
        if($intDia == 16) {
            $strTurno = $arProgramacionDetalle->getDia16();
        }
        if($intDia == 17) {
            $strTurno = $arProgramacionDetalle->getDia17();
        }
        if($intDia == 18) {
            $strTurno = $arProgramacionDetalle->getDia18();
        }
        if($intDia == 19) {
            $strTurno = $arProgramacionDetalle->getDia19();
        }
        if($intDia == 20) {
            $strTurno = $arProgramacionDetalle->getDia20();
        }
        if($intDia == 21) {
            $strTurno = $arProgramacionDetalle->getDia21();
        }
        if($intDia == 22) {
            $strTurno = $arProgramacionDetalle->getDia22();
        }
        if($intDia == 23) {
            $strTurno = $arProgramacionDetalle->getDia23();
        }
        if($intDia == 24) {
            $strTurno = $arProgramacionDetalle->getDia24();
        }
        if($intDia == 25) {
            $strTurno = $arProgramacionDetalle->getDia25();
        }
        if($intDia == 26) {
            $strTurno = $arProgramacionDetalle->getDia26();
        }
        if($intDia == 27) {
            $strTurno = $arProgramacionDetalle->getDia27();
        }
        if($intDia == 28) {
            $strTurno = $arProgramacionDetalle->getDia28();
        }
        if($intDia == 29) {
            $strTurno = $arProgramacionDetalle->getDia29();
        }
        if($intDia == 30) {
            $strTurno = $arProgramacionDetalle->getDia30();
        }        
        if($intDia == 31) {
            $strTurno = $arProgramacionDetalle->getDia31();
        }
        return $strTurno;
    }    

    private function turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, $intHoras, $boolNovedad = 0, $boolDescanso = 0) {        
        if($boolNovedad == 0) {
            $intHorasNocturnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);        
            $intHorasExtrasNocturnas = 0;
            $intTotalHoras = $intHorasNocturnas + $intHoras;
            if($intTotalHoras > 8) {
                $intHorasJornada = 8 - $intHoras;
                if($intHorasJornada >= 1) {
                    $intHorasNocturnasReales = $intHorasNocturnas - $intHorasJornada;
                    $intHorasNocturnas = $intHorasNocturnas - $intHorasNocturnasReales;
                    $intHorasExtrasNocturnas = $intHorasNocturnasReales;
                } else {
                    $intHorasExtrasNocturnas = $intHorasNocturnas;
                    $intHorasNocturnas = 0;
                }
            }

            $intHorasDiurnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 6, 22);            
            $intHorasExtrasDiurnas = 0;
            $intTotalHoras = $intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas + $intHorasDiurnas;
            if($intTotalHoras > 8) {
                $intHorasJornada = 8 - ($intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas);                    
                if($intHorasJornada > 1) {
                    $intHorasDiurnasReales = $intHorasDiurnas - $intHorasJornada;
                    $intHorasDiurnas = $intHorasDiurnas - $intHorasDiurnasReales;
                    $intHorasExtrasDiurnas = $intHorasDiurnasReales;
                } else {
                    $intHorasExtrasDiurnas = $intHorasDiurnas;
                    $intHorasDiurnas = 0;
                }            
            }

            $intHorasNocturnasNoche = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 22, 24); 
            $intHorasExtrasNocturnasNoche = 0;
            $intTotalHoras = $intHorasDiurnas + $intHorasExtrasDiurnas + $intHorasNocturnas + $intHorasNocturnasNoche;                                        
            if($intTotalHoras > 8) {                    
                $intHorasJornada = 8 - ($intHorasNocturnas + $intHorasDiurnas + $intHorasExtrasDiurnas);                    
                if($intHorasJornada > 1) {
                    $intHorasNocturnasNocheReales = $intHorasNocturnasNoche - $intHorasJornada;
                    $intHorasNocturnasNoche = $intHorasNocturnasNoche - $intHorasNocturnasNocheReales;
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNocheReales;                        
                } else {
                    $intHorasExtrasNocturnasNoche = $intHorasNocturnasNoche;
                    $intHorasNocturnasNoche = 0;
                }
            }
            $intHorasNocturnas += $intHorasNocturnasNoche;        
            $intHorasExtrasNocturnas += $intHorasExtrasNocturnasNoche;

            $intHorasFestivasDiurnas = 0;
            $intHorasFestivasNocturnas = 0;
            $intHorasExtrasFestivasDiurnas = 0;
            $intHorasExtrasFestivasNocturnas = 0;
            if($boolFestivo == 1) {
                $intHorasFestivasDiurnas = $intHorasDiurnas;
                $intHorasDiurnas = 0;
                $intHorasFestivasNocturnas = $intHorasNocturnas;
                $intHorasNocturnas = 0;
                $intHorasExtrasFestivasDiurnas = $intHorasExtrasDiurnas;
                $intHorasExtrasDiurnas = 0;
                $intHorasExtrasFestivasNocturnas = $intHorasExtrasNocturnas;
                $intHorasExtrasNocturnas = 0;
            }                
            $intTotalHoras = $intHorasDiurnas+$intHorasNocturnas+$intHorasExtrasDiurnas+$intHorasExtrasNocturnas+$intHorasFestivasDiurnas+$intHorasFestivasNocturnas+$intHorasExtrasFestivasDiurnas+$intHorasExtrasFestivasNocturnas;            
            if($boolDescanso == 1) {                
                $arrHoras = array(
                    'horasDescanso' => $intTotalHoras,
                    'horasNovedad' => 0,
                    'horasDiurnas' => 0, 
                    'horasNocturnas' => 0, 
                    'horasExtrasDiurnas' => 0, 
                    'horasExtrasNocturnas' => 0,
                    'horasFestivasDiurnas' => 0, 
                    'horasFestivasNocturnas' => 0, 
                    'horasExtrasFestivasDiurnas' => 0, 
                    'horasExtrasFestivasNocturnas' => 0,
                    'horas' => $intTotalHoras);                
            } else {
                $arrHoras = array(
                    'horasDescanso' => 0,
                    'horasNovedad' => 0,
                    'horasDiurnas' => $intHorasDiurnas, 
                    'horasNocturnas' => $intHorasNocturnas, 
                    'horasExtrasDiurnas' => $intHorasExtrasDiurnas, 
                    'horasExtrasNocturnas' => $intHorasExtrasNocturnas,
                    'horasFestivasDiurnas' => $intHorasFestivasDiurnas, 
                    'horasFestivasNocturnas' => $intHorasFestivasNocturnas, 
                    'horasExtrasFestivasDiurnas' => $intHorasExtrasFestivasDiurnas, 
                    'horasExtrasFestivasNocturnas' => $intHorasExtrasFestivasNocturnas,
                    'horas' => $intTotalHoras);                
            }
            
        } else {
            $arrHoras = array(
                'horasDescanso' => 0,
                'horasNovedad' => 8,
                'horasDiurnas' => 0, 
                'horasNocturnas' => 0, 
                'horasExtrasDiurnas' => 0, 
                'horasExtrasNocturnas' => 0,
                'horasFestivasDiurnas' => 0, 
                'horasFestivasNocturnas' => 0, 
                'horasExtrasFestivasDiurnas' => 0, 
                'horasExtrasFestivasNocturnas' => 0,
                'horas' => 0);            
        }  
        
        return $arrHoras;
    }    
    
    public function festivo($arFestivos, $dateFecha) {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }

    private function calcularTiempo($intInicial, $intFinal, $intParametroInicio, $intParametroFinal) {
        $intHoras = 0;
        $intHoraIniciaTemporal = 0;
        $intHoraTerminaTemporal = 0;
        if($intInicial < $intParametroInicio) {
            $intHoraIniciaTemporal = $intParametroInicio;
        } else {
            $intHoraIniciaTemporal = $intInicial;
        }
        if($intFinal > $intParametroFinal) {
            if($intInicial > $intParametroFinal) {
                $intHoraTerminaTemporal = $intInicial;
            } else {
                $intHoraTerminaTemporal = $intParametroFinal;
            }
        } else {
            if($intFinal > $intParametroInicio) {
                $intHoraTerminaTemporal = $intFinal;
            } else {
                $intHoraTerminaTemporal = $intParametroInicio;
            }
        }
        $intHoras = $intHoraTerminaTemporal - $intHoraIniciaTemporal;
        return $intHoras;
    }    
         
    public function compensar($codigoSoportePago = "", $codigoSoportePagoPeriodo = "") {
        $em = $this->getEntityManager();
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);        
        $descanso = $arSoportePagoPeriodo->getDiasDescansoFijo();
        $diasPeriodo = $arSoportePagoPeriodo->getDiasPeriodo();
        $horasPeriodo =  $diasPeriodo * 8;
        $horasDescanso = $descanso * 8;                
        $horasTope = $horasPeriodo - $horasDescanso;
        //Semanas para ausentismo y descontar descansos
        $arrSemanas = array();
        $arrDomingos = array();
        $dateFechaDesde = $arSoportePagoPeriodo->getFechaDesde();
        $dateFechaHasta = $arSoportePagoPeriodo->getFechaHasta();
        $intDiaInicial = $dateFechaDesde->format('j');
        $intDiaFinal = $dateFechaHasta->format('j');               
        $diaInicialSemana = $intDiaInicial;
        for($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {                                    
            $strFecha = $dateFechaDesde->format('Y/m/') . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $dateFecha->format('N');
            if($diaSemana == 7) {
                $arrSemanas[] = array('diaInicial' => $diaInicialSemana, 'diaFinal' => $i, 'fechaInicial' => $dateFechaDesde->format('Y/m/') . $diaInicialSemana, 'fechaFinal' => $dateFechaDesde->format('Y/m/') . $i);                            
                $diaInicialSemana = $i + 1;
                $arrDomingos[] = array('domingo' => $dateFecha);
            }                    
        }
        $arSoportePagos = new \Brasa\TurnoBundle\Entity\TurSoportePago();  
        if($codigoSoportePago != "") {
            $arSoportePagos = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPk' => $codigoSoportePago));
        } else {
            $arSoportePagos = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));                                
        }             

        foreach ($arSoportePagos as $arSoportePago) {
            if($arSoportePago->getTurnoFijo() == 0) {
                $diasDescansoSoportePago = $descanso; 
                $novedadesAfectaDescanso = $arSoportePago->getLicenciaNoRemunerada();
                if($novedadesAfectaDescanso > 0) {
                    $descansoDescontar = 0;
                    foreach ($arrSemanas as $arrSemana) {
                       $numeroLicenciasNoRemuneradas =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->numeroLicenciasNoRemunerada($arSoportePago->getCodigoSoportePagoPk(), $arrSemana['fechaInicial'], $arrSemana['fechaFinal']);
                       if($numeroLicenciasNoRemuneradas > 0) {
                           $descansoDescontar++;
                       }
                    } 
                    if($descansoDescontar <= $diasDescansoSoportePago) {
                        $diasDescansoSoportePago = $diasDescansoSoportePago - $descansoDescontar;
                    } else {
                        $diasDescansoSoportePago = 0;
                    }                        
                }
                if($diasDescansoSoportePago > 0) {
                    $domingosPagados = $this->domingosPagados($arrDomingos, $arSoportePago->getCodigoSoportePagoPk());
                    if($domingosPagados <= $diasDescansoSoportePago) {
                        $diasDescansoSoportePago = $diasDescansoSoportePago - $domingosPagados;
                    } else {
                        $diasDescansoSoportePago = 0;
                    }                
                }             
                $horasDescansoSoportePago = $diasDescansoSoportePago * 8;
                $horasDia = $arSoportePago->getHorasDiurnasReales();
                $horasNoche = $arSoportePago->getHorasNocturnasReales();
                $horasFestivasDia = $arSoportePago->getHorasFestivasDiurnasReales();
                $horasFestivasNoche = $arSoportePago->getHorasFestivasNocturnasReales();
                $horasExtraDia = $arSoportePago->getHorasExtrasOrdinariasDiurnasReales();                    
                $horasExtraNoche = $arSoportePago->getHorasExtrasOrdinariasNocturnasReales();
                $horasExtraFestivasDia = $arSoportePago->getHorasExtrasFestivasDiurnasReales();                    
                $horasExtraFestivasNoche = $arSoportePago->getHorasExtrasFestivasNocturnasReales();                    
                $totalHoras = $horasDia + $horasNoche + $horasFestivasDia + $horasFestivasNoche;
                $horasPorCompensar = $horasTope - $totalHoras;
                $totalExtras = $horasExtraDia + $horasExtraNoche + $horasExtraFestivasDia + $horasExtraFestivasNoche;
                if($horasPorCompensar > $totalExtras) {
                    $horasPorCompensar = $totalExtras;
                }
                $porExtraDiurna = 0;
                $porExtraNocturna = 0;
                $porExtraFestivaDiurna = 0;
                $porExtraFestivaNocturna = 0; 
                if($totalExtras > 0) {
                    $porExtraDiurna = $horasExtraDia / $totalExtras;
                    $porExtraNocturna = $horasExtraNoche / $totalExtras;
                    $porExtraFestivaDiurna = $horasExtraFestivasDia / $totalExtras;
                    $porExtraFestivaNocturna = $horasExtraFestivasNoche / $totalExtras;
                }

                $horasCompensarDia = round($porExtraDiurna * $horasPorCompensar);
                $horasCompensarNoche = round($porExtraNocturna * $horasPorCompensar);
                $horasCompensarFestivaDia = round($porExtraFestivaDiurna * $horasPorCompensar);
                $horasCompensarFestivaNoche = round($porExtraFestivaNocturna * $horasPorCompensar);                    
                //Para tema de redondeo
                $horasCompensadas = $horasCompensarDia + $horasCompensarNoche + $horasCompensarFestivaDia + $horasCompensarFestivaNoche;
                if($horasCompensadas > $horasPorCompensar) {
                    $horasCompensarFestivaNoche -= 1;
                }
                //$horasCompensarFestivaNoche = $this->truncateFloat($porExtraFestivaNocturna * $horasPorCompensar, 1);                    


                $horasDia += $horasCompensarDia;
                $horasNoche += $horasCompensarNoche;
                $horasFestivasDia += $horasCompensarFestivaDia;
                $horasFestivasNoche += $horasCompensarFestivaNoche;
                $horasExtraDia -= $horasCompensarDia;                    
                $horasExtraNoche -= $horasCompensarNoche;
                $horasExtraFestivasDia -= $horasCompensarFestivaDia;                    
                $horasExtraFestivasNoche -= $horasCompensarFestivaNoche;                    

                $arSoportePagoAct = new \Brasa\TurnoBundle\Entity\TurSoportePago();
                $arSoportePagoAct = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arSoportePago->getCodigoSoportePagoPk());                    
                $arSoportePagoAct->setHorasDiurnas($horasDia);
                $arSoportePagoAct->setHorasNocturnas($horasNoche);
                $arSoportePagoAct->setHorasFestivasDiurnas($horasFestivasDia);
                $arSoportePagoAct->setHorasFestivasNocturnas($horasFestivasNoche);
                $arSoportePagoAct->setHorasExtrasOrdinariasDiurnas($horasExtraDia);
                $arSoportePagoAct->setHorasExtrasOrdinariasNocturnas($horasExtraNoche);
                $arSoportePagoAct->setHorasExtrasFestivasDiurnas($horasExtraFestivasDia);
                $arSoportePagoAct->setHorasExtrasFestivasNocturnas($horasExtraFestivasNoche);                                        
                $horasDescansoRecurso = $horasDescansoSoportePago;
                if($diasPeriodo == $arSoportePago->getNovedad()) {
                   $horasDescansoRecurso = 0; 
                }
                $arSoportePagoAct->setHorasDescanso($horasDescansoRecurso);
                $horas = $horasDia + $horasNoche + $horasFestivasDia + $horasFestivasNoche + $horasDescansoRecurso;            
                $arSoportePagoAct->setHoras($horas);
                $em->persist($arSoportePagoAct);                
            } else {
                $arSoportePagoAct = new \Brasa\TurnoBundle\Entity\TurSoportePago();
                $arSoportePagoAct = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arSoportePago->getCodigoSoportePagoPk());                                    
                $horasDia = $arSoportePago->getHorasDiurnasReales();
                $horasNoche = $arSoportePago->getHorasNocturnasReales();
                $horasFestivasDia = $arSoportePago->getHorasFestivasDiurnasReales();
                $horasFestivasNoche = $arSoportePago->getHorasFestivasNocturnasReales();
                $horasExtraDia = $arSoportePago->getHorasExtrasOrdinariasDiurnasReales();                    
                $horasExtraNoche = $arSoportePago->getHorasExtrasOrdinariasNocturnasReales();
                $horasExtraFestivasDia = $arSoportePago->getHorasExtrasFestivasDiurnasReales();                    
                $horasExtraFestivasNoche = $arSoportePago->getHorasExtrasFestivasNocturnasReales();
                
                $arSoportePagoAct->setHorasDiurnas($horasDia);
                $arSoportePagoAct->setHorasNocturnas($horasNoche);
                $arSoportePagoAct->setHorasFestivasDiurnas($horasFestivasDia);
                $arSoportePagoAct->setHorasFestivasNocturnas($horasFestivasNoche);
                $arSoportePagoAct->setHorasExtrasOrdinariasDiurnas($horasExtraDia);
                $arSoportePagoAct->setHorasExtrasOrdinariasNocturnas($horasExtraNoche);
                $arSoportePagoAct->setHorasExtrasFestivasDiurnas($horasExtraFestivasDia);
                $arSoportePagoAct->setHorasExtrasFestivasNocturnas($horasExtraFestivasNoche);                                        
                $arSoportePagoAct->setHorasDescanso(0);
                $horas = $horasDia + $horasNoche + $horasFestivasDia + $horasFestivasNoche;            
                $arSoportePagoAct->setHoras($horas);  
                $em->persist($arSoportePagoAct);
            }
        }
        $em->flush();        
    }
    
    private function domingosPagados($arrDomingos, $codigoSoportePago) {
        $em = $this->getEntityManager();
        $descansosPagados = 0;
        foreach ($arrDomingos as $arrDomingo) {
            $descansoPagado = false;
            $arSoportePagoDetalles = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
            $arSoportePagoDetalles = $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->findBy(array('codigoSoportePagoFk' => $codigoSoportePago, 'fecha' => $arrDomingo['domingo']));
            foreach ($arSoportePagoDetalles as $arSoportePagoDetalle) {
                if($arSoportePagoDetalle->getIncapacidad() || $arSoportePagoDetalle->getVacacion() || $arSoportePagoDetalle->getIngreso() || $arSoportePagoDetalle->getRetiro()) {
                    $descansoPagado = true;
                }
            }
            if($descansoPagado == true) {
                $descansosPagados++;
            }
        }  
        return $descansosPagados;
    }
}