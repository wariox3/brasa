<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuLiquidacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                 
            ->add('diasPagados', 'number', array('required' => true))                 
            ->add('guardar', 'submit');
    }
 
    public function getName()
    {
        return 'form';
    }
}

