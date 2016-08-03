<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSoportePagoPeriodoRepository extends EntityRepository {
    public function listaDql($boolEstadoCerrado = "", $codigoRecursoGrupo = "") {
        $dql   = "SELECT spp FROM BrasaTurnoBundle:TurSoportePagoPeriodo spp WHERE spp.codigoSoportePagoPeriodoPk <> 0 ";
        if($boolEstadoCerrado == 1 ) {
            $dql .= " AND spp.estadoCerrado = 1";
        }
        if($boolEstadoCerrado == "0") {
            $dql .= " AND spp.estadoCerrado = 0";
        }
        $dql .= " ORDER BY spp.codigoSoportePagoPeriodoPk DESC";
        return $dql;
    }
    
    public function liquidar($codigoSoportePagoPeriodo) {        
        $em = $this->getEntityManager();        
        $intRegistros = 0;
        $vrTotalPago = 0;
        $vrTotalDevengado = 0;
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();        
        $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo); 
        $dql   = "SELECT COUNT(sp.codigoSoportePagoPk) as numeroRegistros "
                . "FROM BrasaTurnoBundle:TurSoportePago sp "
                . "WHERE sp.codigoSoportePagoPeriodoFk =  " . $codigoSoportePagoPeriodo;
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();         
        if($arrayResultado) {
            $intRegistros = $arrayResultado[0]['numeroRegistros'];                    
        }        
        
        $arSoportePagoPeriodo->setRecursos($intRegistros);        
        $diaAuxilioTransporte = 77700 / 30;
        $porRecargoNocturno = 35;
        $porFestivaDiurna = 175;
        $porFestivaNocturna = 210;
        $porExtraOrdinariaDiurna = 125;
        $porExtraOrdinariaNocturna = 175;        
        $porExtraFestivaDiurna = 200;
        $porExtraFestivaNocturna = 250;        
        $arSoportePagos = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportePagos = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo)); 
        foreach ($arSoportePagos as $arSoportePago) {            
            $arSoportePagoAct = new \Brasa\TurnoBundle\Entity\TurSoportePago();                        
            $arSoportePagoAct = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arSoportePago->getCodigoSoportePagoPk()); 
            $salario = $arSoportePago->getVrSalario();
            $vrDia = $salario / 30;
            $vrHora = $vrDia / 8;
            $vrDiurna = $vrHora * ($arSoportePago->getHorasDiurnas() + $arSoportePago->getHorasNocturnas());            
            //Solo el recargo
            $vrNocturna = (($vrHora * $porRecargoNocturno)/100) * $arSoportePago->getHorasNocturnas();
            $vrDescanso = $vrHora * $arSoportePago->getHorasDescanso();
            $vrFestivaDiurna = (($vrHora * $porFestivaDiurna)/100) * $arSoportePago->getHorasFestivasDiurnas();
            $vrFestivaNocturna = (($vrHora * $porFestivaNocturna)/100) * $arSoportePago->getHorasFestivasNocturnas();
            $vrExtraOrdinariaDiurna = (($vrHora * $porExtraOrdinariaDiurna)/100) * $arSoportePago->getHorasExtrasOrdinariasDiurnas();
            $vrExtraOrdinariaNocturna = (($vrHora * $porExtraOrdinariaNocturna)/100) * $arSoportePago->getHorasExtrasOrdinariasNocturnas();                        
            $vrExtraFestivaDiurna = (($vrHora * $porExtraFestivaDiurna)/100) * $arSoportePago->getHorasExtrasFestivasDiurnas();
            $vrExtraFestivaNocturna = (($vrHora * $porExtraFestivaNocturna)/100) * $arSoportePago->getHorasExtrasFestivasNocturnas();            
            $vrAuxilioTransporte = $diaAuxilioTransporte * $arSoportePago->getDias();
            $vrPago = $vrDiurna + $vrNocturna + $vrDescanso + $vrFestivaDiurna + $vrFestivaNocturna + $vrExtraOrdinariaDiurna + $vrExtraOrdinariaNocturna + $vrExtraFestivaDiurna + $vrExtraFestivaNocturna;
            $vrDevengado = $vrPago + $vrAuxilioTransporte;
            $arSoportePagoAct->setVrPago($vrPago);
            $arSoportePagoAct->setVrAuxilioTransporte($vrAuxilioTransporte);
            $arSoportePagoAct->setVrDevengado($vrDevengado);            
            $vrTotalPago += $vrPago;
            $vrTotalDevengado += $vrDevengado;
        }
        $arSoportePagoPeriodo->setVrPago($vrTotalPago);
        $arSoportePagoPeriodo->setVrDevengado($vrTotalDevengado);
        $em->persist($arSoportePagoPeriodo);
        $em->flush();
        return true;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {                                
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigo);                    
                if($arSoportePagoPeriodo->getEstadoGenerado() == 0) {
                    $em->remove($arSoportePagoPeriodo);                    
                }                                     
            }
            $em->flush();
        }
    }     

    public function analizarInconsistencias($codigoSoportePagoPeriodo) {
        $em = $this->getEntityManager();
        $em->getRepository('BrasaTurnoBundle:TurSoportePagoInconsistencia')->limpiar($codigoSoportePagoPeriodo);        
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
        $arSoportePagoPeriodo->setInconsistencias(0);
        $arrInconsistencias = array();
        $arSoportesPagoProcesar = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportesPagoProcesar = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));        
        foreach ($arSoportesPagoProcesar as $arSoportePago) {
            if($arSoportePago->getRecursoRel()) {
                if($arSoportePago->getCodigoContratoFk()) {
                    if($arSoportePago->getRecursoRel()->getCodigoEmpleadoFk() == 4695) {
                        echo "hola";
                    }                    
                    $intDiasVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->dias($arSoportePago->getRecursoRel()->getCodigoEmpleadoFk(), $arSoportePago->getCodigoContratoFk(), $arSoportePagoPeriodo->getFechaDesde(), $arSoportePagoPeriodo->getFechaHasta());                            
                    $intDiasLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicenciaPeriodo31($arSoportePagoPeriodo->getFechaDesde(), $arSoportePagoPeriodo->getFechaHasta(), $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk());                                                
                    $intDiasIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidadPeriodo31($arSoportePagoPeriodo->getFechaDesde(), $arSoportePagoPeriodo->getFechaHasta(), $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk());                
                    if($intDiasVacaciones != $arSoportePago->getVacacion()) {
                        $arrInconsistencias[] = array('inconsistencia' => "Vacaciones de " . $arSoportePago->getVacacion() . " dias en turnos y de " . $intDiasVacaciones . " en recurso humano", 'recurso' => $arSoportePago->getRecursoRel()->getNombreCorto(), 'numeroIdentificacion' => $arSoportePago->getRecursoRel()->getNumeroIdentificacion(), 'codigo'=> $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk());
                    }
                    $intDiasLicenciaSoportePago = $arSoportePago->getLicencia()+$arSoportePago->getLicenciaNoRemunerada();
                    if($intDiasLicencia != $intDiasLicenciaSoportePago) {
                        $arrInconsistencias[] = array('inconsistencia' => "Licencias de " . $intDiasLicenciaSoportePago . " dias en turnos y de " . $intDiasLicencia . " en recurso humano", 'recurso' => $arSoportePago->getRecursoRel()->getNombreCorto(), 'numeroIdentificacion' => $arSoportePago->getRecursoRel()->getNumeroIdentificacion(), 'codigo'=> $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk());
                    }
                    if($intDiasIncapacidad != $arSoportePago->getIncapacidad()) {
                        $arrInconsistencias[] = array('inconsistencia' => "Incapacidades de " . $arSoportePago->getIncapacidad() . " dias en turnos y de " . $intDiasIncapacidad . " en recurso humano", 'recurso' => $arSoportePago->getRecursoRel()->getNombreCorto(), 'numeroIdentificacion' => $arSoportePago->getRecursoRel()->getNumeroIdentificacion(), 'codigo'=> $arSoportePago->getRecursoRel()->getCodigoEmpleadoFk());
                    }                    
                }                
            }
        }
        if(count($arrInconsistencias) > 0) {  
            $arSoportePagoPeriodo->setInconsistencias(1);
            foreach ($arrInconsistencias as $arrInconsistencia) {
                $arSoportePagoInconsistencia = new \Brasa\TurnoBundle\Entity\TurSoportePagoInconsistencia;
                $arSoportePagoInconsistencia->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
                $arSoportePagoInconsistencia->setRecurso($arrInconsistencia['recurso']);
                $arSoportePagoInconsistencia->setNumeroIdentificacion($arrInconsistencia['numeroIdentificacion']);
                $arSoportePagoInconsistencia->setCodigoRecurso($arrInconsistencia['codigo']);
                $arSoportePagoInconsistencia->setDetalle($arrInconsistencia['inconsistencia']);
                $em->persist($arSoportePagoInconsistencia);                        
            }            
        }
        $em->persist($arSoportePagoPeriodo);
        $em->flush();                
    }    
}