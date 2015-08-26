<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

/**
 * RhuConfiguracionGeneral controller.
 *
 */
class ConfiguracionGeneralController extends Controller
{
    public function configuracionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        
        $formConfiguracionGeneral = $this->createFormBuilder() 
            ->add('conceptoTipoCuenta', 'choice', array('choices' => array('D' => 'DÉBITO', 'C' => 'CRÉDITO'), 'preferred_choices' => array($arConfiguracionGeneral->getTipoCuenta()),))    
            ->add('cuenta', 'text', array('data' => $arConfiguracionGeneral->getCuenta(), 'required' => true))
            ->add('nit', 'number', array('data' => $arConfiguracionGeneral->getNit(), 'required' => true))
            ->add('dv', 'number', array('data' => $arConfiguracionGeneral->getDv(), 'required' => true))
            ->add('empresa', 'text', array('data' => $arConfiguracionGeneral->getEmpresa(), 'required' => true))    
            ->add('guardar', 'submit', array('label' => 'Actualizar'))
            ->getForm();
        $formConfiguracionGeneral->handleRequest($request);
        if ($formConfiguracionGeneral->isValid()) {
            $controles = $request->request->get('form');                        
            
            $ConceptoTipoCuenta = $controles['conceptoTipoCuenta'];
            $NumeroCuenta = $controles['cuenta'];
            $NumeroNit = $controles['nit'];
            $NumeroDv = $controles['dv'];
            $NombreEmpresa = $controles['empresa'];
            // guardar la tarea en la base de datos
            $arConfiguracionGeneral->setTipoCuenta($ConceptoTipoCuenta);
            $arConfiguracionGeneral->setCuenta($NumeroCuenta);
            $arConfiguracionGeneral->setNit($NumeroNit);
            $arConfiguracionGeneral->setDv($NumeroDv);
            $arConfiguracionGeneral->setEmpresa($NombreEmpresa);
            $em->persist($arConfiguracionGeneral);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_configuracion_general', array('codigoConfiguracionPk' => 1)));
        }
        return $this->render('BrasaRecursoHumanoBundle:ConfiguracionGeneral:Configuracion.html.twig', array(
            'formConfiguracionGeneral' => $formConfiguracionGeneral->createView(),
        ));
    }
    
}
