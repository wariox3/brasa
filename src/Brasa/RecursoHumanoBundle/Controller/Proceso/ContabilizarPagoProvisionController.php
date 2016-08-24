<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ContabilizarPagoProvisionController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/proceso/contabilizar/pago/provision", name="brs_rhu_proceso_contabilizar_pago_provision")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if ($form->get('BtnContabilizar')->isClicked()) { 
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();                    
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();                    
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobanteProvision());
                    $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();                    
                    $arCentroCosto =$em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find(1);                                               
                    $arProvisionCesantias = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(1);
                    $arProvisionCesantias2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(1);
                    foreach ($arrSeleccionados AS $codigo) {                                     
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigo);
                        $tipoEmpleado = $arPago->getEmpleadoRel()->getEmpleadoTipoRel()->getTipo();
                        if($arPago->getEstadoContabilizadoProvision() == 0) {
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEmpleadoRel()->getNumeroIdentificacion()));
                            if(count($arTercero) <= 0) {
                                $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                                $arTercero->setCiudadRel($arPago->getEmpleadoRel()->getCiudadRel());
                                $arTercero->setTipoIdentificacionRel($arPago->getEmpleadoRel()->getTipoIdentificacionRel());
                                $arTercero->setNumeroIdentificacion($arPago->getEmpleadoRel()->getNumeroIdentificacion());
                                $arTercero->setNombreCorto($arPago->getEmpleadoRel()->getNombreCorto());
                                $arTercero->setNombre1($arPago->getEmpleadoRel()->getNombre1());
                                $arTercero->setNombre2($arPago->getEmpleadoRel()->getNombre2());
                                $arTercero->setApellido1($arPago->getEmpleadoRel()->getApellido1());
                                $arTercero->setApellido2($arPago->getEmpleadoRel()->getApellido2());
                                $arTercero->setDireccion($arPago->getEmpleadoRel()->getDireccion());
                                $arTercero->setTelefono($arPago->getEmpleadoRel()->getTelefono());
                                $arTercero->setCelular($arPago->getEmpleadoRel()->getCelular());
                                $arTercero->setEmail($arPago->getEmpleadoRel()->getCorreo());
                                $em->persist($arTercero);                                 
                            }  
                            
                            if($arPago->getVrCesantias() > 0) {   
                                $cuenta = $this->cuenta($arProvisionCesantias, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arPago->getNumero());
                                    $arRegistro->setNumeroReferencia($arPago->getNumero());
                                    $arRegistro->setFecha($arPago->getFechaHasta());
                                    $arRegistro->setDebito($arPago->getVrCesantias());                            
                                    $arRegistro->setDescripcionContable('PROVISION CESANTIAS');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionCesantias2, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arPago->getNumero());
                                    $arRegistro->setNumeroReferencia($arPago->getNumero());
                                    $arRegistro->setFecha($arPago->getFechaHasta());
                                    $arRegistro->setCredito($arPago->getVrCesantias());                            
                                    $arRegistro->setDescripcionContable('PROVISION CESANTIAS');
                                    $em->persist($arRegistro);
                                }                                
                            }                             
                            //$arPago->setEstadoContabilizadoProvision(1);                                
                            $em->persist($arPago);                            
                        }
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_pago_provision'));
            }            
        }       

        if ($form->get('BtnActualizar')->isClicked()) { 
            set_time_limit(0);
            ini_set("memory_limit", -1);            
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();            
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            $porcentajeCaja = $arConfiguracion->getAportesPorcentajeCaja();
            $porcentajeCesantias = $arConfiguracion->getPrestacionesPorcentajeCesantias();        
            $porcentajeInteresesCesantias = $arConfiguracion->getPrestacionesPorcentajeInteresesCesantias();
            $porcentajeVacaciones = $arConfiguracion->getPrestacionesPorcentajeVacaciones();
            $porcentajePrimas = $arConfiguracion->getPrestacionesPorcentajePrimas();            
            $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();            
            $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('estadoContabilizadoProvision' => 0));            
            foreach ($arPagos as $arPago) {
                $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContrato = $arPago->getContratoRel();
                $auxilioTransporteCotizacion = $arPago->getVrAuxilioTransporteCotizacion();                                
                $porcentajeRiesgos = $arContrato->getClasificacionRiesgoRel()->getPorcentaje();
                $porcentajePension = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador();                
                $porcentajeSalud = $arContrato->getTipoSaludRel()->getPorcentajeEmpleador();                        
                
                //Prestaciones
                $ingresoBasePrestacion = $arPago->getVrIngresoBasePrestacion();                
                $cesantias = (($ingresoBasePrestacion + $auxilioTransporteCotizacion) * $porcentajeCesantias) / 100; // Porcentaje 8.33                
                $interesesCesantias = ($cesantias * $porcentajeInteresesCesantias) / 100; // Porcentaje 1 sobre las cesantias                        
                $primas = (($ingresoBasePrestacion + $auxilioTransporteCotizacion) * $porcentajePrimas) / 100; // 8.33       
                $vacaciones = ($ingresoBasePrestacion * $porcentajeVacaciones) / 100; // 4.17                                                
                
                //Aportes
                $ingresoBaseCotizacion = $arPago->getVrIngresoBaseCotizacion();                
                $riesgos = ($ingresoBaseCotizacion * $porcentajeRiesgos)/100;        
                $pension = ($ingresoBaseCotizacion * $porcentajePension) / 100; 
                $salud = ($ingresoBaseCotizacion * $porcentajeSalud) / 100;         
                $caja = ($ingresoBaseCotizacion * $porcentajeCaja) / 100; //Porcentaje 4        
                $sena = 0;
                $icbf = 0;                                
                
                //12 aprendiz y 19 practicante        
                if($arContrato->getCodigoTipoCotizanteFk() == '19' || $arContrato->getCodigoTipoCotizanteFk() == '12') {            
                    $salud = ($ingresoBasePrestacion * $porcentajeSalud) / 100;
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
                
                $arPagoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();            
                $arPagoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($arPago->getCodigoPagoPk());                            
                $arPagoActualizar->setVrCesantias(round($cesantias));
                $arPagoActualizar->setVrInteresesCesantias(round($interesesCesantias));
                $arPagoActualizar->setVrPrimas($primas);
                $arPagoActualizar->setVrVacaciones($vacaciones);                
                $arPagoActualizar->setVrPensionEmpleador($pension);
                $arPagoActualizar->setVrEpsEmpleador($salud);
                $arPagoActualizar->setVrArp($riesgos);
                $arPagoActualizar->setVrCaja($caja);
                $arPagoActualizar->setVrSena($sena);
                $arPagoActualizar->setVrIcbf($icbf);                                                 
                $em->persist($arPagoActualizar);
            }
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_pago_provision'));
        }        
        $arPagos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:pagoProvision.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()));
    }          
    
    private function formularioLista() {
        $form = $this->createFormBuilder()                        
            ->add('BtnContabilizar', 'submit', array('label'  => 'Contabilizar',))
            ->add('BtnActualizar', 'submit', array('label'  => 'Actualizar',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->pendientesContabilizarProvisionDql();  
    }         
   
    private function cuenta($arConfiguracion, $tipoEmpleado) {
        $cuenta = "";
        if($tipoEmpleado == 1) {
            $cuenta = $arConfiguracion->getCodigoCuentaFk();
        }        
        if($tipoEmpleado == 2) {
            $cuenta = $arConfiguracion->getCodigoCuentaOperacionFk();
        }        
        if($tipoEmpleado == 3) {
            $cuenta = $arConfiguracion->getCodigoCuentaComercialFk();
        }                
        return $cuenta;
    }
}
