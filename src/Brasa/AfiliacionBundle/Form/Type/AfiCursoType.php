<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class AfiCursoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('entidadEntrenamientoRel', 'entity', array(
                'class' => 'BrasaAfiliacionBundle:AfiEntidadEntrenamiento',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ee')
                    ->orderBy('ee.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))   
            ->add('cursoTipoRel', 'entity', array(
                'class' => 'BrasaAfiliacionBundle:AfiCursoTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ee')
                    ->orderBy('ee.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('fechaVence', 'date', array('format' => 'yyyyMMdd'))
            ->add('fechaProgramacion', 'date', array('format' => 'yyyyMMdd'))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

