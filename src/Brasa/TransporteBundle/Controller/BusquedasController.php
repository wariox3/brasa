<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BusquedasController extends Controller {

    public function buscarConductorAction() {
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
            $arConductores = $em->getRepository('BrasaTransporteBundle:TteConductores')->findAll();
        }

        return $this->render('BrasaTransporteBundle:Busquedas:buscarConductor.html.twig', array("arConductores" => $arConductores));
    }
    
    public function buscarVehiculoAction() {
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
            $arVehiculos = $em->getRepository('BrasaTransporteBundle:TteVehiculos')->findAll();
        }

        return $this->render('BrasaTransporteBundle:Busquedas:buscarVehiculo.html.twig', array("arVehiculos" => $arVehiculos));
    }     

    public function buscarRutaAction() {
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
            $arRutas = $em->getRepository('BrasaTransporteBundle:TteRutas')->findAll();
        }

        return $this->render('BrasaTransporteBundle:Busquedas:buscarRuta.html.twig', array("arRutas" => $arRutas));
    }         
}
