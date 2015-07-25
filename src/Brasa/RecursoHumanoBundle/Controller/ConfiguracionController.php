<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

/**
 * RhuConfiguracion controller.
 *
 */
class ConfiguracionController extends Controller
{
    public function configuracionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);

        $arrayPropiedadesConceptoAuxilioTransporte = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')                                        
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);                   
        $arrayPropiedadesConceptoAuxilioTransporte['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoAuxilioTransporte());                                    
        
        $arrayPropiedadesConceptoCredito = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')                                        
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);                   
        $arrayPropiedadesConceptoCredito['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoCredito());                                    
        
        $arrayPropiedadesConceptoSeguro = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')                                        
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);                   
        $arrayPropiedadesConceptoSeguro['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoSeguro());                                    
        
        $arrayPropiedadesConceptoTiempoSuplementario = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')                                        
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);                   
        $arrayPropiedadesConceptoTiempoSuplementario['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoTiempoSuplementario());                                    
        
        $formConfiguracion = $this->createFormBuilder() 
            ->add('ConceptoAuxilioTransporte', 'entity', $arrayPropiedadesConceptoAuxilioTransporte)    
            ->add('vrAuxilioTransporte', 'number', array('data' => $arConfiguracion->getVrAuxilioTransporte(), 'required' => true))
            ->add('vrSalario', 'number', array('data' => $arConfiguracion->getVrSalario(), 'required' => true))
            ->add('conceptoCredito', 'entity', $arrayPropiedadesConceptoCredito)    
            ->add('conceptoSeguro', 'entity', $arrayPropiedadesConceptoSeguro)    
            ->add('conceptoTiempoSuplementario', 'entity', $arrayPropiedadesConceptoTiempoSuplementario)
            ->add('codigoHoraDiurnaTrabajada', 'number', array('required' => true))
            ->add('codigoAporteSalud', 'number', array('required' => true))
            ->add('codigoAportePension', 'number', array('required' => true))
            ->add('guardar', 'submit', array('label' => 'Actualizar'))
            ->getForm();
        $formConfiguracion->handleRequest($request);
        if ($formConfiguracion->isValid()) {
            $controles = $request->request->get('form');                        
            $codigoConceptoAuxilioTransporte = $controles['ConceptoAuxilioTransporte'];
            $ValorAuxilioTransporte = $controles['vrAuxilioTransporte'];
            $ValorSalario = $controles['vrSalario'];
            $codigoConceptoCredito = $controles['ConceptoCredito'];
            $codigoConceptoSeguro = $controles['ConceptoSeguro'];
            $codigoConceptoTiempoSuplementario = $controles['conceptoTiempoSuplementario'];
            // guardar la tarea en la base de datos
            $arConfiguracion->setCodigoAuxilioTransporte($codigoConceptoAuxilioTransporte);
            $arConfiguracion->setVrAuxilioTransporte($ValorAuxilioTransporte);
            $arConfiguracion->setVrSalario($ValorSalario);
            $arConfiguracion->setCodigoCredito($codigoConceptoCredito);
            $arConfiguracion->setCodigoSeguro($codigoConceptoSeguro);
            $arConfiguracion->setCodigoTiempoSuplementario($codigoConceptoTiempoSuplementario);
            /*$arConfiguracion->setCodigoCredito($arConfiguracion->getCodigoCredito());
            $arConfiguracion->setCodigoSeguro($arConfiguracion->getCodigoSeguro());
            $arConfiguracion->setCodigoTiempoSuplementario($arConfiguracion->getCodigoTiempoSuplementario());
            $arConfiguracion->setCodigoHoraDiurnaTrabajada($arConfiguracion->getCodigoHoraDiurnaTrabajada());
            $arConfiguracion->setCodigoAporteSalud($arConfiguracion->getCodigoAporteSalud());
            $arConfiguracion->setCodigoAportePension($arConfiguracion->getCodigoAportePension());
            $arConfiguracion->setVrAuxilioTransporte($arConfiguracion->getVrAuxilioTransporte());
             * 
             */
            $em->persist($arConfiguracion);
            $em->flush();
        }
        return $this->render('BrasaRecursoHumanoBundle:Configuracion:Configuracion.html.twig', array(
            'formConfiguracion' => $formConfiguracion->createView(),
        ));
    }
    
}
