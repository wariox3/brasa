<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuContratoType;
use Doctrine\ORM\EntityRepository;
class ContratosController extends Controller
{
    var $fechaDesdeInicia;
    var $fechaHastaInicia;

    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
        }

        $arContratos = $paginator->paginate($em->createQuery($session->get('dqlContratoLista')), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:lista.html.twig', array(
            'arContratos' => $arContratos,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoContrato) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();
        $mensaje = 0;
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if ($arContrato->getEstadoActivo() == 1 || $arContrato->getIndefinido() == 1){
            $disabled = FALSE;
        } else {
            $disabled = TRUE;
        }
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir Contrato'))
            ->add('BtnImprimirCartaPresentacion', 'submit', array('label'  => 'Carta presentación'))
            ->add('BtnInactivarContrato', 'submit', array('label'  => 'Inactivar', 'disabled' => $disabled))    
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoContrato();
                $objFormatoContrato->Generar($this, $codigoContrato);
            }
            if($form->get('BtnImprimirCartaPresentacion')->isClicked()) {
                $arUsuario = $this->get('security.context')->getToken()->getUser();
                $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCartaPresentacion();
                $objFormatoContrato->Generar($this, $codigoContrato,$arUsuario);
            }
            if($form->get('BtnInactivarContrato')->isClicked()) {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getCodigoEmpleadoFk());
                $arContrato->setEstadoActivo(0);
                $arContrato->setIndefinido(0);
                $arContrato->setEstadoLiquidado(0);
                $arContrato->setCodigoMotivoTerminacionContratoFk(8);
                $arEmpleado->setCodigoCentroCostoFk(NULL);
                $arEmpleado->setCodigoTipoTiempoFk(NULL);
                $arEmpleado->setVrSalario(0);
                $arEmpleado->setCodigoClasificacionRiesgoFk(NULL);
                $arEmpleado->setCodigoCargoFk(NULL);
                $arEmpleado->setCargoDescripcion(NULL);
                $arEmpleado->setCodigoTipoPensionFk(NULL);
                $arEmpleado->setCodigoTipoCotizanteFk(NULL);
                $arEmpleado->setCodigoSubtipoCotizanteFk(NULL);
                $arEmpleado->setCodigoEntidadSaludFk(NULL);
                $arEmpleado->setCodigoEntidadPensionFk(NULL);
                $arEmpleado->setCodigoEntidadCajaFk(NULL);
                $arEmpleado->setEstadoContratoActivo(0);
                $arEmpleado->setCodigoContratoActivoFk(NULL);
                $arEmpleado->setCodigoContratoUltimoFk($codigoContrato);
                $em->persist($arContrato);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_base_contratos_detalles', array('codigoContrato' => $codigoContrato)));
            }
        }
        $arCambiosSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();
        $arCambiosSalario = $em->getRepository('BrasaRecursoHumanoBundle:RhuCambioSalario')->findBy(array('codigoContratoFk' => $codigoContrato));
        $arCambiosSalario = $paginator->paginate($arCambiosSalario, $this->get('request')->query->get('page', 1),5);
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->findBy(array('codigoContratoFk' => $codigoContrato));
        $arVacaciones = $paginator->paginate($arVacaciones, $this->get('request')->query->get('page', 1),5);
        $arContratoSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoSede();
        $arContratoSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoSede')->findBy(array('codigoContratoFk' => $codigoContrato));
        $arContratoSedes = $paginator->paginate($arContratoSedes, $this->get('request')->query->get('page', 1),5);
        $arTrasladoPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuTrasladoPension')->findBy(array('codigoContratoFk' => $codigoContrato));
        $arTrasladoPension = $paginator->paginate($arTrasladoPension, $this->get('request')->query->get('page', 1),10);
        $arTrasladoSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuTrasladoSalud')->findBy(array('codigoContratoFk' => $codigoContrato));
        $arTrasladoSalud = $paginator->paginate($arTrasladoSalud, $this->get('request')->query->get('page', 1),10);
        $arContratoProrrogas = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoProrroga')->findBy(array('codigoContratoFk' => $codigoContrato), array('codigoContratoProrrogaPk' => 'DESC'));
        $arContratoProrrogas = $paginator->paginate($arContratoProrrogas, $this->get('request')->query->get('page', 1),10);
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:detalle.html.twig', array(
                    'arContrato' => $arContrato,
                    'arCambiosSalario' => $arCambiosSalario,
                    'arVacaciones' => $arVacaciones,
                    'arContratoSedes' => $arContratoSedes,
                    'arTrasladoPension' => $arTrasladoPension,
                    'arTrasladoSalud' => $arTrasladoSalud,
                    'arContratoProrrogas' => $arContratoProrrogas,
                    'form' => $form->createView()
                    ));
    }

    public function nuevoAction($codigoContrato, $codigoEmpleado) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $intEstado = 0;
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
        $douSalarioMinimo = $arConfiguracion->getVrSalario();        
        if($codigoContrato != 0) {
            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        } else {
            $arContrato->setFechaDesde(new \DateTime('now'));
            $arContrato->setFechaHasta(new \DateTime('now'));
            $arContrato->setIndefinido(1);
            $arContrato->setEstadoActivo(1);
            $arContrato->setVrSalario($douSalarioMinimo); //se Parametrizó con configuracion salario minimo
            $douValidarEmpleadoContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->validarEmpleadoContrato($codigoEmpleado);
            if ($douValidarEmpleadoContrato >= 1){
                $objMensaje->Mensaje("error", "El empleado tiene contrato abierto, no se puede generar otro contrato", $this);
                $intEstado = 1;
            }
            if ($arEmpleado->getEmpleadoInformacionInterna() == 1){
               $objMensaje->Mensaje("error", "El empleado esta bloqueado por información interna", $this); 
               $intEstado = 2;
            }

        }
        $form = $this->createForm(new RhuContratoType(), $arContrato);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arContrato = $form->getData();
            $boolValidarTipoContrato = TRUE;
            $boolValidarTipoContratoSalud = TRUE;
            $boolValidarContratoFijo = TRUE;
            $boolValidarSalarioIntegral = TRUE;
            if($arContrato->getContratoTipoRel()->getCodigoContratoTipoPk() == 4 && ($arContrato->getSsoTipoCotizanteRel()->getCodigoTipoCotizantePk() != 12 && $arContrato->getSsoTipoCotizanteRel()->getCodigoTipoCotizantePk() != 19 || $arContrato->getSsoSubtipoCotizanteRel()->getCodigoSubtipoCotizantePk() != 0)) {
                $boolValidarTipoContrato = FALSE;
            } 
                
            if($arContrato->getContratoTipoRel()->getCodigoContratoTipoPk() == 5 && ($arContrato->getSsoTipoCotizanteRel()->getCodigoTipoCotizantePk() != 23 || $arContrato->getSsoSubtipoCotizanteRel()->getCodigoSubtipoCotizantePk() != 0)) {
                $boolValidarTipoContrato = FALSE;
            }
            if($arContrato->getSalarioIntegral() == true) {
                if($arContrato->getVrSalario() < ($douSalarioMinimo * 13)) {
                    $boolValidarSalarioIntegral = FALSE;
                }
            }
            if($arContrato->getContratoTipoRel()->getCodigoContratoTipoPk() == 4 || $arContrato->getContratoTipoRel()->getCodigoContratoTipoPk() == 5) {
                if($arContrato->getTipoSaludRel()->getCodigoTipoSaludPk() != 2) {
                    $boolValidarTipoContratoSalud = FALSE;
                }
            } 
            //fin validación
            if ($codigoContrato == 0){
                $douValidarEmpleadoContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->validarEmpleadoContrato($codigoEmpleado);
                if ($douValidarEmpleadoContrato >= 1){
                    $objMensaje->Mensaje("error", "El empleado tiene contrato abierto, no se puede generar otro contrato", $this);
                    $intEstado = 1;
                } else{
                    /*if ($boolValidarContratoFijo == FALSE){
                        $objMensaje->Mensaje("error", "La duración del contrato no puede ser mayor o igual a un año", $this);
                    } else {*/    
                        if($boolValidarTipoContrato == TRUE) {
                            if($boolValidarTipoContratoSalud == TRUE) {
                                if($boolValidarSalarioIntegral == TRUE) {
                                    if($arContrato->getCentroCostoRel()->getFechaUltimoPago() < $arContrato->getFechaDesde() || $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),1)) {
                                        $arContrato->setFecha(date_create(date('Y-m-d H:i:s')));
                                        $arContrato->setEmpleadoRel($arEmpleado);
                                        $arContrato->setFechaHasta($form->get('fechaHasta')->getData());
                                        $dateFechaUltimoPago = $arContrato->getFechaDesde()->format('Y-m-d');
                                        $dateFechaUltimoPago = date("Y-m-d", strtotime("$dateFechaUltimoPago -1 day"));
                                        $dateFechaUltimoPago = date_create_from_format('Y-m-d H:i', $dateFechaUltimoPago . "00:00");
                                        $arContrato->setFechaUltimoPago($dateFechaUltimoPago);
                                        $arContrato->setFechaUltimoPagoCesantias($arContrato->getFechaDesde());
                                        $arContrato->setFechaUltimoPagoPrimas($arContrato->getFechaDesde());
                                        $arContrato->setFechaUltimoPagoVacaciones($arContrato->getFechaDesde());
                                        $arContrato->setFactor($arContrato->getTipoTiempoRel()->getFactor());
                                        $arContrato->setFactorHorasDia($arContrato->getTipoTiempoRel()->getFactorHorasDia());
                                        if($arContrato->getTipoTiempoRel()->getFactor() > 0) {
                                            $arContrato->setVrSalarioPago($arContrato->getVrSalario() / $arContrato->getTipoTiempoRel()->getFactor());
                                        } else {
                                            $arContrato->setVrSalarioPago($arContrato->getVrSalario());
                                        }                                    
                                        $arContrato->setCodigoUsuario($arUsuario->getUserName());
                                        $em->persist($arContrato);
                                        $em->flush();

                                        //$arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
                                        //$douSalarioMinimo = $arConfiguracion->getVrSalario();
                                        //$douSalarioMinimo = 644350;
                                        if($codigoContrato == 0 && $arContrato->getVrSalario() <= $douSalarioMinimo * 2) {
                                            $arEmpleado->setAuxilioTransporte(1);
                                        } else {
                                            $arEmpleado->setAuxilioTransporte(0);
                                        }
                                        $arEmpleado->setCentroCostoRel($arContrato->getCentroCostoRel());
                                        $arEmpleado->setTipoTiempoRel($arContrato->getTipoTiempoRel());
                                        $arEmpleado->setVrSalario($arContrato->getVrSalario());
                                        $arEmpleado->setFechaContrato($arContrato->getFechaDesde());
                                        $arEmpleado->setFechaFinalizaContrato($arContrato->getFechaHasta());
                                        $arEmpleado->setClasificacionRiesgoRel($arContrato->getClasificacionRiesgoRel());
                                        $arEmpleado->setCargoRel($arContrato->getCargoRel());
                                        $arEmpleado->setCargoDescripcion($arContrato->getCargoDescripcion());
                                        $arEmpleado->setTipoPensionRel($arContrato->getTipoPensionRel());
                                        $arEmpleado->setTipoSaludRel($arContrato->getTipoSaludRel());
                                        $arEmpleado->setSsoTipoCotizanteRel($arContrato->getSsoTipoCotizanteRel());
                                        $arEmpleado->setSsoSubtipoCotizanteRel($arContrato->getSsoSubtipoCotizanteRel());
                                        $arEmpleado->setEstadoContratoActivo(1);
                                        $arEmpleado->setEstadoActivo(1);
                                        $arEmpleado->setCodigoContratoActivoFk($arContrato->getCodigoContratoPk());
                                        $arEmpleado->setEntidadPensionRel($arContrato->getEntidadPensionRel());
                                        $arEmpleado->setEntidadSaludRel($arContrato->getEntidadSaludRel());
                                        $arEmpleado->setEntidadCajaRel($arContrato->getEntidadCajaRel());
                                        $arEmpleado->setCodigoContratoUltimoFk($arContrato->getCodigoContratoPk());
                                        $em->persist($arEmpleado);
                                        $em->flush();
                                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                                    } else {
                                        echo "La fecha de inicio del contrato debe ser mayor a la ultima fecha de pago del centro de costos " . $arContrato->getCentroCostoRel()->getFechaUltimoPago()->format('Y-m-d');
                                    }                                    
                                } else {
                                    $objMensaje->Mensaje('error', "El salario integral debe ser mayor a 13 salarios minimos", $this);
                                }                                
                            } else {
                                $objMensaje->Mensaje("error", "Los contratos de practicante/aprendizaje del sena (lectiva-productiva) la salud va a cargo del empleador", $this);
                            }
                        } else {
                            echo "Verifique el tipo de contrato con el tipo y subtipo de cotizante a seguridad social";
                        }
                    //}
                }
            } else{
                if ($boolValidarContratoFijo == FALSE){
                    $objMensaje->Mensaje("error", "La duración del contrato no puede ser mayor o igual a un año", $this);
                } else {
                    if($boolValidarTipoContrato == TRUE) {
                        if($arContrato->getCentroCostoRel()->getFechaUltimoPago() < $arContrato->getFechaDesde() || $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),1)) {
                            $arContrato->setFecha(date_create(date('Y-m-d H:i:s')));
                            $arContrato->setEmpleadoRel($arEmpleado);
                            $arContrato->setFechaHasta($form->get('fechaHasta')->getData());
                            $dateFechaUltimoPago = $arContrato->getFechaDesde()->format('Y-m-d');
                            $dateFechaUltimoPago = date("Y-m-d", strtotime("$dateFechaUltimoPago -1 day"));
                            $dateFechaUltimoPago = date_create_from_format('Y-m-d H:i', $dateFechaUltimoPago . "00:00");
                            $arContrato->setFechaUltimoPago($dateFechaUltimoPago);
                            $arContrato->setFechaUltimoPagoCesantias($arContrato->getFechaDesde());
                            $arContrato->setFechaUltimoPagoPrimas($arContrato->getFechaDesde());
                            $arContrato->setFechaUltimoPagoVacaciones($arContrato->getFechaDesde());
                            $arContrato->setFactor($arContrato->getTipoTiempoRel()->getFactor());
                            $arContrato->setFactorHorasDia($arContrato->getTipoTiempoRel()->getFactorHorasDia());
                            if($arContrato->getTipoTiempoRel()->getFactor() > 0) {
                                $arContrato->setVrSalarioPago($arContrato->getVrSalario() / $arContrato->getTipoTiempoRel()->getFactor());
                            } else {
                                $arContrato->setVrSalarioPago($arContrato->getVrSalario());
                            }
                            $em->persist($arContrato);
                            $em->flush();
                            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
                            $douSalarioMinimo = $arConfiguracion->getVrSalario();
                            //$douSalarioMinimo = 644350;
                            if($arContrato->getVrSalario() <= $douSalarioMinimo * 2) {
                                $arEmpleado->setAuxilioTransporte(1);
                            } else {
                                $arEmpleado->setAuxilioTransporte(0);
                            }
                            $arEmpleado->setCentroCostoRel($arContrato->getCentroCostoRel());
                            $arEmpleado->setTipoTiempoRel($arContrato->getTipoTiempoRel());
                            $arEmpleado->setVrSalario($arContrato->getVrSalario());
                            $arEmpleado->setFechaContrato($arContrato->getFechaDesde());
                            $arEmpleado->setFechaFinalizaContrato($arContrato->getFechaHasta());
                            $arEmpleado->setClasificacionRiesgoRel($arContrato->getClasificacionRiesgoRel());
                            $arEmpleado->setCargoRel($arContrato->getCargoRel());
                            $arEmpleado->setCargoDescripcion($arContrato->getCargoDescripcion());
                            $arEmpleado->setTipoPensionRel($arContrato->getTipoPensionRel());
                            $arEmpleado->setTipoSaludRel($arContrato->getTipoSaludRel());
                            $arEmpleado->setSsoTipoCotizanteRel($arContrato->getSsoTipoCotizanteRel());
                            $arEmpleado->setSsoSubtipoCotizanteRel($arContrato->getSsoSubtipoCotizanteRel());
                            $arEmpleado->setEstadoContratoActivo(1);
                            $arEmpleado->setEstadoActivo(1);
                            $arEmpleado->setCodigoContratoActivoFk($arContrato->getCodigoContratoPk());
                            $arEmpleado->setEntidadPensionRel($arContrato->getEntidadPensionRel());
                            $arEmpleado->setEntidadSaludRel($arContrato->getEntidadSaludRel());
                            $arEmpleado->setEntidadCajaRel($arContrato->getEntidadCajaRel());
                            $em->persist($arEmpleado);
                            $em->flush();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        } else {
                            echo "La fecha de inicio del contrato debe ser mayor a la ultima fecha de pago del centro de costos " . $arContrato->getCentroCostoRel()->getFechaUltimoPago()->format('Y-m-d');
                          }   
                    } else {
                        echo "Verifique el tipo de contrato con el tipo y subtipo de cotizante a seguridad social";
                      }  
                }  
            }   
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:nuevo.html.twig', array(
            'arContrato' => $arContrato,
            'arEmpleado' => $arEmpleado,
            'intEstado' => $intEstado,
            'form' => $form->createView()));
    }

    public function terminarAction($codigoContrato) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $formContrato = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_contratos_terminar', array('codigoContrato' => $codigoContrato)))
            ->add('fechaTerminacion', 'date', array('label'  => 'Terminacion', 'data' => new \DateTime('now')))
            ->add('terminacionContratoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuMotivoTerminacionContrato',
                        'property' => 'motivo',
            ))
            ->add('ibpAdicional', 'number', array('data' =>$arContrato->getIbpAdicional() ,'required' => false))      
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $formContrato->handleRequest($request);
        
        $arDotacionPendiente = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->dotacionDevolucion($arContrato->getCodigoEmpleadoFk());
        $registrosDotacionesPendientes = count($arDotacionPendiente);
        if ($registrosDotacionesPendientes > 0){
            $mensaje = "El empleado tiene dotaciones pendientes por entregar, no se puede terminar el contrato";
        }else{
            $mensaje = "";
        }    
        if ($formContrato->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $dateFechaHasta = $formContrato->get('fechaTerminacion')->getData();
            $arMotivoTerminacion = new \Brasa\RecursoHumanoBundle\Entity\RhuMotivoTerminacionContrato();
            $codigoMotivoContrato = $formContrato->get('terminacionContratoRel')->getData();
            $arMotivoTerminacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuMotivoTerminacionContrato')->find($codigoMotivoContrato);
            $floIbpAdicional = $formContrato->get('ibpAdicional')->getData();
            if($dateFechaHasta >= $arContrato->getFechaUltimoPago()) {
                if($em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->validarCierreContrato($dateFechaHasta, $arContrato->getCodigoEmpleadoFk())) {
                    if($em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->validarCierreContrato($dateFechaHasta, $arContrato->getCodigoEmpleadoFk())) {
                        if ($registrosDotacionesPendientes <= 0){
                            $arContrato->setFechaHasta($dateFechaHasta);
                            $arContrato->setIndefinido(0);
                            $arContrato->setEstadoActivo(0);
                            $arContrato->setEstadoLiquidado(1);
                            $arContrato->setTerminacionContratoRel($codigoMotivoContrato);
                            $arContrato->setIbpAdicional($floIbpAdicional);
                            $em->persist($arContrato);
                            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getCodigoEmpleadoFk());
                            $arEmpleado->setCodigoCentroCostoFk(NULL);
                            $arEmpleado->setCodigoTipoTiempoFk(NULL);
                            $arEmpleado->setVrSalario(0);
                            $arEmpleado->setCodigoClasificacionRiesgoFk(NULL);
                            $arEmpleado->setCodigoCargoFk(NULL);
                            $arEmpleado->setCargoDescripcion(NULL);
                            $arEmpleado->setCodigoTipoPensionFk(NULL);
                            $arEmpleado->setCodigoTipoCotizanteFk(NULL);
                            $arEmpleado->setCodigoSubtipoCotizanteFk(NULL);
                            $arEmpleado->setCodigoEntidadSaludFk(NULL);
                            $arEmpleado->setCodigoEntidadPensionFk(NULL);
                            $arEmpleado->setCodigoEntidadCajaFk(NULL);
                            $arEmpleado->setEstadoContratoActivo(0);
                            $arEmpleado->setCodigoContratoActivoFk(NULL);
                            $arEmpleado->setCodigoContratoUltimoFk($codigoContrato);
                            $em->persist($arEmpleado);

                            //Generar liquidacion
                            if($arContrato->getCodigoContratoTipoFk() != 4 && $arContrato->getCodigoContratoTipoFk() != 5) {
                                $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                                $arLiquidacion->setFecha(new \DateTime('now'));
                                $arLiquidacion->setCentroCostoRel($arContrato->getCentroCostoRel());
                                $arLiquidacion->setEmpleadoRel($arContrato->getEmpleadoRel());
                                $arLiquidacion->setContratoRel($arContrato);
                                $arLiquidacion->setMotivoTerminacionRel($codigoMotivoContrato);
                                if($arContrato->getFechaUltimoPagoCesantias() > $arContrato->getFechaDesde()) {
                                    $arLiquidacion->setFechaDesde($arContrato->getFechaUltimoPagoCesantias());
                                } else {
                                    $arLiquidacion->setFechaDesde($arContrato->getFechaDesde());
                                }
                                $arLiquidacion->setFechaHasta($arContrato->getFechaHasta());
                                $arLiquidacion->setLiquidarCesantias(1);
                                $arLiquidacion->setLiquidarPrima(1);
                                $arLiquidacion->setLiquidarVacaciones(1);
                                $arLiquidacion->setCodigoUsuario($arUsuario->getUserName());
                                $em->persist($arLiquidacion);
                                //Verificar creditos
                                $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                                $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arContrato->getCodigoEmpleadoFk());
                                foreach ($arCreditos as $arCredito) {
                                    $arLiquidacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                                    $arLiquidacionAdicionalConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionalesConcepto();
                                    $arLiquidacionAdicionalConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionalesConcepto')->find(1);
                                    $arLiquidacionAdicionales->setCreditoRel($arCredito);
                                    $arLiquidacionAdicionales->setLiquidacionRel($arLiquidacion);
                                    $arLiquidacionAdicionales->setLiquidacionAdicionalConceptoRel($arLiquidacionAdicionalConcepto);
                                    $arLiquidacionAdicionales->setVrDeduccion($arCredito->getSaldoTotal());
                                    $em->persist($arLiquidacionAdicionales);
                                }
                            }
                            $em->flush();
                            //$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($arLiquidacion->getCodigoLiquidacionPk());
                        } else {
                            $objMensaje->Mensaje("error", "No puede terminar un contrato con dotaciones pendientes", $this);
                          }
                    } else {
                        $objMensaje->Mensaje("error", "No puede terminar un contrato con licencias pendientes", $this);
                    }
                } else {
                    $objMensaje->Mensaje("error", "No puede terminar un contrato con incapacidades pendientes", $this);
                }
            } else {
                $objMensaje->Mensaje("error", "No puede terminar un contrato antes del ultimo pago, excepto con un permiso especial, consulte con el administrador del sistema", $this);
            }
            return $this->redirect($this->generateUrl('brs_rhu_base_contratos_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:terminar.html.twig', array(
            'arContrato' => $arContrato,
            'formContrato' => $formContrato->createView(),
            'mensaje' => $mensaje
        ));
    }
    
    public function actualizarContratoTerminadoAction($codigoContrato) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $permiso = "";
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $permiso = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),5);
        $formActualizar = $this->createFormBuilder()
            //->setAction($this->generateUrl('brs_rhu_contratos_actualizar_terminado', array('codigoContrato' => $codigoContrato)))
            ->add('clasificacionRiesgoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuClasificacionRiesgo',
                'property' => 'nombre',
                'data' => $arContrato->getClasificacionRiesgoRel(),
            ))
            ->add('terminacionContratoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuMotivoTerminacionContrato',
                'property' => 'motivo',
                'data' => $arContrato->getTerminacionContratoRel(),
            ))    
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $arContrato->getFechaHasta()  ,'attr' => array('class' => 'date',)))                                    
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $formActualizar->handleRequest($request);
        if ($formActualizar->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            if ($permiso == false){
                $objMensaje->Mensaje("error", "No tiene permisos para actualizar el contrato", $this);
            } else {
            $arContrato->setTerminacionContratoRel($formActualizar->get('terminacionContratoRel')->getData());
            $arContrato->setClasificacionRiesgoRel($formActualizar->get('clasificacionRiesgoRel')->getData());
            $arContrato->setFechaHasta($formActualizar->get('fechaHasta')->getData());
            $em->persist($arContrato);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:actualizarContratoTerminado.html.twig', array(
            'arContrato' => $arContrato,
            'formActualizar' => $formActualizar->createView()
        ));
    }
    
    public function ibpAdicionalAction($codigoContrato) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $formIbpAdicional = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_contratos_ibp_adicional', array('codigoContrato' => $codigoContrato)))
            ->add('ibpAdicional', 'number', array('data' =>$arContrato->getIbpAdicional() ,'required' => false))      
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $formIbpAdicional->handleRequest($request);    
        if ($formIbpAdicional->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $floIbpAdicional = $formIbpAdicional->get('ibpAdicional')->getData();
            
            $arContrato->setIbpAdicional($floIbpAdicional);
            $em->persist($arContrato);
            $em->flush();
       
            return $this->redirect($this->generateUrl('brs_rhu_base_contratos_detalles', array('codigoContrato' => $codigoContrato)));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:ibpAdicional.html.twig', array(
            'arContrato' => $arContrato,
            'formIbpAdicional' => $formIbpAdicional->createView()
        ));
    }

    public function detalleSedeNuevoAction($codigoContrato) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $codigoCentroCosto = $arContrato->getCodigoCentroCostoFk();
        $form = $this->createFormBuilder()
            ->add('sedeRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSede',
                'query_builder' => function (EntityRepository $er) use($codigoCentroCosto) {
                    return $er->createQueryBuilder('s')
                    ->where('s.codigoCentroCostoFk = :centroCosto')
                    ->setParameter('centroCosto', $codigoCentroCosto)
                    ->orderBy('s.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('guardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $arContratoSede = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoSede();
            $arContratoSede->setContratoRel($arContrato);
            $arContratoSede->setSedeRel($form->get('sedeRel')->getData());
            $em->persist($arContratoSede);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:nuevaSede.html.twig', array(
            'arContrato' => $arContrato,
            'form' => $form->createView()));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $session->set('dqlContratoLista', $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaDQL(
                $session->get('filtroIdentificacion'),
                $this->fechaDesdeInicia,
                $this->fechaHastaInicia,
                $session->get('filtroContratoActivo'),
                $session->get('filtroCodigoCentroCosto')
                ));
    }
    
    public function documentosAction($codigoContrato) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $form = $this->createFormBuilder() //
            ->add('BtnEntregaDocumentos', 'submit', array('label'  => 'Imprimir'))
            ->getForm(); 
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($arrSeleccionados == null){
                $objMensaje->Mensaje("error", "No ha seleccionado ningun documento", $this);
            } else {
                foreach ($arrSeleccionados AS $codigoDocumento){
                    //$arEntregaDocumento = new \Brasa\RecursoHumanoBundle\Entity\RhuEntregaDocumento();
                    $arEntregaDocumento = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntregaDocumento')->find($codigoDocumento);                                
                }
                $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoEntregaDocumentos();
                $objFormatoContrato->Generar($this, $codigoContrato,$arrSeleccionados);
            }   
            
        }
        $arEntregaDocumentos = new \Brasa\RecursoHumanoBundle\Entity\RhuEntregaDocumento();
        $arEntregaDocumentos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntregaDocumento')->findAll();
        return $this->render('BrasaRecursoHumanoBundle:Base/Contrato:documentos.html.twig', array(
                    'arEntregaDocumentos' => $arEntregaDocumentos,
                    'form'=> $form->createView()
        ));
    }

    private function formularioLista() {
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
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesdeInicia', 'date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHastaInicia', 'date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('estadoActivo', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'ACTIVOS', '0' => 'INACTIVOS')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        if($controles['fechaDesdeInicia']) {
            $this->fechaDesdeInicia = $controles['fechaDesdeInicia'];
        }
        if($controles['fechaHastaInicia']) {
            $this->fechaHastaInicia = $controles['fechaHastaInicia'];
        }
        //$session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);

        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroContratoActivo', $form->get('estadoActivo')->getData());
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
    }

    private function generarExcel() {
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'CODIGO EMPLEADO')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'TIPO')
                    ->setCellValue('F1', 'FECHA')
                    ->setCellValue('G1', 'CENTRO COSTOS')
                    ->setCellValue('H1', 'ENTIDAD SALUD')
                    ->setCellValue('I1', 'ENTIDAD PENSIÓN')
                    ->setCellValue('J1', 'CAJA COMPENSACIÓN')
                    ->setCellValue('K1', 'TIPO DE COTIZANTE')
                    ->setCellValue('L1', 'SUBTIPO DE COTIZANTE')
                    ->setCellValue('M1', 'TIEMPO')
                    ->setCellValue('N1', 'DESDE')
                    ->setCellValue('O1', 'HASTA')
                    ->setCellValue('P1', 'SALARIO')
                    ->setCellValue('Q1', 'CARGO')
                    ->setCellValue('R1', 'CARGO DESCRIPCION')
                    ->setCellValue('S1', 'CLA. RIESGO')
                    ->setCellValue('T1', 'ULT. PAGO')
                    ->setCellValue('U1', 'ULT. PAGO PRIMAS')
                    ->setCellValue('V1', 'ULT. PAGO CESANTIAS')
                    ->setCellValue('W1', 'ULT. PAGO VACACIONES');
        $i = 2;
        $query = $em->createQuery($session->get('dqlContratoLista'));
        //$arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = $query->getResult();
        foreach ($arContratos as $arContrato) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arContrato->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arContrato->getCodigoEmpleadoFk())
                    ->setCellValue('C' . $i, $arContrato->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arContrato->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arContrato->getContratoTipoRel()->getNombre())
                    ->setCellValue('F' . $i, $arContrato->getFecha()->Format('Y-m-d'))
                    ->setCellValue('G' . $i, $arContrato->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arContrato->getEntidadSaludRel()->getNombre())
                    ->setCellValue('I' . $i, $arContrato->getEntidadPensionRel()->getNombre())
                    ->setCellValue('J' . $i, $arContrato->getEntidadCajaRel()->getNombre())
                    ->setCellValue('K' . $i, $arContrato->getSsoTipoCotizanteRel()->getNombre())
                    ->setCellValue('L' . $i, $arContrato->getSsoSubtipoCotizanteRel()->getNombre())
                    ->setCellValue('M' . $i, $arContrato->getTipoTiempoRel()->getNombre())
                    ->setCellValue('N' . $i, $arContrato->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('O' . $i, $arContrato->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('P' . $i, $arContrato->getVrSalario())
                    ->setCellValue('Q' . $i, $arContrato->getCargoRel()->getNombre())
                    ->setCellValue('R' . $i, $arContrato->getCargoDescripcion())
                    ->setCellValue('S' . $i, $arContrato->getClasificacionRiesgoRel()->getNombre())
                    ->setCellValue('T' . $i, $arContrato->getFechaUltimoPago()->Format('Y-m-d'))
                    ->setCellValue('U' . $i, $arContrato->getFechaUltimoPagoPrimas()->Format('Y-m-d'))
                    ->setCellValue('V' . $i, $arContrato->getFechaUltimoPagoCesantias()->Format('Y-m-d'))
                    ->setCellValue('W' . $i, $arContrato->getFechaUltimoPagoVacaciones()->Format('Y-m-d'));
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('contratos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Contratos.xlsx"');
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
