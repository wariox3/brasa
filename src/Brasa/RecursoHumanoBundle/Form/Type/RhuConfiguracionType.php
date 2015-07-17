<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuConfiguracionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoAuxilioTransporte', 'number', array('required' => true))
            ->add('vrSalario', 'number', array('required' => true))
            ->add('codigoCredito', 'number', array('required' => true))
            ->add('codigoSeguro', 'number', array('required' => true))
            ->add('codigoTiempoSuplementario', 'number', array('required' => true))
            ->add('codigoHoraDiurnaTrabajada', 'number', array('required' => true))
            ->add('codigoAporteSalud', 'number', array('required' => true))
            ->add('codigoAportePension', 'number', array('required' => true))
            ->add('guardar', 'submit', array('label' => 'Actualizar'));
    }

    public function getName()
    {
        return 'form';
    }
}
