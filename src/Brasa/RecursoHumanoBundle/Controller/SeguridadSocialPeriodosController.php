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
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);        
        $this->listar();
        if($form->isValid()) { 
            if($request->request->get('OpGenerar')) {
                $codigoPeriodo = $request->request->get('OpGenerar');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->generar($codigoPeriodo);                
            }                                   
            if($request->request->get('OpDesgenerar')) {
                $codigoPeriodo = $request->request->get('OpDesgenerar');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->desgenerar($codigoPeriodo);                
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
                fputs($ar, $arPeriodoDetalle->getFechaPago()->format('Y-m-d'));
                //Numero total de empleados
                fputs($ar, $this->RellenarNr($arPeriodoDetalle->getNumeroEmpleados(), "0", 5, "I"));
                //Valor total de la nomina
                fputs($ar, $this->RellenarNr($arPeriodoDetalle->getVrNomina(), "0", 12, "I"));
                fputs($ar, '1');
                fputs($ar, '89');                            
                fputs($ar, "\n");                            
                $arSsoPila = new \Soga\NominaBundle\Entity\SsoPila();                                                                
                $arSsoPila = $em->getRepository('SogaNominaBundle:SsoPila')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));                                                    
                foreach($arSsoPila as $arSsoPila) {
                    fputs($ar, $arSsoPila->getTipoRegistro());
                    fputs($ar, $arSsoPila->getSecuencia());
                    fputs($ar, $arSsoPila->getTipoDocumento());
                    fputs($ar, $this->RellenarNr($arSsoPila->getNumeroIdentificacion(), " ", 16, "D"));
                    fputs($ar, $arSsoPila->getTipo());
                    fputs($ar, $arSsoPila->getSubtipo());
                    fputs($ar, $arSsoPila->getExtranjeroNoObligadoCotizarPensiones());
                    fputs($ar, $arSsoPila->getColombianoResidenteExterior());
                    fputs($ar, $arSsoPila->getCodigoDepartamento());
                    fputs($ar, $arSsoPila->getCodigoMunicipio());
                    fputs($ar, $arSsoPila->getPrimerApellido());
                    fputs($ar, $arSsoPila->getSegundoApellido());
                    fputs($ar, $arSsoPila->getPrimerNombre());
                    fputs($ar, $arSsoPila->getSegundoNombre());
                    fputs($ar, $arSsoPila->getIngreso());
                    fputs($ar, $arSsoPila->getRetiro());
                    fputs($ar, $arSsoPila->getTrasladoDesdeOtraEps());
                    fputs($ar, $arSsoPila->getTrasladoAOtraEps());
                    fputs($ar, $arSsoPila->getTrasladoDesdeOtraPension());
                    fputs($ar, $arSsoPila->getTrasladoAOtraPension());
                    fputs($ar, $arSsoPila->getVariacionPermanenteSalario());
                    fputs($ar, $arSsoPila->getCorrecciones());
                    fputs($ar, $arSsoPila->getVariacionTransitoriaSalario());
                    fputs($ar, $arSsoPila->getSuspensionTemporalContratoLicenciaServicios());
                    fputs($ar, $arSsoPila->getIncapacidadGeneral());
                    fputs($ar, $arSsoPila->getLicenciaMaternidad());
                    fputs($ar, $arSsoPila->getVacaciones());
                    fputs($ar, $arSsoPila->getAporteVoluntario());
                    fputs($ar, $arSsoPila->getVariacionCentrosTrabajo());
                    fputs($ar, $this->RellenarNr($arSsoPila->getIncapacidadAccidenteTrabajoEnfermedadProfesional(), "0", 2, "I"));
                    fputs($ar, $arSsoPila->getCodigoEntidadPensionPertenece());
                    fputs($ar, $arSsoPila->getCodigoEntidadPensionTraslada());
                    fputs($ar, $arSsoPila->getCodigoEntidadSaludPertenece());
                    fputs($ar, $arSsoPila->getCodigoEntidadSaludTraslada());
                    fputs($ar, $arSsoPila->getCodigoEntidadCajaPertenece());
                    fputs($ar, $this->RellenarNr($arSsoPila->getDiasCotizadosPension(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getDiasCotizadosSalud(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getDiasCotizadosRiesgosProfesionales(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getDiasCotizadosCajaCompensacion(), "0", 2, "I"));                                                                
                    fputs($ar, $this->RellenarNr($arSsoPila->getSalarioBasico(), "0", 9, "I"));
                    fputs($ar, $arSsoPila->getSalarioIntegral());
                    fputs($ar, $this->RellenarNr($arSsoPila->getIbcPension(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getIbcSalud(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getIbcRiesgosProfesionales(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getIbcCaja(), "0", 9, "I"));
                    fputs($ar, $arSsoPila->getTarifaAportesPensiones());
                    fputs($ar, $this->RellenarNr($arSsoPila->getCotizacionObligatoria(), "0", 9, "I"));                                
                    fputs($ar, $arSsoPila->getAporteVoluntarioFondoPensionesObligatorias());
                    fputs($ar, $arSsoPila->getCotizacionVoluntarioFondoPensionesObligatorias());
                    fputs($ar, $this->RellenarNr($arSsoPila->getTotalCotizacion(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getAportesFondoSolidaridadPensionalSolidaridad(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getAportesFondoSolidaridadPensionalSubsistencia(), "0", 9, "I"));
                    fputs($ar, $arSsoPila->getValorNoRetenidoAportesVoluntarios());
                    fputs($ar, $arSsoPila->getTarifaAportesSalud());                                
                    fputs($ar, $this->RellenarNr($arSsoPila->getCotizacionObligatoriaSalud(), "0", 9, "I"));
                    fputs($ar, $arSsoPila->getValorUpcAdicional());
                    fputs($ar, $arSsoPila->getNumeroAutorizacionIncapacidadEnfermedadGeneral());
                    fputs($ar, $arSsoPila->getValorIncapacidadEnfermedadGeneral());
                    fputs($ar, $arSsoPila->getNumeroAutorizacionLicenciaMaternidadPaternidad());
                    fputs($ar, $arSsoPila->getValorLicenciaMaternidadPaternidad());
                    fputs($ar, $arSsoPila->getTarifaAportesRiesgosProfesionales());
                    fputs($ar, $arSsoPila->getCentroTrabajoCodigoCt());
                    fputs($ar, $this->RellenarNr($arSsoPila->getCotizacionObligatoriaRiesgos(), "0", 9, "I"));
                    fputs($ar, $arSsoPila->getTarifaAportesCCF());                                
                    fputs($ar, $this->RellenarNr($arSsoPila->getValorAporteCCF(), "0", 9, "I"));
                    fputs($ar, $arSsoPila->getTarifaAportesSENA());
                    fputs($ar, $arSsoPila->getValorAportesSENA());
                    fputs($ar, $arSsoPila->getTarifaAportesICBF());
                    fputs($ar, $arSsoPila->getValorAporteICBF());
                    fputs($ar, $arSsoPila->getTarifaAportesESAP());
                    fputs($ar, $arSsoPila->getValorAporteESAP());
                    fputs($ar, $arSsoPila->getTarifaAportesMEN());
                    fputs($ar, $arSsoPila->getValorAporteMEN());
                    fputs($ar, $arSsoPila->getTipoDocumentoResponsableUPC());
                    fputs($ar, $arSsoPila->getNumeroIdentificacionResponsableUPCAdicional());
                    fputs($ar, $arSsoPila->getCotizanteExoneradoPagoAporteParafiscalesSalud());
                    fputs($ar, $arSsoPila->getCodigoAdministradoraRiesgosLaborales());
                    fputs($ar, $arSsoPila->getClaseRiesgoAfiliado());
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
        }                            
        $arSsoPeriodoDetalles = $paginator->paginate($em->createQuery($this->strDqlListaDetalle), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalle.html.twig', array(
            'arSsoPeriodoDetalles' => $arSsoPeriodoDetalles,
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
        $arSsoAportes = $paginator->paginate($em->createQuery($this->strDqlListaDetalleAportes), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalleAportes.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arSsoAportes' => $arSsoAportes,
            'form' => $form->createView()));
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
    
}
