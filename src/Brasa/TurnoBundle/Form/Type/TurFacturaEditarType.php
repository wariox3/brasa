<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TurFacturaEditarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                             
            ->add('numero', 'number', array('required' => true))                            
            ->add('fecha', 'date', array('format' => 'yyyyMMdd')) 
            ->add('fechaVence', 'date', array('format' => 'yyyyMMdd')) 
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

