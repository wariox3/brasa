<?php

namespace Brasa\RecursoHumanoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RhuCentroCostoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('codigoPeriodoPagoFk')
            ->add('fechaUltimoPagoProgramado')
            ->add('pagoAbierto')
            ->add('periodoPagoRel')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brasa_recursohumanobundle_rhucentrocosto';
    }
}
