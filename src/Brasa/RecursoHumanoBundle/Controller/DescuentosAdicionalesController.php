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
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoCentroCosto' => $codigoCentroCosto)));
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
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoCentroCosto' => $codigoCentroCosto)));
                }
            }
            if($form->get('BtnRetirarIncapacidad')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarIncapacidad');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoIncapacidad) {
                        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                        $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
                        $em->remove($arIncapacidad);                        
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoCentroCosto' => $codigoCentroCosto)));
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
                  
}
