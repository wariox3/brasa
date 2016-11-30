<?php
namespace Brasa\InventarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class InvItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                  
            ->add('nombre', 'text', array('required' => true))            
            ->add('porcentajeIva', 'number', array('required' => false))
            ->add('vrCostoPredeterminado', 'number', array('required' => false))                                                                        
            ->add('guardar', 'submit')            
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

