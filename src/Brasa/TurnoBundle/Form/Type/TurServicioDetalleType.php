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
            ->add('proyectoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurProyecto',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                    ->where('p.codigoClienteFk = :codigoCliente ')
                    ->setParameter('codigoCliente', $options['data']->getServicioRel()->getCodigoClienteFk())
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false))   
            ->add('grupoFacturacionRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurGrupoFacturacion',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('gf')
                    ->where('gf.codigoClienteFk = :codigoCliente ')
                    ->setParameter('codigoCliente', $options['data']->getServicioRel()->getCodigoClienteFk())
                    ->orderBy('gf.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false))                             
            ->add('puestoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPuesto',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                    ->where('p.codigoClienteFk = :codigoCliente ')
                    ->setParameter('codigoCliente', $options['data']->getServicioRel()->getCodigoClienteFk())
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
                    ->orderBy('ms.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('cantidad', 'number')
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd')) 
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd')) 
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
            ->add('compuesto', 'checkbox', array('required'  => false))                            
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

