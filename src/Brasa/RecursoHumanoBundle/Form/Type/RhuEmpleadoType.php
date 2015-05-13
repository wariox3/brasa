<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuEmpleadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoIdentificacionRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuTipoIdentificacion',
                'property' => 'nombre',
            ))
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'property' => 'nombre',
            ))
            ->add('bancoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuBanco',
                'property' => 'nombre',
            ))                
            ->add('entidadSaludRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'property' => 'nombre',
            ))                
            ->add('entidadPensionRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadPension',
                'property' => 'nombre',
            ))  
            ->add('clasificacionRiesgoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuClasificacionRiesgo',
                'property' => 'nombre',
            ))  
            ->add('tipoTiempoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuTipoTiempo',
                'property' => 'nombre',
            ))                
            ->add('nombreCorto', 'text', array('required' => true))
            ->add('nombre1', 'text', array('required' => true))
            ->add('nombre2', 'text', array('required' => false))
            ->add('apellido1', 'text', array('required' => true))
            ->add('apellido2', 'text', array('required' => false))
            ->add('telefono', 'text', array('required' => false))
            ->add('celular', 'text', array('required' => false))
            ->add('direccion', 'text', array('required' => false))
            ->add('cuenta', 'text', array('required' => true))
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('vrSalario', 'number', array('required' => true))
            ->add('auxilioTransporte', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('pagadoEntidadSalud', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

