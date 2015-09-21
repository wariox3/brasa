<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuBancoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('required' => true))
            ->add('nit', 'text', array('required' => true))    
            ->add('convenioNomina', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('numeroDigitos', 'number', array('required' => true))
            ->add('codigoGeneral', 'text', array('required' => true))
            ->add('telefono', 'text', array('required' => false))
            ->add('direccion', 'text', array('required' => false))
            ->add('guardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
