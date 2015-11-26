<?php
namespace Brasa\ContabilidadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class CtbCuentaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCuentaPk', 'text', array('required' => true))    
            ->add('nombreCuenta', 'text', array('required' => true))    
            ->add('codigoCuentaPadreFk', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbCuenta',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCuenta', 'ASC');},
                'property' => 'nombreCuenta',
                'required' => true,
                'mapped' => false))
            ->add('permiteMovimientos', 'choice', array('choices' => array('0' => 'NO' , '1' => 'SI')))
            ->add('exigeNit', 'choice', array('choices' => array('0' => 'NO' , '1' => 'SI')))
            ->add('exigeCentroCostos', 'choice', array('choices' => array('0' => 'NO' , '1' => 'SI')))                            
            ->add('porcentajeRetencion', 'number', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
