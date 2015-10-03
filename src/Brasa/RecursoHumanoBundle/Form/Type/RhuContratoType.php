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
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'property' => 'nombre',
            ))  
            ->add('ssoTipoCotizanteRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoTipoCotizante',
                'property' => 'nombre',
            ))                            
            ->add('ssoSubtipoCotizanteRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoSubtipoCotizante',
                'property' => 'nombre',
            ))                             
            ->add('fechaDesde', 'date', array('required' => true))
            ->add('fechaHasta', 'date', array('required' => true))                
            ->add('horarioTrabajo', 'text', array('required' => false)) 
            ->add('vrSalario', 'number', array('required' => true))  
            ->add('numero', 'text', array('required' => true))                                           
            ->add('cargoDescripcion', 'text', array('required' => true))                                                                       
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('salarioIntegral', 'choice', array('choices' => array('0' => 'NO', '1' => 'SI')))
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

