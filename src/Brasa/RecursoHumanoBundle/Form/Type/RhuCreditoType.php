<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuCreditoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder           
            ->add('creditoTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCreditoTipo',
                'property' => 'nombre',
            ))                
            ->add('vrPagar', 'number', array('required' => true))                                                                           
            ->add('numeroCuotas', 'number', array('required' => true))
            ->add('tipoPago', 'choice', array('choices' => array('' => 'Seleccione..', 'Nomina' => 'Nomina','Otro'=>'Otro')))    
            ->add('comentarios', 'textarea', array('required' => false))                                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

