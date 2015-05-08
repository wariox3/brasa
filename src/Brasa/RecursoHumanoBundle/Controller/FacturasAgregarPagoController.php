<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class FacturasAgregarPagoController extends Controller
{

    public function listaAction($codigoFactura) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();        
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p";
        $query = $em->createQuery($dql);        
        $arPagos = $paginator->paginate($query, $request->query->get('page', 1), 10);                       
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAgregar')->isClicked()) {
                if (isset($arrControles['TxtHoras'])) {
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intCodigo);
                        if(count($arPagoConcepto) > 0) {
                            //echo $form->get('empleadoRel')->getNormData()->getCodigoEmpleadoPk();
                            if($arPagoConcepto->getComponePorcentaje()) {                                
                                if($arrControles['TxtHoras'][$intIndice] != "" && $arrControles['TxtHoras'][$intIndice] != 0) {
                                    $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                    $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                    $arPagoAdicional->setEmpleadoRel($form->get('empleadoRel')->getData());
                                    $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                    $intHoras = $arrControles['TxtHoras'][$intIndice];
                                    $arPagoAdicional->setCantidad($intHoras);
                                    $em->persist($arPagoAdicional);                                
                                }
                            }
                            if($arPagoConcepto->getComponeValor()) {
                                if($arrControles['TxtValor'][$intIndice] != "" && $arrControles['TxtValor'][$intIndice] != 0) {
                                    $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                    $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                    $arPagoAdicional->setEmpleadoRel($form->get('empleadoRel')->getData());
                                    $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                                    $intValor = $arrControles['TxtValor'][$intIndice];
                                    $arPagoAdicional->setValor($intValor);
                                    $em->persist($arPagoAdicional);                                
                                }
                            }
                        }
                        $intIndice++;
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Facturas:agregarPago.html.twig', array(
            'arPagos' => $arPagos,
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }
}
