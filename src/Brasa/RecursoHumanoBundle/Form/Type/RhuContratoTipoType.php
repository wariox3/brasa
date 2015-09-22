<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuContratoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                                                                    
            ->add('titulo', 'text', array('required' => true))                                
            ->add('contenido', 'textarea', array('required' => true))                                
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}
