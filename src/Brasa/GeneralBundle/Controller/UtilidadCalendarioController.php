<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class UtilidadCalendarioController extends Controller
{
    public function verAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario        
        return $this->render('BrasaGeneralBundle:Utilidades/Calendario:calendario.html.twig');
    }   
        
}
