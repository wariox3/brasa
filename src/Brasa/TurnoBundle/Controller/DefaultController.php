<?php

namespace Brasa\TurnoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BrasaTurnoBundle:Default:index.html.twig', array('name' => $name));
    }
}
