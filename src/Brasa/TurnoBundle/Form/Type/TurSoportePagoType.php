<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class TurSoportePagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                       
            ->add('dias', 'number')
            ->add('horas', 'number')
            ->add('horasExtrasOrdinariasDiurnas', 'number')
            ->add('horasExtrasOrdinariasNocturnas', 'number')
            ->add('horasExtrasFestivasDiurnas', 'number')
            ->add('horasExtrasFestivasNocturnas', 'number')
            
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

