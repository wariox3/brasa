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
        $arConsecutivo = new \Brasa\RecursoHumanoBundle\Entity\RhuConsecutivo();
        $arConsecutivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->findAll();
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
        
        $arrayPropiedadesConceptoHoraDiurnaTrabajada = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')                                        
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);                   
        $arrayPropiedadesConceptoHoraDiurnaTrabajada['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoHoraDiurnaTrabajada());                                    
        
        $arrayPropiedadesConceptoAportePension = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')                                        
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);                   
        $arrayPropiedadesConceptoAportePension['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoAportePension());                                    
        
        $arrayPropiedadesConceptoAporteSalud = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')                                        
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);                   
        $arrayPropiedadesConceptoAporteSalud['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoAporteSalud());                                    
        
        $arrayPropiedadesConceptoRiesgoProfesional = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('rp')                                        
                ->orderBy('rp.codigoEntidadRiesgoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);                   
        $arrayPropiedadesConceptoRiesgoProfesional['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional", $arConfiguracion->getCodigoEntidadRiesgoFk());                                    
        
        $arrayPropiedadesConceptoIncapacidad = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')                                        
                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');},
            'property' => 'nombre',
            'required' => false);                   
        $arrayPropiedadesConceptoIncapacidad['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoIncapacidad());
        
        $formConfiguracion = $this->createFormBuilder() 
            ->add('conceptoAuxilioTransporte', 'entity', $arrayPropiedadesConceptoAuxilioTransporte)    
            ->add('vrAuxilioTransporte', 'number', array('data' => $arConfiguracion->getVrAuxilioTransporte(), 'required' => true))
            ->add('vrSalario', 'number', array('data' => $arConfiguracion->getVrSalario(), 'required' => true))
            ->add('conceptoCredito', 'entity', $arrayPropiedadesConceptoCredito, array('required' => true))    
            ->add('conceptoSeguro', 'entity', $arrayPropiedadesConceptoSeguro, array('required' => true))    
            ->add('conceptoTiempoSuplementario', 'entity', $arrayPropiedadesConceptoTiempoSuplementario, array('required' => true))
            ->add('conceptoHoraDiurnaTrabajada', 'entity', $arrayPropiedadesConceptoHoraDiurnaTrabajada, array('required' => true))
            ->add('conceptoAportePension', 'entity', $arrayPropiedadesConceptoAportePension, array('required' => true))
            ->add('conceptoAporteSalud', 'entity', $arrayPropiedadesConceptoAporteSalud, array('required' => true))
            ->add('conceptoRiesgoProfesional', 'entity', $arrayPropiedadesConceptoRiesgoProfesional, array('required' => true))
            ->add('porcentajePensionExtra', 'number', array('data' => $arConfiguracion->getPorcentajePensionExtra(), 'required' => true))    
            ->add('conceptoIncapacidad', 'entity', $arrayPropiedadesConceptoIncapacidad, array('required' => true))    
            ->add('guardar', 'submit', array('label' => 'Actualizar'))
            ->getForm();
        $formConfiguracion->handleRequest($request);
        if ($formConfiguracion->isValid()) {
            $controles = $request->request->get('form');                        
            $codigoConceptoAuxilioTransporte = $controles['conceptoAuxilioTransporte'];
            $ValorAuxilioTransporte = $controles['vrAuxilioTransporte'];
            $porcentajePensionExtra = $controles['porcentajePensionExtra'];
            $ValorSalario = $controles['vrSalario'];
            $codigoConceptoCredito = $controles['conceptoCredito'];
            $codigoConceptoIncapacidad = $controles['conceptoIncapacidad'];
            $codigoConceptoSeguro = $controles['conceptoSeguro'];
            $codigoConceptoTiempoSuplementario = $controles['conceptoTiempoSuplementario'];
            $codigoConceptoHoraDiurnaTrabajada = $controles['conceptoHoraDiurnaTrabajada'];
            $codigoConceptoAportePension = $controles['conceptoAportePension'];
            $codigoConceptoAporteSalud = $controles['conceptoAporteSalud'];
            $codigoConceptoRiesgoProfesional = $controles['conceptoRiesgoProfesional'];
            // guardar la tarea en la base de datos
            $arConfiguracion->setCodigoAuxilioTransporte($codigoConceptoAuxilioTransporte);
            $arConfiguracion->setVrAuxilioTransporte($ValorAuxilioTransporte);
            $arConfiguracion->setPorcentajePensionExtra($porcentajePensionExtra);
            $arConfiguracion->setVrSalario($ValorSalario);
            $arConfiguracion->setCodigoCredito($codigoConceptoCredito);
            $arConfiguracion->setCodigoIncapacidad($codigoConceptoIncapacidad);
            $arConfiguracion->setCodigoSeguro($codigoConceptoSeguro);
            $arConfiguracion->setCodigoTiempoSuplementario($codigoConceptoTiempoSuplementario);
            $arConfiguracion->setCodigoHoraDiurnaTrabajada($codigoConceptoHoraDiurnaTrabajada);
            $arConfiguracion->setCodigoAportePension($codigoConceptoAportePension);
            $arConfiguracion->setCodigoAporteSalud($codigoConceptoAporteSalud);
            $arConfiguracion->setCodigoEntidadRiesgoFk($codigoConceptoRiesgoProfesional);
            
            $arrControles = $request->request->All();
            $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        $arConsecutivo = new \Brasa\RecursoHumanoBundle\Entity\RhuConsecutivo();
                        $arConsecutivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->find($intCodigo);
                        if(count($arConsecutivo) > 0) {                                            
                                $intConsecutivo = $arrControles['TxtConsecutivo'.$intCodigo];
                                $arConsecutivo->setConsecutivo($intConsecutivo);
                                $em->persist($arConsecutivo);
                            
                        }
                        $intIndice++;
                    }
            $em->persist($arConfiguracion);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_configuracion_nomina', array('codigoConfiguracionPk' => 1)));
        }
        return $this->render('BrasaRecursoHumanoBundle:Configuracion:Configuracion.html.twig', array(
            'formConfiguracion' => $formConfiguracion->createView(),
            'arConsecutivo' => $arConsecutivo
        ));
    }
    
}
