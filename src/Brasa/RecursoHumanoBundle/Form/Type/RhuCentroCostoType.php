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
            ->add('clienteRel', 'entity',
                array('class' => 'BrasaRecursoHumanoBundle:RhuCliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => false
                ))                
            ->add('periodoPagoRel', 'entity',
                array('class' => 'BrasaRecursoHumanoBundle:RhuPeriodoPago',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true,                
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
            ->add('estadoActivo', 'checkbox', array('required'  => false))
            ->add('administrativo', 'checkbox', array('required'  => false))
            ->add('generarPagoAutomatico', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('porcentajeAdministracion', 'number', array('required' => false))
            ->add('valorAdministracion', 'number', array('required' => false))
            ->add('diasPago', 'text', array('required' => true))            
            ->add('generaServicioCobrar', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))                
            ->add('descansoOrdinario', 'checkbox', array('required'  => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

