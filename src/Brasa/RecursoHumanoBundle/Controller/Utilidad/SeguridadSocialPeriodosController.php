<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSsoPeriodoType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSsoPeriodoDetalleType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSsoAporteType; 

class SeguridadSocialPeriodosController extends Controller
{
    var $strDqlLista = "";
    var $strDqlListaDetalle = "";
    var $strDqlListaEmpleados = "";
    var $strDqlListaDetalleAportes = "";
    var $strCodigoPeriodoDetalleTraslados = "";
    var $strCodigoPeriodoDetalleCopias = "";

    /**
     * @Route("/rhu/seguridadsocial/periodo/lista", name="brs_rhu_ss_periodo_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 77)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($request->request->get('OpGenerar')) {
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $codigoPeriodo = $request->request->get('OpGenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->generar($codigoPeriodo);
            }
            if($request->request->get('OpDesgenerar')) {
                $codigoPeriodo = $request->request->get('OpDesgenerar');
                $resultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->desgenerar($codigoPeriodo);
                if ($resultado == false){
                    $objMensaje->Mensaje("error", "No se puede desgenerar el registro por que hay sucursal(es) generada(s) y cerrada(s)", $this);
                }
            }
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
            if($request->request->get('OpEliminar')) {
                //$codigoPeriodo = $request->request->get('OpEliminar');
                $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodo) {
                        $arPeriodoEliminar = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
                        $arPeriodoEliminar =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
                        if ($arPeriodoEliminar->getEstadoCerrado() == 1){
                            $objMensaje->Mensaje("error", "No se puede eliminar el registro se encuentra cerrado", $this);
                        } else {
                            if ($arPeriodoEliminar->getEstadoGenerado() == 1){
                                $objMensaje->Mensaje("error", "Se debe desgenerar el registro para eliminarlo", $this);
                            } else {
                                $em->remove($arPeriodoEliminar);
                                $em->flush();
                              }        
                         }
                    }
                }
                return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_lista'));
            }
        }
        $arSsoPeriodos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:lista.html.twig', array(
            'arSsoPeriodos' => $arSsoPeriodos,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/seguridadsocial/periodo/nuevo/{codigoPeriodo}", name="brs_rhu_ss_periodo_nuevo")
     */
    public function nuevoAction($codigoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        if ($codigoPeriodo != 0)
        {
            $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        }    
        $form = $this->createForm(new RhuSsoPeriodoType(), $arPeriodo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arPeriodo = $form->getData();
            $em->persist($arPeriodo);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:nuevo.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/{codigoPeriodo}", name="brs_rhu_ss_periodo_detalle")
     */
    public function detalleAction($codigoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        $this->listarDetalle($codigoPeriodo);
        if($form->isValid()) {
            if($request->request->get('OpEliminar')) {
                $codigoPeriodo = $request->request->get('OpEliminar');
                $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalle) {
                        $arPeriodoDetalleEliminar = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                        $arPeriodoDetalleEliminar =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
                        if ($arPeriodoDetalleEliminar->getEstadoGenerado() == 1){
                            $objMensaje->Mensaje("error", "No se puede eliminar el registro se encuentra generado", $this);
                        } else {
                            $arPeriodoEmpleadoEliminar = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                            $arPeriodoEmpleadoEliminar =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));
                            foreach ($arPeriodoEmpleadoEliminar AS $arPeriodoEmpleadoEliminar) {
                                $em->remove($arPeriodoEmpleadoEliminar);
                                $em->flush();
                            }
                            $em->remove($arPeriodoDetalleEliminar);
                            $em->flush();
                          }
                            return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
                    }
                }
            }
            if($request->request->get('OpGenerar')) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $codigoPeriodoDetalle = $request->request->get('OpGenerar');
                $arSsoAportes = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
                $arSsoAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));
                $resultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->generar($codigoPeriodoDetalle);
                if ($resultado == false){
                    $objMensaje->Mensaje("error", "No hay personal a generar en el periodo detalle " . $codigoPeriodoDetalle . "", $this);
                }
                return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
            }
            if($request->request->get('OpDesgenerar')) {
                $codigoPeriodoDetalle = $request->request->get('OpDesgenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->desgenerar($codigoPeriodoDetalle);
                return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
            }
            if($request->request->get('OpCerrar')) {
                $codigoPeriodo = $request->request->get('OpCerrar');
                $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodo);
                if ($arPeriodoDetalle->getEstadoGenerado() == 0){
                    $objMensaje->Mensaje("error", "Debe generar periodo de la sucursal para poder cerrarlo", $this);
                } else {
                    $arPeriodoDetalle->setEstadoCerrado(1);
                    $em->persist($arPeriodoDetalle);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
            }
            if($request->request->get('OpGenerarArchivo')) {
                $codigoPeriodoDetalle = $request->request->get('OpGenerarArchivo');
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                
                $arEntidadRiesgos = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
                $arEntidadRiesgos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($arConfiguracionNomina->getCodigoEntidadRiesgoFk());
                $condigoInterfaceRiesgos = $arEntidadRiesgos->getCodigoInterface();
                $strRutaArchivo = $arConfiguracion->getRutaTemporal();
                $tipoSucursal = $arConfiguracionNomina->getTipoPlanillaSso();
                if ($tipoSucursal == null){
                    $tipoSucursal = 'S';
                } 
                ///$strRutaArchivo = "c:/xampp/";
                $strNombreArchivo = "pila" . date('YmdHis') . ".txt";
                ob_clean();
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
                //fputs($ar, '3');
                fputs($ar, $arConfiguracion->getDigitoVerificacionEmpresa());
                fputs($ar, 'E');
                fputs($ar, '          ');
                fputs($ar, '          '); // Nro 9 del formato
                fputs($ar, $tipoSucursal); // Nro 10 del formato
                if($tipoSucursal == "U") {
                    fputs($ar, $this->RellenarNr("", " ", 10, "D")); //Nro 11
                    fputs($ar, $this->RellenarNr("", " ", 40, "D")); //Nro 12                    
                } else {
                    fputs($ar, $this->RellenarNr($arPeriodoDetalle->getSsoSucursalRel()->getCodigoInterface(), " ", 10, "D")); //Nro 11
                    fputs($ar, $this->RellenarNr($arPeriodoDetalle->getSsoSucursalRel()->getNombre(), " ", 40, "D")); //Nro 12                    
                }

                //Arp del aportante
                //fputs($ar, '14-18 '); 
                fputs($ar, $this->RellenarNr($condigoInterfaceRiesgos, " ", 6, "D")); //Nro 13
                //Periodo pago para los diferentes sistemas
                fputs($ar, $arPeriodoDetalle->getSsoPeriodoRel()->getAnio().'-'. $this->RellenarNr($arPeriodoDetalle->getSsoPeriodoRel()->getMes(), "0", 2, "I")); //Nro 14
                fputs($ar, $arPeriodoDetalle->getSsoPeriodoRel()->getAnioPago().'-'. $this->RellenarNr($arPeriodoDetalle->getSsoPeriodoRel()->getMesPago(), "0", 2, "I")); //Nro 15
                //Numero radicacion de la planilla
                fputs($ar, '0000000000'); //Nro 16
                
                //Fecha de pago
                //fputs($ar, $arPeriodoDetalle->getSsoPeriodoRel()->getFechaPago()->format('Y-m-d')); //Nro 17
                fputs($ar, $this->RellenarNr("", " ", 10, "D"));
                
                //Numero total de empleados
                fputs($ar, $this->RellenarNr($arPeriodoDetalle->getNumeroEmpleados(), "0", 5, "I")); 
                //Valor total de la nomina parafiscales (ibc caja)
                fputs($ar, $this->RellenarNr($arPeriodoDetalle->getTotalIngresoBaseCotizacionCaja(), "0", 12, "I"));
                //fputs($ar, '000000000000'); //Es el anterior
                fputs($ar, '1');
                fputs($ar, '88');
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
                    fputs($ar, $arSsoAporte->getIngreso()); //
                    fputs($ar, $arSsoAporte->getRetiro()); //
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
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadPensionTraslada(), " ", 6, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadSaludPertenece(), " ", 6, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadSaludTraslada(), " ", 6, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoEntidadCajaPertenece(), " ", 6, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosPension(), "0", 2, "I")); //estaba $this->RellenarNr($arSsoAporte->getDiasCotizadosPension(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosSalud(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosRiesgosProfesionales(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getDiasCotizadosCajaCompensacion(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getSalarioBasico(), "0", 9, "I"));
                    fputs($ar, $arSsoAporte->getSalarioIntegral());
                    fputs($ar, $this->RellenarNr($arSsoAporte->getIbcPension(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getIbcSalud(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getIbcRiesgosProfesionales(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getIbcCaja(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaPension()/100, 5, '.', '') , "0", 7, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionPension(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getAporteVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getTotalCotizacionFondos(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getAportesFondoSolidaridadPensionalSolidaridad(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getAportesFondoSolidaridadPensionalSubsistencia(), "0", 9, "I"));
                    fputs($ar, '000000000');
                    fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaSalud()/100, 5, '.', '') , "0", 7, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionSalud(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getValorUpcAdicional(), "0", 9, "I"));
                    
                    fputs($ar, $this->RellenarNr($arSsoAporte->getNumeroAutorizacionIncapacidadEnfermedadGeneral(), " ", 15, "D"));                    
                    fputs($ar, $this->RellenarNr($arSsoAporte->getValorIncapacidadEnfermedadGeneral(), "0", 9, "D"));                    
                    fputs($ar, $this->RellenarNr($arSsoAporte->getNumeroAutorizacionLicenciaMaternidadPaternidad(), " ", 15, "D"));                   
                    fputs($ar, $this->RellenarNr($arSsoAporte->getValorIncapacidadLicenciaMaternidadPaternidad(), "0", 9, "D"));
                    
                    fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaRiesgos()/100, 7, '.', '') , "0", 9, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCentroTrabajoCodigoCt(), "0", 9, "I"));                    
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionRiesgos(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaCaja()/100, 5, '.', '')  , "0", 7, "D"));
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionCaja(), "0", 9, "I"));                    
                    fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaSENA()/100, 5, '.', ''), "0", 7, "D"));                    
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionSena(), "0", 9, "I"));                    
                    fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaIcbf()/100, 5, '.', '') , "0", 7, "D"));                    
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCotizacionIcbf(), "0", 9, "I"));                    
                    fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaAportesESAP()/100, 5, '.', '') , "0", 7, "D"));                   
                    fputs($ar, $this->RellenarNr($arSsoAporte->getValorAportesESAP(), "0", 9, "I"));                    
                    fputs($ar, $this->RellenarNr(number_format($arSsoAporte->getTarifaAportesMEN()/100, 5, '.', '') , "0", 7, "D"));
                    //fputs($ar, "0.00000");
                    fputs($ar, $this->RellenarNr($arSsoAporte->getValorAportesMEN(), "0", 9, "I"));
                    //fputs($ar, "000000000");                    
                    fputs($ar, $this->RellenarNr($arSsoAporte->getTipoDocumentoResponsableUPC(), " ", 2, "D"));
                    //fputs($ar, "  ");
                    fputs($ar, $this->RellenarNr($arSsoAporte->getNumeroIdentificacionResponsableUPCAdicional(), " ", 2, "I"));
                    //fputs($ar, "                ");
                    fputs($ar, $arSsoAporte->getCotizanteExoneradoPagoAporteParafiscalesSalud());
                    //fputs($ar, " ");
                    fputs($ar, $this->RellenarNr($arSsoAporte->getCodigoAdministradoraRiesgosLaborales(), " ", 6, "D"));
                    //fputs($ar, "      ");
                    fputs($ar, $arSsoAporte->getClaseRiesgoAfiliado());
                    fputs($ar, "                ");
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
                ob_clean();
                set_time_limit(0);
                ini_set("memory_limit", -1);                 
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
                $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
                $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
                for($col = 'A'; $col !== 'AN'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                    
                }
                for($col = 'G'; $col !== 'M'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'G.PAGO')
                    ->setCellValue('E1', 'CONTRATO')
                    ->setCellValue('F1', 'C.COSTO')
                    ->setCellValue('G1', 'ING')
                    ->setCellValue('H1', 'RET')
                    ->setCellValue('I1', 'VST')
                    ->setCellValue('J1', 'SLN')
                    ->setCellValue('K1', 'IGE')
                    ->setCellValue('L1', 'LMA')
                    ->setCellValue('M1', 'VAC')    
                    ->setCellValue('N1', 'IRP')
                    ->setCellValue('O1', 'SALARIO')
                    ->setCellValue('P1', 'VR.VAC')
                    ->setCellValue('Q1', 'SI')
                    ->setCellValue('R1', 'D.P')
                    ->setCellValue('S1', 'D.S')
                    ->setCellValue('T1', 'D.R.P')
                    ->setCellValue('U1', 'D.C')
                    ->setCellValue('V1', 'IBC P')
                    ->setCellValue('W1', 'IBC S')
                    ->setCellValue('X1', 'IBC R')
                    ->setCellValue('Y1', 'IBC C')
                    ->setCellValue('Z1', 'T.P')
                    ->setCellValue('AA1', 'T.S')
                    ->setCellValue('AB1', 'T.R')
                    ->setCellValue('AC1', 'T.C')
                    ->setCellValue('AD1', 'T.SN')
                    ->setCellValue('AE1', 'T.I')
                    ->setCellValue('AF1', 'C.P')
                    ->setCellValue('AG1', 'C.FSSO')
                    ->setCellValue('AH1', 'C.FSSU')
                    ->setCellValue('AI1', 'C.S')
                    ->setCellValue('AJ1', 'C.R')
                    ->setCellValue('AK1', 'C.C')
                    ->setCellValue('AL1', 'C.SN')
                    ->setCellValue('AM1', 'C.I')
                    ->setCellValue('AN1', 'TOTAL')
                    ->setCellValue('AO1', 'E.PENSION')
                    ->setCellValue('AP1', 'E.SALUD')
                    ->setCellValue('AQ1', 'E.RIESGOS')
                    ->setCellValue('AR1', 'E.CAJA');
                
                $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
                /*$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B1', $arPeriodoDetalle->getCodigoPeriodoDetallePk())
                    ->setCellValue('B2', $arPeriodoDetalle->getCodigoSucursalFk())
                    ->setCellValue('B3', $arPeriodoDetalle->getSsoSucursalRel()->getNombre())    ;*/
                $i = 2;
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
                       $licenciaMaternidad = $arSsoAporte->getLicenciaMaternidad()." ". $arSsoAporte->getDiasLicenciaMaternidad(); 
                    }
                    $vacaciones = "";
                    if ($arSsoAporte->getVacaciones() == "X"){
                       $vacaciones = $arSsoAporte->getVacaciones()." ". $arSsoAporte->getDiasVacaciones(); 
                    }
                    $riesgosProfesionales = "";
                    if ($arSsoAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional() > 0){
                       $riesgosProfesionales = $arSsoAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional(); 
                    }
                    $salarioIntegral = "";
                    if ($arSsoAporte->getSalarioIntegral() == "X"){
                       $salarioIntegral = $arSsoAporte->getSalarioIntegral(); 
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSsoAporte->getCodigoAportePk())
                    ->setCellValue('B' . $i, $arSsoAporte->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arSsoAporte->getEmpleadoRel()->getNombreCorto())       
                    ->setCellValue('D' . $i, $arSsoAporte->getContratoRel()->getCentroCostoRel()->getNombre())        
                    ->setCellValue('E' . $i, $arSsoAporte->getCodigoContratoFk())
                    ->setCellValue('F' . $i, $arSsoAporte->getEmpleadoRel()->getCodigoCentroCostoContabilidadFk()) 
                    ->setCellValue('G' . $i, $arSsoAporte->getIngreso())        
                    ->setCellValue('H' . $i, $arSsoAporte->getRetiro())
                    ->setCellValue('I' . $i, $arSsoAporte->getVariacionTransitoriaSalario())
                    ->setCellValue('J' . $i, $suspencionTemporalContratoLicenciaServicios)
                    ->setCellValue('K' . $i, $incapacidadGeneral)
                    ->setCellValue('L' . $i, $licenciaMaternidad)
                    ->setCellValue('M' . $i, $vacaciones)
                    ->setCellValue('N' . $i, $riesgosProfesionales)
                    ->setCellValue('O' . $i, $arSsoAporte->getSalarioBasico())
                    ->setCellValue('P' . $i, $arSsoAporte->getVrVacaciones())
                    ->setCellValue('Q' . $i, $salarioIntegral)        
                    ->setCellValue('R' . $i, $arSsoAporte->getDiasCotizadosPension())
                    ->setCellValue('S' . $i, $arSsoAporte->getDiasCotizadosSalud())
                    ->setCellValue('T' . $i, $arSsoAporte->getDiasCotizadosRiesgosProfesionales())
                    ->setCellValue('U' . $i, $arSsoAporte->getDiasCotizadosCajaCompensacion())
                    ->setCellValue('V' . $i, $arSsoAporte->getIbcPension())
                    ->setCellValue('W' . $i, $arSsoAporte->getIbcSalud())
                    ->setCellValue('X' . $i, $arSsoAporte->getIbcRiesgosProfesionales())
                    ->setCellValue('Y' . $i, $arSsoAporte->getIbcCaja())
                    ->setCellValue('Z' . $i, $arSsoAporte->getTarifaPension())
                    ->setCellValue('AA' . $i, $arSsoAporte->getTarifaSalud())
                    ->setCellValue('AB' . $i, $arSsoAporte->getTarifaRiesgos())
                    ->setCellValue('AC' . $i, $arSsoAporte->getTarifaCaja())
                    ->setCellValue('AD' . $i, $arSsoAporte->getTarifaSena())
                    ->setCellValue('AE' . $i, $arSsoAporte->getTarifaIcbf())
                    ->setCellValue('AF' . $i, $arSsoAporte->getCotizacionPension())
                    ->setCellValue('AG' . $i, $arSsoAporte->getAportesFondoSolidaridadPensionalSolidaridad())
                    ->setCellValue('AH' . $i, $arSsoAporte->getAportesFondoSolidaridadPensionalSubsistencia())
                    ->setCellValue('AI' . $i, $arSsoAporte->getCotizacionSalud())
                    ->setCellValue('AJ' . $i, $arSsoAporte->getCotizacionRiesgos())
                    ->setCellValue('AK' . $i, $arSsoAporte->getCotizacionCaja())
                    ->setCellValue('AL' . $i, $arSsoAporte->getCotizacionSena())
                    ->setCellValue('AM' . $i, $arSsoAporte->getCotizacionIcbf())
                    ->setCellValue('AN' . $i, $arSsoAporte->getTotalCotizacion());
                    if($arSsoAporte->getCodigoEntidadPensionFk()) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO' . $i, $arSsoAporte->getEntidadPensionRel()->getNombre());
                    }
                    if($arSsoAporte->getCodigoEntidadSaludFk()) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP' . $i, $arSsoAporte->getEntidadSaludRel()->getNombre());
                    }                    
                    if($arSsoAporte->getCodigoEntidadRiesgoFk()) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ' . $i, $arSsoAporte->getEntidadRiesgoProfesionalRel()->getNombre());
                    }
                    if($arSsoAporte->getCodigoEntidadCajaFk()) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR' . $i, $arSsoAporte->getEntidadCajaRel()->getNombre());
                    }                    
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
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalle.html.twig', array(
            'arSsoPeriodoDetalles' => $arSsoPeriodoDetalles,
            'codigoPeriodo' => $codigoPeriodo,
            'arPeriodo' => $arPeriodo,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/nuevo/{codigoPeriodo}/{codigoPeriodoDetallePk}", name="brs_rhu_ss_periodo_detalle_nuevo")
     */
    public function detalleNuevoAction($codigoPeriodo, $codigoPeriodoDetallePk) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        if ($codigoPeriodoDetallePk != 0)
        {
            $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetallePk);
        }    
        $form = $this->createForm(new RhuSsoPeriodoDetalleType(), $arPeriodoDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPeriodoDetalle = $form->getData();
            $arPeriodoDetalle->setSsoPeriodoRel($arPeriodo);
            $em->persist($arPeriodoDetalle);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
            return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalleNuevo.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/empleados/{codigoPeriodoDetalle}", name="brs_rhu_ss_periodo_detalle_empleados")
     */
    public function detalleEmpleadosAction($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $form = $this->formularioDetalleEmpleado($arPeriodoDetalle);
        $form->handleRequest($request);
        $this->listarEmpleados($codigoPeriodoDetalle);        
        if($form->isValid()) {
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalleEmpleadoPk) {
                        $arPeriodoDetalleEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arPeriodoDetalleEmpleado =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($codigoPeriodoDetalleEmpleadoPk);
                        $em->remove($arPeriodoDetalleEmpleado);
                        
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle_empleados', array('codigoPeriodoDetalle' => $codigoPeriodoDetalle)));
                }
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarDetalleEmpleado($form);
                $this->listarEmpleados($codigoPeriodoDetalle);
            }
            if($form->get('BtnActualizarEmpleadoAporte')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->actualizar($codigoPeriodoDetalle);
            }
            if($form->get('BtnActualizarEmpleados')->isClicked()) {                
                $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->contratosPeriodo($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'));
                foreach ($arContratos as $arContrato) {
                    if($arContrato->getCentroCostoRel()->getCodigoSucursalFk() == $arPeriodoDetalle->getCodigoSucursalFk()) {
                        $arPeriodoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arPeriodoEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->findOneBy(array('codigoEmpleadoFk' => $arContrato->getCodigoEmpleadoFk(), 'codigoPeriodoFk' => $arPeriodoDetalle->getCodigoPeriodoFk()));                
                        if(!$arPeriodoEmpleado) {
                            $arPeriodoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                            $arPeriodoEmpleado->setEmpleadoRel($arContrato->getEmpleadoRel());
                            $arPeriodoEmpleado->setSsoPeriodoRel($arPeriodoDetalle->getSsoPeriodoRel());
                            $arPeriodoEmpleado->setSsoSucursalRel($arPeriodoDetalle->getSsoSucursalRel());
                            $arPeriodoEmpleado->setContratoRel($arContrato);               
                            $arPeriodoEmpleado->setSsoPeriodoDetalleRel($arPeriodoDetalle);
                            $em->persist($arPeriodoEmpleado);
                        }
                    }
                }
                $em->flush();
                //$em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->actualizar($codigoPeriodoDetalle);
            }                        
            if($form->get('BtnActualizarSalarioMinimo')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {                
                    if($arrControles['TxtSalario'.$intCodigo] != "" && $arrControles['TxtSalario'.$intCodigo] != 0) {
                        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                        $arConfiguracion =  $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                        $arPeriodoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arPeriodoEmpleado =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($intCodigo);
                        $floSalario = $arConfiguracion->getVrSalario();
                        $arPeriodoEmpleado->setVrSalario($floSalario);
                        $em->persist($arPeriodoEmpleado);                        
                    }
                }
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle_empleados', array('codigoPeriodoDetalle' => $codigoPeriodoDetalle)));
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcelEmpleados($codigoPeriodoDetalle);
            }            
        }
        $arSsoPeriodoEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaEmpleados), $request->query->get('page', 1), 500);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:empleados.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arPeriodoEmpleados' => $arSsoPeriodoEmpleados,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/aportes/{codigoPeriodoDetalle}", name="brs_rhu_ss_periodo_detalle_aportes")
     */
    public function detalleAportesAction($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioDetalleAporte();
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
            /*if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalleAportePk) {
                        $arPeriodoDetalleAporte = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
                        $arPeriodoDetalleAporte =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->find($codigoPeriodoDetalleAportePk);
                        $em->remove($arPeriodoDetalleAporte);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_ss_periodo_detalle_aportes', array('codigoPeriodoDetalle' => $codigoPeriodoDetalle)));
                }
            }*/
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarDetalleAporte($form);
                $this->listarDetalleAportes($codigoPeriodoDetalle);
            }
        }
        $arSsoAportes = $paginator->paginate($em->createQuery($this->strDqlListaDetalleAportes), $request->query->get('page', 1), 2000);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalleAportes.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arSsoAportes' => $arSsoAportes,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/aporte/editar/{codigoAporte}", name="brs_rhu_seguridadsocial_periodo_detalle_aporte_editar")
     */         
    public function detalleAportesEditarAction($codigoAporte) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $arAporte = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
        $arAporte =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->find($codigoAporte);                
        $form = $this->createForm(new RhuSsoAporteType, $arAporte);
        $form->handleRequest($request);
        if($form->isValid()) {
            $arAporte = $form->getData();
            if(!$arAporte->getIngreso()) {
                $arAporte->setIngreso(' ');
            }
            if(!$arAporte->getRetiro()) {
                $arAporte->setRetiro(' ');
            }            
            $totalCotizacion = $arAporte->getAportesFondoSolidaridadPensionalSolidaridad() + $arAporte->getAportesFondoSolidaridadPensionalSubsistencia() + $arAporte->getCotizacionPension() + $arAporte->getCotizacionSalud() + $arAporte->getCotizacionRiesgos() + $arAporte->getCotizacionCaja() + $arAporte->getCotizacionIcbf() + $arAporte->getCotizacionSena();            
            $arAporte->setTotalCotizacion($totalCotizacion);
            $em->persist($arAporte);
            $em->flush();
            $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->liquidar($arAporte->getCodigoPeriodoDetalleFk());
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }        
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:detalleAportesEditar.html.twig', array(
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/empleados/trasladar/{codigoPeriodoDetalle}", name="brs_rhu_ss_periodo_detalle_empleados_trasladar")
     */
    public function trasladarEmpleadosAction($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $form = $this->formularioTrasladarEmpleado($arPeriodoDetalle);
        $form->handleRequest($request);
        $this->listarTrasladoEmpleados($codigoPeriodoDetalle);
        if($form->isValid()) {
            if($form->get('BtnTrasladar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalleEmpleadoPk) {
                        $arPeriodoDetalleEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arPeriodoDetalleEmpleado =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($codigoPeriodoDetalleEmpleadoPk);
                        $arPeriodoDetalleEmpleado->setSsoPeriodoRel($arPeriodoDetalle->getSsoPeriodoRel());
                        $arPeriodoDetalleEmpleado->setSsoPeriodoDetalleRel($arPeriodoDetalle);
                        $arPeriodoDetalleEmpleado->setSsoSucursalRel($arPeriodoDetalle->getSsoSucursalRel());
                        $em->persist($arPeriodoDetalleEmpleado);
                    }
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarTrasladoEmpleado($form, $codigoPeriodoDetalle);
                $this->listarTrasladoEmpleados($codigoPeriodoDetalle);
            }
        }
        $arSsoTrasladoEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaEmpleados), $request->query->get('page', 1), 200);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:trasladar.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arTrasladoEmpleados' => $arSsoTrasladoEmpleados,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/seguridadsocial/periodo/detalle/empleados/copiar/{codigoPeriodoDetalle}", name="brs_rhu_ss_periodo_detalle_empleados_copiar")
     */
    public function copiarEmpleadosAction($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);
        $form = $this->formularioCopiarEmpleado($arPeriodoDetalle);
        $form->handleRequest($request);
        $this->listarCopiarEmpleados($codigoPeriodoDetalle);
        if($form->isValid()) {
            if($form->get('BtnCopiar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPeriodoDetalleEmpleadoPk) {
                        $arPeriodoDetalleEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arPeriodoDetalleEmpleado =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($codigoPeriodoDetalleEmpleadoPk);
                        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
                        $arPeriodo =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($arPeriodoDetalleEmpleado->getCodigoPeriodoFk());
                        $arSucursal = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal();
                        $arSucursal =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoSucursal')->find($arPeriodoDetalleEmpleado->getCodigoSucursalFk());
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado =  $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPeriodoDetalleEmpleado->getCodigoEmpleadoFk());
                        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                        $arContrato =  $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arPeriodoDetalleEmpleado->getCodigoContratoFk());
                        $arCopiarEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                        $arCopiarEmpleado->setSsoPeriodoRel($arPeriodo);
                        $arCopiarEmpleado->setSsoPeriodoDetalleRel($arPeriodoDetalle);
                        //$arCopiarEmpleado->setSsoSucursalRel($arSucursal);
                        $arCopiarEmpleado->setSsoSucursalRel($arPeriodoDetalle->getSsoSucursalRel());
                        $arCopiarEmpleado->setEmpleadoRel($arEmpleado);
                        $arCopiarEmpleado->setContratoRel($arContrato);
                        $arCopiarEmpleado->setDias($arPeriodoDetalleEmpleado->getDias());
                        $arCopiarEmpleado->setVrSalario($arPeriodoDetalleEmpleado->getVrSalario());
                        $arCopiarEmpleado->setVrSuplementario($arPeriodoDetalleEmpleado->getVrSuplementario());
                        $arCopiarEmpleado->setIngreso($arPeriodoDetalleEmpleado->getIngreso());
                        $arCopiarEmpleado->setRetiro($arPeriodoDetalleEmpleado->getRetiro());
                        $arCopiarEmpleado->setSalarioIntegral($arPeriodoDetalleEmpleado->getSalarioIntegral());
                        $arCopiarEmpleado->setDiasLicencia($arPeriodoDetalleEmpleado->getDiasLicencia());
                        $arCopiarEmpleado->setDiasIncapacidadGeneral($arPeriodoDetalleEmpleado->getDiasIncapacidadGeneral());
                        $arCopiarEmpleado->setDiasLicenciaMaternidad($arPeriodoDetalleEmpleado->getDiasLicenciaMaternidad());
                        $arCopiarEmpleado->setDiasIncapacidadLaboral($arPeriodoDetalleEmpleado->getDiasIncapacidadLaboral());
                        $arCopiarEmpleado->setDiasVacaciones($arPeriodoDetalleEmpleado->getDiasVacaciones());
                        $arCopiarEmpleado->setTarifaPension($arPeriodoDetalleEmpleado->getTarifaPension());
                        $arCopiarEmpleado->setTarifaRiesgos($arPeriodoDetalleEmpleado->getTarifaRiesgos());
                        $arCopiarEmpleado->setCodigoEntidadPensionPertenece($arPeriodoDetalleEmpleado->getCodigoEntidadPensionPertenece());
                        $arCopiarEmpleado->setCodigoEntidadSaludPertenece($arPeriodoDetalleEmpleado->getCodigoEntidadSaludPertenece());
                        $arCopiarEmpleado->setCodigoEntidadCajaPertenece($arPeriodoDetalleEmpleado->getCodigoEntidadCajaPertenece());
                        
                        $em->persist($arCopiarEmpleado);
                    }
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarCopiarEmpleado($form, $codigoPeriodoDetalle);
                $this->listarCopiarEmpleados($codigoPeriodoDetalle);
            }
        }
        $arSsoCopiarEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaEmpleados), $request->query->get('page', 1), 200);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:copiar.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arCopiarEmpleados' => $arSsoCopiarEmpleados,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/seguridadsocial/resumen/pago/{codigoPeriodoDetalle}/{codigoEmpleado}", name="brs_rhu_ss_resumen_pago")
     */
    public function resumenPagosAction($codigoPeriodoDetalle, $codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado =  $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);        
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);

        $arPagos = $paginator->paginate($em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaConsultaPagosDQL("", $arEmpleado->getNumeroIdentificacion(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y/m/d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y/m/d'), "","")), $request->query->get('page', 1), 50);
        $arPagosDetalles = $paginator->paginate($em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaConsultaPagosDetallesDQL($arEmpleado->getNumeroIdentificacion(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta())), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial/Periodos:resumenPagos.html.twig', array(
            'arPeriodoDetalle' => $arPeriodoDetalle,
            'arPagos' => $arPagos,
            'arPagosDetalles' => $arPagosDetalles,
            ));
    }    
    
    private function formularioDetalleEmpleado($ar) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrBotonEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonActualizarEmpleados = array('label' => 'Actualizar empleados', 'disabled' => false);        
        $arrBotonActualizarDatos = array('label' => 'Actualizar datos', 'disabled' => false);        
        $arrBotonActualizarSalarioMinimo = array('label' => 'Actualizar salario minimo', 'disabled' => false);

        $arrBotonExcel = array('label' => 'Excel', 'disabled' => false);
        if($ar->getEstadoGenerado() == 1) {            
            $arrBotonEliminar['disabled'] = true;
            $arrBotonActualizarDatos['disabled'] = true;            
            $arrBotonActualizarSalarioMinimo['disabled'] = true;
            $arrBotonActualizarEmpleados['disabled'] = true;
        } 
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnActualizarEmpleadoAporte', 'submit', $arrBotonActualizarDatos)    
            ->add('BtnActualizarEmpleados', 'submit', $arrBotonActualizarEmpleados)                                
            ->add('BtnActualizarSalarioMinimo', 'submit', $arrBotonActualizarSalarioMinimo)  
            ->add('BtnExcel', 'submit', $arrBotonExcel)    
            ->add('BtnEliminar', 'submit', $arrBotonEliminar)
            ->getForm();
        return $form;
    }
    
    private function formularioTrasladarEmpleado($ar) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $codigoPeriodo = $ar->getCodigoPeriodoFk();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }        
        
       $arrayPropiedadesSucursal = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle',
                'query_builder' => function (EntityRepository $er) use($codigoPeriodo) {
                    return $er->createQueryBuilder('ssopd')
                    ->where('ssopd.codigoPeriodoFk = :codigoPeriodo')
                    ->setParameter('codigoPeriodo', $codigoPeriodo)
                    ->orderBy('ssopd.codigoPeriodoDetallePk', 'ASC');},
                'property' => 'detalle',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                );
        $form = $this->createFormBuilder()
            ->add('numeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroNumeroIdentificacion')))                                            
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('sucursalRel', 'entity', $arrayPropiedadesSucursal)
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnTrasladar', 'submit', array('label'  => 'Trasladar',))    
            ->getForm();
        return $form;
    }
    
    private function formularioCopiarEmpleado($ar) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $codigoPeriodo = $ar->getCodigoPeriodoFk();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $arrayPropiedadesSucursal = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle',
                'query_builder' => function (EntityRepository $er) use($codigoPeriodo) {
                    return $er->createQueryBuilder('ssopd')
                    ->where('ssopd.codigoPeriodoFk = :codigoPeriodo')
                    ->setParameter('codigoPeriodo', $codigoPeriodo)
                    ->orderBy('ssopd.codigoPeriodoDetallePk', 'ASC');},
                'property' => 'detalle',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                );
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('numeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroNumeroIdentificacion')))                                            
            ->add('sucursalRel', 'entity', $arrayPropiedadesSucursal)    
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnCopiar', 'submit', array('label'  => 'Copiar',))    
            ->getForm();
        return $form;
    }
    
    private function formularioDetalleAporte() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function filtrarDetalleEmpleado($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
    }
    
    private function filtrarTrasladoEmpleado($form, $codigoPeriodoDetalle) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $numeroIdentificacion = $form->get('numeroIdentificacion')->getData();
        $this->strCodigoPeriodoDetalleTraslados = $codigoPeriodoDetalle;
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroNumeroIdentificacion', $numeroIdentificacion);
        
    }
    
    private function filtrarCopiarEmpleado($form, $codigoPeriodoDetalle) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $numeroIdentificacion = $form->get('numeroIdentificacion')->getData();
        $this->strCodigoPeriodoDetalleCopias = $codigoPeriodoDetalle;
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroNumeroIdentificacion', $numeroIdentificacion);
    }
    
    private function filtrarDetalleAporte($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->listaDQL();
    }

    private function listarDetalle($codigoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlListaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->listaDQL($codigoPeriodo);
    }

    private function listarEmpleados($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strDqlListaEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->listaDql($codigoPeriodoDetalle, $session->get('filtroCodigoCentroCosto'));
    }
    
    private function listarTrasladoEmpleados($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);        
        $this->strDqlListaEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->listaTrasladoDql($codigoPeriodoDetalle, $session->get('filtroCodigoCentroCosto'),$this->strCodigoPeriodoDetalleCopias, $session->get('filtroNumeroIdentificacion'), $arPeriodoDetalle->getCodigoPeriodoFk());        
    }
    
    private function listarCopiarEmpleados($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);        
        $this->strDqlListaEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->listaCopiarDql($codigoPeriodoDetalle, $session->get('filtroCodigoCentroCosto'),$this->strCodigoPeriodoDetalleCopias, $session->get('filtroNumeroIdentificacion'), $arPeriodoDetalle->getCodigoPeriodoFk());
    }

    private function listarDetalleAportes($codigoPeriodoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strDqlListaDetalleAportes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->listaDQL($codigoPeriodoDetalle, $session->get('filtroCodigoCentroCosto'));
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
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
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
                    ->setCellValue('N' . $i, 0)
                    ->setCellValue('O' . $i, 0)
                    ->setCellValue('P' . $i, $arPago->getVrDeducciones())
                    ->setCellValue('Q' . $i, $arPago->getVrDevengado())
                    ->setCellValue('R' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('S' . $i, $arPago->getVrIngresoBasePrestacion())
                    ->setCellValue('T' . $i, $arPago->getVrNeto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pagos '.$dateFechaPeriodo);
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objPHPExcel->createSheet(2)->setTitle('detalles'.$dateFechaPeriodo)
                ->setCellValue('A1', 'CODIGO')
                ->setCellValue('B1', 'PAGO')                
                ->setCellValue('C1', 'CONCEPTO PAGO')
                ->setCellValue('D1', 'IDENTIFICACIÓN')
                ->setCellValue('E1', 'EMPLEADO')
                ->setCellValue('F1', 'CENTRO COSTO')
                ->setCellValue('G1', 'DESDE')
                ->setCellValue('H1', 'HASTA')
                ->setCellValue('I1', 'VR.PAGO')
                ->setCellValue('J1', 'VR.HORA')
                ->setCellValue('K1', 'VR.DÍA')
                ->setCellValue('L1', 'HORAS')
                ->setCellValue('M1', 'DIAS')
                ->setCellValue('N1', 'POR')
                ->setCellValue('O1', 'IBC')    
                ->setCellValue('P1', 'IBP')
                ->setCellValue('Q1', 'ADI')
                ->setCellValue('R1', 'PRE')
                ->setCellValue('S1', 'COT');

        $i = 2;
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
        $dateFechaPeriodo = $arPeriodo->getFechaDesde()->format('Y-m-d'). ' - ' . $arPeriodo->getFechaHasta()->format('Y-m-d');
        $arPagosDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagosDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDqlPagosDetallePeriodoAportes($arPeriodo->getFechaDesde(),$arPeriodo->getFechaHasta());
        
        foreach ($arPagosDetalle as $arPagoDetalle) {
        $objPHPExcel->setActiveSheetIndex(1)
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
                ->setCellValue('P' . $i, $arPagoDetalle->getVrIngresoBasePrestacion())
                ->setCellValue('Q' . $i, $objFunciones->devuelveBoolean($arPagoDetalle->getAdicional()))
                ->setCellValue('R' . $i, $objFunciones->devuelveBoolean($arPagoDetalle->getPrestacional()))
                ->setCellValue('S' . $i, $objFunciones->devuelveBoolean($arPagoDetalle->getCotizacion()));
        $i++;
        }
        
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'T'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'I'; $col !== 'P'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }                 
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
    /*    ob_clean();
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
        exit;*/
    }
    
    private function generarAportesPeriodoExcel($codigoPeriodo) {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AW')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AX')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AY')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AZ')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BA')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BB')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BC')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BD')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BE')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('BF')->setAutoSize(true);
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

    private function generarExcelEmpleados($codigoPeriodoDetalle) {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        set_time_limit(0);
        ini_set("memory_limit", -1);        
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
        for($col = 'A'; $col !== 'G'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'F'; $col !== 'G'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'CONTRATO')
                    ->setCellValue('F1', 'DIAS');

        $i = 2;
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->listaDql($codigoPeriodoDetalle);        
        $arPeriodoEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();                       
        $query = $em->createQuery($dql);        
        $arPeriodoEmpleados = $query->getResult();
        
        foreach ($arPeriodoEmpleados as $arPeriodoEmpleado) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPeriodoEmpleado->getCodigoPeriodoEmpleadoPk())
                    ->setCellValue('B' . $i, $arPeriodoEmpleado->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arPeriodoEmpleado->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arPeriodoEmpleado->getCodigoContratoFk())                    
                    ->setCellValue('F' . $i, $arPeriodoEmpleado->getDias());                    
            if($arPeriodoEmpleado->getContratoRel()->getCodigoCentroCostoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arPeriodoEmpleado->getContratoRel()->getCentroCostoRel()->getNombre());
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PeriodoEmpleado');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PedidosDetalles.xlsx"');
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
