<?php
namespace Brasa\TransporteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TteNovedadesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('novedadConceptoRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteNovedadesConceptos',
                'property' => 'nombre',
            ))            
            ->add('novedad', 'textarea')
            ->add('solucion', 'textarea', array('required' => false))
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'frmNovedad';
    }
}

