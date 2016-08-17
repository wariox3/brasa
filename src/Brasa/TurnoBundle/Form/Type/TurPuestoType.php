<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurPuestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('centroCostoContabilidadRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                 
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))    
            ->add('programadorRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurProgramador',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('zonaRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurZona',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('z')
                    ->orderBy('z.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))  
            ->add('operacionRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurOperacion',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('o')
                    ->orderBy('o.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('nombre', 'text', array('required'  => true))
            ->add('direccion', 'text', array('required'  => false))
            ->add('telefono', 'text', array('required'  => false))
            ->add('celular', 'text', array('required'  => false))
            ->add('contacto', 'text', array('required'  => false))
            ->add('telefonoContacto', 'text', array('required'  => false))
            ->add('celularContacto', 'text', array('required'  => false))
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

