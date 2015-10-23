<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuCapacitacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                 
            ->add('fecha', 'date')            
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

