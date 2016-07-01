<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TurConceptoServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('required' => true))            
            ->add('nombreFacturacion', 'text', array('required' => true))            
            ->add('tipo', 'number')
            ->add('porBaseIva', 'number')
            ->add('porIva', 'number')
            ->add('horas', 'number', array('required' => true))                
            ->add('horasDiurnas', 'number', array('required' => true))                
            ->add('horasNocturnas', 'number', array('required' => true))
            ->add('vrCosto', 'number')                 
            //->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

