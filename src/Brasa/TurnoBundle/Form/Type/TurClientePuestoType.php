<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurClientePuestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('nombre', 'text', array('required'  => true))
            ->add('direccion', 'text', array('required'  => false))
            ->add('telefono', 'text', array('required'  => false))
            ->add('celular', 'text', array('required'  => false))
            ->add('contacto', 'text', array('required'  => false))
            ->add('telefonoContacto', 'text', array('required'  => false))
            ->add('celularContacto', 'text', array('required'  => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

