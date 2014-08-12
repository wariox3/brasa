<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseTercerosController extends Controller
{
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $arTerceros = new \Brasa\GeneralBundle\Entity\GenTerceros();
        $arTerceros = $em->getRepository('BrasaGeneralBundle:GenTerceros')->findAll();        
        return $this->render('BrasaGeneralBundle:Base/Terceros:lista.html.twig', array('arTerceros'=> $arTerceros));
    }
        
}
