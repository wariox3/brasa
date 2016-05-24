<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class AfiNovedadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('novedadTipoRel', 'entity', array(
                'class' => 'BrasaAfiliacionBundle:AfiNovedadTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('nt')
                    ->orderBy('nt.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                                             
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                            
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                       
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

