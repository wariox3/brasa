<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuVacacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                        
            
            ->add('fechaDesdePeriodo','date',array('widget' => 'single_text', 'format' => 'yyyy/MM/dd','attr' => array('class' => 'date', 'disabled' => 'disabled')))
            ->add('fechaHastaPeriodo','date',array('widget' => 'single_text', 'format' => 'yyyy/MM/dd', 'attr' => array('class' => 'date', 'disabled' => 'disabled')))
            ->add('comentarios', 'textarea', array('required' => false))    
            ->add('guardar', 'submit');
    }
 
    public function getName()
    {
        return 'form';
    }
}

