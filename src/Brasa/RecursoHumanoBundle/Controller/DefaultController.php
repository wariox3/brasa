<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function inicioAction() {
        $request = $this->getRequest();
        $form = $this->createFormBuilder()                        
            ->add('BtnInactivar', 'submit', array('label'  => 'Activar / Inactivar',))
                               
            ->getForm();                               
        $form->handleRequest($request);
        return $this->render('BrasaRecursoHumanoBundle:Default:inicio.html.twig', array (
            'form' => $form->createView()
        ));
    }
    
}
