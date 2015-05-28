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
            ->add('fechaDesde', 'date', array('required' => true)) 
            ->add('vrSalario', 'number', array('required' => true))  
            ->add('numero', 'text', array('required' => true))                                           
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('estadoActivo', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))                
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

