<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuExamenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder           
            ->add('examenClaseRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuExamenClase',
                'property' => 'nombre',
            ))                
            ->add('entidadExamenRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadExamen',
                'property' => 'nombre',
            ))
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'property' => 'nombre',
            ))
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'property' => 'nombre',
            ))    
            ->add('fecha', 'date')
            ->add('codigoSexoFk', 'choice', array('choices'   => array('M' => 'MASCULINO', 'F' => 'FEMENINO')))    
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('identificacion', 'number', array('required' => true))
            ->add('nombreCorto', 'text', array('required' => true))
            ->add('fechaNacimiento', 'date')
            ->add('cargoDescripcion', 'text', array('required' => true))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

