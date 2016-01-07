<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuHorarioAccesoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('tipoAccesoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuTipoAcceso',
                'property' => 'nombre',
                'required' => true))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

