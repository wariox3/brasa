<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ProcesoContabilizarPagoBancoController extends Controller
{
    var $strDqlLista = "";
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
                    $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobanteContable();                    
                    $arComprobanteContable =$em->getRepository('BrasaContabilidadBundle:CtbComprobanteContable')->find(2);
                    $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();                    
                    $arCentroCosto =$em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find(1);                           
                    foreach ($arrSeleccionados AS $codigo) {                                     
                        $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                        $arPagoBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->find($codigo);
                        if($arPagoBancoDetalle->getEstadoContabilizado() == 0) {
                            $arTercero = $em->getRepository('BrasaGeneralBundle:GenTercero')->findOneBy(array('nit' => $arPagoBancoDetalle->getNumeroIdentificacion()));
                            if(count($arTercero) > 0) {                              
                                //La cuenta
                                $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find('250501');                            
                                $arRegistro->setComprobanteContableRel($arComprobanteContable);
                                $arRegistro->setCentroCostosRel($arCentroCosto);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arPagoBancoDetalle->getCodigoPagoBancoDetallePk());
                                $arRegistro->setNumeroReferencia($arPagoBancoDetalle->getPagoRel()->getNumero());                                
                                $arRegistro->setFecha($arPagoBancoDetalle->getPagoBancoRel()->getFechaAplicacion());
                                $arRegistro->setDebito($arPagoBancoDetalle->getVrPago());                            
                                $arRegistro->setDescripcionContable('PAGO');                                
                                $em->persist($arRegistro);  
                                
                                //Banco
                                $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro(); 
                                $codigoCuenta = $arPagoBancoDetalle->getPagoBancoRel()->getCuentaRel()->getCodigoCuentaFk();
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuenta);                            
                                $arRegistro->setComprobanteContableRel($arComprobanteContable);
                                $arRegistro->setCentroCostosRel($arCentroCosto);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arPagoBancoDetalle->getCodigoPagoBancoDetallePk());
                                $arRegistro->setNumeroReferencia($arPagoBancoDetalle->getPagoRel()->getNumero());                                
                                $arRegistro->setFecha($arPagoBancoDetalle->getPagoBancoRel()->getFechaAplicacion());
                                $arRegistro->setCredito($arPagoBancoDetalle->getVrPago());                            
                                $arRegistro->setDescripcionContable($arPagoBancoDetalle->getNombreCorto());
                                $em->persist($arRegistro);  
                                
                                $arPagoBancoDetalle->setEstadoContabilizado(1);
                                $em->persist($arPagoBancoDetalle);  
                            }                                                   
                        }
                    }
                    $em->flush();
                }
            }            
        }       
                
        $arPagosBancoDetalle = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:pagoBanco.html.twig', array(
            'arPagosBancoDetalle' => $arPagosBancoDetalle,
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
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->pendientesContabilizarDql();  
    }         
    
}
