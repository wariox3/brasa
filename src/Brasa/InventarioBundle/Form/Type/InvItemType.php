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
            ->add('vrCostoPredeterminado', 'number', array('required' => true))                
            ->add('vrCostoPromedio', 'number', array('required' => true))                    
            ->add('vrPrecioPredeterminado', 'number', array('required' => true))                    
            ->add('porcentajeIva', 'number', array('required' => true))                    
            ->add('cantidadExistencia', 'number', array('required' => true))                    
            ->add('cantidadRemisionada', 'number', array('required' => true))                        
            ->add('cantidadDisponible', 'number', array('required' => true))                        
            ->add('guardar', 'submit')
            ->add('cancelar', 'submit', array('label'  => 'Cancelar'))
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

