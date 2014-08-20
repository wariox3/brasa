<?php

namespace Brasa\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BusquedasController extends Controller {

    public function buscarItemAction() {
        $em = $this->get('doctrine.orm.entity_manager');
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            if ($request->request->get('TxtCodigo') != "" && is_numeric($request->request->get('TxtCodigo'))) {
                $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findBy(array('codigoItemPk' => $request->request->get('TxtCodigoItem')));
            } elseif ($request->request->get('TxtDescripcionItem') != "") {
                $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->BuscarDescripcionItem($request->request->get('TxtDescripcionItem'));
            }
            // Todos los productos
            else
                $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
        }
        else {
            $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
        }

        return $this->render('BrasaInventarioBundle:Busquedas:buscarItem.html.twig', array("arItem" => $arItem));
    }

}
