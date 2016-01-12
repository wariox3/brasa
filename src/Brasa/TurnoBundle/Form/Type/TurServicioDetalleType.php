<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurServicioDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {       
        $builder
            ->add('puestoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPuesto',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                    ->where('p.codigoClienteFk = :codigoCliente ')
                    ->setParameter('codigoCliente', $options['data']->getServicioRel()->getCodigoClienteFk())
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false))                
            ->add('turnoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurTurno',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('t')
                    ->where('t.servicio = :servicio ')
                    ->setParameter('servicio', 1)
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
            ->add('periodoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPeriodo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                                                         
            ->add('plantillaRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPlantilla',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false))                            
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
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

