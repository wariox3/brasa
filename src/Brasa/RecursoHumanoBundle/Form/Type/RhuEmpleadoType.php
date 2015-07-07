<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuEmpleadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoIdentificacionRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuTipoIdentificacion',
                'property' => 'nombre',
            ))
            ->add('bancoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuBanco',
                'property' => 'nombre',
            ))                
            ->add('entidadSaludRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'property' => 'nombre',
            ))                
            ->add('entidadPensionRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadPension',
                'property' => 'nombre',
            ))  
            ->add('entidadCajaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadCaja',
                'property' => 'nombre',
            ))                 
            ->add('estadoCivilRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEstadoCivil',
                'property' => 'nombre',
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
            ->add('codigoSexoFk', 'choice', array('choices'   => array('M' => 'MASCULINO', 'F' => 'FEMENINO')))                                                        
            ->add('fechaNacimiento', 'date', array('required' => true, 'widget' => 'single_text'))
            ->add('edad', 'text', array('required' => false))
            ->add('nombre1', 'text', array('required' => true))
            ->add('nombre2', 'text', array('required' => false))
            ->add('apellido1', 'text', array('required' => true))
            ->add('apellido2', 'text', array('required' => false))
            ->add('telefono', 'text', array('required' => false))
            ->add('celular', 'text', array('required' => false))
            ->add('direccion', 'text', array('required' => false))
            ->add('barrioRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenBarrio',
                'property' => 'nombre',
            ))
            ->add('rhRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuRh',
                'property' => 'tipo',
            ))                
            ->add('correo', 'text', array('required' => false))
            ->add('cuenta', 'text', array('required' => true))
            ->add('numeroIdentificacion', 'text', array('required' => true))            
            ->add('auxilioTransporte', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('padreFamilia', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))                
            ->add('pagadoEntidadSalud', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('camisa', 'text', array('required' => false))                            
            ->add('jeans', 'text', array('required' => false))                            
            ->add('calzado', 'text', array('required' => false))                                            
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

