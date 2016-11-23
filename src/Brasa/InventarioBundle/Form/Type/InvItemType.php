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
            ->add('vrCostoPredeterminado', 'number', array('required' => false))                
            ->add('vrCostoPromedio', 'number', array('required' => true))                    
            ->add('vrPrecioPredeterminado', 'number', array('required' => true))                    
            ->add('porcentajeIva', 'number', array('required' => false))                    
            ->add('cantidadExistencia', 'number', array('required' => true))                    
            ->add('cantidadRemisionada', 'number', array('required' => false))                        
            ->add('cantidadDisponible', 'number', array('required' => true))                        
            ->add('guardar', 'submit')            
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

