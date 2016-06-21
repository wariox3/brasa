<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurRecursoGrupoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                       
            ->add('nombre', 'text', array('required' => true))  
            ->add('codigoTurnoFijoNominaFk', 'text', array('required' => false))
            ->add('codigoTurnoFijoDescansoFk', 'text', array('required' => false))                
            ->add('diasDescansoFijo', 'number', array('required' => false))                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

