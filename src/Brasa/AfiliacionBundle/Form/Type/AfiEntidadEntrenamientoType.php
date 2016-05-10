<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class AfiEntidadEntrenamientoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('nit', 'text', array('required' => false))
            ->add('digitoVerificacion', 'text', array('required' => false))                
            ->add('nombreCorto', 'text', array('required' => true))                          
            ->add('direccion', 'text', array('required' => false))  
            ->add('telefono', 'text', array('required' => false))                              
            ->add('celular', 'text', array('required' => false))                                          
            ->add('email', 'text', array('required' => false))                              
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

