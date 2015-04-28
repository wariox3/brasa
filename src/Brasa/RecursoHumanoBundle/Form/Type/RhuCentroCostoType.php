<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuCentroCostoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder           
            ->add('periodoPagoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPeriodoPago',
                'property' => 'nombre',
            ))                
            ->add('nombre', 'text', array('required' => true))   
            ->add('fechaUltimoPagoProgramado', 'date')
            ->add('horaPagoAutomatico', 'time')                
            ->add('estadoActivo', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('generarPagoAutomatico', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('comentarios', 'textarea', array('required' => false))                                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

