<?php

namespace Brasa\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Brasa\SeguridadBundle\Form\Type\UserType;

class SegAccesoController extends Controller
{
    var $strDqlLista = "";
    public function errorPermisoEspecialAction()
    {
        return $this->render('BrasaSeguridadBundle:Acceso:errorPermisoEspecial.html.twig');
    }
}