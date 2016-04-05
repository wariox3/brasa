<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurNovedadTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder       
            ->add('turnoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurTurno',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('t')
                    ->orderBy('t.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                 
            ->add('nombre', 'text', array('required' => true))  
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

