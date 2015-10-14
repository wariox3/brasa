<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuPagoConceptoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('required' => true))
            ->add('componeSalario', 'text', array('required' => false))
            ->add('componePorcentaje', 'text', array('required' => false))
            ->add('componeValor', 'text', array('required' => false))
            ->add('porPorcentaje', 'number', array('required' => true))
            ->add('prestacional', 'text', array('required' => false))
            ->add('operacion', 'number', array('required' => true))
            ->add('conceptoAdicion', 'text', array('required' => false))
            ->add('conceptoIncapacidad', 'text', array('required' => false))
            ->add('conceptoAuxilioTransporte', 'text', array('required' => false))
            ->add('codigoCuentaFk', 'number', array('required' => true))
            ->add('tipoCuenta', 'number', array('required' => true))    
            ->add('conceptoPension', 'text', array('required' => false))
            ->add('conceptoSalud', 'text', array('required' => false))
            ->add('tipoAdicional', 'number', array('required' => true))
            ->add('guardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
