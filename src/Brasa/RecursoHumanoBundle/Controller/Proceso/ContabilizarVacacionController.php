<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ContabilizarVacacionController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/rhu/proceso/contabilizar/vacacion/", name="brs_rhu_proceso_contabilizar_vacacion")
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
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobanteVacacion());
                    $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();                    
                    $arCentroCosto =$em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find(0);  
                    //Cuentas
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(7);
                    $codigoCuentaPagadas = $arCuenta->getCodigoCuentaFk();                    
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(8);
                    $codigoCuentaDisfrutadas = $arCuenta->getCodigoCuentaFk(); 
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(9);
                    $codigoCuentaSalud = $arCuenta->getCodigoCuentaFk();                                        
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(10);
                    $codigoCuentaPension = $arCuenta->getCodigoCuentaFk(); 
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(12);
                    $codigoCuentaVacacion = $arCuenta->getCodigoCuentaFk();                                        
                    foreach ($arrSeleccionados AS $codigo) {                                     
                        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigo);
                        if($arVacacion->getEstadoContabilizado() == 0) {
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arVacacion->getEmpleadoRel()->getNumeroIdentificacion()));                            
                            if(!$arTercero) {
                                $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                                $arTercero->setCiudadRel($arVacacion->getEmpleadoRel()->getCiudadRel());
                                $arTercero->setTipoIdentificacionRel($arVacacion->getEmpleadoRel()->getTipoIdentificacionRel());
                                $arTercero->setNumeroIdentificacion($arVacacion->getEmpleadoRel()->getNumeroIdentificacion());
                                $arTercero->setNombreCorto($arVacacion->getEmpleadoRel()->getNombreCorto());
                                $arTercero->setNombre1($arVacacion->getEmpleadoRel()->getNombre1());
                                $arTercero->setNombre2($arVacacion->getEmpleadoRel()->getNombre2());
                                $arTercero->setApellido1($arVacacion->getEmpleadoRel()->getApellido1());
                                $arTercero->setApellido2($arVacacion->getEmpleadoRel()->getApellido2());
                                $arTercero->setDireccion($arVacacion->getEmpleadoRel()->getDireccion());
                                $arTercero->setTelefono($arVacacion->getEmpleadoRel()->getTelefono());
                                $arTercero->setCelular($arVacacion->getEmpleadoRel()->getCelular());
                                $arTercero->setEmail($arVacacion->getEmpleadoRel()->getCorreo());
                                $em->persist($arTercero);                                 
                            }         
                            
                            //Vacaciones
                            if($arVacacion->getVrVacacionBruto() >  0) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaDisfrutadas); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFecha());
                                    $arRegistro->setDebito($arVacacion->getVrVacacionBruto());                            
                                    $arRegistro->setDescripcionContable('VACACIONES');
                                    $em->persist($arRegistro);
                                }             
                            }

                            //Pension
                            if($arVacacion->getVrPension() >  0) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaPension); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFecha());
                                    $arRegistro->setCredito($arVacacion->getVrPension());                            
                                    $arRegistro->setDescripcionContable('PENSION');
                                    $em->persist($arRegistro);
                                }             
                            }                            
                            
                            //Salud
                            if($arVacacion->getVrSalud() >  0) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaSalud); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFecha());
                                    $arRegistro->setCredito($arVacacion->getVrSalud());                            
                                    $arRegistro->setDescripcionContable('SALUD');
                                    $em->persist($arRegistro);
                                }             
                            }               
                            
                            //Bonificaciones
                            $arVacacionBonificaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionBonificacion();
                            $arVacacionBonificaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionBonificacion')->findBy(array('codigoVacacionFk' => $codigo));
                            foreach ($arVacacionBonificaciones as $arVacacionBonificacion) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arVacacionBonificacion->getPagoConceptoRel()->getCodigoCuentaFk()); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFecha()); 
                                    $arRegistro->setDebito($arVacacionBonificacion->getVrBonificacion());                                                                       
                                    $arRegistro->setDescripcionContable($arVacacionBonificacion->getPagoConceptoRel()->getNombre());
                                    $em->persist($arRegistro);
                                }                                    
                            }                             
                            
                            
                            //Deducciones
                            $arVacacionDeducciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
                            $arVacacionDeducciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionCredito')->findBy(array('codigoVacacionFk' => $codigo));
                            foreach ($arVacacionDeducciones as $arVacacionDeduccion) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arVacacionDeduccion->getPagoConceptoRel()->getCodigoCuentaFk()); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFecha()); 
                                    $arRegistro->setCredito($arVacacionDeduccion->getVrDeduccion());                                                                       
                                    $arRegistro->setDescripcionContable($arVacacionDeduccion->getPagoConceptoRel()->getNombre());
                                    $em->persist($arRegistro);
                                }                                    
                            }                            
                            
                            //Vacaciones por pagar
                            if($arVacacion->getVrVacacion() >  0) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaVacacion); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFecha());
                                    $arRegistro->setCredito($arVacacion->getVrVacacion());                            
                                    $arRegistro->setDescripcionContable('VACACIONES POR PAGAR');
                                    $em->persist($arRegistro);
                                }             
                            }                            
                            
                            $arVacacion->setEstadoContabilizado(1);                                
                            $em->persist($arVacacion);                                 
                                                         
                        }
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_vacacion'));
            }            
        }       
                
        $arVacaciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:vacacion.html.twig', array(
            'arVacaciones' => $arVacaciones,
            'form' => $form->createView()));
    }          
    
    private function formularioLista() {
        $form = $this->createFormBuilder()                        
            ->add('BtnContabilizar', 'submit', array('label'  => 'Contabilizar',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->pendientesContabilizarDql();  
    }             
    
}
