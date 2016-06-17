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
            ->add('recursoGrupoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurRecursoGrupo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('rg')
                    ->orderBy('rg.codigoRecursoGrupoPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd'))
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd'))  
            ->add('diasPeriodo', 'number', array('required' => false))
            ->add('festivos', 'number', array('required' => false))
            ->add('diasAdicionales', 'number', array('required' => false))
            ->add('descansoFestivoFijo', 'checkbox', array('required'  => false))                            
            ->add('diaFestivoReal', 'number', array('required' => false))                            
            ->add('diaDomingoReal', 'number', array('required' => false))                            
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

