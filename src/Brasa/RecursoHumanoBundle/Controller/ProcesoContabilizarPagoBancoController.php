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
                        $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                        $arPagoBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->find($codigo);
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($arPagoBancoDetalle->getCodigoPagoFk());
                        if($arPagoBancoDetalle->getEstadoContabilizado() == 0) {
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPagoBancoDetalle->getNumeroIdentificacion()));
                            if(count($arTercero) > 0) {                              
                                $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->contabilizarPagoBanco($codigo,$arComprobanteContable,$arCentroCosto,$arTercero,$arPagoBancoDetalle);  
                                $arPagoBancoDetalle->setEstadoContabilizado(1);
                                $em->persist($arPagoBancoDetalle);  
                            }else {
                                //$objMensaje->Mensaje("error", "No existe el tercero", $this);
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
                                $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->contabilizarPagoBanco($codigo,$arComprobanteContable,$arCentroCosto,$arTercero,$arPagoBancoDetalle);  
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
