<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurClientePuestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('required'  => true))
            ->add('telefono', 'text', array('required'  => false))
            ->add('celular', 'text', array('required'  => false))
            ->add('contacto', 'text', array('required'  => false))
            ->add('telefonoContacto', 'text', array('required'  => false))
            ->add('celularContacto', 'text', array('required'  => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

