<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('contratoTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuContratoTipo',
                        'property' => 'nombre',
            ))                 
            ->add('contratoGrupoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuContratoGrupo',
                        'property' => 'nombre',
            ))                 
            ->add('clasificacionRiesgoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuClasificacionRiesgo',
                'property' => 'nombre',
            ))                 
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'property' => 'nombre',
            ))  
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                 
            ->add('tipoTiempoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuTipoTiempo',
                'property' => 'nombre',
            )) 
            ->add('tipoPensionRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuTipoPension',
                'property' => 'nombre',
            ))                            
            ->add('tipoSaludRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuTipoSalud',
                'property' => 'nombre',
            ))                            
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
            ))  
            ->add('ssoTipoCotizanteRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoTipoCotizante',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('tc')
                    ->orderBy('tc.codigoTipoCotizantePk', 'ASC');},
                'property' => 'nombre',
            ))                            
            ->add('ssoSubtipoCotizanteRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoSubtipoCotizante',
                'property' => 'nombre',
            ))
            ->add('salarioTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSalarioTipo',
                'property' => 'nombre',
                'required' => true
            ))                
            ->add('entidadSaludRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('es')
                    ->orderBy('es.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('entidadCesantiaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadCesantia',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ec')
                    ->orderBy('ec.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                
            ->add('entidadPensionRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadPension',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
            ))
            ->add('entidadCajaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadCaja',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ec')
                    ->orderBy('ec.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                
            //->add('fechaDesde', 'date', array('required' => true))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                
            //->add('fechaHasta', 'date', array('required' => true))                
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                
            ->add('horarioTrabajo', 'text', array('required' => false)) 
            ->add('vrSalario', 'number', array('required' => true))  
            //->add('numero', 'text', array('required' => true))                                           
            ->add('cargoDescripcion', 'text', array('required' => false))                                                                       
            ->add('comentarios', 'textarea', array('required' => false))            
            ->add('salarioIntegral', 'checkbox', array('required'  => false))
            ->add('limitarHoraExtra', 'checkbox', array('required'  => false))     
            ->add('vrDevengadoPactado', 'number', array('required' => true))
            ->add('ciudadContratoRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('ciudadLaboraRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))   
            ->add('turnoFijoOrdinario', 'checkbox', array('required'  => false))   
            ->add('secuencia', 'number', array('required' => false))                             
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

