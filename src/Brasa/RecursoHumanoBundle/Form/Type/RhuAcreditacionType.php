<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuAcreditacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('acreditacionTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAcreditacionTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('at')
                    ->orderBy('at.codigoAcreditacionTipoPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('acreditacionRechazoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAcreditacionRechazo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ar')
                    ->orderBy('ar.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false))                            
            ->add('academiaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAcademia',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                                  
            ->add('estadoRechazado', 'checkbox', array('required'  => false))                            
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('numeroRegistro', 'text', array('required' => false))                            
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

