<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function inicioAction() {
        return $this->render('BrasaRecursoHumanoBundle:Default:inicio.html.twig');
    }
    
}
