<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class TurNovedadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder        
            ->add('novedadTipoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurNovedadTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('nt')
                    ->orderBy('nt.codigoNovedadTipoPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))                 
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd'))                            
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd'))                            
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

