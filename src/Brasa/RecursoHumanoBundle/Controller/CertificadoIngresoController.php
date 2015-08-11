<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

/**
 * RhuCertificadoIngreso controller.
 *
 */
class CertificadoIngresoController extends Controller
{
    public function CertificadoAction($codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $ConfiguracionGeneral = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionGeneral();
        $ConfiguracionGeneral = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionGeneral')->find(1);
        $empleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $empleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        
        $formCertificado = $this->createFormBuilder() 
            ->add('fechaExpedicion','date', array('data' => new \ DateTime('now')))
            ->add('LugarExpedicion', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('afc', 'number', array('required' => false))
            ->add('valorRecibido1', 'number', array('required' => false))
            ->add('valorRecibido2', 'number', array('required' => false))
            ->add('valorRecibido3', 'number', array('required' => false))
            ->add('valorRecibido4', 'number', array('required' => false))
            ->add('valorRecibido5', 'number', array('required' => false))
            ->add('valorRecibido6', 'number', array('required' => false))
            ->add('valorRetenido1', 'number', array('required' => false))
            ->add('valorRetenido2', 'number', array('required' => false))
            ->add('valorRetenido3', 'number', array('required' => false))
            ->add('valorRetenido4', 'number', array('required' => false))
            ->add('valorRetenido5', 'number', array('required' => false))
            ->add('valorRetenido6', 'number', array('required' => false))                
            ->add('bienesPoseidos1', 'text',  array('required' => false))                            
            ->add('valorPatrimonial1', 'number', array('required' => false))
            ->add('bienesPoseidos1', 'text', array('required' => false))                            
            ->add('valorPatrimonial2', 'number', array('required' => false))
            ->add('bienesPoseidos2', 'text', array('required' => false))                            
            ->add('valorPatrimonial3', 'number', array('required' => false))
            ->add('bienesPoseidos3', 'text', array('required' => false))                            
            ->add('valorPatrimonial4', 'number', array('required' => false))
            ->add('bienesPoseidos4', 'text', array('required' => false))                            
            ->add('valorPatrimonial5', 'number', array('required' => false))
            ->add('bienesPoseidos5', 'text', array('required' => false))                            
            ->add('valorPatrimonial6', 'number', array('required' => false))
            ->add('bienesPoseidos6', 'text', array('required' => false))                            
            ->add('valorPatrimonial7', 'number', array('required' => false))
            ->add('bienesPoseidos7', 'text', array('required' => false))
            ->add('personasDependientesCc1', 'number', array('required' => false))
            ->add('personasDependientesCc2', 'number', array('required' => false))
            ->add('personasDependientesCc3', 'number', array('required' => false))                
            ->add('personasDependientesCc4', 'number', array('required' => false))                
            ->add('personasDependientesNyA1', 'text', array('required' => false))                
            ->add('personasDependientesNyA2', 'text', array('required' => false))
            ->add('personasDependientesNyA3', 'text', array('required' => false))
            ->add('personasDependientesNyA4', 'text', array('required' => false))                
            ->add('personasDependientesP1', 'text', array('required' => false))
            ->add('personasDependientesP2', 'text', array('required' => false))
            ->add('personasDependientesP3', 'text', array('required' => false))
            ->add('personasDependientesP4', 'text', array('required' => false))                
            ->add('BtnGenerar', 'submit', array('label' => 'Generar'))
            ->getForm();
        $formCertificado->handleRequest($request);
        if ($formCertificado->isValid()) {
            $controles = $request->request->get('formCertificado');
            if($formCertificado->get('BtnGenerar')->isClicked()) {
                $strFechaExpedicion = $controles['fechaExpedicion'];
                $objFormatoCertificadoIngreso = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCertificadoIngreso();
                $objFormatoCertificadoIngreso->Generar($this, $codigoEmpleado,$strFechaExpedicion);
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/CertificadoIngreso:certificado.html.twig', array(
            'ConfiguracionGeneral' => $ConfiguracionGeneral,
            'empleado' => $empleado,
            'formCertificado' => $formCertificado->createView(),
        ));
    }
    
}
