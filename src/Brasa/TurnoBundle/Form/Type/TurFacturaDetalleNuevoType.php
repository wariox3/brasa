<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurFacturaDetalleNuevoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {       
        $builder
            ->add('puestoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPuesto',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                    ->where('p.codigoClienteFk = :codigoCliente ')
                    ->setParameter('codigoCliente', $options['data']->getFacturaRel()->getCodigoClienteFk())
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))  
            ->add('grupoFacturacionRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurGrupoFacturacion',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('gf')
                    ->where('gf.codigoClienteFk = :codigoCliente ')
                    ->setParameter('codigoCliente', $options['data']->getFacturaRel()->getCodigoClienteFk())
                    ->orderBy('gf.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false))                             
            ->add('conceptoServicioRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurConceptoServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('cs')
                    ->where('cs.tipo = 2 ')
                    ->orderBy('cs.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))  
            ->add('cantidad', 'number')                            
            ->add('vrPrecio', 'number')                            
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

