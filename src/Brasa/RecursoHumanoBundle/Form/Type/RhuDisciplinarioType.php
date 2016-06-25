<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuDisciplinarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('disciplinarioTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDisciplinarioTipo',
                'property' => 'nombre',
            ))               
            ->add('asunto', 'textarea', array('required' => false))
            ->add('descargos', 'textarea', array('required' => false))
            ->add('fechaAplicaProceso', 'text', array('required' => false))
            ->add('diasSuspencion', 'text', array('required' => false)) 
            ->add('reentrenamiento', 'choice', array('choices'   => array('0' => 'NO', '1' => 'SI'))) 
            ->add('puesto', 'text', array('required' => false))
            ->add('zona', 'text', array('required' => false))
            ->add('operacion', 'text', array('required' => false))    
            ->add('fechaAplicaHastaProceso', 'text', array('required' => false))
            ->add('fechaIngresoTrabajo', 'text', array('required' => false))    
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

