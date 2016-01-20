<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuHorarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('required' => true))
            ->add('horaEntrada', 'time', array('required' => true))
            ->add('horaSalida', 'time', array('required' => true))
            ->add('generaHoraExtra', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))     
                
            ->add('guardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
