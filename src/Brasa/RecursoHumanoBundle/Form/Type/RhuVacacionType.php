<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuVacacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('diasDisfrutados', 'number', array('required' => true))                 
            ->add('diasPagados', 'number', array('required' => true))                 
            ->add('fechaDesdeDisfrute', 'date')
            ->add('fechaHastaDisfrute', 'date')
            ->add('vrPromedioRecargoNocturno', 'number')                
            ->add('comentarios', 'textarea', array('required' => false))    
            ->add('ver', 'submit')
            ->add('guardar', 'submit');
    }
 
    public function getName()
    {
        return 'form';
    }
}

