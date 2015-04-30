<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuCentroCostoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder           
            ->add('periodoPagoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPeriodoPago',
                'property' => 'nombre',
            ))                
    
            ->add('terceroRel', 'entity', array(
            'class' => 'BrasaGeneralBundle:GenTercero',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('t')                    
                ->orderBy('t.nombreCorto', 'ASC');},
            'property' => 'nombreCorto',
            'required' => true))             
                
            ->add('nombre', 'text', array('required' => true))   
            ->add('fechaUltimoPagoProgramado', 'date')
            ->add('horaPagoAutomatico', 'time')                
            ->add('estadoActivo', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('generarPagoAutomatico', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('comentarios', 'textarea', array('required' => false))                                
            ->add('porcentajeAdministracion', 'number', array('required' => false))
            ->add('valorAdministracion', 'number', array('required' => false))                        
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

