<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuExamenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder           
            ->add('entidadExamenRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadExamen',
                'property' => 'nombre',
            ))
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'property' => 'nombre',
            ))   
            ->add('fecha', 'date')                                                                           
            ->add('identificacion', 'number', array('required' => true))
            ->add('nombreCorto', 'text', array('required' => true))
            ->add('estadoAprobado', 'choice', array('choices' => array('0' =>'NO', '1' => 'SI')))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

