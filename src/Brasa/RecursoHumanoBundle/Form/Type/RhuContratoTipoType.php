<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuContratoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
              
    }
 
    public function getName()
    {
        return 'form';
    }
}
