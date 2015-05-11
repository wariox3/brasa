<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuLicenciaType;

class DescuentosAdicionalesAgregarLicenciaController extends Controller
{

    public function nuevoAction($codigoCentroCosto) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $arLicencia = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();       
        $arLicencia->setFechaDesde(new \DateTime('now'));
        $arLicencia->setFechaHasta(new \DateTime('now'));    
        $arLicencia->setCentroCostoRel($arCentroCosto);
        $form = $this->createForm(new RhuLicenciaType(), $arLicencia); 
                    
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arLicencia = $form->getData();                          
            $intDias = $arLicencia->getFechaDesde()->diff($arLicencia->getFechaHasta());
            $intDias = $intDias->format('%a');
            $intDias = $intDias + 1; 
            $arLicencia->setCantidad($intDias);
            $arLicencia->setCantidadPendiente($intDias);
            $em->persist($arLicencia);
            $em->flush();                        
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_descuentos_adicionales_agregar_licencia', array('codigoCentroCosto' => $codigoCentroCosto)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:DescuentosAdicionales:agregarLicencia.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'form' => $form->createView()));
    }
}
