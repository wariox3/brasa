<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class TurRecursoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('recursoTipoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurRecursoTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('rt')
                    ->orderBy('rt.codigoRecursoTipoPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))  
            ->add('numeroIdentificacion', 'text', array('required' => true))                             
            ->add('nombreCorto', 'text', array('required' => true))                  
            ->add('pagoPromedio', 'checkbox', array('required'  => false))                
            ->add('pagoVariable', 'checkbox', array('required'  => false))                
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

