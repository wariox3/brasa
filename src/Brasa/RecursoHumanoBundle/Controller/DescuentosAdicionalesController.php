<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DescuentosAdicionalesController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $form = $this->createFormBuilder()
            ->add('Generar', 'submit')
            ->getForm();
        $form->handleRequest($request);        
        
        $arCentrosCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentrosCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();                      

        return $this->render('BrasaRecursoHumanoBundle:DescuentosAdicionales:lista.html.twig', array(
            'arCentrosCostos' => $arCentrosCostos,
            'form' => $form->createView()));
    }       
    
    public function detalleAction($codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');        
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $form = $this->createFormBuilder()
            ->add('BtnRetirarConcepto', 'submit', array('label'  => 'Retirar',))
            ->add('BtnConceptoPermanente', 'submit', array('label'  => 'Permanente',))                
            ->add('BtnRetirarLicencia', 'submit', array('label'  => 'Retirar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnRetirarConcepto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoDescuentoAdicional) {
                        $arDescuentoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional();
                        $arDescuentoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuDescuentoAdicional')->find($codigoDescuentoAdicional);
                        $em->remove($arDescuentoAdicional);                        
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_descuentos_adicionales_detalle', array('codigoCentroCosto' => $codigoCentroCosto)));
                }
            }
            if($form->get('BtnConceptoPermanente')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoDescuentoAdicional) {
                        $arDescuentoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional();
                        $arDescuentoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuDescuentoAdicional')->find($codigoDescuentoAdicional);
                        if($arDescuentoAdicional->getPermanente() == 1) {
                            $arDescuentoAdicional->setPermanente(0);
                        } else {
                            $arDescuentoAdicional->setPermanente(1);
                        }
                        $em->persist($arDescuentoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_descuentos_adicionales_detalle', array('codigoCentroCosto' => $codigoCentroCosto)));
                }
            }
            if($form->get('BtnRetirarLicencia')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarLicencia');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoLicencia) {
                        $arLicencia = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
                        $arLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->find($codigoLicencia);
                        $em->remove($arLicencia);                        
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_descuentos_adicionales_detalle', array('codigoCentroCosto' => $codigoCentroCosto)));
                }
            }            
        }
        $arDescuentosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional();
        $arDescuentosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuDescuentoAdicional')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto, 'descuentoAplicado' => 0));        
        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto));        
        return $this->render('BrasaRecursoHumanoBundle:DescuentosAdicionales:detalle.html.twig', array(
                    'arCentroCosto' => $arCentroCosto,
                    'arDescuentosAdicionales' => $arDescuentosAdicionales,
                    'arLicencias' => $arLicencias,
                    'form' => $form->createView()
                    ));
    }        
 
    public function generarMasivoDetalleAction($codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');
        
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);               
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaDQL('', $codigoCentroCosto, 1, "", 0));
        $arEmpleados = $paginator->paginate($query, $request->query->get('page', 1), 50);        
        $form = $this->createFormBuilder()
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnGenerar')->isClicked()) {
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {                        
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($intCodigo);
                    if(count($arEmpleado) > 0) {                                                                                        
                        if($arrControles['TxtValor'][$intIndice] != "" && $arrControles['TxtValor'][$intIndice] != 0) {
                            $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                            $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(21);
                            $arDescuentoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional();
                            $arDescuentoAdicional->setPagoConceptoRel($arPagoConcepto);
                            $arDescuentoAdicional->setEmpleadoRel($arEmpleado);
                            $arDescuentoAdicional->setCentroCostoRel($arCentroCosto);                                    
                            $arDescuentoAdicional->setValor($arrControles['TxtValor'][$intIndice]);
                            $em->persist($arDescuentoAdicional);                                
                        }                                                      
                    }
                    $intIndice++;
                }
                $em->flush();                                    
            }
        }        
        
        return $this->render('BrasaRecursoHumanoBundle:DescuentosAdicionales:generarMasivoDetalle.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }    
}
