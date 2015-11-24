<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurPlantillaDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dia1', 'text', array('required'  => false))
            ->add('dia2', 'text', array('required'  => false))
            ->add('dia3', 'text', array('required'  => false))
            ->add('dia4', 'text', array('required'  => false))
            ->add('dia5', 'text', array('required'  => false))
            ->add('dia6', 'text', array('required'  => false))
            ->add('dia7', 'text', array('required'  => false))
            ->add('dia8', 'text', array('required'  => false))
            ->add('dia9', 'text', array('required'  => false))
            ->add('dia10', 'text', array('required'  => false))
            ->add('dia11', 'text', array('required'  => false))
            ->add('dia12', 'text', array('required'  => false))
            ->add('dia13', 'text', array('required'  => false))
            ->add('dia14', 'text', array('required'  => false))
            ->add('dia15', 'text', array('required'  => false))
            ->add('dia16', 'text', array('required'  => false))
            ->add('dia17', 'text', array('required'  => false))
            ->add('dia18', 'text', array('required'  => false))
            ->add('dia19', 'text', array('required'  => false))
            ->add('dia20', 'text', array('required'  => false))
            ->add('dia21', 'text', array('required'  => false))
            ->add('dia22', 'text', array('required'  => false))
            ->add('dia23', 'text', array('required'  => false))
            ->add('dia24', 'text', array('required'  => false))
            ->add('dia25', 'text', array('required'  => false))
            ->add('dia26', 'text', array('required'  => false))
            ->add('dia27', 'text', array('required'  => false))
            ->add('dia28', 'text', array('required'  => false))
            ->add('dia29', 'text', array('required'  => false))
            ->add('dia30', 'text', array('required'  => false))
            ->add('dia31', 'text', array('required'  => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

