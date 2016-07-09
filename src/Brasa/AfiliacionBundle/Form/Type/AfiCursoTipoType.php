<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class AfiCursoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('proveedorRel', 'entity', array(
                'class' => 'BrasaAfiliacionBundle:AfiProveedor',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ee')
                    ->orderBy('ee.codigoProveedorPk', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))                 
            ->add('nombre', 'text', array('required' => true))                          
            ->add('precio', 'number', array('required' => true))              
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

