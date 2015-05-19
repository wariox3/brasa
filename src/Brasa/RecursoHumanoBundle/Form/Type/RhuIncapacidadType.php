<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuIncapacidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder             
            ->add('empleadoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('e')
                    ->where('e.codigoCentroCostoFk = :centroCosto AND e.estadoActivo = 1')
                    ->setParameter('centroCosto', $options['data']->getCentroCostoRel()->getCodigoCentroCostoPk())
                    ->orderBy('e.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))   
            ->add('pagoAdicionalSubtipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoAdicionalSubtipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('st')
                    ->where('st.codigoPagoAdicionalTipoFk = :codigoPagoTipo')
                    ->setParameter('codigoPagoTipo', 6)
                    ->orderBy('st.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('numeroEps', 'text', array('required' => true))   
            ->add('fechaDesde', 'date')                
            ->add('fechaHasta', 'date')  
            ->add('comentarios', 'textarea', array('required' => false))                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

