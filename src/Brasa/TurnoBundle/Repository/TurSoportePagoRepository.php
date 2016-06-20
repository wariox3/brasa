<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSoportePagoRepository extends EntityRepository {
    
    public function listaDql($codigoSoportePagoPeriodo = "") {
        $dql   = "SELECT sp FROM BrasaTurnoBundle:TurSoportePago sp WHERE sp.codigoSoportePagoPeriodoFk = " . $codigoSoportePagoPeriodo;
        return $dql;
    }
    
    public function resumen($dateFechaDesde, $dateFechaHasta, $arSoportePagoPeriodo) {
        $em = $this->getEntityManager();
        $arSoportePagoPeriodoActualizar = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo(); 
        $arSoportePagoPeriodoActualizar = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($arSoportePagoPeriodo->getCodigoSoportePagoPeriodoPk());
        $dql   = "SELECT spd.codigoRecursoFk, "
                . "SUM(spd.descanso) as descanso, "                
                . "SUM(spd.novedad) as novedad, "                
                . "SUM(spd.incapacidad) as incapacidad, "                
                . "SUM(spd.licencia) as licencia, "
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
                . "WHERE spd.codigoSoportePagoPeriodoFk =  " . $arSoportePagoPeriodo->getCodigoSoportePagoPeriodoPk() . " "
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
            if($arSoportePagoPeriodo->getDiasAdicionales() > 0) {
                $arrayResultado[$i]['dias'] += $arSoportePagoPeriodo->getDiasAdicionales();
                $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodo->getDiasAdicionales() * 8;                
            }
            $intHorasPago = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];
            
            /*if($arrayResultado[$i]['incapacidad'] > 0) {
                $arrayResultado[$i]['dias'] += $arrayResultado[$i]['incapacidad'];
                //$arrayResultado[$i]['horasDiurnas'] += $arrayResultado[$i]['incapacidad'] * 8;
            }
            if($arrayResultado[$i]['licencia'] > 0) {
                $arrayResultado[$i]['dias'] += $arrayResultado[$i]['licencia'];
                //$arrayResultado[$i]['horasDiurnas'] += $arrayResultado[$i]['licencia'] * 8;
            }   
            if($arrayResultado[$i]['vacacion'] > 0) {
                $arrayResultado[$i]['dias'] += $arrayResultado[$i]['licencia'];
                //$arrayResultado[$i]['horasDiurnas'] += $arrayResultado[$i]['vacacion'] * 8;
            } 
             * 
             */           
            if($arSoportePagoPeriodoActualizar->getDescansoFestivoFijo()) {
                $arrayResultado[$i]['horasDiurnas'] += $arSoportePagoPeriodoActualizar->getFestivos() * 8;
                $arrayResultado[$i]['descanso'] += $arSoportePagoPeriodoActualizar->getFestivos();
            }            
            $intHoras = $arrayResultado[$i]['horasDescanso'] + $arrayResultado[$i]['horasNovedad'] + $arrayResultado[$i]['horasDiurnas'] + $arrayResultado[$i]['horasNocturnas'] + $arrayResultado[$i]['horasFestivasDiurnas'] + $arrayResultado[$i]['horasFestivasNocturnas'];
            $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
            $arSoportePago->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
            $arSoportePago->setRecursoRel($arRecurso);
            $arSoportePago->setFechaDesde($dateFechaDesde);
            $arSoportePago->setFechaHasta($dateFechaHasta);
            if($arrayResultado[$i]['dias'] > $arSoportePagoPeriodoActualizar->getDiasPeriodo()) {
                $arrayResultado[$i]['dias'] = $arSoportePagoPeriodoActualizar->getDiasPeriodo(); 
            }
            $arSoportePago->setDias($arrayResultado[$i]['dias']);
            $arSoportePago->setDescanso($arrayResultado[$i]['descanso']);
            $arSoportePago->setNovedad($arrayResultado[$i]['novedad']);
            $arSoportePago->setIncapacidad($arrayResultado[$i]['incapacidad']);
            $arSoportePago->setLicencia($arrayResultado[$i]['licencia']);
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
            $em->persist($arSoportePago);   
            
        }
        $arSoportePagoPeriodoActualizar->setRecursos($i);
        $em->persist($arSoportePagoPeriodoActualizar);
        $em->flush();
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportesPago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $arSoportePagoPeriodo->getCodigoSoportePagoPeriodoPk()));
        foreach ($arSoportesPago as $arSoportePago) {
            $strSql = "UPDATE tur_soporte_pago_detalle SET codigo_soporte_pago_fk = " . $arSoportePago->getCodigoSoportePagoPk() . " WHERE codigo_recurso_fk = " . $arSoportePago->getRecursoRel()->getCodigoRecursoPk() . " AND codigo_soporte_pago_periodo_fk = " . $arSoportePagoPeriodo->getCodigoSoportePagoPeriodoPk();           
            $em->getConnection()->executeQuery($strSql);            
        }
        
        return $arrayResultado;        
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

}