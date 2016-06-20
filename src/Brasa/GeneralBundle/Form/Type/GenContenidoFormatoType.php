<?php
namespace Brasa\GeneralBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GenContenidoFormatoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                                                                    
            ->add('titulo', 'text', array('required' => true))
            ->add('nombreFormatoIso', 'text', array('required' => false))
            ->add('version', 'text', array('required' => false))
            ->add('fechaVersion', 'date', array('required' => true))    
            ->add('contenido', 'textarea', array('required' => true))                                
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}
