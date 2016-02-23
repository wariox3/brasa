<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class TurPedidoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pedidoTipoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPedidoTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('pt')
                    ->where('pt.control = 0')                    
                    ->orderBy('pt.codigoPedidoTipoPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))                
            ->add('sectorRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurSector',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('s')
                    ->orderBy('s.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))  
            ->add('fechaProgramacion', 'date', array('format' => 'yyyyMMdd'))                            
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

