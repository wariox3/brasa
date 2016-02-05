<?php
namespace Brasa\GeneralBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class GenTareaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder                                        
            ->add('asunto', 'text', array('required' => true))   
            ->add('fecha', 'date', array('format' => 'yyyyMMMMdd'))                
            ->add('hora', 'time')
            ->add('comentarios', 'textarea', array('required' => false))                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

