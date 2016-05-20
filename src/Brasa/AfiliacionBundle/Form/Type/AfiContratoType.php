<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class AfiContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('clienteRel', 'entity', array(
                'class' => 'BrasaAfiliacionBundle:AfiCliente',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))                                                         
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ec')
                    ->orderBy('ec.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true)) 
            ->add('entidadSaludRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('es')
                    ->orderBy('es.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('entidadPensionRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadPension',
                'property' => 'nombre',
            ))
            ->add('entidadCajaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadCaja',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ec')
                    ->orderBy('ec.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))  
            ->add('ssoTipoCotizanteRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoTipoCotizante',
                'property' => 'nombre',
            ))                            
            ->add('ssoSubtipoCotizanteRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoSubtipoCotizante',
                'property' => 'nombre',
            ))
            ->add('clasificacionRiesgoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuClasificacionRiesgo',
                'property' => 'nombre',
            ))                  
            ->add('numero', 'text', array('required' => false))
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd'))                            
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd'))
            ->add('indefinido', 'checkbox', array('required'  => false))
            ->add('vrSalario', 'number', array('required' => true))  
            ->add('generaPension', 'checkbox', array('required'  => false))
            ->add('generaSalud', 'checkbox', array('required'  => false))
            ->add('generaRiesgos', 'checkbox', array('required'  => false))
            ->add('generaCaja', 'checkbox', array('required'  => false))                            
            ->add('generaSena', 'checkbox', array('required'  => false))
            ->add('generaIcbf', 'checkbox', array('required'  => false))
            ->add('porcentajePension', 'number', array('required' => true))
            ->add('porcentajeSalud', 'number', array('required' => true))
            ->add('porcentajeCaja', 'number', array('required' => true))
            ->add('comentarios', 'textarea', array('required' => false))
            
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

