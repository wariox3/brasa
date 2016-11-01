<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class TurSoportePagoPeriodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder       
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('cc')
                    ->where('cc.generaSoportePago = 1')
                    ->orderBy('cc.codigoCentroCostoPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))                                                                                                                   
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd'))
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd'))  
            ->add('diasPeriodo', 'number', array('required' => false))
            ->add('festivos', 'number', array('required' => false))
            ->add('diasAdicionales', 'number', array('required' => false))
            ->add('descansoFestivoFijo', 'checkbox', array('required'  => false))                            
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

