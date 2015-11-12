<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class SeguridadSocialPeriodosController extends Controller
{
    var $strDqlLista = "";
    var $strDqlListaDetalle = "";
    var $strDqlListaEmpleados = "";
    var $strDqlListaDetalleAportes = "";

    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($request->request->get('OpGenerar')) {
                $codigoPeriodo = $request->request->get('OpGenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->generar($codigoPeriodo);
            }
            /*if($request->request->get('OpDesgenerar')) {
                $codigoPeriodo = $request->request->get('OpDesgenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->desgenerar($codigoPeriodo);
            }*/
            if($request->request->get('OpCerrar')) {
                $codigoPeriodo = $request->request->get('OpCerrar');
                //$em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->cerrar($codigoPeriodo);
                $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
                $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
                $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
                if ($arPeriodo->getEstadoGenerado() == 0){
                    $objMensaje->Mensaje("error", "Debe generar periodo para poder cerrarlo", $this);
                } else {
                    $arPeriodoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                    $arPeriodoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->findBy(array('codigoPeriodoFk' => $codigoPeriodo, 'estadoCerrado' => 0));
                    $intTotal = count($arPeriodoDetalles);
                    if ($intTotal > 0){
                        $objMensaje->Mensaje("error", "Hay periodos de sucursales sin cerrar", $this);
                    }else{
                        $arPeriodo->setEstadoCerrado(1);
                        $em->persist($arPeriodo);
                        $em->flush();
                    }
                    
                }
            }
        }
        $arSsoPeriodos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:lista.html.twig', array(
            'arSsoPeriodos' => $arSsoPeriodos,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        $this->listarDetalle($codigoPeriodo);
        if($form->isValid()) {
            if($request->request->get('OpGenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpGenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->generar($codigoPeriodoDetalle);
            }
            if($request->request->get('OpDesgenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpDesgenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->desgenerar($codigoPeriodoDetalle);
            }
            if($request->request->get('OpCerrar')) {
                $codigoPeriodo = $request->request->get('OpCerrar');
                $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodo);
                $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
                if ($arPeriodoDetalle->getEstadoGenerado() == 0){
                    $objMensaje->Mensaje("error", "Debe generar periodo de la sucursal para poder cerrarlo", $this);
                } else {
                    $arPeriodoDetalle->setEstadoCerrado(1);
                    $em->persist($arPeriodoDetalle);
                    $em->flush();
                }
            }
            if($request->request->get('OpGenerarArchivo')) {
                $codigoPeriodoDetalle = $request->request->get('OpGenerarArchivo');
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $strRutaArchivo = $arConfiguracion->getRutaTemporal();
                $strNombreArchivo = "pila" . date('YmdHis') . ".txt";
                $ar = fopen($strRutaArchivo . $strNombreArchivo, "a") or
                    die("Problemas en la creacion del archivo plano");
                $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
                fputs($ar, '01');
                fputs($ar, '1');
                fputs($ar, '0001');
                fputs($ar, $this->RellenarNr($arConfiguracion->getNombreEmpresa(), " ", 200, "D"));
                fputs($ar, 'NI');
                fputs($ar, $this->RellenarNr($arConfiguracion->getNitEmpresa(), " ", 16, "D"));
                fputs($ar, '3');
                fputs($ar, 'E');
                fputs($ar, '          ');
                fputs($ar, '          ');
                fputs($ar, 'S');
                fputs($ar, $this->RellenarNr($arPeriodoDetalle->getSsoSucursalRel()->getCodigoInterface(), " ", 10, "D"));
                fputs($ar, $this->RellenarNr($arPeriodoDetalle->getSsoSucursalRel()->getNombre(), " ", 40, "D"));
                //Arp del aportante
                fputs($ar, '14-18 ');
                //Periodo pago para los diferentes sistemas
                fputs($ar, $arPeriodoDetalle->getSsoPeriodoRel()->getAnioPago().'-'. $this->RellenarNr($arPeriodoDetalle->getSsoPeriodoRel()->getMesPago(), "0", 2, "I"));
                fputs($ar, $arPeriodoDetalle->getSsoPeriodoRel()->getAnio().'-'. $this->RellenarNr($arPeriodoDetalle->getSsoPeriodoRel()->getMes(), "0", 2, "I"));
                //Numero radicacion de la planilla
                fputs($ar, '0000000000');
                //Fecha de pago
                fputs($ar, $arPeriodoDetalle->getSsoPeriodoRel()->getFechaPago()->format('Y-m-d'));
                //Numero total de empleados
                fputs($ar, $this->RellenarNr(0, "0", 5, "I"));
                //Valor total de la nomina
                fputs($ar, $this->RellenarNr(0, "0", 12, "I"));
                fputs($ar, '1');
                fputs($ar, '89');
                fputs($ar, "\n");

                $arSsoAportes = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
                $arSsoAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));
                foreach($arSsoAportes as $arSsoAporte) {
                    fputs($ar, $this->RellenarNr($arSsoAporte->getTipoRegistro(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getSecuencia(), "0", 5, "I"));
                    fputs($ar, $arSsoAporte->getTipoDocumento());
                    fputs($ar, $this->RellenarNr($arSsoAporte->getEmpleadoRel()->getNumeroIdentificacion(), " ", 16, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getTipoCotizante(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getSubtipoCotizante(), "0", 2, "I"));
                    fputs($ar, $arSsoAporte->getExtranjeroNoObligadoCotizarPension());
                    fputs($ar, $arSsoAporte->getColombianoResidenteExterior());
                    fputs($ar, $arSsoAporte->getCodigoDepartamentoUbicacionlaboral());
                    fputs($ar, $arSsoAporte->getCodigoMunicipioUbicacionlaboral());
                    fputs($ar, $this->RellenarNr($arSsoAporte->getPrimerApellido(), " ", 20, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getSegundoApellido(), " ", 30, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getPrimerNombre(), " ", 20, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getSegundoNombre(), " ", 30, "D"));
                    fputs($ar, $arSsoAporte->getIngreso());
                    fputs($ar, $arSsoAporte->getRetiro());
                    fputs($ar, $arSsoAporte->getTrasladoDesdeOtraEps());
                    fputs($ar, $arSsoAporte->getTrasladoAOtraEps());
                    fputs($ar, $arSsoAporte->getTrasladoDesdeOtraPension());
                    fputs($ar, $arSsoAporte->getTrasladoAOtraPension());
                    fputs($ar, $arSsoAporte->getVariacionPermanenteSalario());
                    fputs($ar, $arSsoAporte->getCorrecciones());
                    fputs($ar, $arSsoAporte->getVariacionTransitoriaSalario());
                    fputs($ar, $arSsoAporte->getSuspensionTemporalContratoLicenciaServicios());
                    fputs($ar, $arSsoAporte->getIncapacidadGeneral());
                    fputs($ar, $arSsoAporte->getLicenciaMaternidad());
                    fputs($ar, $arSsoAporte->getVacaciones());
                    fputs($ar, $arSsoAporte->getAporteVoluntario());
                    fputs($ar, $arSsoAporte->getVariacionCentrosTrabajo());
                    fputs($ar, $this->RellenarNr($arSsoAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadPensionPertenece(), " ", 6, "D"));
                    fputs($ar, $arSsoAporte->getCodigoEntidadPensionTraslada());
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadSaludPertenece(), " ", 6, "D"));
                    fputs($ar, $arSsoAporte->getCodigoEntidadSaludTraslada());
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadCajaPertenece(), " ", 6, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosPension(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosSalud(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosRiesgosProfesionales(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosCajaCompensacion(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getSalarioBasico(), "0", 9, "I"));
                    fputs($ar, $arSsoAporte->getSalarioIntegral());
                    fputs($ar, $this->RellenarNr($arSsoAporte->getIbcPension(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getIbcSalud(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getIbcRiesgosProfesionales(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getIbcCaja(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr(($arSsoAporte->getTarifaPension()/100), "0", 7, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionPension(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getAporteVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getTotalCotizacion(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getAportesFondoSolidaridadPensionalSolidaridad(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getAportesFondoSolidaridadPensionalSubsistencia(), "0", 9, "I"));
                    fputs($ar, '000000000');
                    fputs($ar, $this->RellenarNr(($arSsoAporte->getTarifaSalud()/100), "0", 7, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionSalud(), "0", 9, "I"));
                    //fputs($ar, $arSsoAporte->getValorUpcAdicional());
                    fputs($ar, "000000000");
                    //fputs($ar, $arSsoAporte->getNumeroAutorizacionIncapacidadEnfermedadGeneral());
                    fputs($ar, "               ");
                    //fputs($ar, $arSsoAporte->getValorIncapacidadEnfermedadGeneral());
                    fputs($ar, "000000000");
                    //fputs($ar, $arSsoAporte->getNumeroAutorizacionLicenciaMaternidadPaternidad());
                    fputs($ar, "               ");
                    //fputs($ar, $arSsoAporte->getValorLicenciaMaternidadPaternidad());
                    fputs($ar, "000000000");
                    fputs($ar, $this->RellenarNr(($arSsoAporte->getTarifaRiesgos()/100), "0", 9, "D"));
                    //fputs($ar, $arSsoAporte->getCentroTrabajoCodigoCt());
                    fputs($ar, "000000000");
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionRiesgos(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr(($arSsoAporte->getTarifaCaja()/100), "0", 7, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionCaja(), "0", 9, "I"));
                    //fputs($ar, $arSsoAporte->getTarifaAportesSENA());
                    fputs($ar, "0.00000");
                    //fputs($ar, $arSsoAporte->getValorAportesSENA());
                    fputs($ar, "000000000");
                    //fputs($ar, $arSsoAporte->getTarifaAportesICBF());
                    fputs($ar, "0.00000");
                    //fputs($ar, $arSsoAporte->getValorAporteICBF());
                    fputs($ar, "000000000");
                    //fputs($ar, $arSsoAporte->getTarifaAportesESAP());
                    fputs($ar, "0.00000");
                    //fputs($ar, $arSsoAporte->getValorAporteESAP());
                    fputs($ar, "000000000");
                    //fputs($ar, $arSsoAporte->getTarifaAportesMEN());
                    fputs($ar, "0.00000");
                    //fputs($ar, $arSsoAporte->getValorAporteMEN());
                    fputs($ar, "000000000");                    
                    //fputs($ar, $arSsoAporte->getTipoDocumentoResponsableUPC());
                    fputs($ar, "  ");
                    //fputs($ar, $arSsoAporte->getNumeroIdentificacionResponsableUPCAdicional());
                    fputs($ar, "                ");
                    //fputs($ar, $arSsoAporte->getCotizanteExoneradoPagoAporteParafiscalesSalud());
                    fputs($ar, " ");
                    //fputs($ar, $arSsoAporte->getCodigoAdministradoraRiesgosLaborales());
                    fputs($ar, "      ");
                    //fputs($ar, $arSsoAporte->getClaseRiesgoAfiliado());
                    fputs($ar, " ");
                    fputs($ar, "\n");
                }
                fclose($ar);
                $strArchivo = $strRutaArchivo.$strNombreArchivo;
                header('Content-Description: File Transfer');
                header('Content-Type: text/csv; charset=ISO-8859-15');
                header('Content-Disposition: attachment; filename='.basename($strArchivo));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($strArchivo));
                readfile($strArchivo);
                $em->flush();
                exit;
            }
            
            if($request->request->get('OpGenerarExcel')) {
                $codigoPeriodoDetalle = $request->request->get('OpGenerarExcel');

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

                $objPHPExcel->setActiveSheetIndex(0)
                    
                    ->setCellValue('A1', 'PERIODO DETALLE')
                    ->setCellValue('A2', 'CÓDIGO SUCURSAL')
                    ->setCellValue('A3', 'SUCURSAL')
                    ->setCellValue('A5', 'CÓDIGO PERIODO')
                    ->setCellValue('B5', 'IDENTIFICACIÓN')
                    ->setCellValue('C5', 'CONTRATO')
                    ->setCellValue('D5', 'INGRESO')
                    ->setCellValue('E5', 'RETIRO')
                    ->setCellValue('F5', 'VARIACIÓN TRANSITORIA SALARIO')
                    ->setCellValue('G5', 'LICENCIA NO REMUNERADA')
                    ->setCellValue('H5', 'INCAPACIDAD GENERAL')
                    ->setCellValue('I5', 'LICENCIA MATERNIDAD')
                    ->setCellValue('J5', 'RIESGOS PROFESIONALES')
                    ->setCellValue('K5', 'SALARIO')
                    ->setCellValue('L5', 'SUPLEMENTARIO')
                    ->setCellValue('M5', 'DÍAS PENSION')
                    ->setCellValue('N5', 'DÍAS SALUD')
                    ->setCellValue('O5', 'DÍAS RIESGOS PROFESIONALES')
                    ->setCellValue('P5', 'DÍAS CAJA COMPENSACIÓN')
                    ->setCellValue('Q5', 'IBC PENSIÓN')
                    ->setCellValue('R5', 'IBC SALUD')
                    ->setCellValue('S5', 'IBC RIESGOS PROFESIONALES')
                    ->setCellValue('T5', 'IBC CAJA COMPENSACIÓN')
                    ->setCellValue('U5', 'TARIFA PENSIÓN')
                    ->setCellValue('V5', 'TARIFA SALUD')
                    ->setCellValue('W5', 'TARIFA RIESGOS PROFESIONALES')
                    ->setCellValue('X5', 'TARIFA CAJA COMPENSACIÓN')
                    ->setCellValue('Y5', 'COTIZACIÓN PENSIÓN')
                    ->setCellValue('Z5', 'COTIZACIÓN SALUD')
                    ->setCellValue('AA5', 'COTIZACIÓN RIESGOS PROFESIONALES')
                    ->setCellValue('AB5', 'COTIZACIÓN CAJA COMPENSACIÓN');
                
                $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B1', $arPeriodoDetalle->getCodigoPeriodoDetallePk())
                    ->setCellValue('B2', $arPeriodoDetalle->getCodigoSucursalFk())
                    ->setCellValue('B3', $arPeriodoDetalle->getSsoSucursalRel()->getNombre())    ;
                $i = 6;
                $arSsoAportes = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
                $arSsoAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));
                foreach ($arSsoAportes as $arSsoAporte) {            
                    $suspencionTemporalContratoLicenciaServicios = "";
                    if ($arSsoAporte->getSuspensionTemporalContratoLicenciaServicios() == "X"){
                       $suspencionTemporalContratoLicenciaServicios = $arSsoAporte->getSuspensionTemporalContratoLicenciaServicios()." ". $arSsoAporte->getDiasLicencia(); 
                    }
                    $incapacidadGeneral = "";
                    if ($arSsoAporte->getIncapacidadGeneral() == "X"){
                       $incapacidadGeneral = $arSsoAporte->getIncapacidadGeneral()." ". $arSsoAporte->getDiasIncapacidadGeneral(); 
                    }
                    $licenciaMaternidad = "";
                    if ($arSsoAporte->getLicenciaMaternidad() == "X"){
                       $licenciaMaternidad = $arSsoAporte->getgetLicenciaMaternidad()." ". $arSsoAporte->getDiasLicenciaMaternidad(); 
                    }
                    $riesgosProfesionales = "";
                    if ($arSsoAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional() > 0){
                       $riesgosProfesionales = $arSsoAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional(); 
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSsoAporte->getCodigoAportePk())
                    ->setCellValue('B' . $i, $arSsoAporte->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arSsoAporte->getCodigoContratoFk())
                    ->setCellValue('D' . $i, $arSsoAporte->getIngreso())        
                    ->setCellValue('E' . $i, $arSsoAporte->getRetiro())
                    ->setCellValue('F' . $i, $arSsoAporte->getVariacionTransitoriaSalario())
                    ->setCellValue('G' . $i, $suspencionTemporalContratoLicenciaServicios)
                    ->setCellValue('H' . $i, $incapacidadGeneral)
                    ->setCellValue('I' . $i, $licenciaMaternidad)
                    ->setCellValue('J' . $i, $riesgosProfesionales)
                    ->setCellValue('K' . $i, $arSsoAporte->getSalarioBasico())
                    ->setCellValue('L' . $i, $arSsoAporte->getSuplementario())
                    ->setCellValue('M' . $i, $arSsoAporte->getDiasCotizadosPension())
                    ->setCellValue('N' . $i, $arSsoAporte->getDiasCotizadosSalud())
                    ->setCellValue('O' . $i, $arSsoAporte->getDiasCotizadosRiesgosProfesionales())
                    ->setCellValue('P' . $i, $arSsoAporte->getDiasCotizadosCajaCompensacion())
                    ->setCellValue('Q' . $i, $arSsoAporte->getIbcPension())
                    ->setCellValue('R' . $i, $arSsoAporte->getIbcSalud())
                    ->setCellValue('S' . $i, $arSsoAporte->getIbcRiesgosProfesionales())
                    ->setCellValue('T' . $i, $arSsoAporte->getIbcCaja())
                    ->setCellValue('U' . $i, $arSsoAporte->getTarifaPension())
                    ->setCellValue('V' . $i, $arSsoAporte->getTarifaSalud())
                    ->setCellValue('W' . $i, $arSsoAporte->getTarifaRiesgos())
                    ->setCellValue('X' . $i, $arSsoAporte->getTarifaCaja())
                    ->setCellValue('Y' . $i, $arSsoAporte->getCotizacionPension())
                    ->setCellValue('Z' . $i, $arSsoAporte->getCotizacionSalud())
                    ->setCellValue('AA' . $i, $arSsoAporte->getCotizacionRiesgos())
                    ->setCellValue('AB' . $i, $arSsoAporte->getCotizacionCaja());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('SsoAportes');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="SsoAportes.xlsx"');
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
            
            if($request->request->get('OpGenerarPagosExcel')) {
                $this->generarPagosPeriodoExcel($codigoPeriodo);
            }
            if($request->request->get('OpGenerarPagosDetalleExcel')) {
                $this->generarPagosDetallePeriodoExcel($codigoPeriodo);
            }
            if($request->request->get('OpGenerarAportesExcel')) {
                $this->generarAportesPeriodoExcel($codigoPeriodo);
            }
        }
        $arSsoPeriodoDetalles = $paginator->paginate($em->createQuery($this->strDqlListaDetalle), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalle.html.twig', array(
            'arSsoPeriodoDetalles' => $arSsoPeriodoDetalles,
            'codigoPeriodo' => $codigoPeriodo,
            'form' => $form->createView()));
    }

    public function detalleEmpleadosAction($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        $this->listarEmpleados($arPeriodoDetalle->getCodigoPeriodoFk(), $arPeriodoDetalle->getCodigoSucursalFk());
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalle) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->generar($codigoPeriodoDetalle);
                    }
                }
            }
        }
        $arSsoPeriodoEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaEmpleados), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:empleados.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arPeriodoEmpleados' => $arSsoPeriodoEmpleados,
            'form' => $form->createView()));
    }

    public function detalleAportesAction($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $this->listarDetalleAportes($codigoPeriodoDetalle);
        if($form->isValid()) {
            if($request->request->get('OpGenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpGenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->generar($codigoPeriodoDetalle);
            }
            if($request->request->get('OpDesgenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpDesgenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->desgenerar($codigoPeriodoDetalle);
            }
        }
        $arSsoAportes = $paginator->paginate($em->createQuery($this->strDqlListaDetalleAportes), $request->query->get('page', 1), 500);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalleAportes.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arSsoAportes' => $arSsoAportes,
            'form' => $form->createView()));
    }

    public function resumenPagosAction($codigoPeriodoDetalle, $codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado =  $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);        
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);

        $arPagos = $paginator->paginate($em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaConsultaPagosDQL("", $arEmpleado->getNumeroIdentificacion(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), "","")), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:resumenPagos.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arPagos' => $arPagos,
            ));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->listaDQL();
    }

    private function listarDetalle($codigoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlListaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->listaDQL($codigoPeriodo);
    }

    private function listarEmpleados($codigoPeriodo, $codigoSucursal) {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlListaEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->listaDql($codigoPeriodo, $codigoSucursal);
    }

    private function listarDetalleAportes($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlListaDetalleAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->listaDQL($codigoPeriodoDetalle);
    }

    public static function RellenarNr($Nro, $Str, $NroCr, $strPosicion) {
        $Nro = utf8_decode($Nro);
        $Longitud = strlen($Nro);
        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++) {
            if($strPosicion == "I") {
                $Nro = $Str . $Nro;
            } else {
                $Nro = $Nro . $Str;
            }

        }

        return (string) $Nro;
    }
    
    private function generarPagosPeriodoExcel($codigoPeriodo) {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'TIPO')
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'EMPLEADO')
                    ->setCellValue('F1', 'CENTRO COSTO')
                    ->setCellValue('G1', 'PERIODO PAGO')
                    ->setCellValue('H1', 'FECHA PAGO DESDE')
                    ->setCellValue('I1', 'FECHA PAGO HASTA')
                    ->setCellValue('J1', 'DÍAS PERIODO')
                    ->setCellValue('K1', 'VR SALARIO EMPLEADO')
                    ->setCellValue('L1', 'VR SALARIO PERIODO')
                    ->setCellValue('M1', 'VR AUX TRANSPORTE')
                    ->setCellValue('N1', 'VR EPS')
                    ->setCellValue('O1', 'VR PENSIÓN')
                    ->setCellValue('P1', 'VR DEDUCCIONES')    
                    ->setCellValue('Q1', 'VR DEVENGADO')
                    ->setCellValue('R1', 'VR INGRESO BASE COTIZACIÓN')
                    ->setCellValue('S1', 'VR INGRESO BASE PRESTACIONAL')
                    ->setCellValue('T1', 'VE NETO PAGAR');

        $i = 2;
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        $dateFechaPeriodo = $arPeriodo->getFechaDesde()->format('Y-m-d'). ' - ' . $arPeriodo->getFechaHasta()->format('Y-m-d');
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaDqlPagosPeriodoAportes($arPeriodo->getFechaDesde(),$arPeriodo->getFechaHasta());
        $query = $em->createQuery($arPagos);
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getNumero())
                    ->setCellValue('C' . $i, $arPago->getPagoTipoRel()->getNombre())
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getFechaDesde()->format('Y-m-d'). " - " .$arPago->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arPago->getFechaDesdePago()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arPago->getFechaHastaPago()->format('Y-m-d'))
                    ->setCellValue('J' . $i, $arPago->getDiasPeriodo())
                    ->setCellValue('K' . $i, $arPago->getVrSalarioEmpleado())
                    ->setCellValue('L' . $i, $arPago->getVrSalarioPeriodo())
                    ->setCellValue('M' . $i, $arPago->getVrAuxilioTransporte())
                    ->setCellValue('N' . $i, $arPago->getVrEps())
                    ->setCellValue('O' . $i, $arPago->getVrPension())
                    ->setCellValue('P' . $i, $arPago->getVrDeducciones())
                    ->setCellValue('Q' . $i, $arPago->getVrDevengado())
                    ->setCellValue('R' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('S' . $i, $arPago->getVrIngresoBasePrestacion())
                    ->setCellValue('T' . $i, $arPago->getVrNeto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pagos '.$dateFechaPeriodo);
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pagos '.$dateFechaPeriodo.'.xlsx"');
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
    
    private function generarPagosDetallePeriodoExcel($codigoPeriodo) {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO DETALLE')
                    ->setCellValue('B1', 'CÓDIGO PAGO')
                    ->setCellValue('C1', 'CONCEPTO PAGO')
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'EMPLEADO')
                    ->setCellValue('F1', 'CENTRO COSTO')
                    ->setCellValue('G1', 'FECHA PAGO DESDE')
                    ->setCellValue('H1', 'FECHA PAGO HASTA')
                    ->setCellValue('I1', 'VR PAGO')
                    ->setCellValue('J1', 'VR HORA')
                    ->setCellValue('K1', 'VR DÍA')
                    ->setCellValue('L1', 'NÚMERO HORAS')
                    ->setCellValue('M1', 'NÚMERO DÍAS')
                    ->setCellValue('N1', 'PORCENTAJE APLICADO')
                    ->setCellValue('O1', 'VR INGRESO BASE COTIZACIÓN')    
                    ->setCellValue('P1', 'CÓDIGO PROGRAMACION PAGO DETALLE')
                    ->setCellValue('Q1', 'CÓDIGO CRÉDITO')
                    ->setCellValue('R1', 'VR INGRESO BASE PRESTACIONAL')
                    ->setCellValue('S1', 'DÍAS AUSENTIMO');

        $i = 2;
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        $dateFechaPeriodo = $arPeriodo->getFechaDesde()->format('Y-m-d'). ' - ' . $arPeriodo->getFechaHasta()->format('Y-m-d');
        $arPagosDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagosDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDqlPagosDetallePeriodoAportes($arPeriodo->getFechaDesde(),$arPeriodo->getFechaHasta());
        
        foreach ($arPagosDetalle as $arPagoDetalle) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoDetalle->getCodigoPagoDetallePk())
                    ->setCellValue('B' . $i, $arPagoDetalle->getCodigoPagoFk())
                    ->setCellValue('C' . $i, $arPagoDetalle->getPagoConceptoRel()->getNombre())
                    ->setCellValue('D' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPagoDetalle->getPagoRel()->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPagoDetalle->getPagoRel()->getFechaDesdePago())
                    ->setCellValue('H' . $i, $arPagoDetalle->getPagoRel()->getFechaHastaPago())
                    ->setCellValue('I' . $i, $arPagoDetalle->getVrPago())
                    ->setCellValue('J' . $i, $arPagoDetalle->getVrHora())
                    ->setCellValue('K' . $i, $arPagoDetalle->getVrDia())
                    ->setCellValue('L' . $i, $arPagoDetalle->getNumeroHoras())
                    ->setCellValue('M' . $i, $arPagoDetalle->getNumeroDias())
                    ->setCellValue('N' . $i, $arPagoDetalle->getPorcentajeAplicado())
                    ->setCellValue('O' . $i, $arPagoDetalle->getVrIngresoBaseCotizacion())
                    ->setCellValue('P' . $i, $arPagoDetalle->getCodigoProgramacionPagoDetalleFk())
                    ->setCellValue('Q' . $i, $arPagoDetalle->getCodigoCreditoFk())
                    ->setCellValue('R' . $i, $arPagoDetalle->getVrIngresoBasePrestacion())
                    ->setCellValue('S' . $i, $arPagoDetalle->getDiasAusentismo());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('detalles'.$dateFechaPeriodo);
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="detalle '.$dateFechaPeriodo.'.xlsx"');
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
    
    private function generarAportesPeriodoExcel($codigoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'SUCURSAL')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'SECUENCIA')
                    ->setCellValue('F1', 'TIPO DOCUMENTO')
                    ->setCellValue('G1', 'TIPO COTIZANTE')
                    ->setCellValue('H1', 'SUBTIPO COTIZANTE')
                    ->setCellValue('I1', 'DEPARTAMENTO')
                    ->setCellValue('J1', 'MUNICIPIO')
                    ->setCellValue('K1', 'INGRESO')
                    ->setCellValue('L1', 'RETIRO')
                    ->setCellValue('M1', 'TRANSLADO DESDE OTRA EPS')
                    ->setCellValue('N1', 'TRANSLADO A OTRA EPS')
                    ->setCellValue('O1', 'TRANSLADO DESDE OTRA PENSIÓN')
                    ->setCellValue('P1', 'TRANSLADO A OTRA PENSIÓN')
                    ->setCellValue('Q1', 'VARIACIÓN PERMANENTE SALARIO')
                    ->setCellValue('R1', 'CORRECCIONES')
                    ->setCellValue('S1', 'VARIACIÓN TRANSITORIA SALARIO')
                    ->setCellValue('T1', 'SUSPENCIÓN TEMPORAL CONTRATO LICENCIA SERVICIOS')
                    ->setCellValue('U1', 'DÍAS LICENCIAS')
                    ->setCellValue('V1', 'SALARIO BÁSICO')
                    ->setCellValue('W1', 'SALARIO MES ANTERIOR')
                    ->setCellValue('X1', 'SALARIO INTEGRAL')
                    ->setCellValue('Y1', 'SUPLEMENTARIO')
                    ->setCellValue('Z1', 'INCAPACIDAD GENERAL')
                    ->setCellValue('AA1', 'DÍAS INCAPACIDAD GENERAL')
                    ->setCellValue('AB1', 'LICENCIA MATERNIDAD')
                    ->setCellValue('AC1', 'DÍAS LICENCIAS MATERNIDAD')
                    ->setCellValue('AD1', 'VACACIONES')
                    ->setCellValue('AE1', 'APORTE VOLUNTARIO')
                    ->setCellValue('AF1', 'VARIACIÓN CENTRO TRABAJO')
                    ->setCellValue('AG1', 'INCAPACIDAD ACCIDENTE TRABAJO ENFERMEDAD PROFESIONAL')
                    ->setCellValue('AH1', 'ENTIDAD PENSIÓN')
                    ->setCellValue('AI1', 'ENTIDAD PENSIÓN TRASLADA')
                    ->setCellValue('AJ1', 'ENTIDAD SALUD')
                    ->setCellValue('AK1', 'ENTIDAD SALUD TRASLADA')
                    ->setCellValue('AL1', 'CAJA COMPENSACIÓN')
                    ->setCellValue('AM1', 'DÍAS COTIZADOS PENSIÓN')
                    ->setCellValue('AN1', 'DÍAS COTIZADOS SALUD')
                    ->setCellValue('AO1', 'DIAS COTIZADOS RIESGOS PROFESIONALES')
                    ->setCellValue('AP1', 'DIAS COTIZADOS CAJAS COMPENSACIÓN')
                    ->setCellValue('AQ1', 'IBC PENSIÓN')
                    ->setCellValue('AR1', 'IBC SALUD')
                    ->setCellValue('AS1', 'IBC RIESGOS PROFESIONALES')
                    ->setCellValue('AT1', 'IBC CAJA COMPENSACIÓN')
                    ->setCellValue('AU1', 'TARIFA PENSIÓN')
                    ->setCellValue('AV1', 'TARIFA SALUD')
                    ->setCellValue('AW1', 'TARIFA RIESGOS PROFESIONALES')
                    ->setCellValue('AX1', 'TARIFA CAJA COMPENSACIÓN')
                    ->setCellValue('AY1', 'COTIZACIÓN PENSIÓN')
                    ->setCellValue('AZ1', 'COTIZACIÓN SALUD')
                    ->setCellValue('BA1', 'COTIZACIÓN RIESGOS PROFESIONALES')
                    ->setCellValue('BB1', 'COTIZACION CAJA COMPENSACIÓN')
                    ->setCellValue('BC1', 'APORTE VOLUNTARIO FONDO PENSIONES OBLIGATORIAS')
                    ->setCellValue('BD1', 'COTIZACIÓN VOLUNTARIO FONDO PENSIONES OBLIGATORIAS')
                    ->setCellValue('BE1', 'TOTAL COTIZACIÓN')
                    ->setCellValue('BF1', 'APORTES FONDO SOLIDARIDAD PENSIONAL SOLIDARIDAD');
        $i = 2;
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        $dateFechaPeriodo = $arPeriodo->getFechaDesde()->format('Y-m-d'). ' - ' . $arPeriodo->getFechaHasta()->format('Y-m-d');
        $arAportes = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
        $arAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->findBy(array('codigoPeriodoFk' => $codigoPeriodo));

        foreach ($arAportes as $arAporte) {
        $arEntidadPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        $arEntidadPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->findBy(array('codigoInterface' =>$arAporte->getCodigoEntidadPensionPertenece()));
        $arEntidadPensionPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        $arEntidadPensionPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->find($arEntidadPension[0]);

        $arEntidadSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        $arEntidadSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->findBy(array('codigoInterface' =>$arAporte->getCodigoEntidadSaludPertenece()));
        $arEntidadSaludPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        $arEntidadSaludPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->find($arEntidadSalud[0]);

        $arEntidadCaja = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
        $arEntidadCaja = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->findBy(array('codigoInterface' =>$arAporte->getCodigoEntidadCajaPertenece()));
        $arEntidadCajaPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
        $arEntidadCajaPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->find($arEntidadCaja[0]);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAporte->getCodigoAportePk())
                    ->setCellValue('B' . $i, $arAporte->getSsoSucursalRel()->getNombre())
                    ->setCellValue('C' . $i, $arAporte->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arAporte->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arAporte->getSecuencia())
                    ->setCellValue('F' . $i, $arAporte->getEmpleadoRel()->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('G' . $i, $arAporte->getContratoRel()->getSsoTipoCotizanteRel()->getNombre())
                    ->setCellValue('H' . $i, $arAporte->getContratoRel()->getSsoSubtipoCotizanteRel()->getNombre())
                    ->setCellValue('I' . $i, $arAporte->getEmpleadoRel()->getCiudadRel()->getDepartamentoRel()->getNombre())
                    ->setCellValue('J' . $i, $arAporte->getEmpleadoRel()->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arAporte->getIngreso())
                    ->setCellValue('L' . $i, $arAporte->getRetiro())
                    ->setCellValue('M' . $i, $arAporte->getTrasladoDesdeOtraEps())
                    ->setCellValue('N' . $i, $arAporte->getTrasladoAOtraEps())
                    ->setCellValue('O' . $i, $arAporte->getTrasladoDesdeOtraPension())
                    ->setCellValue('P' . $i, $arAporte->getTrasladoAOtraPension())
                    ->setCellValue('Q' . $i, $arAporte->getVariacionPermanenteSalario())
                    ->setCellValue('R' . $i, $arAporte->getCorrecciones())
                    ->setCellValue('S' . $i, $arAporte->getVariacionTransitoriaSalario())
                    ->setCellValue('T' . $i, $arAporte->getSuspensionTemporalContratoLicenciaServicios())
                    ->setCellValue('U' . $i, $arAporte->getDiasLicencia())
                    ->setCellValue('V' . $i, $arAporte->getSalarioBasico())
                    ->setCellValue('W' . $i, $arAporte->getSalarioMesAnterior())
                    ->setCellValue('X' . $i, $arAporte->getSalarioIntegral())
                    ->setCellValue('Y' . $i, $arAporte->getSuplementario())
                    ->setCellValue('Z' . $i, $arAporte->getIncapacidadGeneral())
                    ->setCellValue('AA' . $i, $arAporte->getDiasIncapacidadGeneral())
                    ->setCellValue('AB' . $i, $arAporte->getLicenciaMaternidad())
                    ->setCellValue('AC' . $i, $arAporte->getDiasLicenciaMaternidad())
                    ->setCellValue('AD' . $i, $arAporte->getVacaciones())
                    ->setCellValue('AE' . $i, $arAporte->getAporteVoluntario())
                    ->setCellValue('AF' . $i, $arAporte->getVariacionCentrosTrabajo())
                    ->setCellValue('AG' . $i, $arAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional())
                    ->setCellValue('AH' . $i, $arEntidadPensionPertenece->getNombre())
                    ->setCellValue('AI' . $i, $arAporte->getCodigoEntidadPensionTraslada())
                    ->setCellValue('AJ' . $i, $arEntidadSaludPertenece->getNombre())
                    ->setCellValue('AK' . $i, $arAporte->getCodigoEntidadSaludTraslada())
                    ->setCellValue('AL' . $i, $arEntidadCajaPertenece->getNombre())
                    ->setCellValue('AM' . $i, $arAporte->getDiasCotizadosPension())
                    ->setCellValue('AN' . $i, $arAporte->getDiasCotizadosSalud())
                    ->setCellValue('AO' . $i, $arAporte->getDiasCotizadosRiesgosProfesionales())
                    ->setCellValue('AP' . $i, $arAporte->getDiasCotizadosCajaCompensacion())
                    ->setCellValue('AQ' . $i, $arAporte->getIbcPension())
                    ->setCellValue('AR' . $i, $arAporte->getIbcSalud())
                    ->setCellValue('AS' . $i, $arAporte->getIbcRiesgosProfesionales())
                    ->setCellValue('AT' . $i, $arAporte->getIbcCaja())
                    ->setCellValue('AU' . $i, $arAporte->getTarifaPension())
                    ->setCellValue('AV' . $i, $arAporte->getTarifaSalud())
                    ->setCellValue('AW' . $i, $arAporte->getTarifaRiesgos())
                    ->setCellValue('AX' . $i, $arAporte->getTarifaCaja())
                    ->setCellValue('AY' . $i, $arAporte->getCotizacionPension())
                    ->setCellValue('AZ' . $i, $arAporte->getCotizacionSalud())
                    ->setCellValue('BA' . $i, $arAporte->getCotizacionRiesgos())
                    ->setCellValue('BB' . $i, $arAporte->getCotizacionCaja())
                    ->setCellValue('BC' . $i, $arAporte->getAporteVoluntarioFondoPensionesObligatorias())
                    ->setCellValue('BD' . $i, $arAporte->getCotizacionVoluntarioFondoPensionesObligatorias())
                    ->setCellValue('BE' . $i, $arAporte->getTotalCotizacion())
                    ->setCellValue('BF' . $i, $arAporte->getAportesFondoSolidaridadPensionalSolidaridad())
                    ;
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Aportes'.$dateFechaPeriodo);
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Aportes '.$dateFechaPeriodo.'.xlsx"');
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
