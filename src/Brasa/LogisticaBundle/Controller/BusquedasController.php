<?php

namespace Brasa\LogisticaBundle\Controller;

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
            $arConductores = $em->getRepository('BrasaLogisticaBundle:LogConductores')->findAll();
        }

        return $this->render('BrasaLogisticaBundle:Busquedas:buscarConductor.html.twig', array("arConductores" => $arConductores));
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
            $arVehiculos = $em->getRepository('BrasaLogisticaBundle:LogVehiculos')->findAll();
        }

        return $this->render('BrasaLogisticaBundle:Busquedas:buscarVehiculo.html.twig', array("arVehiculos" => $arVehiculos));
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
            $arRutas = $em->getRepository('BrasaLogisticaBundle:LogRutas')->findAll();
        }

        return $this->render('BrasaLogisticaBundle:Busquedas:buscarRuta.html.twig', array("arRutas" => $arRutas));
    }         
}
