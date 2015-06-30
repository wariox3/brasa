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
            ->add('suspension', 'text', array('required' => false))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

