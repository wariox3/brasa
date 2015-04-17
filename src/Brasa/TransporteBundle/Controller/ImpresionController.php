<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ImpresionController extends Controller
{
    public function manifiestoAction($name = 'Mario Andres') {
        $facade = $this->get('ps_pdf.facade');
        $response = new Response();
        $this->render('BrasaTransporteBundle:Despachos:prueba.pdf.twig', array(), $response);
        
        $xml = $response->getContent();
        
        $content = $facade->render($xml);
        
        return new Response($content, 200, array('content-type' => 'application/pdf'));
    } 
        
}
