<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TurFacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder  
            ->add('facturaTipoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurFacturaTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ft')
                    ->orderBy('ft.codigoFacturaTipoPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))    
            ->add('facturaServicioRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurFacturaServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ft')
                    ->orderBy('ft.codigoFacturaServicioPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('imprimirRelacion', 'checkbox', array('required'  => false))  
            ->add('imprimirAgrupada', 'checkbox', array('required'  => false))
            ->add('soporte', 'text', array('required' => false))
            ->add('descripcion', 'text', array('required' => false))                            
            ->add('tituloRelacion', 'text', array('required' => false))                            
            ->add('detalleRelacion', 'text', array('required' => false))                            
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

