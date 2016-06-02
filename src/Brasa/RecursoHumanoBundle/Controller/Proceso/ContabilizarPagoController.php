<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ContabilizarPagoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/rhu/proceso/contabilizar/pago/", name="brs_rhu_proceso_contabilizar_pago")
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
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();                    
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion;
                    $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();                    
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobantePagoNomina());
                    $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();                    
                    $arCentroCosto =$em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find(1);                           
                    foreach ($arrSeleccionados AS $codigo) {                                     
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigo);
                        if($arPago->getEstadoContabilizado() == 0) {
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEmpleadoRel()->getNumeroIdentificacion()));
                            if(count($arTercero) > 0) {
                                $this->contabilizarPagoNomina($codigo,$arComprobanteContable,$arCentroCosto,$arTercero,$arPago, $arConfiguracionNomina);  
                                $arPago->setEstadoContabilizado(1);
                                $em->persist($arPago);  
                            } else {
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
                                $this->contabilizarPagoNomina($codigo,$arComprobanteContable,$arCentroCosto,$arTercero,$arPago, $arConfiguracionNomina);  
                                $arPago->setEstadoContabilizado(1);                                
                                $em->persist($arPago);
                            }                                                    
                        }
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_pago'));
            }            
        }       
                
        $arPagos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:pago.html.twig', array(
            'arPagos' => $arPagos,
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
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->pendientesContabilizarDql();  
    }         
    
    private function contabilizarPagoNomina($codigo,$arComprobanteContable,$arCentroCosto,$arTercero,$arPago, $arConfiguracionNomina) {
        $em = $this->getDoctrine()->getManager();
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigo));
        foreach ($arPagoDetalles as $arPagoDetalle) {
            $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro(); 
            if($arPago->getCentroCostoRel()->getAdministrativo() == 1) {
                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arPagoDetalle->getPagoConceptoRel()->getCodigoCuentaFk());                            
            } else {
                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arPagoDetalle->getPagoConceptoRel()->getCodigoCuentaOperacionFk());                            
            }
            
            $arRegistro->setComprobanteRel($arComprobanteContable);
            $arRegistro->setCentroCostoRel($arCentroCosto);
            $arRegistro->setCuentaRel($arCuenta);
            $arRegistro->setTerceroRel($arTercero);
            $arRegistro->setNumero($arPago->getNumero());
            $arRegistro->setNumeroReferencia($arPago->getNumero());
            $arRegistro->setFecha($arPago->getFechaDesde());
            if($arPagoDetalle->getPagoConceptoRel()->getTipoCuenta() == 1) {
                $arRegistro->setDebito($arPagoDetalle->getVrPago());
            } else {
                $arRegistro->setCredito($arPagoDetalle->getVrPago());
            }
            $arRegistro->setDescripcionContable($arPagoDetalle->getPagoConceptoRel()->getNombre());
            $em->persist($arRegistro);                                
            //echo $arPagoDetalle->getCodigoPagoDetallePk() . "[" . $arPagoDetalle->getPagoConceptoRel()->getCodigoCuentaFk() . "]" . "<br/>";
        }
        //Nomina por pagar
        if($arConfiguracionNomina->getCuentaNominaPagar() != '') {           
            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionNomina->getCuentaNominaPagar()); //estaba 250501                           
            if($arCuenta) {
                $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                $arRegistro->setComprobanteRel($arComprobanteContable);
                $arRegistro->setCentroCostoRel($arCentroCosto);
                $arRegistro->setCuentaRel($arCuenta);
                $arRegistro->setTerceroRel($arTercero);
                $arRegistro->setNumero($arPago->getNumero());
                $arRegistro->setNumeroReferencia($arPago->getNumero());
                $arRegistro->setFecha($arPago->getFechaDesde());
                $arRegistro->setCredito($arPago->getVrNeto() );                            
                $arRegistro->setDescripcionContable('NOMINA POR PAGAR');
                $em->persist($arRegistro);
            }            
        }
        $em->flush();
    }    
 
    /**
     * @Route("/rhu/proceso/descontabilizar/pago/", name="brs_rhu_proceso_descontabilizar_pago")
     */     
    public function listaDescontabilizarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioListaDescontabilizar();
        $form->handleRequest($request);
        $this->listarDescontabilizar();
        if($form->isValid()) {            
            if ($form->get('BtnDesContabilizar')->isClicked()) {    
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                                    
                    foreach ($arrSeleccionados AS $codigo) {                                     
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigo);
                        $arPago->setEstadoContabilizado(0);                                                    
                        $em->persist($arPago);
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje('error', 'Debe seleccionar al menos un registro', $this);
                }
            }            
        }       
                
        $arPagos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:descontabilizar.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()));
    }          
    
    private function formularioListaDescontabilizar() {
        $form = $this->createFormBuilder()                        
            ->add('BtnDesContabilizar', 'submit', array('label'  => 'DesContabilizar',))
            ->getForm();        
        return $form;
    }      
    
    private function listarDescontabilizar() {
        $em = $this->getDoctrine()->getManager();                
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->contabilizadosDql();  
    }
    
}
