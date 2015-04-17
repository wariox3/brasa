<?php

namespace Brasa\TransporteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DespachosAgregarGuiaController extends Controller
{

    public function listaAction($codigoDespacho) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arGuias = $em->getRepository('BrasaTransporteBundle:TteGuia')->findBy(array('codigoDespachoFk' => NULL, 'estadoGenerada' => 1, 'estadoAnulada' => 0));
        $arDespacho = new \Brasa\TransporteBundle\Entity\TteDespacho();
        $arDespacho = $em->getRepository('BrasaTransporteBundle:TteDespacho')->find($codigoDespacho);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            switch ($request->request->get('OpSubmit')) {
                case "OpBuscar";
                    if($request->request->get('TxtDescripcionItem') != "")
                        $arItemes = $em->getRepository('BrasaInventarioBundle:InvItem')->BuscarDescripcionItem($request->request->get('TxtDescripcionItem'));

                    if($request->request->get('TxtCodigoItem') != "")
                        $arItemes = $em->getRepository('BrasaInventarioBundle:InvItem')->find($request->request->get('TxtCodigoItem'));
                    break;
                case "OpAgregar";
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if (count($arrSeleccionados) > 0) {
                        $intUnidades = $arDespacho->getCtUnidades();
                        $intPesoReal = $arDespacho->getCtPesoReal();
                        $intPesoVolumen = $arDespacho->getCtPesoVolumen();
                        $intGuias = $arDespacho->getCtGuias();
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\TransporteBundle\Entity\TteGuia();
                            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuia')->find($codigoGuia);
                            $arGuia->setEstadoDespachada(1);
                            $arGuia->setDespachoRel($arDespacho);
                            $em->persist($arGuia);
                            $em->flush();
                            $intUnidades = $intUnidades + $arGuia->getCtUnidades();
                            $intPesoReal = $intPesoReal + $arGuia->getCtPesoReal();
                            $intPesoVolumen = $intPesoVolumen + $arGuia->getCtPesoVolumen();
                            $intGuias = $intGuias + 1;
                        }

                        $arDespacho->setCtUnidades($intUnidades);
                        $arDespacho->setCtPesoReal($intPesoReal);
                        $arDespacho->setCtPesoVolumen($intPesoVolumen);
                        $arDespacho->setCtGuias($intGuias);
                        $em->persist($arDespacho);
                        $em->flush();
                    }
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    break;
            }
        }
        return $this->render('BrasaTransporteBundle:Despachos:agregarGuia.html.twig', array(
            'arGuias' => $arGuias, 
            'arDespacho' => $arDespacho));
    }
}
