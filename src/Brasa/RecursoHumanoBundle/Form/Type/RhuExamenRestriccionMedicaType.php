<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuExamenRestriccionMedicaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('examenRevisionMedicaTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuExamenRevisionMedicaTipo',
                'property' => 'nombre',
            ))
            ->add('dias', 'number', array('required' => true))    
            //->add('comentarios', 'textarea', array('required' => false))
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}

