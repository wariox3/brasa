<?php

namespace Brasa\TransporteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FacturasAgregarGuiaController extends Controller
{

    public function listaAction($codigoFactura) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arFactura = new \Brasa\TransporteBundle\Entity\TteFacturas();
        $arFactura = $em->getRepository('BrasaTransporteBundle:TteFacturas')->find($codigoFactura);
        $arGuias = $em->getRepository('BrasaTransporteBundle:TteGuias')->findBy(array('estadoFacturada' => 0, 'estadoGenerada' => 1, 'estadoAnulada' => 0, 'codigoTipoPagoFk' => 1, 'codigoTerceroFk' => $arFactura->getCodigoTerceroFk()));
        if ($request->getMethod() == 'POST') {            
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
                        $intUnidades = $arFactura->getCtUnidades();
                        $intGuias = $arFactura->getCtGuias();
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
                            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuias')->find($codigoGuia);
                            $arGuia->setEstadoFacturada(1);
                            $arGuia->setFacturaRel($arFactura);
                            $em->persist($arGuia);
                            $em->flush();
                            $intUnidades = $intUnidades + $arGuia->getCtUnidades();
                            $intGuias = $intGuias + 1;
                        }

                        $arFactura->setCtUnidades($intUnidades);
                        $arFactura->setCtGuias($intGuias);
                        $em->persist($arFactura);
                        $em->flush();
                    }
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    break;
            }
        }
        return $this->render('BrasaTransporteBundle:Facturas:agregarGuia.html.twig', array(
            'arGuias' => $arGuias, 
            'arFactura' => $arFactura));
    }
}
