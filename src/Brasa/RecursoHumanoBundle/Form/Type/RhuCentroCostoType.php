<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuCentroCostoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('periodoPagoRel', 'entity',
                array('class' => 'BrasaRecursoHumanoBundle:RhuPeriodoPago',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => 4,
                'empty_value' => 'QUINCENAL',
                ))
            ->add('sucursalRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoSucursal',
                'property' => 'nombre',
                'required' => true))
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('nombre', 'text', array('required' => true))
            ->add('fechaUltimoPagoProgramado', 'date')
            ->add('fechaUltimoPagoPrima', 'date')
            ->add('fechaUltimoPagoCesantias', 'date')
            ->add('horaPagoAutomatico', 'time')
            ->add('estadoActivo', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('generarPagoAutomatico', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('porcentajeAdministracion', 'number', array('required' => false))
            ->add('valorAdministracion', 'number', array('required' => false))
            ->add('diasPago', 'text', array('required' => true))
            ->add('generaServicioCobrar', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

