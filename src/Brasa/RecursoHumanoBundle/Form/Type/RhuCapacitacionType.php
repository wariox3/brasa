<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuCapacitacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('capacitacionTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCapacitacionTipo',
                        'property' => 'nombre',
            ))
            ->add('capacitacionMetodologiaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCapacitacionMetodologia',
                        'property' => 'nombre',
            ))
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                        'property' => 'nombre',
            ))    
            ->add('fechaCapacitacion', 'datetime', array('format' => 'yyyyMMdd'))
            ->add('tema', 'text', array('required' => false))
            ->add('numeroPersonasCapacitar', 'number', array('required' => true))
            ->add('vrCapacitacion', 'text', array('required' => false))
            ->add('lugar', 'text', array('required' => false))    
            ->add('duracion', 'text', array('required' => false))
            ->add('objetivo', 'textarea', array('required' => false))
            ->add('contenido', 'textarea', array('required' => false))
            ->add('facilitador', 'text', array('required' => false))
            ->add('numeroIdentificacionFacilitador', 'text', array('required' => false))    
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

