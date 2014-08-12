<?php

namespace Brasa\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocumentosController extends Controller
{
    public function listaAction($codigoTipoDocumento)
    {
        $em = $this->getDoctrine()->getManager();
        $arDocumentos = new \Brasa\InventarioBundle\Entity\InvDocumentos();
        $arDocumentos = $em->getRepository('BrasaInventarioBundle:InvDocumentos')->findBy(array('codigoDocumentoTipoFk' => $codigoTipoDocumento));        
        return $this->render('BrasaInventarioBundle:Documentos:lista.html.twig', array('arDocumentos'=> $arDocumentos));
    }
}
