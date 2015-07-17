<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuConfiguracionType;

/**
 * RhuConfiguracion controller.
 *
 */
class ConfiguracionController extends Controller
{
    public function configuracionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);            
        $formConfiguracion = $this->createForm(new RhuConfiguracionType(), $arConfiguracion);
        $formConfiguracion->handleRequest($request);
        if ($formConfiguracion->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arConfiguracion);
            $arConfiguracion = $formConfiguracion->getData();
            $em->flush();
        }
        return $this->render('BrasaRecursoHumanoBundle:Configuracion:Configuracion.html.twig', array(
            'formConfiguracion' => $formConfiguracion->createView(),
        ));
    }
    
}
