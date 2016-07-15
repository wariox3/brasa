<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuCreditoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pagoConceptoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'property' => 'nombre',
                'required' => false
            ))    
            ->add('nombre', 'text', array('required' => true))
            ->add('cupoMaximo', 'number', array('required' => false))
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

