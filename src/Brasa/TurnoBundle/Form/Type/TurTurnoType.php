<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TurTurnoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoTurnoPk', 'text', array('required' => true))
            ->add('nombre', 'text', array('required' => true))            
            ->add('horaDesde', 'time', array('required' => true))
            ->add('horaHasta', 'time', array('required' => true))
            ->add('horas', 'number', array('required' => true))                
            ->add('horasDiurnas', 'number', array('required' => true))                
            ->add('horasNocturnas', 'number', array('required' => true))                
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

