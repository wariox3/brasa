<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuTurnoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoTurnoPk', 'text', array('required' => true))
            ->add('nombre', 'text', array('required' => true))
            ->add('horaDesde', 'time', array('required' => true))
            ->add('horaHasta', 'time', array('required' => true))
            ->add('horas', 'text', array('required' => true))    
            ->add('horasDiurnas', 'text', array('required' => true))        
            ->add('horasNocturnas', 'text', array('required' => true))        
            ->add('novedad', 'text', array('required' => false))        
            ->add('descanso', 'text', array('required' => false))        
            ->add('comentarios', 'textarea', array('required' => false))        
            ->add('guardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
