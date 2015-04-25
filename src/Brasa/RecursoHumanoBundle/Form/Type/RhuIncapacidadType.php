<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuIncapacidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder           
            ->add('incapacidadTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuIncapacidadTipo',
                'property' => 'nombre',
            ))                
            ->add('numeroEps', 'text', array('required' => true))   
            ->add('fechaDesde', 'datetime')                
            ->add('fechaHasta', 'datetime')  
            ->add('comentarios', 'textarea', array('required' => false))                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

