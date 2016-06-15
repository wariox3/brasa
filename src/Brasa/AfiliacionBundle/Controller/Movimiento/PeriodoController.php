<?php
namespace Brasa\AfiliacionBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\AfiliacionBundle\Form\Type\AfiPeriodoType;
class PeriodoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/movimiento/periodo", name="brs_afi_movimiento_periodo")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            
            if($request->request->get('OpGenerar')) {            
                $codigoPeriodo = $request->request->get('OpGenerar');
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generar($codigoPeriodo);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }
            if($request->request->get('OpDeshacer')) {            
                $codigoPeriodo = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM afi_periodo_detalle WHERE codigo_periodo_fk = " . $codigoPeriodo;           
                $em->getConnection()->executeQuery($strSql);                 
                $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);                
                $arPeriodo->setEstadoGenerado(0);
                $arPeriodo->setSubtotal(0);
                $arPeriodo->setTotal(0);
                $arPeriodo->setIva(0);
                $arPeriodo->setAdministracion(0);
                $arPeriodo->setSalud(0);
                $arPeriodo->setPension(0);
                $arPeriodo->setRiesgos(0);
                $arPeriodo->setCaja(0);
                $arPeriodo->setIcbf(0);
                $arPeriodo->setSena(0);
                $em->persist($arPeriodo);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }
            
            if($request->request->get('OpGenerarPago')) {            
                $codigoPeriodo = $request->request->get('OpGenerarPago');
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generarPago($codigoPeriodo);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }            
                        
            if($request->request->get('OpDeshacerPago')) {            
                $codigoPeriodo = $request->request->get('OpDeshacerPago');
                $strSql = "DELETE FROM afi_periodo_detalle_pago WHERE codigo_periodo_fk = " . $codigoPeriodo;           
                $em->getConnection()->executeQuery($strSql);                 
                $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);                
                $arPeriodo->setEstadoPagoGenerado(0);
                $em->persist($arPeriodo);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }            

            if($request->request->get('OpCerrar')) {            
                $codigoPeriodo = $request->request->get('OpCerrar');
                $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
                if($arPeriodo->getEstadoCerrado() == 0) {
                    $arPeriodo->setEstadoCerrado(1);
                    $em->persist($arPeriodo);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }
            
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arPeriodos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:lista.html.twig', array(
            'arPeriodos' => $arPeriodos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/periodo/nuevo/{codigoPeriodo}", name="brs_afi_movimiento_periodo_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoPeriodo = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        if($codigoPeriodo != '' && $codigoPeriodo != '0') {
            $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        } else {
            $fecha = new \DateTime('now');
            $arPeriodo->setFechaDesde($fecha);
            $arPeriodo->setFechaHasta($fecha);
            $arPeriodo->setFechaPago($fecha);
            $arPeriodo->setAnio($fecha->format('Y'));
            $arPeriodo->setMes($fecha->format('m'));
            $arPeriodo->setAnioPago($fecha->format('Y'));
            $arPeriodo->setMesPago($fecha->format('m'));
        }        
        $form = $this->createForm(new AfiPeriodoType, $arPeriodo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPeriodo = $form->getData();                        
            $em->persist($arPeriodo);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo_nuevo', array('codigoPeriodo' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:nuevo.html.twig', array(
            'arPeriodo' => $arPeriodo,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/movimiento/periodo/detalle/{codigoPeriodo}", name="brs_afi_movimiento_periodo_detalle")
     */    
    public function detalleAction(Request $request, $codigoPeriodo = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();                
        $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);            
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        $this->listaDetalle($codigoPeriodo);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');            
            if ($form->get('BtnDetalleCobroExcel')->isClicked()) {
                $this->listaDetalle($codigoPeriodo);
                $this->generarDetalleExcel();
            }
            if ($form->get('BtnDetalleCobroImprimir')->isClicked()) {
                $objPeriodoCobro = new \Brasa\AfiliacionBundle\Formatos\PeriodoCobro();
                $objPeriodoCobro->Generar($this, $codigoPeriodo);
                //$this->listaDetalle($codigoPeriodo);
                //$this->generarDetalleExcel();
            }            
            if ($form->get('BtnDetallePagoExcel')->isClicked()) {
                $this->listaDetallePago($codigoPeriodo);
                $this->generarDetallePagoExcel();
            }            
            if ($form->get('BtnDetallePagoArchivo')->isClicked()) {                
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $strRutaArchivo = $arConfiguracion->getRutaTemporal();
                $strNombreArchivo = "pila" . date('YmdHis') . ".txt";
                $ar = fopen($strRutaArchivo . $strNombreArchivo, "a") or
                    die("Problemas en la creacion del archivo plano");
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
                fputs($ar, $this->RellenarNr($arPeriodo->getClienteRel()->getCodigoSucursal(), " ", 10, "D"));
                fputs($ar, $this->RellenarNr($arPeriodo->getClienteRel()->getCodigoSucursal(), " ", 40, "D"));
                //Arp del aportante
                fputs($ar, '14-18 ');
                //Periodo pago para los diferentes sistemas
                fputs($ar, $arPeriodo->getAnioPago().'-'. $this->RellenarNr($arPeriodo->getMesPago(), "0", 2, "I"));
                fputs($ar, $arPeriodo->getAnio().'-'. $this->RellenarNr($arPeriodo->getMes(), "0", 2, "I"));
                //Numero radicacion de la planilla
                fputs($ar, '0000000000');
                //Fecha de pago
                fputs($ar, $arPeriodo->getFechaPago()->format('Y-m-d'));
                //Numero total de empleados
                fputs($ar, $this->RellenarNr(0, "0", 5, "I"));
                //Valor total de la nomina
                fputs($ar, $this->RellenarNr(0, "0", 12, "I"));
                fputs($ar, '1');
                fputs($ar, '89');
                fputs($ar, "\n");

                $arPeriodoDetallePagos = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago();
                $arPeriodoDetallePagos = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetallePago')->findBy(array('codigoPeriodoFk' => $codigoPeriodo));
                foreach($arPeriodoDetallePagos as $arPeriodoDetallePago) {
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTipoRegistro(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSecuencia(), "0", 5, "I"));
                    fputs($ar, $arPeriodoDetallePago->getTipoDocumento());
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getEmpleadoRel()->getNumeroIdentificacion(), " ", 16, "D"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTipoCotizante(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSubtipoCotizante(), "0", 2, "I"));
                    fputs($ar, $arPeriodoDetallePago->getExtranjeroNoObligadoCotizarPension());
                    fputs($ar, $arPeriodoDetallePago->getColombianoResidenteExterior());
                    fputs($ar, $arPeriodoDetallePago->getCodigoDepartamentoUbicacionlaboral());
                    fputs($ar, $arPeriodoDetallePago->getCodigoMunicipioUbicacionlaboral());
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getPrimerApellido(), " ", 20, "D"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSegundoApellido(), " ", 30, "D"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getPrimerNombre(), " ", 20, "D"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSegundoNombre(), " ", 30, "D"));
                    fputs($ar, $arPeriodoDetallePago->getIngreso());
                    fputs($ar, $arPeriodoDetallePago->getRetiro());
                    fputs($ar, $arPeriodoDetallePago->getTrasladoDesdeOtraEps());
                    fputs($ar, $arPeriodoDetallePago->getTrasladoAOtraEps());
                    fputs($ar, $arPeriodoDetallePago->getTrasladoDesdeOtraPension());
                    fputs($ar, $arPeriodoDetallePago->getTrasladoAOtraPension());
                    fputs($ar, $arPeriodoDetallePago->getVariacionPermanenteSalario());
                    fputs($ar, $arPeriodoDetallePago->getCorrecciones());
                    fputs($ar, $arPeriodoDetallePago->getVariacionTransitoriaSalario());
                    fputs($ar, $arPeriodoDetallePago->getSuspensionTemporalContratoLicenciaServicios());
                    fputs($ar, $arPeriodoDetallePago->getIncapacidadGeneral());
                    fputs($ar, $arPeriodoDetallePago->getLicenciaMaternidad());
                    fputs($ar, $arPeriodoDetallePago->getVacaciones());
                    fputs($ar, $arPeriodoDetallePago->getAporteVoluntario());
                    fputs($ar, $arPeriodoDetallePago->getVariacionCentrosTrabajo());
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIncapacidadAccidenteTrabajoEnfermedadProfesional(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadPensionPertenece(), " ", 6, "D"));
                    fputs($ar, $arPeriodoDetallePago->getCodigoEntidadPensionTraslada());
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadSaludPertenece(), " ", 6, "D"));
                    fputs($ar, $arPeriodoDetallePago->getCodigoEntidadSaludTraslada());
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCodigoEntidadCajaPertenece(), " ", 6, "D"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosPension(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosSalud(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosRiesgosProfesionales(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getDiasCotizadosCajaCompensacion(), "0", 2, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getSalarioBasico(), "0", 9, "I"));
                    fputs($ar, $arPeriodoDetallePago->getSalarioIntegral());
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcPension(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcSalud(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcRiesgosProfesionales(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getIbcCaja(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr(($arPeriodoDetallePago->getTarifaPension()/100), "0", 7, "D"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionPension(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getAporteVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionVoluntarioFondoPensionesObligatorias(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getTotalCotizacion(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getAportesFondoSolidaridadPensionalSolidaridad(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getAportesFondoSolidaridadPensionalSubsistencia(), "0", 9, "I"));
                    fputs($ar, '000000000');
                    fputs($ar, $this->RellenarNr(($arPeriodoDetallePago->getTarifaSalud()/100), "0", 7, "D"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionSalud(), "0", 9, "I"));
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
                    fputs($ar, $this->RellenarNr(($arPeriodoDetallePago->getTarifaRiesgos()/100), "0", 9, "D"));
                    //fputs($ar, $arSsoAporte->getCentroTrabajoCodigoCt());
                    fputs($ar, "000000000");
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionRiesgos(), "0", 9, "I"));
                    fputs($ar, $this->RellenarNr(($arPeriodoDetallePago->getTarifaCaja()/100), "0", 7, "D"));
                    fputs($ar, $this->RellenarNr($arPeriodoDetallePago->getCotizacionCaja(), "0", 9, "I"));
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
                //return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo_detalle', array('codigoPeriodo' => $codigoPeriodo)));
            }            
            
        }
        
        $arPeriodoDetalles = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetallePago')->listaDQL($codigoPeriodo);         
        $arPeriodoDetallesPagos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:detalle.html.twig', array(
            'arPeriodo' => $arPeriodo, 
            'arPeriodoDetalles' => $arPeriodoDetalles, 
            'arPeriodoDetallesPagos' => $arPeriodoDetallesPagos, 
            'form' => $form->createView()));
    }    
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->listaDQL(
                $session->get('filtroCodigoCliente'),
                $session->get('filtroPeriodoEstadoCerrado')
                ); 
    }
    
    private function listaDetalle($codigoPeriodo) {            
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->listaDQL(
                $codigoPeriodo   
                ); 
    }    
    
    private function listaDetallePago($codigoPeriodo) {            
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetallePago')->listaDQL(
                $codigoPeriodo   
                ); 
    }    
    
    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();        
        $session->set('filtroNit', $form->get('TxtNit')->getData()); 
        $session->set('filtroPeriodoEstadoCerrado', $form->get('estadoCerrado')->getData());                  
        $this->lista();
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        } 
        $form = $this->createFormBuilder()            
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                                                            
            ->add('estadoCerrado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'CERRADO', '0' => 'SIN CERRAR'), 'data' => $session->get('filtroPeriodoEstadoCerrado')))                                                
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    
    
    private function formularioDetalle() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()                        
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnDetalleCobroExcel', 'submit', array('label'  => 'Excel',))            
            ->add('BtnDetalleCobroImprimir', 'submit', array('label'  => 'Imprimir',))            
            ->add('BtnDetallePagoEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnDetallePagoExcel', 'submit', array('label'  => 'Excel',))                            
            ->add('BtnDetallePagoArchivo', 'submit', array('label'  => 'Archivo plano',))                                            
            ->getForm();
        return $form;
    }     

    private function generarExcel() {
        ob_clean();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'C'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        /*for($col = 'AI'; $col !== 'AK'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }*/         
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'CLIENTE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arPeriodos = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodos = $query->getResult();
                
        foreach ($arPeriodos as $arPeriodo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPeriodo->getCodigoPeriodoPk())
                    ->setCellValue('B' . $i, $arPeriodo->getClienteRel()->getNombreCorto());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Periodo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Periodos.xlsx"');
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

    private function generarDetalleExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'R'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'I'; $col !== 'R'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'COD')
                    ->setCellValue('B1', 'CLIENTE')
                    ->setCellValue('C1', 'DESDE')
                    ->setCellValue('D1', 'HASTA')
                    ->setCellValue('E1', 'IDENTIFICACION')
                    ->setCellValue('F1', 'NOMBRE')
                    ->setCellValue('G1', 'ING')
                    ->setCellValue('H1', 'DIAS')
                    ->setCellValue('I1', 'SALARIO')
                    ->setCellValue('J1', 'PENSION')
                    ->setCellValue('K1', 'SALUD')
                    ->setCellValue('L1', 'CAJA')
                    ->setCellValue('M1', 'RIESGO')
                    ->setCellValue('N1', 'SENA')
                    ->setCellValue('O1', 'ICBF')
                    ->setCellValue('P1', 'ADMIN')
                    ->setCellValue('Q1', 'TOTAL');
        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arPeriodoDetalles = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle();
        $arPeriodoDetalles = $query->getResult();
                
        foreach ($arPeriodoDetalles as $arPeriodoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPeriodoDetalle->getCodigoPeriodoDetallePk())
                    ->setCellValue('B' . $i, $arPeriodoDetalle->getPeriodoRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arPeriodoDetalle->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arPeriodoDetalle->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arPeriodoDetalle->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arPeriodoDetalle->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $objFunciones->devuelveBoolean($arPeriodoDetalle->getIngreso()))
                    ->setCellValue('H' . $i, $arPeriodoDetalle->getDias())
                    ->setCellValue('I' . $i, $arPeriodoDetalle->getSalario())
                    ->setCellValue('J' . $i, $arPeriodoDetalle->getPension())
                    ->setCellValue('K' . $i, $arPeriodoDetalle->getSalud())
                    ->setCellValue('L' . $i, $arPeriodoDetalle->getCaja())
                    ->setCellValue('M' . $i, $arPeriodoDetalle->getRiesgos())
                    ->setCellValue('N' . $i, $arPeriodoDetalle->getSena())
                    ->setCellValue('O' . $i, $arPeriodoDetalle->getIcbf())
                    ->setCellValue('P' . $i, $arPeriodoDetalle->getAdministracion())
                    ->setCellValue('Q' . $i, $arPeriodoDetalle->getTotal());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('PeriodoDetalle');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PeriodoDetalles.xlsx"');
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
    
    private function generarDetallePagoExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'R'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'D'; $col !== 'J'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'COD')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'PENSION')
                    ->setCellValue('E1', 'SALUD')
                    ->setCellValue('F1', 'CAJA')
                    ->setCellValue('G1', 'RIESGO')
                    ->setCellValue('H1', 'SENA')
                    ->setCellValue('I1', 'ICBF');
        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arPeriodoDetallesPagos = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetallePago();
        $arPeriodoDetallesPagos = $query->getResult();
                
        foreach ($arPeriodoDetallesPagos as $arPeriodoDetallePago) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPeriodoDetallePago->getCodigoPeriodoDetallePagoPk())
                    ->setCellValue('B' . $i, $arPeriodoDetallePago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arPeriodoDetallePago->getEmpleadoRel()->getNombreCorto())                    
                    ->setCellValue('D' . $i, $arPeriodoDetallePago->getCotizacionPension())
                    ->setCellValue('E' . $i, $arPeriodoDetallePago->getCotizacionSalud())
                    ->setCellValue('F' . $i, $arPeriodoDetallePago->getCotizacionCaja())
                    ->setCellValue('G' . $i, $arPeriodoDetallePago->getCotizacionRiesgos())
                    ->setCellValue('H' . $i, $arPeriodoDetallePago->getCotizacionSena())
                    ->setCellValue('I' . $i, $arPeriodoDetallePago->getCotizacionIcbf());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('PeriodoDetallePago');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PeriodoDetallePago.xlsx"');
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
    
}