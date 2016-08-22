<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ContabilizarLiquidacionController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/rhu/proceso/contabilizar/liquidacion/", name="brs_rhu_proceso_contabilizar_liquidacion")
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
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobanteLiquidacion());
                    $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();                    
                    $arCentroCosto =$em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find(1);                           
                    foreach ($arrSeleccionados AS $codigo) {                                     
                        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigo);
                        if($arLiquidacion->getEstadoContabilizado() == 0) {
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion()));
                            if($arTercero) {
                                $this->contabilizar($codigo,$arComprobanteContable,$arCentroCosto,$arTercero,$arLiquidacion, $arConfiguracion);  
                                //$arLiquidacion->setEstadoContabilizado(1);                                
                                $em->persist($arLiquidacion);                                 
                            }                             
                        }
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_liquidacion'));
            }            
        }       
                
        $arLiquidaciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:liquidacion.html.twig', array(
            'arLiquidaciones' => $arLiquidaciones,
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
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->pendientesContabilizarDql();  
    }         
    
    private function contabilizar($codigo,$arComprobanteContable,$arCentroCosto,$arTercero,$arLiquidacion, $arConfiguracionNomina) {
        $em = $this->getDoctrine()->getManager();
        /*$arLiquidacionDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDetalle();
        $arLiquidacionDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionDetalle')->findBy(array('codigoPagoFk' => $codigo));
        foreach ($arLiquidacionDetalles as $arLiquidacionDetalle) {
            if($arLiquidacionDetalle->getVrPago() > 0) {
                $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                 
                if($arLiquidacion->getEmpleadoRel()->getEmpleadoTipoRel()->getTipo() == 1) {
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arLiquidacionDetalle->getPagoConceptoRel()->getCodigoCuentaFk());                            
                } 
                if($arLiquidacion->getEmpleadoRel()->getEmpleadoTipoRel()->getTipo() == 2) {
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arLiquidacionDetalle->getPagoConceptoRel()->getCodigoCuentaOperacionFk());                            
                }
                if($arLiquidacion->getEmpleadoRel()->getEmpleadoTipoRel()->getTipo() == 3) {
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arLiquidacionDetalle->getPagoConceptoRel()->getCodigoCuentaComercialFk());                            
                }
                $arRegistro->setComprobanteRel($arComprobanteContable);
                if($arCuenta->getExigeCentroCostos() == 1) {
                    $arRegistro->setCentroCostoRel($arCentroCosto);    
                }                
                $arRegistro->setCuentaRel($arCuenta);
                $arRegistro->setTerceroRel($arTercero);
                $arRegistro->setNumero($arLiquidacion->getNumero());
                $arRegistro->setNumeroReferencia($arLiquidacion->getNumero());
                $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                if($arLiquidacionDetalle->getPagoConceptoRel()->getTipoCuenta() == 1) {
                    $arRegistro->setDebito($arLiquidacionDetalle->getVrPago());
                } else {
                    $arRegistro->setCredito($arLiquidacionDetalle->getVrPago());
                }
                $arRegistro->setDescripcionContable($arLiquidacionDetalle->getPagoConceptoRel()->getNombre());
                $em->persist($arRegistro);                 
            }                                           
        }
        */

        //Liquidacion por pagar
        if($arLiquidacion->getVrNeto() > 0) {
            if($arConfiguracionNomina->getCuentaNominaPagar() != '') {           
                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionNomina->getCuentaNominaPagar()); //estaba 250501                           
                if($arCuenta) {
                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                    $arRegistro->setComprobanteRel($arComprobanteContable);
                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                    $arRegistro->setCuentaRel($arCuenta);
                    $arRegistro->setTerceroRel($arTercero);
                    $arRegistro->setNumero($arLiquidacion->getNumero());
                    $arRegistro->setNumeroReferencia($arLiquidacion->getNumero());
                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                    $arRegistro->setCredito($arLiquidacion->getVrNeto());                            
                    $arRegistro->setDescripcionContable('LIQUIDACION POR PAGAR');
                    $em->persist($arRegistro);
                }            
            }            
        }        
    }    
    
}
