<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiPeriodoRepository extends EntityRepository {  
    
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaAfiliacionBundle:AfiPeriodo p WHERE p.codigoPeriodoPk <> 0";
        $dql .= " ORDER BY p.codigoPeriodoPk";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }     
    
    public function generar($codigoPeriodo) {
        $em = $this->getEntityManager();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();                
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        $administracion = $arPeriodo->getClienteRel()->getAdministracion();
        $arContratos = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->contratosPeriodo($arPeriodo->getFechaDesde()->format('Y/m/d'), $arPeriodo->getFechaHasta()->format('Y/m/d'), $arPeriodo->getCodigoClienteFk());      
        foreach($arContratos as $arContrato) {
            //$arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
            $salarioMinimo = $arConfiguracion->getVrSalario();
            
            $porcentajeIcbf = 3;
            $porcentajeSena = 2;
            $intDias = $this->diasContrato($arPeriodo, $arContrato);
            $salario = $arContrato->getVrSalario();
            $vrDia = $salario / 30;
            $salarioPeriodo = $vrDia * $intDias;            
            $pension = 0;
            $salud = 0;
            $caja = 0;
            $riesgos = 0;
            $sena = 0;
            $icbf = 0;
            if($arContrato->getGeneraPension() == 1) {
                $pension = ($salarioPeriodo * $arContrato->getPorcentajePension())/100;
            }
            if($arContrato->getGeneraSalud() == 1) {
                $salud = ($salarioPeriodo * $arContrato->getPorcentajeSalud())/100;
            }
            if($arContrato->getGeneraCaja() == 1) {
                $caja = ($salarioPeriodo * $arContrato->getPorcentajeCaja())/100;
            }
            if($arContrato->getGeneraRiesgos() == 1) {
                $riesgos = ($salarioPeriodo * $arContrato->getClasificacionRiesgoRel()->getPorcentaje())/100;
            }            

            if($salarioPeriodo >= $salarioMinimo * 4) {
                $icbf = ($salarioPeriodo * $porcentajeIcbf)/100;
                $sena = ($salarioPeriodo * $porcentajeSena)/100;
            }
            $total = $pension + $salud + $caja + $riesgos + $sena + $icbf + $administracion;
            $arPeriodoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle();
            $arPeriodoDetalle->setPeriodoRel($arPeriodo);
            $arPeriodoDetalle->setContratoRel($arContrato);
            $arPeriodoDetalle->setEmpleadoRel($arContrato->getEmpleadoRel());
            $arPeriodoDetalle->setFechaDesde($arPeriodo->getFechaDesde());
            $arPeriodoDetalle->setFechaHasta($arPeriodo->getFechaHasta());           
            $arPeriodoDetalle->setDias($intDias);
            $arPeriodoDetalle->setSalario($arContrato->getVrSalario());
            $arPeriodoDetalle->setPension($pension);
            $arPeriodoDetalle->setSalud($salud);
            $arPeriodoDetalle->setCaja($caja);
            $arPeriodoDetalle->setRiesgos($riesgos);
            $arPeriodoDetalle->setAdministracion($administracion);
            $arPeriodoDetalle->setTotal($total);
            if($arContrato->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                $arPeriodoDetalle->setIngreso(1);
            }
            $em->persist($arPeriodoDetalle);            
        }
            
        $arPeriodo->setEstadoGenerado(1);
        $em->persist($arPeriodo);
        $em->flush();        
    }
    
    public function diasContrato($arPeriodo, $arContrato) {        
        $dateFechaDesde =  "";
        $dateFechaHasta =  "";
        $intDiasDevolver = 0;
        $fechaFinalizaContrato = $arContrato->getFechaHasta();
        if($arContrato->getIndefinido() == 1) {
            $fecha = date_create(date('Y-m-d'));
            date_modify($fecha, '+100000 day');
            $fechaFinalizaContrato = $fecha;
        }
        if($arContrato->getFechaDesde() <  $arPeriodo->getFechaDesde() == true) {
            $dateFechaDesde = $arPeriodo->getFechaDesde();
        } else {
            if($arContrato->getFechaDesde() > $arPeriodo->getFechaHasta() == true) {
                if($arContrato->getFechaDesde() == $arPeriodo->getFechaHasta()) {
                    $dateFechaDesde = $arPeriodo->getFechaHasta();
                    $intDiasDevolver = 1;                        
                } else {
                    $intDiasDevolver = 0;                        
                }

            } else {
                $dateFechaDesde = $arContrato->getFechaDesde();
            }
        }
        if($fechaFinalizaContrato >  $arPeriodo->getFechaHasta() == true) {
            $dateFechaHasta = $arPeriodo->getFechaHasta();
        } else {
            if($fechaFinalizaContrato < $arPeriodo->getFechaDesde() == true) {
                $intDiasDevolver = 0;
            } else {
                $dateFechaHasta = $fechaFinalizaContrato;
            }
        }
        if($dateFechaDesde != "" && $dateFechaHasta != "") {
            $intDias = $dateFechaDesde->diff($dateFechaHasta);
            $intDias = $intDias->format('%a');
            $intDiasDevolver = $intDias + 1;                    
        }         
        return $intDiasDevolver;
    }
}