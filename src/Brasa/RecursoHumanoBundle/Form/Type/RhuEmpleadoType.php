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
            ->add('nombreCorto', 'text', array('required' => true))                           
            ->add('numeroIdentificacion', 'text', array('required' => true))                                           
            ->add('vrSalario', 'number', array('required' => true))                                                           
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

