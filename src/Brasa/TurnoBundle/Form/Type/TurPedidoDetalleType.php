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
            ->add('puestoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPuesto',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                    ->where('p.codigoClienteFk = :codigoCliente ')
                    ->setParameter('codigoCliente', $options['data']->getPedidoRel()->getCodigoClienteFk())
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))    
            ->add('proyectoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurProyecto',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                    ->where('p.codigoClienteFk = :codigoCliente ')
                    ->setParameter('codigoCliente', $options['data']->getPedidoRel()->getCodigoClienteFk())
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false))                            
            ->add('conceptoServicioRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurConceptoServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('cs')
                    ->orderBy('cs.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))              
            ->add('modalidadServicioRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurModalidadServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ms')
                    ->orderBy('ms.nombre', 'DESC');},
                'property' => 'nombre',
                'required' => true))  
            ->add('periodoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPeriodo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.codigoPeriodoPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))                            
            ->add('cantidad', 'number')
            ->add('diaDesde', 'number')
            ->add('diaHasta', 'number')
            ->add('lunes', 'checkbox', array('required'  => false))
            ->add('martes', 'checkbox', array('required'  => false))
            ->add('miercoles', 'checkbox', array('required'  => false))
            ->add('jueves', 'checkbox', array('required'  => false))
            ->add('viernes', 'checkbox', array('required'  => false))
            ->add('sabado', 'checkbox', array('required'  => false))
            ->add('domingo', 'checkbox', array('required'  => false))
            ->add('festivo', 'checkbox', array('required'  => false))                                              
            ->add('dia31', 'checkbox', array('required'  => false))                            
            ->add('liquidarDiasReales', 'checkbox', array('required'  => false))                            
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

