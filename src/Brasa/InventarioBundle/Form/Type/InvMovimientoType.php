<?php
namespace Brasa\InventarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class InvMovimientoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
             ->add('terceroRel', 'entity', array(
                'class' => 'BrasaInventarioBundle:InvTercero',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                    ->orderBy('t.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))    
            ->add('fecha', 'date', array('format' => 'yyyyMMdd'))            
            ->add('guardar', 'submit')
            ->add('cancelar', 'submit', array('label'  => 'Cancelar'))
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

