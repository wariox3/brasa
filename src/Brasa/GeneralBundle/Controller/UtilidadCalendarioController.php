<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class UtilidadCalendarioController extends Controller
{
    public function verAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario        
        $arEventos = new \Brasa\GeneralBundle\Entity\GenEvento();
        $arEventos = $em->getRepository('BrasaGeneralBundle:GenEvento')->findAll();       
        $arrayEventos = "";
        foreach($arEventos as $arEvento)
        {
            $strTiempo = '';
            if($arEvento->getHora()->format('H:i:s') != '00:00:00') {
                $strTiempo = 'T' . $arEvento->getHora()->format('H:i:s');
            }
            
            
            $arrayEventos .= "{ title:'" . $arEvento->getAsunto() . "', start: '" . $arEvento->getFecha()->format('Y-m-d') . "$strTiempo'" . "},";
        }
        $arrayEventos .= "";   
        $fechaActual = date('Y-m-d');
        return $this->render('BrasaGeneralBundle:Utilidades/Calendario:calendario.html.twig', array(
            'fechaActual' => $fechaActual,
            'arrayEventos' => $arrayEventos
        ));
    }   
        
}
