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
                    fputs($ar, $this->RellenarNr($arSsoAporte->getPrimerApellido(), "-", 20, "D"));
                    $strPrueba = $this->RellenarNr($arSsoAporte->getPrimerApellido(), " ", 20, "D");
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
                    /*fputs($ar, $arSsoAporte->getCodigoEntidadPensionPertenece());
                    fputs($ar, $arSsoAporte->getCodigoEntidadPensionTraslada());
                    fputs($ar, $arSsoAporte->getCodigoEntidadSaludPertenece());
                    fputs($ar, $arSsoAporte->getCodigoEntidadSaludTraslada());
                    fputs($ar, $arSsoAporte->getCodigoEntidadCajaPertenece());
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosPension(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosSalud(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosRiesgosProfesionales(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosCajaCompensacion(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getSalarioBasico(), "0", 9, "I"));
                    fputs($ar, $arSsoPila->getSalarioIntegral());
                    fputs($ar, $this->RellenarNr($arSsoPila->getIbcPension(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getIbcSalud(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getIbcRiesgosProfesionales(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getIbcCaja(), "0", 9, "I"));
                    fputs($ar, $arSsoAporte->getTarifaAportesPensiones());
                    fputs($ar, $this->RellenarNr($arSsoPila->getCotizacionObligatoria(), "0", 9, "I"));
                    fputs($ar, $arSsoAporte->getAporteVoluntarioFondoPensionesObligatorias());
                    fputs($ar, $arSsoAporte->getCotizacionVoluntarioFondoPensionesObligatorias());
                    fputs($ar, $this->RellenarNr($arSsoPila->getTotalCotizacion(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getAportesFondoSolidaridadPensionalSolidaridad(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoPila->getAportesFondoSolidaridadPensionalSubsistencia(), "0", 9, "I"));
                    fputs($ar, $arSsoAporte->getValorNoRetenidoAportesVoluntarios());
                    fputs($ar, $arSsoAporte->getTarifaAportesSalud());
                    fputs($ar, $this->RellenarNr($arSsoPila->getCotizacionObligatoriaSalud(), "0", 9, "I"));
                    fputs($ar, $arSsoAporte->getValorUpcAdicional());
                    fputs($ar, $arSsoAporte->getNumeroAutorizacionIncapacidadEnfermedadGeneral());
                    fputs($ar, $arSsoAporte->getValorIncapacidadEnfermedadGeneral());
                    fputs($ar, $arSsoAporte->getNumeroAutorizacionLicenciaMaternidadPaternidad());
                    fputs($ar, $arSsoAporte->getValorLicenciaMaternidadPaternidad());
                    fputs($ar, $arSsoAporte->getTarifaAportesRiesgosProfesionales());
                    fputs($ar, $arSsoAporte->getCentroTrabajoCodigoCt());
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionObligatoriaRiesgos(), "0", 9, "I"));
                    fputs($ar, $arSsoAporte->getTarifaAportesCCF());
                    fputs($ar, $this->RellenarNr($arSsoAporte->getValorAporteCCF(), "0", 9, "I"));
                    fputs($ar, $arSsoAporte->getTarifaAportesSENA());
                    fputs($ar, $arSsoAporte->getValorAportesSENA());
                    fputs($ar, $arSsoAporte->getTarifaAportesICBF());
                    fputs($ar, $arSsoAporte->getValorAporteICBF());
                    fputs($ar, $arSsoAporte->getTarifaAportesESAP());
                    fputs($ar, $arSsoAporte->getValorAporteESAP());
                    fputs($ar, $arSsoAporte->getTarifaAportesMEN());
                    fputs($ar, $arSsoAporte->getValorAporteMEN());
                    fputs($ar, $arSsoAporte->getTipoDocumentoResponsableUPC());
                    fputs($ar, $arSsoAporte->getNumeroIdentificacionResponsableUPCAdicional());
                    fputs($ar, $arSsoAporte->getCotizanteExoneradoPagoAporteParafiscalesSalud());
                    fputs($ar, $arSsoAporte->getCodigoAdministradoraRiesgosLaborales());
                    fputs($ar, $arSsoAporte->getClaseRiesgoAfiliado());
                    */
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
