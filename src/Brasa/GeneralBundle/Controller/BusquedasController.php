<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BusquedasController extends Controller {

    public function buscarTerceroAction($campoCodigo, $campoNombre) {
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
            $arTerceros = $em->getRepository('BrasaGeneralBundle:GenTercero')->findAll();
        }

        return $this->render('BrasaGeneralBundle:Busquedas:buscarTercero.html.twig', array(
            "arTerceros" => $arTerceros,
            "campoCodigo" => $campoCodigo,
            "campoNombre" => $campoNombre));
    }
    
    public function buscarCiudadAction($campoCodigo, $campoNombre) {
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
            $arCiudades = $em->getRepository('BrasaGeneralBundle:GenCiudad')->findAll();
        }

        return $this->render('BrasaGeneralBundle:Busquedas:buscarCiudad.html.twig', array(
            "arCiudades" => $arCiudades,
            "campoCodigo" => $campoCodigo,
            "campoNombre" => $campoNombre));
    }    

}
