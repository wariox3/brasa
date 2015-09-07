<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuAccidenteTrabajoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('codigoFurat', 'number', array('required' => false))
            ->add('fechaAccidente','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('tipoAccidente', 'choice', array('choices'   => array('1' => 'ACCIDENTE', '2' => 'ACCIDENTE GRAVE', '3' => 'ACCIDENTE MORTAL', '4' => 'INCIDENTE')))
            ->add('fechaEnviaInvestigacion', 'date', array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaIncapacidadDesde', 'date', array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaIncapacidadHasta', 'date', array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('dias', 'number', array('required' => false))
            ->add('cie10', 'text', array('required' => false))
            ->add('diagnostico', 'text', array('required' => false))
            ->add('naturalezaLesion', 'text', array('required' => false))
            ->add('cuerpoAfectado', 'text', array('required' => false))
            ->add('agente', 'text', array('required' => false))
            ->add('mecanismoAccidente', 'text', array('required' => false))
            ->add('lugarAccidente', 'text', array('required' => false))                
            ->add('descripcionAccidente', 'textarea', array('required' => false))
            ->add('actoInseguro', 'textarea', array('required' => false))
            ->add('condicionInsegura', 'textarea', array('required' => false))
            ->add('factorPersonal', 'textarea', array('required' => false))
            ->add('factorTrabajo', 'textarea', array('required' => false))
            ->add('planAccion1', 'textarea', array('required' => false))
            ->add('tipoControl1', 'choice', array('choices' => array('1' => 'FUENTE', '2' => 'MEDIO', '3' => 'PERSONA')))
            ->add('fechaVerificacion1', 'date', array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('areaResponsable1', 'text', array('required' => false))
            ->add('planAccion2', 'textarea', array('required' => false))
            ->add('tipoControl2', 'choice', array('choices' => array('1' => 'FUENTE', '2' => 'MEDIO', '3' => 'PERSONA')))
            ->add('fechaVerificacion2', 'date', array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('areaResponsable2', 'text', array('required' => false))                
            ->add('planAccion3', 'textarea', array('required' => false))
            ->add('tipoControl3', 'choice', array('choices' => array('1' => 'FUENTE', '2' => 'MEDIO', '3' => 'PERSONA')))
            ->add('fechaVerificacion3', 'date', array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('areaResponsable3', 'text', array('required' => false))
            ->add('participanteInvestigacion1', 'text', array('required' => false))                
            ->add('cargoParticipanteInvestigacion1', 'text', array('required' => false))
            ->add('participanteInvestigacion2', 'text', array('required' => false))                
            ->add('cargoParticipanteInvestigacion2', 'text', array('required' => false))                
            ->add('participanteInvestigacion3', 'text', array('required' => false))                
            ->add('cargoParticipanteInvestigacion3', 'text', array('required' => false))                
            ->add('representanteLegal', 'text', array('required' => false))                
            ->add('cargoRepresentanteLegal', 'text', array('required' => false))
            ->add('licencia', 'text', array('required' => false))
            ->add('fechaVerificacion', 'date', array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('responsableVerificacion', 'text', array('required' => false))                
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
