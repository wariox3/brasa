<?php
namespace Brasa\TransporteBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TteDespachosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('despachoTipoRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteDespachosTipos',
                'property' => 'nombre',
            ))                
            ->add('ciudadOrigenRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudades',
                'property' => 'nombre',
            ))                
            ->add('ciudadDestinoRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudades',
                'property' => 'nombre',
            )) 
            ->add('rutaRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteRutas',
                'property' => 'nombre',
            ))                 
            ->add('conductorRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteConductores',
                'property' => 'nombreCorto',
            ))
            ->add('vehiculoRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteVehiculos',
                'property' => 'placa',
            ))                                 
            ->add('vrFlete', 'text')
            ->add('vrAnticipo', 'text')                                                
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

