<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ProcesoContabilizarPagoController extends Controller
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
                    $arComprobanteContable =$em->getRepository('BrasaContabilidadBundle:CtbComprobanteContable')->find(8);
                    $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();                    
                    $arCentroCosto =$em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find(1);                           
                    foreach ($arrSeleccionados AS $codigo) {                                     
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigo);
                        if($arPago->getEstadoContabilizado() == 0) {
                            $arTercero = $em->getRepository('BrasaGeneralBundle:GenTercero')->findOneBy(array('nit' => $arPago->getEmpleadoRel()->getNumeroIdentificacion()));
                            if(count($arTercero) > 0) {
                                $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigo));
                                foreach ($arPagoDetalles as $arPagoDetalle) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arPagoDetalle->getPagoConceptoRel()->getCodigoCuentaFk());                            
                                    $arRegistro->setComprobanteContableRel($arComprobanteContable);
                                    $arRegistro->setCentroCostosRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arPago->getNumero());
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
                                $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find('281505');                            
                                $arRegistro->setComprobanteContableRel($arComprobanteContable);
                                $arRegistro->setCentroCostosRel($arCentroCosto);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setNumero($arPago->getNumero());
                                $arRegistro->setFecha($arPago->getFechaDesde());
                                $arRegistro->setCredito($arPago->getVrNeto() );                            
                                $arRegistro->setDescripcionContable('NOMINA POR PAGAR');
                                $em->persist($arRegistro);  
                                $arPago->setEstadoContabilizado(1);
                                $em->persist($arPago);  
                            } else {
                                $arTercero = new \Brasa\GeneralBundle\Entity\GenTercero();
                                $arTercero->setNombreCorto($arPago->getEmpleadoRel()->getNombreCorto());
                                $arTercero->setNit($arPago->getEmpleadoRel()->getNumeroIdentificacion());
                                $em->persist($arTercero);                                
                            }                                                    
                        }
                    }
                    $em->flush();
                }
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
    
}
