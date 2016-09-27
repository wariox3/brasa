<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class ContabilizarPagoBancoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/proceso/contabilizar/pago/banco/", name="brs_rhu_proceso_contabilizar_pago_banco")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 70)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if ($form->get('BtnContabilizar')->isClicked()) {    
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();                    
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();                    
                    $arComprobanteContable =$em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobantePagoBanco());
                    $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();                    
                    $arCentroCosto =$em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find(1);                           
                    foreach ($arrSeleccionados AS $codigo) { 
                        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
                        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigo);
                        $arPagoBancoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                        $arPagoBancoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array('codigoPagoBancoFk' => $codigo));
                        foreach ($arPagoBancoDetalles as $arPagoBancoDetalle) {
                            if($arPagoBancoDetalle->getVrPago() > 0) {
                                $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPagoBancoDetalle->getNumeroIdentificacion()));
                                if(!$arTercero) {                                                                  
                                    $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                                    $arTercero->setCiudadRel($arPagoBancoDetalle->getEmpleadoRel()->getCiudadRel());
                                    $arTercero->setTipoIdentificacionRel($arPagoBancoDetalle->getEmpleadoRel()->getTipoIdentificacionRel());
                                    $arTercero->getDigitoVerificacion($arPagoBancoDetalle->getEmpleadoRel()->getDigitoVerificacion());
                                    $arTercero->setNumeroIdentificacion($arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion());
                                    $arTercero->setNombreCorto($arPagoBancoDetalle->getEmpleadoRel()->getNombreCorto());
                                    $arTercero->setNombre1($arPagoBancoDetalle->getEmpleadoRel()->getNombre1());
                                    $arTercero->setNombre2($arPagoBancoDetalle->getEmpleadoRel()->getNombre2());
                                    $arTercero->setApellido1($arPagoBancoDetalle->getEmpleadoRel()->getApellido1());
                                    $arTercero->setApellido2($arPagoBancoDetalle->getEmpleadoRel()->getApellido2());
                                    $arTercero->setDireccion($arPagoBancoDetalle->getEmpleadoRel()->getDireccion());
                                    $arTercero->setTelefono($arPagoBancoDetalle->getEmpleadoRel()->getTelefono());
                                    $arTercero->setCelular($arPagoBancoDetalle->getEmpleadoRel()->getCelular());
                                    $arTercero->setEmail($arPagoBancoDetalle->getEmpleadoRel()->getCorreo());
                                }                                
                                $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arPagoBanco->getPagoBancoTipoRel()->getCodigoCuentaFk());                                                                                            
                                $arRegistro->setComprobanteRel($arComprobanteContable);                                
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arPagoBanco->getCodigoPagoBancoPk());
                                $arRegistro->setNumeroReferencia($arPagoBanco->getCodigoPagoBancoPk());                                
                                $arRegistro->setFecha($arPagoBanco->getFecha());
                                $arRegistro->setDebito($arPagoBancoDetalle->getVrPago());                            
                                $arRegistro->setDescripcionContable('PAGO');                                
                                $em->persist($arRegistro);  

                                //Banco
                                $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro(); 
                                $codigoCuenta = $arPagoBanco->getCuentaRel()->getCodigoCuentaFk();
                                $arCuentaBanco = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find(1105);                            
                                $arRegistro->setComprobanteRel($arComprobanteContable);                                
                                $arRegistro->setCuentaRel($arCuentaBanco);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arPagoBanco->getCodigoPagoBancoPk());
                                $arRegistro->setNumeroReferencia($arPagoBanco->getCodigoPagoBancoPk());                                                                
                                $arRegistro->setFecha($arPagoBancoDetalle->getPagoBancoRel()->getFechaAplicacion());
                                $arRegistro->setCredito($arPagoBancoDetalle->getVrPago());                            
                                $arRegistro->setDescripcionContable($arPagoBancoDetalle->getNombreCorto());
                                $em->persist($arRegistro);                                
                                
                            }                            
                        }
                        //$arPagoBanco->setEstadoContabilizado(1);
                        //$em->persist($arPagoBanco);
                    }
                    $em->flush();
                }
            }            
        }       
                
        $arPagosBanco = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:pagoBanco.html.twig', array(
            'arPagosBanco' => $arPagosBanco,
            'form' => $form->createView()));
    }          

    /**
     * @Route("/rhu/proceso/descontabilizar/pago/banco/", name="brs_rhu_proceso_descontabilizar_pago_banco")
     */    
    public function descontabilizarPagoBancoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession(); 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('pagoDesde', 'number', array('label'  => 'Pago desde'))
            ->add('pagoHasta', 'number', array('label'  => 'Pago hasta'))
            ->add('BtnDescontabilizar', 'submit', array('label'  => 'Descontabilizar',))    
            ->getForm();
        $form->handleRequest($request);        
        if ($form->isValid()) {             
            if ($form->get('BtnDescontabilizar')->isClicked()) {
                $intPagoDesde = $form->get('pagoDesde')->getData();
                $intPagoHasta = $form->get('pagoHasta')->getData();
                if($intPagoDesde != "" || $intPagoHasta != "") {
                    $arRegistros = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                    $arRegistros = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->contabilizadosPagoBancoDql($intPagoDesde,$intPagoHasta);  
                    foreach ($arRegistros as $codigoRegistro) {
                        $arRegistro = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                        $arRegistro = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->find($codigoRegistro);
                        $arRegistro->setEstadoContabilizado(0);                                                    
                        $em->persist($arRegistro);                    
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje('error', 'Debe seleccionar un filtro', $this);
                }                               
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:descontabilizarPagoBanco.html.twig', array(
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
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->pendientesContabilizarDql();  
    }                
}
