<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TurClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('nit', 'number', array('required' => true))
            ->add('nombreCorto', 'text', array('required' => true))  
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

