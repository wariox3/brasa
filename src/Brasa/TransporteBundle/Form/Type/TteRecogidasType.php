<?php
namespace Brasa\TransporteBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TteRecogidaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('terceroRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenTercero',
                'property' => 'nombreCorto',
            ))       
            ->add('anunciante', 'text')
            ->add('direccion', 'text')
            ->add('telefono', 'text')                
            ->add('fechaRecogida', 'datetime')                
            ->add('ctUnidades', 'text')
            ->add('ctPesoReal', 'text')
            ->add('ctPesoVolumen', 'text') 
            ->add('vrDeclarado', 'text')
            ->add('contenido', 'textarea', array('required' => false))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

