<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

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
            ->add('sucursalRel', 'entity', array(
                'class' => 'BrasaAfiliacionBundle:AfiSucursal',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('s')
                    ->orderBy('s.nombre', 'ASC');},
                'property' => 'nombre',
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
            ->add('numero', TextType::class, array('required' => false))
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd'))                            
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('indefinido', CheckboxType::class, array('required'  => false))
            ->add('vrSalario', NumberType::class, array('required' => true))  
            ->add('generaPension', CheckboxType::class, array('required'  => false))
            ->add('generaSalud', CheckboxType::class, array('required'  => false))
            ->add('generaRiesgos', CheckboxType::class, array('required'  => false))
            ->add('generaCaja', CheckboxType::class, array('required'  => false))                            
            ->add('generaSena', CheckboxType::class, array('required'  => false))
            ->add('generaIcbf', CheckboxType::class, array('required'  => false))
            ->add('porcentajePension', NumberType::class, array('required' => true))
            ->add('porcentajeSalud', NumberType::class, array('required' => true))
            ->add('porcentajeCaja', NumberType::class, array('required' => true))
            ->add('comentarios', TextareaType::class, array('required' => false))
            
            ->add('guardar', SubmitType::class);
    }

    public function getName()
    {
        return 'form';
    }
}

