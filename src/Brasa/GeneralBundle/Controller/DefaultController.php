<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BrasaGeneralBundle:Default:index.html.twig');
    }
    
    public function menuAction()
    {
        //$arUsuario = new \Soga\SeguridadBundle\Entity\User();
        //$arUsuario = $this->get('security.context')->getToken()->getUser();
        //$strUsuario = $arUsuario->getNombreCorto();
        $strUsuario = "Mario Andres";
        return $this->render('BrasaGeneralBundle:plantillas:menu.html.twig', array('Usuario' => $strUsuario));
    }                  
    
}
