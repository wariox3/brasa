<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDescuentoAdicionalType;

class DescuentosAdicionalesAgregarConceptoController extends Controller
{
    public function nuevoAction($codigoCentroCosto) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $arDescuentoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional();           
        $arDescuentoAdicional->setCentroCostoRel($arCentroCosto);
        $form = $this->createForm(new RhuDescuentoAdicionalType(), $arDescuentoAdicional); 
                    
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arDescuentoAdicional = $form->getData(); 
            $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(21);
            $arDescuentoAdicional->setPagoConceptoRel($arPagoConcepto);
            $em->persist($arDescuentoAdicional);
            $em->flush();                        
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_descuentos_adicionales_agregar_concepto', array('codigoCentroCosto' => $codigoCentroCosto)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:DescuentosAdicionales:agregarConcepto.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'form' => $form->createView()));
    }
}
