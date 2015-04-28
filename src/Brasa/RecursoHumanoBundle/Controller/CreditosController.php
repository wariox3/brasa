<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCreditoType;

class CreditosController extends Controller
{    
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        $form->handleRequest($request);        
        
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();        
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuCredito c";
        $query = $em->createQuery($dql);        
        $arCreditos = $paginator->paginate($query, $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Creditos:lista.html.twig', array(
            'arCreditos' => $arCreditos,
            'form' => $form->createView()
            ));
    }     
    
    public function nuevoAction($codigoCredito) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito(); 
        if($codigoCredito != 0) {
            $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
        }
        $form = $this->createForm(new RhuCreditoType(), $arCredito);       
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arCredito = $form->getData();
            $douVrPagar = $form->get('vrPagar')->getData();
            $intCuotas = $form->get('numeroCuotas')->getData();
            $douVrCuota = $douVrPagar / $intCuotas;
            $arCredito->setVrCuota($douVrCuota);
            $arCredito->setNumeroCuotaActual(0);
            $em->persist($arCredito);
            $em->flush();                            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_creditos_nuevo', array('codigoCredito' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_creditos_lista'));
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Creditos:nuevo.html.twig', array(
            'arCredito' => $arCredito,
            'form' => $form->createView()));
    }            
}
