<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiPeriodoRepository extends EntityRepository {  
    
    public function listaDql($codigoCliente = "", $boolEstadoCerrado = "", $strDesde = "", $strHasta = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT p,c FROM BrasaAfiliacionBundle:AfiPeriodo p JOIN p.clienteRel c WHERE p.codigoPeriodoPk <> 0";        
        if($codigoCliente != "" ) {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;
        }        
        if($boolEstadoCerrado == 1 ) {
            $dql .= " AND p.estadoCerrado = 1";
        }
        if($boolEstadoCerrado == "0") {
            $dql .= " AND p.estadoCerrado = 0";
        }
        if($strDesde != "") {
            $dql .= " AND p.fechaDesde >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaDesde <='" . $strHasta . "'";
        }
        $dql .= " ORDER BY c.nombreCorto asc";
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
        set_time_limit(0);
        ob_clean();
        $em = $this->getEntityManager();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();                
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        $administracion = $arPeriodo->getClienteRel()->getAdministracion();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
        $salarioMinimo = $arConfiguracion->getVrSalario();
        $totalPension = 0;
        $totalSalud = 0;
        $totalCaja = 0;
        $totalRiesgos = 0;
        $totalSena = 0;
        $totalIcbf = 0;  
        $totalAdministracion = 0;        
        $subtotalGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        $arContratos = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->contratosPeriodo($arPeriodo->getFechaDesde()->format('Y/m/d'), $arPeriodo->getFechaHasta()->format('Y/m/d'), $arPeriodo->getCodigoClienteFk());      
        $numeroContratos = count($arContratos);
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
                $pension = $this->redondearAporte($arContrato->getVrSalario(), $salarioPeriodo, $arContrato->getPorcentajePension(), $intDias, $salarioMinimo);
            }
            if($arContrato->getGeneraSalud() == 1) {
                $salud = ($salarioPeriodo * $arContrato->getPorcentajeSalud())/100;
                $salud = $this->redondearAporte($salarioPeriodo, $salarioPeriodo, $arContrato->getPorcentajeSalud(), $intDias, $salarioMinimo);
            }
            if($arContrato->getGeneraCaja() == 1) {
                $caja = ($salarioPeriodo * $arContrato->getPorcentajeCaja())/100;
                $caja = $this->redondearAporte($salarioPeriodo, $salarioPeriodo, $arContrato->getPorcentajeCaja(), $intDias, $salarioMinimo);
            }
            if($arContrato->getGeneraRiesgos() == 1) {
                $riesgos = ($salarioPeriodo * $arContrato->getClasificacionRiesgoRel()->getPorcentaje())/100;
                $riesgos = $this->redondearAporte($salarioPeriodo, $salarioPeriodo, $arContrato->getClasificacionRiesgoRel()->getPorcentaje(), $intDias, $salarioMinimo);
            }            

            /*if($salarioPeriodo >= $salarioMinimo * 4) {
                $icbf = ($salarioPeriodo * $porcentajeIcbf)/100;
                $sena = ($salarioPeriodo * $porcentajeSena)/100;
            }*/
            $floCotizacionFSPSolidaridad = 0;
            $floCotizacionFSPSubsistencia = 0;
            if($salarioPeriodo >= ($salarioMinimo * 4)) {
                $floCotizacionFSPSolidaridad = round($salarioPeriodo * 0.005, -2, PHP_ROUND_HALF_DOWN);
                $floCotizacionFSPSubsistencia = round($salarioPeriodo * 0.005, -2, PHP_ROUND_HALF_DOWN);
            }
            $totalFondosSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
            $subtotal = $pension + $salud + $caja + $riesgos + $sena + $icbf + $administracion + $totalFondosSolidaridad;
            $iva = round($administracion * $arConfiguracion->getPorcentajeIva() / 100);
            //$iva = $this->redondearCien($iva);
            $total = $subtotal + $iva;
            $total = $this->redondearCien($total);
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
            $arPeriodoDetalle->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
            $arPeriodoDetalle->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSubsistencia);
            $arPeriodoDetalle->setSubtotal($subtotal);
            $arPeriodoDetalle->setIva($iva);
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
            $subtotalGeneral += $subtotal;
            $ivaGeneral += $iva;
            $totalGeneral += $total;
            
        }
        //if($arPeriodo->getClienteRel()->getRedondearCobro() == 1) {
        //    $totalGeneral = $this->redondearCien($totalGeneral);
        //}    
        $arPeriodo->setEstadoGenerado(1);
        $arPeriodo->setPension($totalPension);
        $arPeriodo->setSalud($totalSalud);
        $arPeriodo->setCaja($totalCaja);
        $arPeriodo->setRiesgos($totalRiesgos);
        $arPeriodo->setSena($totalSena);
        $arPeriodo->setIcbf($totalIcbf);
        $arPeriodo->setAdministracion($totalAdministracion);
        $arPeriodo->setSubtotal($subtotalGeneral);
        $arPeriodo->setIva($ivaGeneral);
        $arPeriodo->setTotal($totalGeneral);
        $arPeriodo->setNumeroEmpleados($numeroContratos);
        $em->persist($arPeriodo);
        $em->flush();
        
        // interes por mora
        if ($arPeriodo->getEstadoGenerado() == 1 && $arPeriodo->getEstadoCerrado() == 0 && $arPeriodo->getEstadoFacturado() == 0){
            $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generarInteresMora($codigoPeriodo);
        }
                
    }
    
    public function generarPago($codigoPeriodo) {
        set_time_limit(0);
        ob_clean();
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
            $arPeriodoDetallePago->setAnio($arPeriodo->getAnioPago());
            $arPeriodoDetallePago->setMes($arPeriodo->getMes());
            $arPeriodoDetallePago->setFechaDesde($arPeriodo->getFechaDesde());
            $arPeriodoDetallePago->setFechaHasta($arPeriodo->getFechaHasta());
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
            $arPeriodoDetallePago->setSalarioBasico($arContrato->getVrSalario());
            $arPeriodoDetallePago->setSegundoApellido($arEmpleado->getApellido2());
            $arPeriodoDetallePago->setCodigoEntidadPensionPertenece($arContrato->getEntidadPensionRel()->getCodigoInterface());
            $arPeriodoDetallePago->setCodigoEntidadSaludPertenece($arContrato->getEntidadSaludRel()->getCodigoInterface());
            $arPeriodoDetallePago->setCodigoEntidadCajaPertenece($arContrato->getEntidadCajaRel()->getCodigoInterface());
            $arPeriodoDetallePago->setSucursalRel($arContrato->getSucursalRel());
            //Parametros generales
            $floSalario = $arContrato->getVrSalario();
            $floSalarioIntegral = $arPeriodoDetallePago->getSalarioIntegral();
            $floSuplementario = 0;
            //$floSuplementario = $arPeriodoEmpleado->getVrSuplementario();
            $floSuplementario = 0;
            $intDiasLicenciaNoRemunerada = 0;//$arPeriodoEmpleado->getDiasLicencia();
            $intDiasLicenciaNoRemunerada = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 3);
            $intDiasSuspension = 0;//$arPeriodoEmpleado->getDiasLicencia();
            $intDiasSuspension = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 8);
            
            $floIbcIncapacidades = 0;
            
            $intDiasIncapacidadGeneral = 0; 
            $intDiasIncapacidadGeneral = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 1);
            $intDiasIncapacidadLaboral = 0; 
            $intDiasIncapacidadLaboral = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 2);
            $intDiasIncapacidades = $intDiasIncapacidadGeneral + $intDiasIncapacidadLaboral;//$arPeriodoEmpleado->getDiasIncapacidadGeneral() + $arPeriodoEmpleado->getDiasIncapacidadLaboral();
            $intDiasLicenciaMaternidad = 0;//$arPeriodoEmpleado->getDiasLicenciaMaternidad();
            $intDiasLicenciaMaternidad = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->diasLicencia($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), 5);
            $intDiasVacaciones = 0;//$arPeriodoEmpleado->getDiasVacaciones();
            //$intDiasVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->diasVacacionesDisfrute($arPeriodo->getFechaDesde(), $arPeriodo->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), $arContrato->getCodigoContratoPk());
            $intDiasCotizarPension = $intDiasLicenciaNoRemunerada;
            $intDiasCotizarSalud = $intDiasLicenciaNoRemunerada;
            $intDiasCotizarRiesgos = $intDiasLicenciaNoRemunerada;
            $intDiasCotizarCaja = $intDiasLicenciaNoRemunerada;
            $fechaTerminaCotrato = $arContrato->getFechaHasta()->format('Y-m-d');
            if($arContrato->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                $arPeriodoDetallePago->setIngreso('X'); 
            }
            if($arContrato->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodo->getFechaHasta()) {                    
                $arPeriodoDetallePago->setRetiro('X');                    
            }
            if($arPeriodoDetallePago->getIngreso() == 'X' && $arPeriodoDetallePago->getRetiro() == 'X'){
                $arPeriodoDetallePago->setIngreso(' ');
            }
            if($intDiasIncapacidadGeneral > 0) {
                $arPeriodoDetallePago->setIncapacidadGeneral('X');
                $arPeriodoDetallePago->setDiasIncapacidadGeneral($intDiasIncapacidadGeneral);
                $floSalarioMesActual = $floSalario;   
                
                $floIbcIncapacidadGeneral = $this->liquidarIncapacidadGeneral($floSalarioMesActual, 0, $intDiasIncapacidadGeneral);                        
                $floIbcIncapacidades += $floIbcIncapacidadGeneral;                
            }
            if($intDiasLicenciaMaternidad > 0) {
                $arPeriodoDetallePago->setLicenciaMaternidad('X');
                $arPeriodoDetallePago->setDiasLicenciaMaternidad($intDiasLicenciaMaternidad);
            }       
            if($intDiasIncapacidadLaboral > 0) {
                $arPeriodoDetallePago->setIncapacidadAccidenteTrabajoEnfermedadProfesional($intDiasIncapacidadLaboral);
                $arPeriodoDetallePago->setDiasIncapacidadLaboral($intDiasIncapacidadLaboral);
                
                $floSalarioMesActual = $floSalario + $floSuplementario;   
                
                $floIbcIncapacidadLaboral = $this->liquidarIncapacidadLaboral($floSalarioMesActual, 0, $intDiasIncapacidadLaboral);                        
                $floIbcIncapacidades += $floIbcIncapacidadLaboral;                                        
            }          
                        
            //Dias
            $intDiasCotizar = $this->diasContrato($arPeriodo, $arContrato);            
            $intDiasCotizarPension = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
            $intDiasCotizarSalud = $intDiasCotizar - $intDiasLicenciaNoRemunerada - $intDiasSuspension;
            $intDiasCotizarRiesgos = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasVacaciones - $intDiasSuspension;
            $intDiasCotizarCaja = $intDiasCotizar - $intDiasIncapacidades - $intDiasLicenciaNoRemunerada - $intDiasLicenciaMaternidad - $intDiasSuspension;
            if($arContrato->getCodigoTipoCotizanteFk() == '19' || $arContrato->getCodigoTipoCotizanteFk() == '12' || $arContrato->getCodigoTipoCotizanteFk() == '23') {
                $intDiasCotizarPension = 0;
                $intDiasCotizarCaja = 0;
            }            
            if($arContrato->getCodigoTipoCotizanteFk() == '12' || $arContrato->getCodigoTipoCotizanteFk() == '19') {
                $intDiasCotizarRiesgos = 0;
            }
            if ($arContrato->getCodigoEntidadPensionFk() == 10){ //sin fondo
                $intDiasCotizarPension = 0;
                
            }
            if ($arContrato->getCodigoEntidadCajaFk() == 44){ // sin caja
                $intDiasCotizarCaja = 0;
                
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
            if($intDiasCotizarPension <= 0) {
                $floIbcPension = 0;
            }
            if($intDiasCotizarCaja <= 0) {
                $floIbcCaja = 0;
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
            if ($floIbcRiesgos == 0){
                $floTarifaRiesgos = 0;
            }
            if ($floIbcPension == 0){
                $floTarifaPension = 0;
            }
            if ($floIbcCaja == 0){
                $floTarifaCaja = 0;
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
            $floCotizacionPension = $this->redondearAporte($floSalario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo);            
            if($floSalario >= ($salarioMinimo * 4)) {
                $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
            }
            $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo);
            $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo);
            $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo);
            $floCotizacionIcbf = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaIcbf, $intDiasCotizarCaja, $salarioMinimo);
            $floCotizacionSena = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaSena, $intDiasCotizarCaja, $salarioMinimo);
            $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
            $floTotalFondoSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
            $arPeriodoDetallePago->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
            $arPeriodoDetallePago->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
            $arPeriodoDetallePago->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
            $arPeriodoDetallePago->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
            $arPeriodoDetallePago->setCotizacionPension($floCotizacionPension);
            $arPeriodoDetallePago->setCotizacionSalud($floCotizacionSalud);
            $arPeriodoDetallePago->setCotizacionRiesgos($floCotizacionRiesgos);
            $arPeriodoDetallePago->setCotizacionCaja($floCotizacionCaja); 
            $arPeriodoDetallePago->setCotizacionIcbf($floCotizacionIcbf);
            $arPeriodoDetallePago->setCotizacionSena($floCotizacionSena);                        
            //$arPeriodoDetallePago->setCentroTrabajoCodigoCt($arEmpleado->getContratoRel()->getCodigoCentroCostoFk());
            $floTotalCotizacion = $floTotalFondoSolidaridad + $floTotalCotizacionFondos + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf+$floCotizacionSena;
            $arPeriodoDetallePago->setTotalCotizacion($floTotalCotizacion);
            $em->persist($arPeriodoDetallePago);            
            $secuencia++;
            //Para las licencias segunda linea solo licencias
            if($intDiasLicenciaNoRemunerada > 0 || $intDiasSuspension > 0) {
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
                $arPeriodoDetallePago->setSalarioBasico($arContrato->getVrSalario());
                //Parametros generales
                $floSuplementario = 0;
                $floSuplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->tiempoSuplementario($arPeriodo->getFechaDesde()->format('Y-m-d'), $arPeriodo->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());            
                $floIbcIncapacidades = 0;
                $fechaTerminaCotrato = $arContrato->getFechaHasta()->format('Y-m-d');
                /*if($arContrato->getFechaDesde() >= $arPeriodo->getFechaDesde()) {
                    $arPeriodoDetallePago->setIngreso('X');
                }
                if($arContrato->getIndefinido() == 0 && $fechaTerminaCotrato <= $arPeriodo->getFechaHasta()) {                    
                    $arPeriodoDetallePago->setRetiro('X');
                }*/
                if($intDiasSuspension > 0) {
                    $arPeriodoDetallePago->setSuspensionTemporalContratoLicenciaServicios('X');
                    $arPeriodoDetallePago->setDiasLicencia($intDiasLicenciaNoRemunerada);
                }
                if ($intDiasLicenciaNoRemunerada > 0){
                    $intDiasCotizarPension = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarSalud = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarRiesgos = $intDiasLicenciaNoRemunerada;
                    $intDiasCotizarCaja = $intDiasLicenciaNoRemunerada;
                } else {
                    if ($intDiasSuspension > 0){
                        $intDiasCotizarPension = $intDiasSuspension;
                        $intDiasCotizarSalud = $intDiasSuspension;
                        $intDiasCotizarRiesgos = $intDiasSuspension;
                        $intDiasCotizarCaja = $intDiasSuspension;
                        
                    }
                }         
                
                
                if($arPeriodoDetallePago->getTipoCotizante() == '19' || $arPeriodoDetallePago->getTipoCotizante() == '12' || $arPeriodoDetallePago->getTipoCotizante() == '23') {
                    $intDiasCotizarPension = 0;
                    $intDiasCotizarCaja = 0;
                }
                if($arPeriodoDetallePago->getTipoCotizante() == '12' || $arPeriodoDetallePago->getTipoCotizante() == '19') {
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
                
                if ($intDiasSuspension <=0){
                    $arPeriodoDetallePago->setIbcSalud($floIbcSalud);
                    $arPeriodoDetallePago->setIbcRiesgosProfesionales($floIbcRiesgos);
                    $arPeriodoDetallePago->setIbcCaja($floIbcCaja);
                }
                                                    
                if ($intDiasSuspension > 0){
                    $floTarifaPension = 12;
                }
                $floTarifaSalud = 0;
                $floTarifaRiesgos = 0;
                $floTarifaCaja = 0;
                $arPeriodoDetallePago->setTarifaPension($floTarifaPension);
                $arPeriodoDetallePago->setTarifaSalud($floTarifaSalud);
                $arPeriodoDetallePago->setTarifaRiesgos($floTarifaRiesgos);
                $arPeriodoDetallePago->setTarifaCaja($floTarifaCaja);
                $floCotizacionFSPSolidaridad = 0;
                $floCotizacionFSPSubsistencia = 0; 
                $floAporteVoluntarioFondoPensionesObligatorias = 0;
                $floCotizacionVoluntariaFondoPensionesObligatorias = 0;
                $floCotizacionPension = $this->redondearAporte($floSalario + $floSuplementario, $floIbcPension, $floTarifaPension, $intDiasCotizarPension, $salarioMinimo);            
                if($floSalario >= ($salarioMinimo * 4)) {
                    //$floCotizacionFSPSolidaridad = 0;
                    //$floCotizacionFSPSubsistencia = 0;
                    $floCotizacionFSPSolidaridad = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                    $floCotizacionFSPSubsistencia = round($floIbcPension * 0.005, -2, PHP_ROUND_HALF_DOWN);
                }
                $floCotizacionSalud = $this->redondearAporte($floSalario + $floSuplementario, $floIbcSalud, $floTarifaSalud, $intDiasCotizarSalud, $salarioMinimo);
                $floCotizacionRiesgos = $this->redondearAporte($floSalario + $floSuplementario, $floIbcRiesgos, $floTarifaRiesgos, $intDiasCotizarRiesgos, $salarioMinimo);
                $floCotizacionCaja = $this->redondearAporte($floSalario + $floSuplementario, $floIbcCaja, $floTarifaCaja, $intDiasCotizarCaja, $salarioMinimo);
                $floTotalCotizacionFondos = $floAporteVoluntarioFondoPensionesObligatorias + $floCotizacionVoluntariaFondoPensionesObligatorias + $floCotizacionPension;
                $floTotalFondoSolidaridad = $floCotizacionFSPSolidaridad + $floCotizacionFSPSubsistencia;
                $arPeriodoDetallePago->setAporteVoluntarioFondoPensionesObligatorias($floAporteVoluntarioFondoPensionesObligatorias);
                $arPeriodoDetallePago->setCotizacionVoluntarioFondoPensionesObligatorias($floCotizacionVoluntariaFondoPensionesObligatorias);
                $arPeriodoDetallePago->setAportesFondoSolidaridadPensionalSolidaridad($floCotizacionFSPSolidaridad);
                $arPeriodoDetallePago->setAportesFondoSolidaridadPensionalSubsistencia($floCotizacionFSPSolidaridad);
                //$arPeriodoDetallePago->setTotalCotizacionFondos($floTotalCotizacionFondos);
                $arPeriodoDetallePago->setCotizacionPension($floCotizacionPension);
                $arPeriodoDetallePago->setCotizacionSalud($floCotizacionSalud);
                $arPeriodoDetallePago->setCotizacionRiesgos($floCotizacionRiesgos);
                $arPeriodoDetallePago->setCotizacionCaja($floCotizacionCaja);
                //$arPeriodoDetallePago->setCentroTrabajoCodigoCt($arEmpleado->getContratoRel()->getCodigoCentroCostoFk());
                $totalCotizacion = $floTotalCotizacionFondos + $floTotalFondoSolidaridad + $floCotizacionSalud + $floCotizacionRiesgos + $floCotizacionCaja + $floCotizacionIcbf+$floCotizacionSena;
                //$totalCotizacionGeneral += $totalCotizacion;
                $totalCotizacion = $this->redondearCien($totalCotizacion);
                $arPeriodoDetallePago->setTotalCotizacion($totalCotizacion);
                $em->persist($arPeriodoDetallePago);
                $secuencia++;
            }    
            
        }
        $fecha = new \DateTime('now');
        $fechaPeriodo = $arPeriodo->getFechaDesde();
        $arPeriodo->setFechaPago($fecha);
        $arPeriodo->setAnio($fechaPeriodo->format('Y'));
        $arPeriodo->setMes($fechaPeriodo->format('m'));
        $arPeriodo->setAnioPago($fecha->format('Y'));
        $arPeriodo->setMesPago($fecha->format('m'));
        $arPeriodo->setEstadoPagoGenerado(1);
        $em->persist($arPeriodo);
        $em->flush();        
    }

    public function generarInteresMora($codigoPeriodo) {
        set_time_limit(0);
        ob_clean();
        $em = $this->getEntityManager();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();                
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
        $arPeriodo->getFechaDesde();                
        
        $fecha = $arPeriodo->getFechaDesde()->format('Y-m-d');
        $nuevafecha = strtotime ( '+1 month' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        //$fecha1 lleva 15 dias de mora
        //$nuevafecha = strtotime ( '+15 day' , strtotime ( $nuevafecha ) ) ;
        $nuevafecha = strtotime ( '+1 day' , strtotime ( $nuevafecha ) ) ; // se cambio por un dia de mora en adelante, estaba a partir de 15 dias en adelante
        //$fecha1 lleva 1 dia de mora
        $fecha1 = date ( 'Y-m-d' , $nuevafecha );
        //$fecha2, mas de 20 dias de mora
        //$fecha2 = strtotime ( '+4 day' , strtotime ( $fecha1 ) ) ;
        $fecha2 = strtotime ( '+19 day' , strtotime ( $fecha1 ) ) ;
        $fecha2 = date ( 'Y-m-d' , $fecha2 );
        //$fecha1 lleva 1 dia de mora
        $fecha16 = new \DateTime($fecha1);
        //$fecha2, mas de 20 dias de mora
        $fecha20 = new \DateTime($fecha2);
        //fecha actual
        $hoy = new \DateTime(date('Y-m-d'));    
        
        $porcentajeInteres = 0;
        $control = false;
        //si a fecha de hoy lleva mora de menos de 15 dias
        if ($hoy >= $fecha16 && $hoy <= $fecha20){
            $porcentajeInteres = 0.5;
            $control = true;
        }
        //si a fecha de hoy lleva mora mas de 15 dias de mora
        if ($hoy > $fecha20){
            $porcentajeInteres = 1;
            $control = true;
        }
        
        if ($control == true){           
            if ($arPeriodo->getInteresMora() == 0){
                $valorTotal = $arPeriodo->getTotal();
                $valorSubtotal = $arPeriodo->getSubtotal();
                //$porcentajeInteres = 2;
                $valorInteresMora = $valorTotal * $porcentajeInteres / 100;
                $arPeriodo->setTotalAnterior($valorTotal);
                $arPeriodo->setInteresMora($valorInteresMora);
                $arPeriodo->setSubtotal($arPeriodo->getSubtotal() + $valorInteresMora);
                $arPeriodo->setSubtotalAnterior($valorSubtotal);
                $arPeriodo->setTotal($this->redondearCien($arPeriodo->getTotal() + $valorInteresMora));
            } else {
                $valorTotal = $arPeriodo->getTotalAnterior();
                $valorSubtotal = $arPeriodo->getSubtotalAnterior();
                //$porcentajeInteres = 2;
                $valorInteresMora = $valorTotal * $porcentajeInteres / 100;
                $arPeriodo->setInteresMora($valorInteresMora);
                $arPeriodo->setSubtotal($valorSubtotal + $valorInteresMora);
                $arPeriodo->setTotal($this->redondearCien($valorTotal + $valorInteresMora));
            }
        }
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
    
    public function redondearCien($valor) {               
        $valor = round($valor);   
        if($valor != 0) {
            $residuo = fmod($valor, 100);        
            if($residuo != 0) {
                if($residuo > 50) {
                    $valor = intval($valor/100)*100+100;
                } else {
                    $valor = intval($valor/100)*100-100;
                }               
            }         
        }
        return $valor;
    } 
    
    public function liquidarIncapacidadGeneral($floSalario, $floSalarioAnterior, $intDias) {
        $em = $this->getEntityManager();
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        if($floSalarioAnterior > 0) {
            $floSalario = $floSalarioAnterior;
        }                
        $floValorDia = $floSalario / 30;
        $floValorDiaSalarioMinimo = $arConfiguracionNomina->getVrSalario() / 30;
        $floIbcIncapacidad = 0;       
                
        if($floSalario <= $arConfiguracionNomina->getVrSalario()) {
            $floIbcIncapacidad = $intDias * $floValorDia;            
        }
        if($floSalario > $arConfiguracionNomina->getVrSalario() && $floSalario <= $arConfiguracionNomina->getVrSalario() * 1.5) {
            $floIbcIncapacidad = $intDias * $floValorDiaSalarioMinimo;            
        }
        if($floSalario > ($arConfiguracionNomina->getVrSalario() * 1.5)) {
            $floIbcIncapacidad = $intDias * $floValorDia; 
            $floIbcIncapacidad = ($floIbcIncapacidad * 66.67)/100;            
        }        
        
        return $floIbcIncapacidad;
    }    
    
    public function liquidarIncapacidadLaboral($floSalario, $floSalarioAnterior, $intDias) {
        if($floSalarioAnterior > 0) {
            $floSalario = $floSalarioAnterior;
        }                
        $floValorDia = $floSalario / 30;        
        $floIbcIncapacidad = $intDias * $floValorDia;         
        return $floIbcIncapacidad;
    }
    
     
}