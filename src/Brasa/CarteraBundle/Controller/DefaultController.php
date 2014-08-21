<?php

namespace Brasa\CarteraBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BrasaCarteraBundle:Default:index.html.twig', array('name' => $name));
    }
}
