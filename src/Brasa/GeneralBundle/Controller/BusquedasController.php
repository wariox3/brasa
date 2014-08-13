<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BusquedasController extends Controller {

    public function buscarTerceroAction() {
        $em = $this->get('doctrine.orm.entity_manager');
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            if ($request->request->get('TxtCodigo') != "" && is_numeric($request->request->get('TxtCodigo'))) {
                $arItem = $em->getRepository('zikmontInventarioBundle:InvItem')->findBy(array('codigoItemPk' => $request->request->get('TxtCodigoItem')));
            } elseif ($request->request->get('TxtDescripcionItem') != "") {
                $arItem = $em->getRepository('zikmontInventarioBundle:InvItem')->BuscarDescripcionItem($request->request->get('TxtDescripcionItem'));
            }
            // Todos los productos
            else
                $arItem = $em->getRepository('zikmontInventarioBundle:InvItem')->findAll();
        }
        else {
            $arTerceros = $em->getRepository('BrasaGeneralBundle:GenTerceros')->findAll();
        }

        return $this->render('BrasaGeneralBundle:Busquedas:buscarTercero.html.twig', array("arTerceros" => $arTerceros));
    }

}
