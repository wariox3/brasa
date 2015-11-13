<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurPedidoDetalleType extends AbstractType
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
            ->add('modalidadServicioRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurModalidadServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ms')
                    ->orderBy('ms.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('fechaDesde', 'date')
            ->add('fechaHasta', 'date')
            ->add('cantidad', 'number')
            ->add('lunes', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                
            ->add('martes', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                
            ->add('miercoles', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                
            ->add('jueves', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                                
            ->add('viernes', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                                                
            ->add('sabado', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                                                
            ->add('domingo', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                                                
            ->add('festivo', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                                                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

