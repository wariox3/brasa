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
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if($form->get('BtnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);                 
                $this->generarExcel();
            }            
            
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
                    $arProvisionCesantias2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(2);
                    $arProvisionInteresesCesantias = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(3);
                    $arProvisionInteresesCesantias2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(4);
                    $arProvisionPrima = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(5);
                    $arProvisionPrima2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(6);                    
                    $arProvisionVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(7);
                    $arProvisionVacaciones2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(8);                    
                    $arProvisionIndemnizaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(9);
                    $arProvisionIndemnizaciones2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(10);                    
                    $arProvisionRiesgos = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(11);
                    $arProvisionRiesgos2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(12);                                        
                    $arProvisionSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(13);
                    $arProvisionSalud2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(14);                                        
                    $arProvisionPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(15);
                    $arProvisionPension2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(16);                                                            
                    $arProvisionCaja = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(17);
                    $arProvisionCaja2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(18);                                                            
                    $arProvisionIcbf = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(19);
                    $arProvisionIcbf2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(20);                                                            
                    $arProvisionSena = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(21);
                    $arProvisionSena2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find(22);                                                            
                    $errorDatos = false;
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
                            //Cesantias
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
                            
                            //Cesantias Intereses
                            if($arPago->getVrInteresesCesantias() > 0) {   
                                $cuenta = $this->cuenta($arProvisionInteresesCesantias, $tipoEmpleado);                                
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
                                    $arRegistro->setDebito($arPago->getVrInteresesCesantias());                            
                                    $arRegistro->setDescripcionContable('PROVISION INTERESES CESANTIAS');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionInteresesCesantias2, $tipoEmpleado);                                
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
                                    $arRegistro->setCredito($arPago->getVrInteresesCesantias());                            
                                    $arRegistro->setDescripcionContable('PROVISION INTERESES CESANTIAS');
                                    $em->persist($arRegistro);
                                }                                
                            }         
                            
                            //Prima
                            if($arPago->getVrPrimas() > 0) {   
                                $cuenta = $this->cuenta($arProvisionPrima, $tipoEmpleado);                                
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
                                    $arRegistro->setDebito($arPago->getVrPrimas());                            
                                    $arRegistro->setDescripcionContable('PROVISION PRIMAS');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionPrima2, $tipoEmpleado);                                
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
                                    $arRegistro->setCredito($arPago->getVrPrimas());                            
                                    $arRegistro->setDescripcionContable('PROVISION PRIMAS');
                                    $em->persist($arRegistro);
                                }                                
                            } 

                            //Vacaciones
                            if($arPago->getVrVacaciones() > 0) {   
                                $cuenta = $this->cuenta($arProvisionVacaciones, $tipoEmpleado);                                
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
                                    $arRegistro->setDebito($arPago->getVrVacaciones());                            
                                    $arRegistro->setDescripcionContable('PROVISION VACACIONES');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionVacaciones2, $tipoEmpleado);                                
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
                                    $arRegistro->setCredito($arPago->getVrVacaciones());                            
                                    $arRegistro->setDescripcionContable('PROVISION VACACIONES');
                                    $em->persist($arRegistro);
                                }                                
                            }    
                            
                            //Indemnizaciones
                            if($arPago->getVrIndemnizacion() > 0) {   
                                $cuenta = $this->cuenta($arProvisionIndemnizaciones, $tipoEmpleado);                                
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
                                    $arRegistro->setDebito($arPago->getVrIndemnizacion());                            
                                    $arRegistro->setDescripcionContable('PROVISION INDEMNIZACION');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionIndemnizaciones2, $tipoEmpleado);                                
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
                                    $arRegistro->setCredito($arPago->getVrIndemnizacion());                            
                                    $arRegistro->setDescripcionContable('PROVISION INDEMNIZACION');
                                    $em->persist($arRegistro);
                                }                                
                            } 
                            
                            //Pension
                            if($arPago->getVrPensionEmpleador() > 0) {   
                                $arTerceroPension = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getContratoRel()->getEntidadPensionRel()->getNit()));
                                if($arTerceroPension) {
                                    $cuenta = $this->cuenta($arProvisionPension, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroPension);
                                        $arRegistro->setNumero($arPago->getNumero());
                                        $arRegistro->setNumeroReferencia($arPago->getNumero());
                                        $arRegistro->setFecha($arPago->getFechaHasta());
                                        $arRegistro->setDebito($arPago->getVrPensionEmpleador());                            
                                        $arRegistro->setDescripcionContable('PROVISION PENSION');
                                        $em->persist($arRegistro);
                                    }  
                                    $cuenta = $this->cuenta($arProvisionPension2, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroPension);
                                        $arRegistro->setNumero($arPago->getNumero());
                                        $arRegistro->setNumeroReferencia($arPago->getNumero());
                                        $arRegistro->setFecha($arPago->getFechaHasta());
                                        $arRegistro->setCredito($arPago->getVrPensionEmpleador());                            
                                        $arRegistro->setDescripcionContable('PROVISION PENSION');
                                        $em->persist($arRegistro);
                                    }                                 
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "Pago numero: " . $arPago->getNumero() . ", en terceros de contabilidad no existe la entidad de pension " . $arPago->getContratoRel()->getEntidadPensionRel()->getNombre() . " Nit: " . $arPago->getContratoRel()->getEntidadPensionRel()->getNit(), $this);
                                    break 1;
                                }                                                                 
                            }                             
                            
                            //Salud
                            if($arPago->getVrEpsEmpleador() > 0) {   
                                $arTerceroSalud = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getContratoRel()->getEntidadSaludRel()->getNit()));
                                if($arTerceroSalud) {
                                    $cuenta = $this->cuenta($arProvisionSalud, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSalud);
                                        $arRegistro->setNumero($arPago->getNumero());
                                        $arRegistro->setNumeroReferencia($arPago->getNumero());
                                        $arRegistro->setFecha($arPago->getFechaHasta());
                                        $arRegistro->setDebito($arPago->getVrEpsEmpleador());                            
                                        $arRegistro->setDescripcionContable('PROVISION SALUD');
                                        $em->persist($arRegistro);
                                    }  
                                    $cuenta = $this->cuenta($arProvisionSalud2, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSalud);
                                        $arRegistro->setNumero($arPago->getNumero());
                                        $arRegistro->setNumeroReferencia($arPago->getNumero());
                                        $arRegistro->setFecha($arPago->getFechaHasta());
                                        $arRegistro->setCredito($arPago->getVrEpsEmpleador());                            
                                        $arRegistro->setDescripcionContable('PROVISION SALUD');
                                        $em->persist($arRegistro);
                                    }                                     
                                }  else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "Pago numero: " . $arPago->getNumero() . ", en terceros de contabilidad no existe la entidad de salud " . $arPago->getContratoRel()->getEntidadSaludRel()->getNombre() . " Nit: " . $arPago->getContratoRel()->getEntidadSaludRel()->getNit(), $this);
                                    break 1;
                                }                                                               
                            }                             
                            
                            //Riesgos
                            if($arPago->getVrArp() > 0) {  
                                $arEntidadRiesgos = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
                                $arEntidadRiesgos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($arConfiguracion->getCodigoEntidadRiesgoFk());
                                $arTerceroRiesgos = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arEntidadRiesgos->getNit()));
                                if($arTerceroRiesgos) {
                                    $cuenta = $this->cuenta($arProvisionRiesgos, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroRiesgos);
                                        $arRegistro->setNumero($arPago->getNumero());
                                        $arRegistro->setNumeroReferencia($arPago->getNumero());
                                        $arRegistro->setFecha($arPago->getFechaHasta());
                                        $arRegistro->setDebito($arPago->getVrArp());                            
                                        $arRegistro->setDescripcionContable('PROVISION RIESGOS');
                                        $em->persist($arRegistro);
                                    }  
                                    $cuenta = $this->cuenta($arProvisionRiesgos2, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroRiesgos);
                                        $arRegistro->setNumero($arPago->getNumero());
                                        $arRegistro->setNumeroReferencia($arPago->getNumero());
                                        $arRegistro->setFecha($arPago->getFechaHasta());
                                        $arRegistro->setCredito($arPago->getVrArp());                            
                                        $arRegistro->setDescripcionContable('PROVISION RIESGOS');
                                        $em->persist($arRegistro);
                                    }                                     
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "Pago numero: " . $arPago->getNumero() . ", en terceros de contabilidad no existe la entidad de riesgos " . $arEntidadRiesgos->getNombre() . " Nit: " . $arEntidadRiesgos->getNit(), $this);
                                    break 1;
                                }                                              
                            }                             
                            
                            //Caja
                            if($arPago->getVrCaja() > 0) {
                                $arTerceroCaja = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getContratoRel()->getEntidadCajaRel()->getNit()));
                                if($arTerceroCaja) {
                                    $cuenta = $this->cuenta($arProvisionCaja, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroCaja);
                                        $arRegistro->setNumero($arPago->getNumero());
                                        $arRegistro->setNumeroReferencia($arPago->getNumero());
                                        $arRegistro->setFecha($arPago->getFechaHasta());
                                        $arRegistro->setDebito($arPago->getVrCaja());                            
                                        $arRegistro->setDescripcionContable('PROVISION CAJA');
                                        $em->persist($arRegistro);
                                    }  
                                    $cuenta = $this->cuenta($arProvisionCaja2, $tipoEmpleado);                                
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroCaja);
                                        $arRegistro->setNumero($arPago->getNumero());
                                        $arRegistro->setNumeroReferencia($arPago->getNumero());
                                        $arRegistro->setFecha($arPago->getFechaHasta());
                                        $arRegistro->setCredito($arPago->getVrCaja());                            
                                        $arRegistro->setDescripcionContable('PROVISION CAJA');
                                        $em->persist($arRegistro);
                                    }                                     
                                }  else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "Pago numero: " . $arPago->getNumero() . ", en terceros de contabilidad no existe la entidad de caja " . $arPago->getContratoRel()->getEntidadCajaRel()->getNombre() . " Nit: " . $arPago->getContratoRel()->getEntidadCajaRel()->getNit(), $this);
                                    break 1;
                                }                                                             
                            }                             

                            //Sena
                            if($arPago->getVrSena() > 0) {   
                                $cuenta = $this->cuenta($arProvisionSena, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arPago->getNumero());
                                    $arRegistro->setNumeroReferencia($arPago->getNumero());
                                    $arRegistro->setFecha($arPago->getFechaHasta());
                                    $arRegistro->setDebito($arPago->getVrSena());                            
                                    $arRegistro->setDescripcionContable('PROVISION SENA');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionSena2, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arPago->getNumero());
                                    $arRegistro->setNumeroReferencia($arPago->getNumero());
                                    $arRegistro->setFecha($arPago->getFechaHasta());
                                    $arRegistro->setCredito($arPago->getVrSena());                            
                                    $arRegistro->setDescripcionContable('PROVISION SENA');
                                    $em->persist($arRegistro);
                                }                                
                            }                             
                            
                            //Icbf
                            if($arPago->getVrIcbf() > 0) {   
                                $cuenta = $this->cuenta($arProvisionIcbf, $tipoEmpleado);                                
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($cuenta);                                                                                                     
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arPago->getNumero());
                                    $arRegistro->setNumeroReferencia($arPago->getNumero());
                                    $arRegistro->setFecha($arPago->getFechaHasta());
                                    $arRegistro->setDebito($arPago->getVrIcbf());                            
                                    $arRegistro->setDescripcionContable('PROVISION ICBF');
                                    $em->persist($arRegistro);
                                }  
                                $cuenta = $this->cuenta($arProvisionIcbf2, $tipoEmpleado);                                
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
                                    $arRegistro->setCredito($arPago->getVrIcbf());                            
                                    $arRegistro->setDescripcionContable('PROVISION ICBF');
                                    $em->persist($arRegistro);
                                }                                
                            }                             
                            
                            $arPago->setEstadoContabilizadoProvision(1);                                
                            if($errorDatos == false) {
                                $em->persist($arPago);                                                            
                            }                            
                        }
                    }
                    if($errorDatos == false) {
                        $em->flush();                        
                    }
                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_pago_provision'));
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
                $porcentajeIndemnizacion = $arConfiguracion->getPrestacionesPorcentajeIndemnizacion();            
                $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();            
                $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('estadoContabilizadoProvision' => 0));            
                foreach ($arPagos as $arPago) {                    
                    $ingresoBaseIndemnizacion = 0;
                    $ingresoBaseVacacion = 0;
                    $arPagosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                    $arPagosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));            
                    foreach ($arPagosDetalles as $arPagoDetalle) {                        
                        if($arPagoDetalle->getPagoConceptoRel()->getProvisionIndemnizacion() == 1) {
                            $ingresoBaseIndemnizacion +=  $arPagoDetalle->getVrIngresoBasePrestacion();
                        }
                        if($arPagoDetalle->getPagoConceptoRel()->getProvisionVacacion() == 1) {
                            $ingresoBaseVacacion +=  $arPagoDetalle->getVrIngresoBasePrestacion();
                        }                        
                    }
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato = $arPago->getContratoRel();
                    $auxilioTransporteCotizacion = $arPago->getVrAuxilioTransporteCotizacion();                                
                    $ingresoBasePrestacion = $arPago->getVrIngresoBasePrestacion();                                    
                    $porcentajeRiesgos = $arContrato->getClasificacionRiesgoRel()->getPorcentaje();
                    $porcentajePension = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador();                
                    $porcentajeSalud = $arContrato->getTipoSaludRel()->getPorcentajeEmpleador();                        

                    //Prestaciones
                    if($arContrato->getSalarioIntegral() == 0) {
                        $cesantias = (($ingresoBasePrestacion + $auxilioTransporteCotizacion) * $porcentajeCesantias) / 100; // Porcentaje 8.33                
                        $interesesCesantias = ($cesantias * $porcentajeInteresesCesantias) / 100; // Porcentaje 1 sobre las cesantias                        
                        $primas = (($ingresoBasePrestacion + $auxilioTransporteCotizacion) * $porcentajePrimas) / 100; // 8.33                               
                    } else {
                        $cesantias = 0;
                        $interesesCesantias = 0;
                        $primas = 0;
                    }                    
                    $vacaciones = ($ingresoBaseVacacion * $porcentajeVacaciones) / 100; // 4.17                                                
                    $indemnizacion = ($ingresoBaseIndemnizacion * $porcentajeIndemnizacion) / 100; // 4.17                                                

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
                    $arPagoActualizar->setVrIndemnizacion($indemnizacion);
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
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
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
    
    private function generarExcel() {
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
        for($col = 'A'; $col !== 'U'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'I'; $col !== 'U'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'TIPO')
                    ->setCellValue('D1', 'DOCUMENTO')
                    ->setCellValue('E1', 'EMPLEADO')
                    ->setCellValue('F1', 'C.COSTO')                    
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')
                    ->setCellValue('I1', 'SALARIO')
                    ->setCellValue('J1', 'CESANTIAS')
                    ->setCellValue('K1', 'INTERESES')
                    ->setCellValue('L1', 'PRIMAS')
                    ->setCellValue('M1', 'VACACIONES')
                    ->setCellValue('N1', 'INDEMNIZACIONES')
                    ->setCellValue('O1', 'PENSION')
                    ->setCellValue('P1', 'SALUD')
                    ->setCellValue('Q1', 'CAJA')
                    ->setCellValue('R1', 'RIESGOS')
                    ->setCellValue('S1', 'SENA')
                    ->setCellValue('T1', 'ICBF');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getNumero())
                    ->setCellValue('C' . $i, $arPago->getPagoTipoRel()->getNombre())
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())                    
                    ->setCellValue('G' . $i, $arPago->getFechaDesdePago()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arPago->getFechaHastaPago()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arPago->getVrSalarioEmpleado())
                    ->setCellValue('J' . $i, $arPago->getVrCesantias())
                    ->setCellValue('K' . $i, $arPago->getVrInteresesCesantias())
                    ->setCellValue('L' . $i, $arPago->getVrPrimas())
                    ->setCellValue('M' . $i, $arPago->getVrVacaciones())
                    ->setCellValue('N' . $i, $arPago->getVrIndemnizacion())
                    ->setCellValue('O' . $i, $arPago->getVrPensionEmpleador())
                    ->setCellValue('P' . $i, $arPago->getVrEpsEmpleador())
                    ->setCellValue('Q' . $i, $arPago->getVrCaja())
                    ->setCellValue('R' . $i, $arPago->getVrArp())
                    ->setCellValue('S' . $i, $arPago->getVrSena())
                    ->setCellValue('T' . $i, $arPago->getVrIcbf());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('pagosProvisiones');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pagos.xlsx"');
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
