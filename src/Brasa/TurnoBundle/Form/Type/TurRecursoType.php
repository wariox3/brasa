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
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurCentroCosto',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.codigoCentroCostoPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('numeroIdentificacion', 'text', array('required' => true))                             
            ->add('nombreCorto', 'text', array('required' => true))
            ->add('codigoTurnoFijoNominaFk', 'text', array('required' => false))
            ->add('apodo', 'text', array('required' => false))    
            ->add('telefono', 'text', array('required' => false))
            ->add('celular', 'text', array('required' => false))
            ->add('direccion', 'text', array('required' => false))
            ->add('correo', 'text', array('required' => true))
            ->add('fechaNacimiento','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('BtnActualizar', 'submit', array('label'  => 'Actualizar datos desde empleado RH'))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
                    
    }

    public function getName()
    {
        return 'form';
    }
}

