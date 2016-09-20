<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ProvisionController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/rhu/proceso/provision/", name="brs_rhu_proceso_provision")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 63)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {            
            if($request->request->get('OpGenerar')) {
                $codigoProvisionPeriodo = $request->request->get('OpGenerar');
                set_time_limit(0);
                ini_set("memory_limit", -1);            
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();            
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                $salarioMinimo = $arConfiguracion->getVrSalario();
                $porcentajeCaja = $arConfiguracion->getAportesPorcentajeCaja();
                $porcentajeCesantias = $arConfiguracion->getPrestacionesPorcentajeCesantias();        
                $porcentajeInteresesCesantias = $arConfiguracion->getPrestacionesPorcentajeInteresesCesantias();
                $porcentajeVacaciones = $arConfiguracion->getPrestacionesPorcentajeVacaciones();
                $porcentajePrimas = $arConfiguracion->getPrestacionesPorcentajePrimas();            
                $porcentajeIndemnizacion = $arConfiguracion->getPrestacionesPorcentajeIndemnizacion();                 
                $arProvisionPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuProvisionPeriodo();
                $arProvisionPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvisionPeriodo')->find($codigoProvisionPeriodo);
                $ingresoBasePrestacionTotal = 0;
                $ingresoBaseCotizacionTotal = 0;
                $cesantiasTotal = 0;
                $interesesCesantiasTotal = 0;
                $primasTotal = 0;  
                $vacacionesTotal = 0;
                $indemnizacionTotal = 0;                          
                $pensionTotal = 0; 
                $saludTotal = 0;         
                $riesgosTotal = 0;
                $cajaTotal = 0;
                $senaTotal = 0;
                $icbfTotal = 0;                 
                $dql   = "SELECT p.codigoEmpleadoFk, p.codigoContratoFk FROM BrasaRecursoHumanoBundle:RhuPago p "
                        . "WHERE p.estadoPagado = 1 AND p.fechaDesdePago >= '" . $arProvisionPeriodo->getFechaDesde()->format('Y/m/d') . "' AND p.fechaDesdePago <= '" . $arProvisionPeriodo->getFechaHasta()->format('Y/m/d') . "' "
                        . "GROUP BY p.codigoEmpleadoFk, p.codigoContratoFk";
                $query = $em->createQuery($dql);
                $arEmpleados = $query->getResult();                
                foreach ($arEmpleados as $arEmpleado) {
                    $ingresoBasePrestacion = 0;
                    $ingresoBaseCotizacion = 0;
                    $ingresoBaseIndemnizacion = 0;
                    $ingresoBaseVacacion = 0;                    
                    $dql   = "SELECT pd.vrIngresoBasePrestacion, pd.vrIngresoBaseCotizacion, pc.provisionIndemnizacion, pc.provisionVacacion FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.pagoConceptoRel pc "
                            . "WHERE p.codigoEmpleadoFk = " . $arEmpleado['codigoEmpleadoFk'] . " AND p.codigoContratoFk = " . $arEmpleado['codigoContratoFk'] . " AND p.estadoPagado = 1 AND p.fechaDesdePago >= '" . $arProvisionPeriodo->getFechaDesde()->format('Y/m/d') . "' AND p.fechaDesdePago <= '" . $arProvisionPeriodo->getFechaHasta()->format('Y/m/d') . "'";
                    $query = $em->createQuery($dql);
                    $arPagosDetalles = $query->getResult();                                         
                    foreach ($arPagosDetalles as $arPagoDetalle) {
                        $ingresoBasePrestacion += $arPagoDetalle['vrIngresoBasePrestacion'];
                        $ingresoBaseCotizacion += $arPagoDetalle['vrIngresoBaseCotizacion'];
                        if($arPagoDetalle['provisionIndemnizacion'] == 1) {
                            $ingresoBaseIndemnizacion +=  $arPagoDetalle['vrIngresoBasePrestacion'];
                        }
                        if($arPagoDetalle['provisionVacacion'] == 1) {
                            $ingresoBaseVacacion +=  $arPagoDetalle['vrIngresoBasePrestacion'];
                        }                         
                    }
                    $arEmpleadoAct = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleadoAct = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arEmpleado['codigoEmpleadoFk']);                                        
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado['codigoContratoFk']);                    
                    $porcentajeRiesgos = $arContrato->getClasificacionRiesgoRel()->getPorcentaje();
                    $porcentajePension = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador();                
                    $porcentajeSalud = $arContrato->getTipoSaludRel()->getPorcentajeEmpleador();
                    //Prestaciones
                    if($arContrato->getSalarioIntegral() == 0) {
                        $cesantias = ($ingresoBasePrestacion * $porcentajeCesantias) / 100; // Porcentaje 8.33                
                        $interesesCesantias = ($cesantias * $porcentajeInteresesCesantias) / 100; // Porcentaje 1 sobre las cesantias                        
                        $primas = ($ingresoBasePrestacion * $porcentajePrimas) / 100; // 8.33                               
                    } else {
                        $cesantias = 0;
                        $interesesCesantias = 0;
                        $primas = 0;
                    }
                    $vacaciones = ($ingresoBaseVacacion * $porcentajeVacaciones) / 100; // 4.17                                                
                    $indemnizacion = ($ingresoBaseIndemnizacion * $porcentajeIndemnizacion) / 100; // 4.17    
                    //Aportes                    
                    $riesgos = ($ingresoBaseCotizacion * $porcentajeRiesgos)/100;        
                    $pension = ($ingresoBaseCotizacion * $porcentajePension) / 100; 
                    $salud = 0;         
                    $caja = ($ingresoBaseCotizacion * $porcentajeCaja) / 100; //Porcentaje 4        
                    $sena = 0;
                    $icbf = 0;                                
                    $salarioAporte = 0;
                    if($arContrato->getSalarioIntegral() == 1) {
                        $salarioAporte = ($ingresoBaseCotizacion * 70) / 100;
                    } else {
                        $salarioAporte = $ingresoBaseCotizacion;
                    }
                    
                    if($salarioAporte > $salarioMinimo * 10) {
                        $salud = ($ingresoBaseCotizacion * $porcentajeSalud) / 100;
                        $sena = ($ingresoBaseCotizacion * 2) / 100;
                        $icbf = ($ingresoBaseCotizacion * 3) / 100;
                    }
                    //12 aprendiz y 19 practicante        
                    if($arContrato->getCodigoTipoCotizanteFk() == '19' || $arContrato->getCodigoTipoCotizanteFk() == '12') {            
                        $salud = ($ingresoBaseCotizacion * $porcentajeSalud) / 100;
                        $pension = 0;            
                        $caja = 0;
                        $cesantias = 0;
                        $interesesCesantias = 0; 
                        $primas = 0;
                        $vacaciones = 0;                    
                    }
                    if($arContrato->getCodigoTipoCotizanteFk() == '12') {
                        $riesgos = 0;
                    }
                                        
                    $ingresoBasePrestacionTotal += $ingresoBasePrestacion;
                    $ingresoBaseCotizacionTotal += $ingresoBaseCotizacion;
                    $cesantiasTotal += $cesantias;                    
                    $interesesCesantiasTotal += $interesesCesantias;
                    $primasTotal += $primas;
                    $vacacionesTotal += $vacaciones;
                    $indemnizacionTotal += $indemnizacion;
                    $pensionTotal += $pension; 
                    $saludTotal += $salud;         
                    $riesgosTotal += $riesgos;
                    $cajaTotal += $caja;
                    $senaTotal += $sena;
                    $icbfTotal += $icbf;                    
                    
                    $arProvision = new \Brasa\RecursoHumanoBundle\Entity\RhuProvision();
                    $arProvision->setEmpleadoRel($arEmpleadoAct);
                    $arProvision->setContratoRel($arContrato);
                    $arProvision->setProvisionPeriodoRel($arProvisionPeriodo);
                    $arProvision->setVrSalario($arContrato->getVrSalarioPago());
                    $arProvision->setVrIngresoBasePrestacion($ingresoBasePrestacion);
                    $arProvision->setVrIngresoBaseCotizacion($ingresoBaseCotizacion);
                    $arProvision->setVrCesantias($cesantias);
                    $arProvision->setVrInteresesCesantias($interesesCesantias);
                    $arProvision->setVrPrimas($primas);
                    $arProvision->setVrVacaciones($vacaciones);
                    $arProvision->setVrIndemnizacion($indemnizacion);
                    $arProvision->setVrPension($pension);
                    $arProvision->setVrSalud($salud);
                    $arProvision->setVrRiesgos($riesgos);
                    $arProvision->setVrCaja($caja);
                    $arProvision->setVrSena($sena);
                    $arProvision->setVrIcbf($icbf);
                    $em->persist($arProvision);
                }
                $arProvisionPeriodo->setVrIngresoBasePrestacion($ingresoBasePrestacionTotal);
                $arProvisionPeriodo->setVrIngresoBaseCotizacion($ingresoBaseCotizacionTotal);
                $arProvisionPeriodo->setVrCesantias($cesantiasTotal);
                $arProvisionPeriodo->setVrInteresesCesantias($interesesCesantiasTotal);
                $arProvisionPeriodo->setVrPrimas($primasTotal);
                $arProvisionPeriodo->setVrVacaciones($vacacionesTotal);
                $arProvisionPeriodo->setVrIndemnizacion($indemnizacionTotal); 
                $arProvisionPeriodo->setVrPension($pensionTotal);
                $arProvisionPeriodo->setVrSalud($saludTotal);
                $arProvisionPeriodo->setVrRiesgos($riesgosTotal);
                $arProvisionPeriodo->setVrCaja($cajaTotal);
                $arProvisionPeriodo->setVrSena($senaTotal);
                $arProvisionPeriodo->setVrIcbf($icbfTotal);                
                $arProvisionPeriodo->setEstadoGenerado(1);
                $em->persist($arProvisionPeriodo);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_proceso_provision'));
            }
            if($request->request->get('OpDeshacer')) {
                $codigoProvisionPeriodo = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM rhu_provision WHERE codigo_provision_periodo_fk = " . $codigoProvisionPeriodo;
                $em->getConnection()->executeQuery($strSql);                
                $arProvisionPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuProvisionPeriodo();
                $arProvisionPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvisionPeriodo')->find($codigoProvisionPeriodo);                
                $arProvisionPeriodo->setEstadoGenerado(0);
                $arProvisionPeriodo->setVrIngresoBasePrestacion(0);                
                $arProvisionPeriodo->setVrIngresoBaseCotizacion(0);
                $arProvisionPeriodo->setVrCesantias(0);
                $arProvisionPeriodo->setVrInteresesCesantias(0);
                $arProvisionPeriodo->setVrPrimas(0);
                $arProvisionPeriodo->setVrVacaciones(0);
                $arProvisionPeriodo->setVrIndemnizacion(0); 
                $arProvisionPeriodo->setVrPension(0);
                $arProvisionPeriodo->setVrSalud(0);
                $arProvisionPeriodo->setVrRiesgos(0);
                $arProvisionPeriodo->setVrCaja(0);
                $arProvisionPeriodo->setVrSena(0);
                $arProvisionPeriodo->setVrIcbf(0);                
                $em->persist($arProvisionPeriodo);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_proceso_provision'));                
            }
            if($request->request->get('OpExcel')) {
                set_time_limit(0);
                ini_set("memory_limit", -1); 
                $codigoProvisionPeriodo = $request->request->get('OpExcel');
                $this->generarExcel($codigoProvisionPeriodo);
            }            
        }       
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvisionPeriodo')->listaDql(); 
        $arProvisionPeriodo = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Provision:lista.html.twig', array(
            'arProvisionPeriodo' => $arProvisionPeriodo,
            'form' => $form->createView()));
    }          
    
    private function formularioLista() {
        $form = $this->createFormBuilder()                        
            
            ->getForm();        
        return $form;
    }                  
    
    private function generarExcel($codigoProvisionPeriodo) {
        ob_clean();
        $em = $this->getDoctrine()->getManager();        
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'T'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'F'; $col !== 'T'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'COD')                    
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'EMPLEADO')                    
                    ->setCellValue('D1', 'DESDE')
                    ->setCellValue('E1', 'HASTA')
                    ->setCellValue('F1', 'SALARIO')
                    ->setCellValue('G1', 'IBP')
                    ->setCellValue('H1', 'IBC')
                    ->setCellValue('I1', 'CESANTIAS')
                    ->setCellValue('J1', 'INTERESES')
                    ->setCellValue('K1', 'PRIMAS')
                    ->setCellValue('L1', 'VACACIONES')
                    ->setCellValue('M1', 'INDEMNIZACIONES')
                    ->setCellValue('N1', 'PENSION')
                    ->setCellValue('O1', 'SALUD')
                    ->setCellValue('P1', 'CAJA')
                    ->setCellValue('Q1', 'RIESGOS')
                    ->setCellValue('R1', 'SENA')
                    ->setCellValue('S1', 'ICBF');

        $i = 2;
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->listaDql($codigoProvisionPeriodo);
        $query = $em->createQuery($dql);
        $arProvisiones = new \Brasa\RecursoHumanoBundle\Entity\RhuProvision();
        $arProvisiones = $query->getResult();
        foreach ($arProvisiones as $arProvision) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProvision->getCodigoProvisionPk())
                    ->setCellValue('B' . $i, $arProvision->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arProvision->getEmpleadoRel()->getNombreCorto())                    
                    ->setCellValue('D' . $i, $arProvision->getProvisionPeriodoRel()->getFechaDesde()->format('Y-m-d'))
                    ->setCellValue('E' . $i, $arProvision->getProvisionPeriodoRel()->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('F' . $i, $arProvision->getVrSalario())
                    ->setCellValue('G' . $i, $arProvision->getVrIngresoBasePrestacion())
                    ->setCellValue('H' . $i, $arProvision->getVrIngresoBaseCotizacion())
                    ->setCellValue('I' . $i, $arProvision->getVrCesantias())
                    ->setCellValue('J' . $i, $arProvision->getVrInteresesCesantias())
                    ->setCellValue('K' . $i, $arProvision->getVrPrimas())
                    ->setCellValue('L' . $i, $arProvision->getVrVacaciones())
                    ->setCellValue('M' . $i, $arProvision->getVrIndemnizacion())
                    ->setCellValue('N' . $i, $arProvision->getVrPension())
                    ->setCellValue('O' . $i, $arProvision->getVrSalud())
                    ->setCellValue('P' . $i, $arProvision->getVrCaja())
                    ->setCellValue('Q' . $i, $arProvision->getVrRiesgos())
                    ->setCellValue('R' . $i, $arProvision->getVrSena())
                    ->setCellValue('S' . $i, $arProvision->getVrIcbf());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Provisiones');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Provisiones.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }     
    
}
