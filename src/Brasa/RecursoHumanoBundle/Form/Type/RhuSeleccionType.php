<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuSeleccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seleccionTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSeleccionTipo',
                'property' => 'nombre',
            ))
            ->add('seleccionGrupoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSeleccionGrupo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('sg')
                    ->where('sg.estadoAbierto = :estadoAbierto')
                    ->setParameter('estadoAbierto', 1)
                    ->orderBy('sg.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                            
            ->add('tipoIdentificacionRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuTipoIdentificacion',
                'property' => 'nombre',
            ))
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))     
            ->add('estadoCivilRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEstadoCivil',
                'property' => 'nombre',
            ))
            ->add('rhRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuRh',
                'property' => 'tipo',
            ))                
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->where('c.codigoDepartamentoFk = :codigoDepartamento')
                    ->setParameter('codigoDepartamento', 5)
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('ciudadNacimientoRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->where('c.codigoDepartamentoFk = :codigoDepartamento')
                    ->setParameter('codigoDepartamento', 5)
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                
            ->add('fechaNacimiento', 'date', array('required' => true, 'widget' => 'single_text'))                                 
            ->add('fechaPruebas', 'datetime', array('required' => false))                 
            ->add('nombre1', 'text', array('required' => true))
            ->add('nombre2', 'text', array('required' => false))
            ->add('apellido1', 'text', array('required' => true))
            ->add('apellido2', 'text', array('required' => false))
            ->add('correo', 'text', array('required' => false))                            
            ->add('telefono', 'text', array('required' => false))
            ->add('celular', 'text', array('required' => false))
            ->add('direccion', 'text', array('required' => false))                            
            ->add('barrioRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenBarrio',
                'property' => 'nombre',
            ))
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('codigoSexoFk', 'choice', array('choices'   => array('M' => 'MASCULINO', 'F' => 'FEMENINO')))                            
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

