<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuSeleccionReferenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                
            ->add('nombreCorto', 'text', array('required' => true))                            
            ->add('telefono', 'text', array('required' => false))
            ->add('celular', 'text', array('required' => false))
            ->add('direccion', 'text', array('required' => false))                            
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

