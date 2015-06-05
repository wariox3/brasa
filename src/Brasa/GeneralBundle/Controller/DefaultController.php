<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\DoctrineBundle\ConnectionFactory;
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BrasaGeneralBundle:Default:index.html.twig');
    }
    
    public function menuAction()
    {
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $this->get('security.context')->getToken()->getUser();
        $strUsuario = $arUsuario->getNombreCorto();
        
        //$destinatario = $this->contenedor->getParameter('contact_email');
        //$obj = new \Brasa\GeneralBundle\MisClases\CambiarBD();
        //$obj->setUpAppConnection($this);
        //\Brasa\GeneralBundle\MisClases\CambiarBD::setUpAppConnection();
        
        return $this->render('BrasaGeneralBundle:plantillas:menu.html.twig', array('Usuario' => $strUsuario));
    }                  
    
}
