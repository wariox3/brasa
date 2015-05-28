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
    
    public function nuevoAction($codigoCredito, $codigoEmpleado) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito(); 
        if($codigoCredito != 0) {
            $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
        } else {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        }
        $form = $this->createForm(new RhuCreditoType(), $arCredito);       
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arCredito = $form->getData();
            $douVrPagar = $form->get('vrPagar')->getData();
            $intCuotas = $form->get('numeroCuotas')->getData();
            $douVrCuota = $douVrPagar / $intCuotas;
            $arCredito->setVrCuota($douVrCuota);
            $arCredito->setSaldo($douVrPagar);
            $arCredito->setNumeroCuotaActual(0);
            $arCredito->setEmpleadoRel($arEmpleado);
            $em->persist($arCredito);
            $em->flush();                            
            echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";                
        }                

        return $this->render('BrasaRecursoHumanoBundle:Creditos:nuevo.html.twig', array(
            'arCredito' => $arCredito,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoCreditoPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()    
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        $codigoCreditoFk = $codigoCreditoPk;
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCreditoPk);
        $arCreditoPago = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
        $arCreditoPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoPago')->findBy(array('codigoCreditoFk' => $codigoCreditoFk));
        if($form->isValid()) {
                      
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoHojaVida = new \Brasa\RecursoHumanoBundle\Formatos\FormatoHojaVida();
                $objFormatoHojaVida->Generar($this, $codigoCreditoFk);
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Creditos:detalle.html.twig', array(
                    'arCreditoPago' => $arCreditoPago,
                    'arCreditos' => $arCreditos,
                    'form' => $form->createView()
                    ));
    }
    
    public function nuevoDetalleAction($codigoCreditoPk) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
        $form = $this->createFormBuilder()
            ->add('codigoCreditoFk', 'text', array('data' => $codigoCreditoPk, 'attr' => array('readonly' => 'readonly')))
            ->add('vrCuota','text')
            ->add('tipoPago','hidden', array('data' => 'ABONO'))    
            ->add('save', 'submit', array('label' => 'Guardar'))    
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
            $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCreditoPk);
            $saldoA = $arCredito->getSaldo();
            $Abono = $form->get('vrCuota')->getData();
            if ($Abono > $saldoA)
            {
                echo "El Abono no puede ser superior al Saldo del Credito";
            }
            else
            {    
                $saldoA = $saldoA - $Abono;
                $arCredito->setSaldo($saldoA - $Abono);
                                    if ($arCredito->getSaldo() <= 0)
                                    {
                                       $arCredito->setEstadoPagado(1); 
                                    }        
                                    
                $arPagoCredito->setcodigoCreditoFk($form->get('codigoCreditoFk')->getData());
                $arPagoCredito->setvrCuota($form->get('vrCuota')->getData());
                $arPagoCredito->setfechaPago(new \ DateTime("now"));
                $arPagoCredito->settipoPago('ABONO');
                $em->persist($arPagoCredito);
                $em->persist($arCredito);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
            }    
                
        }                
        return $this->render('BrasaRecursoHumanoBundle:Creditos:nuevoDetalle.html.twig', array(
            'arPagoCredito' => $arPagoCredito,
            'form' => $form->createView()));
    }
    
}
