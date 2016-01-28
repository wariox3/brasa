<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TurProspectoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('nit', 'number', array('required' => true))
            ->add('nombreCorto', 'text', array('required' => true))  
            ->add('estrato', 'text', array('required' => false))                                   
            ->add('contacto', 'text', array('required' => false))                  
            ->add('celularContacto', 'text', array('required' => false))  
            ->add('telefonoContacto', 'text', array('required' => false))  
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

