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
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
        $salarioMinimo = $arConfiguracion->getVrSalario();
        $totalPension = 0;
        $totalSalud = 0;
        $totalCaja = 0;
        $totalRiesgos = 0;
        $totalSena = 0;
        $totalIcbf = 0;  
        $totalAdministracion = 0;
        $totalGeneral = 0;
        $arContratos = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->contratosPeriodo($arPeriodo->getFechaDesde()->format('Y/m/d'), $arPeriodo->getFechaHasta()->format('Y/m/d'), $arPeriodo->getCodigoClienteFk());      
        foreach($arContratos as $arContrato) {
            //$arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();            
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
            $totalPension += $pension;
            $totalSalud += $salud;
            $totalCaja += $caja;
            $totalRiesgos += $riesgos;
            $totalSena += $sena;
            $totalIcbf += $icbf;             
            $totalAdministracion += $administracion;
            $totalGeneral += $total;
        }
            
        $arPeriodo->setEstadoGenerado(1);
        $arPeriodo->setPension($totalPension);
        $arPeriodo->setSalud($totalSalud);
        $arPeriodo->setCaja($totalCaja);
        $arPeriodo->setRiesgos($totalRiesgos);
        $arPeriodo->setSena($totalSena);
        $arPeriodo->setIcbf($totalIcbf);
        $arPeriodo->setAdministracion($totalAdministracion);
        $arPeriodo->setTotal($totalGeneral);
        $em->persist($arPeriodo);
        $em->flush();        
    }
    
    public function generarPago($codigoPeriodo) {
        $em = $this->getEntityManager();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();                
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);    
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
        $salarioMinimo = $arConfiguracion->getVrSalario();  
        $secuencia = 1;
        $arContratos = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
        $arContratos = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->contratosPeriodo($arPeriodo->getFechaDesde()->format('Y/m/d'), $arPeriodo->getFechaHasta()->format('Y/m/d'), $arPeriodo->getCodigoClienteFk());      
        foreach($arContratos as $arContrato) {  
            $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
            $arEmpleado = $arContrato->getEmpleadoRel();
            $arPeriodoDetallePago = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago();
            $arPeriodoDetallePago->setPeriodoRel($arPeriodo);
            $arPeriodoDetallePago->setEmpleadoRel($arContrato->getEmpleadoRel());
            $arPeriodoDetallePago->setContratoRel($arContrato);
            $arPeriodoDetallePago->setTipoRegistro(2);
            $arPeriodoDetallePago->setSecuencia($secuencia);
            $arPeriodoDetallePago->setTipoDocumento($arEmpleado->getTipoIdentificacionRel()->getCodigoInterface());
            $arPeriodoDetallePago->setTipoCotizante($arContrato->getCodigoTipoCotizanteFk());
            $arPeriodoDetallePago->setSubtipoCotizante($arContrato->getCodigoSubtipoCotizanteFk());
            $arPeriodoDetallePago->setExtranjeroNoObligadoCotizarPension(" ");
            $arPeriodoDetallePago->setColombianoResidenteExterior(" ");
            $arPeriodoDetallePago->setCodigoDepartamentoUbicacionlaboral("05");
            $arPeriodoDetallePago->setCodigoMunicipioUbicacionlaboral("001");
            $arPeriodoDetallePago->setPrimerNombre($arEmpleado->getNombre1());
            $arPeriodoDetallePago->setSegundoNombre($arEmpleado->getNombre2());
            $arPeriodoDetallePago->setPrimerApellido($arEmpleado->getApellido1());
            $arPeriodoDetallePago->setSegundoApellido($arEmpleado->getApellido2());
            $arPeriodoDetallePago->setCodigoEntidadPensionPertenece($arContrato->getEntidadPensionRel()->getCodigoInterface());
            $arPeriodoDetallePago->setCodigoEntidadSaludPertenece($arContrato->getEntidadSaludRel()->getCodigoInterface());
            $arPeriodoDetallePago->setCodigoEntidadCajaPertenece($arContrato->getEntidadCajaRel()->getCodigoInterface());

            //Parametros generales
            $floSalario = $arContrato->getVrSalario();
            $floSuplementario = 0;            
            $floIbcIncapacidades = 0;
            $intDiasLicenciaNoRemunerada = 0;//$arPeriodoEmpleado->getDiasLicencia();
            $intDiasIncapacidadGeneral = 0;
            $intDiasIncapacidadLaboral = 0;
            $intDiasIncapacidades = $intDiasIncapacidadGeneral + $intDiasIncapacidadLaboral;//$arPeriodoEmpleado->getDiasIncapacidadGeneral() + $arPeriodoEmpleado->getDiasIncapacidadLaboral();
            $intDiasLicenciaMaternidad = 0;//$arPeriodoEmpleado->getDiasLicenciaMaternidad();
            $intDiasVacaciones = 0;//$arPeriodoEmpleado->getDiasVacaciones();
            
            if($floSuplementario > 0) {
                $arPeriodoDetallePago->setVariacionTransitoriaSalario('X');
                $arPeriodoDetallePago->setSuplementario($floSuplementario);
            }
            if($intDiasIncapacidadGeneral > 0) {
                $arPeriodoDetallePago->setIncapacidadGeneral('X');
                $arPeriodoDetallePago->setDiasIncapacidadGeneral($intDiasIncapacidadGeneral);
                $floSalarioMesActual = $floSalario + $floSuplementario;   
                $floSalarioMesAnterior = $this->ibcMesAnterior($arEmpleado->getCodigoEmpleadoPk(), $arPeriodoDetallePago->getSsoPeriodoRel()->getMes(), $arPeriodoDetallePago->getSsoPeriodoRel()->getAnio());
                $floIbcIncapacidadGeneral = $this->liquidarIncapacidadGeneral($floSalarioMesActual, $floSalarioMesAnterior, $intDiasIncapacidadGeneral);                        
                $floIbcIncapacidades += $floIbcIncapacidadGeneral;                
            }
            if($intDiasLicenciaMaternidad > 0) {
                $arPeriodoDetallePago->setLicenciaMaternidad('X');
                $arPeriodoDetallePago->setDiasLicenciaMaternidad($intDiasLicenciaMaternidad);
            }       
            if($intDiasIncapacidadLaboral > 0) {
                $arPeriodoDetallePago->setIncapacidadAccidenteTrabajoEnfermedadProfesional($intDiasIncapacidadLaboral);
                $floSalarioMesActual = $floSalario + $floSuplementario;   
                $floSalarioMesAnterior = $this->ibcMesAnterior($arEmpleado->getCodigoEmpleadoPk(), $arPeriodoDetallePago->getSsoPeriodoRel()->getMes(), $arPeriodoDetallePago->getSsoPeriodoRel()->getAnio());
                $floIbcIncapacidadLaboral = $this->liquidarIncapacidadLaboral($floSalarioMesActual, $floSalarioMesAnterior, $intDiasIncapacidadLaboral);                        
                $floIbcIncapacidades += $floIbcIncapacidadLaboral;                                        
            }          
            if($intDiasVacaciones > 0) {
                $arPeriodoDetallePago->setVacaciones('X');
                $arPeriodoDetallePago->setDiasVacaciones($intDiasVacaciones);
            }            
            
            //Dias
            $intDiasCotizar = $this->diasContrato($arPeriodo, $arContrato);            
            $intDiasCotizarPension = $intDiasCotizar - $intDiasLicenciaNoRemunerada;
            $intDiasCotizarSalud = $intDiasCotizar - $intDiasLicenciaNoRemunerada;
            $intDiasCotizarRiesgos = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones;
            $intDiasCotizarCaja = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad;
            if($arContrato->getCodigoTipoCotizanteFk() == '19' || $arContrato->getCodigoTipoCotizanteFk() == '12') {
                $intDiasCotizarPension = 0;
                $intDiasCotizarCaja = 0;
            }            
            if($arContrato->getCodigoTipoCotizanteFk() == '12') {
                $intDiasCotizarRiesgos = 0;
            }             
            $arPeriodoDetallePago->setDiasCotizadosPension($intDiasCotizarPension);
            $arPeriodoDetallePago->setDiasCotizadosSalud($intDiasCotizarSalud);
            $arPeriodoDetallePago->setDiasCotizadosRiesgosProfesionales($intDiasCotizarRiesgos);
            $arPeriodoDetallePago->setDiasCotizadosCajaCompensacion($intDiasCotizarCaja); 
            
            //Ibc
            $floIbcBrutoPension = (($intDiasCotizarPension - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;
            $floIbcBrutoSalud = (($intDiasCotizarSalud - $intDiasIncapacidades) * ($floSalario / 30)) + $floIbcIncapacidades + $floSuplementario;                    
            $floIbcBrutoRiesgos = ($intDiasCotizarRiesgos * ($floSalario / 30)) + $floSuplementario;
            $floIbcBrutoCaja = ($intDiasCotizarCaja * ($floSalario / 30)) + $floSuplementario;

            $floIbcPension = $this->redondearIbc($intDiasCotizarPension, $floIbcBrutoPension, $salarioMinimo);
            $floIbcSalud = $this->redondearIbc($intDiasCotizarSalud, $floIbcBrutoSalud, $salarioMinimo);
            $floIbcRiesgos = $this->redondearIbc($intDiasCotizarRiesgos, $floIbcBrutoRiesgos, $salarioMinimo);
            $floIbcCaja = $this->redondearIbc($intDiasCotizarCaja, $floIbcBrutoCaja, $salarioMinimo);              
            
            if($intDiasCotizarRiesgos <= 0) {
                $floIbcRiesgos = 0;
            }
            $arPeriodoDetallePago->setIbcPension($floIbcPension);
            $arPeriodoDetallePago->setIbcSalud($floIbcSalud);
            $arPeriodoDetallePago->setIbcRiesgosProfesionales($floIbcRiesgos);
            $arPeriodoDetallePago->setIbcCaja($floIbcCaja);                                    

            $floTarifaPension = $arContrato->getPorcentajePension();            
            $floTarifaSalud = $arContrato->getPorcentajeSalud();
            $floTarifaRiesgos = $arContrato->getClasificacionRiesgoRel()->getPorcentaje();
            $floTarifaCaja = 4;
            $floTarifaIcbf = 0;
            $floTarifaSena = 0;
            if($arContrato->getCodigoTipoCotizanteFk() == 19 || $arContrato->getCodigoTipoCotizanteFk() == 12) {
                $floTarifaSalud = 12.5;
            }
            if((($floSalario + $floSuplementario) > (10 * $salarioMinimo))) {
                $floTarifaSalud = 12.5;  
                $floTarifaIcbf = 3;
                $floTarifaSena = 2;                
            }

            $arPeriodoDetallePago->setTarifaPension($floTarifaPension);
            $arPeriodoDetallePago->setTarifaSalud($floTarifaSalud);
            $arPeriodoDetallePago->setTarifaRiesgos($floTarifaRiesgos);
            $arPeriodoDetallePago->setTarifaCaja($floTarifaCaja);
            $arPeriodoDetallePago->setTarifaIcbf($floTarifaIcbf);
            $arPeriodoDetallePago->setTarifaSena($floTarifaSena);

            $floCotizacionFSPSolidaridad = 0;
            $floCotizacionFSPSubsistencia = 0;            
            $floAporteVoluntarioFondoPensionesObligatorias = 0;
            $floCotizacionVoluntariaFondoPensionesObligatorias = 0;

            $floCotizacionPension = $this->redondearAporte($floSalario + $floSuplementario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo);            
            if($floSalario >= ($salarioMinimo * 4)) {
                $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
            }
            $floTotalCotizacion = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
            $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo);
            $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo);
            $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo);
            $floCotizacionIcbf = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaIcbf, $intDiasCotizarCaja, $salarioMinimo);
            $floCotizacionSena = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaSena, $intDiasCotizarCaja, $salarioMinimo);

            $arPeriodoDetallePago->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
            $arPeriodoDetallePago->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
            $arPeriodoDetallePago->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
            $arPeriodoDetallePago->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
            $arPeriodoDetallePago->setTotalCotizacion($floTotalCotizacion);
            $arPeriodoDetallePago->setCotizacionPension($floCotizacionPension);
            $arPeriodoDetallePago->setCotizacionSalud($floCotizacionSalud);
            $arPeriodoDetallePago->setCotizacionRiesgos($floCotizacionRiesgos);
            $arPeriodoDetallePago->setCotizacionCaja($floCotizacionCaja); 
            $arPeriodoDetallePago->setCotizacionIcbf($floCotizacionIcbf);
            $arPeriodoDetallePago->setCotizacionSena($floCotizacionSena);                        
            
            $em->persist($arPeriodoDetallePago);            
            $secuencia++;
        }  
        $arPeriodo->setEstadoPagoGenerado(1);
        $em->persist($arPeriodo);
        $em->flush();        
    }    
    
    public function redondearIbc($intDias, $floIbcBruto, $salarioMinimo) {
        $floIbc = 0;       
        $floIbcRedondedado = round($floIbcBruto, -3, PHP_ROUND_HALF_DOWN);
        $floIbcMinimo = $this->redondearIbcMinimo($intDias, $salarioMinimo);
        $floResiduo = fmod($floIbcBruto, 1000);
        if($floIbcRedondedado < $floIbcMinimo) {
            if($floResiduo > 500) {
                $floIbc = intval($floIbcBruto/1000)*1000+1000;
            } else {
                $floIbc = $floIbcBruto;
            }
            $floIbc = ceil($floIbc);
        } else {
            $floIbc = $floIbcRedondedado;
        }

        return $floIbc;
    }  
    
    public function redondearIbcMinimo ($intDias, $salarioMinimo) {
        $floValorDia = $salarioMinimo / 30;
        $floIbcBruto = intval($intDias * $floValorDia);
        return $floIbcBruto;
    }     
    
    public function redondearAporte($floIbcTotal, $floIbc, $floTarifa, $intDias, $salarioMinimo) {
        $floTarifa = $floTarifa / 100;
        $floIbcBruto = ($floIbcTotal / 30) * $intDias;        
        $floCotizacionRedondeada = round($floIbc * $floTarifa, -2, PHP_ROUND_HALF_DOWN);        
        $floCotizacionCalculada = $floIbcBruto * $floTarifa;
        $floCotizacionIBC = $floIbc * $floTarifa;
        $floResiduo = fmod($floCotizacionIBC, 100);
        $floCotizacionMinimo = $this->redondearAporteMinimo($floTarifa, $intDias, $salarioMinimo);
        if($floCotizacionRedondeada < $floCotizacionMinimo) {
            if($floResiduo > 50) {
                $floCotizacionRedondeada = intval($floCotizacionIBC/100) * 100 + 100;
            } else {
                if($floCotizacionIBC - $floResiduo >= $floCotizacionCalculada) {
                    $floCotizacionRedondeada = $floCotizacionIBC - $floResiduo;
                } else {
                    $floCotizacionRedondeada = $floCotizacionIBC;
                }
            }

            if(round($floCotizacionRedondeada) >= $floCotizacionCalculada) {
                $floCotizacion = round($floCotizacionRedondeada);
            } else {
                $floCotizacion = ceil($floCotizacionRedondeada);                                
            }
        } else {
            $floCotizacion = $floCotizacionRedondeada;
        }
        return $floCotizacion;
    }

    public function redondearAporteMinimo($floTarifa, $intDias, $salarioMinimo) {
        $floSalario = $salarioMinimo;
        $douValorDia = $floSalario / 30;
        $floIbcReal = $douValorDia * $intDias;
        if($intDias != 30) {
            $floIbcRedondeo = round($floIbcReal, -3, PHP_ROUND_HALF_DOWN);
            if($floIbcRedondeo > $floIbcReal) {
                $floIbc = ceil($floIbcRedondeo);
            } else {
                $floIbc = ceil($floIbcReal);
            }

        } else {
            $floIbc = $floSalario;
        }
        $douCotizacion = 0;
        $floCotizacionCalculada = $floIbcReal * $floTarifa;
        $floCotizacionIBC = $floIbc * $floTarifa;
        $floResiduo = fmod($floCotizacionIBC, 100);
        if($floResiduo > 50) {
            $floCotizacionRedondeada = intval($floCotizacionIBC/100) * 100 + 100;
        } else {
            if($floCotizacionIBC - $floResiduo >= $floCotizacionCalculada) {
                $floCotizacionRedondeada = $floCotizacionIBC - $floResiduo;
            } else {
                $floCotizacionRedondeada = $floCotizacionIBC;
            }
        }

        if(round($floCotizacionRedondeada) >= $floCotizacionCalculada) {
            $douCotizacion = round($floCotizacionRedondeada);
        } else {
            $douCotizacion = ceil($floCotizacionRedondeada);
        }
        return $douCotizacion;
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
    
    public function pendienteDql($codigoCliente) {        
        $dql   = "SELECT p FROM BrasaAfiliacionBundle:AfiPeriodo p WHERE p.estadoFacturado = 0 AND p.codigoClienteFk = " . $codigoCliente;
        $dql .= " ORDER BY p.codigoPeriodoPk DESC";
        return $dql;
    }                    
}