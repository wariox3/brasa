<?php

namespace Brasa\LogisticaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DespachosAgregarGuiaController extends Controller
{   
    
    public function listaAction($codigoDespacho) {                
        $request = $this->getRequest();                   
        $em = $this->getDoctrine()->getManager();
        $arGuias = $em->getRepository('BrasaLogisticaBundle:LogGuias')->findBy(array('codigoDespachoFk' => NULL));     
        $arDespacho = new \Brasa\LogisticaBundle\Entity\LogDespachos();
        $arDespacho = $em->getRepository('BrasaLogisticaBundle:LogDespachos')->find($codigoDespacho);
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
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\LogisticaBundle\Entity\LogGuias();
                            $arGuia = $em->getRepository('BrasaLogisticaBundle:LogGuias')->find($codigoGuia);
                            $arGuia->setDespachoRel($arDespacho);
                            $em->persist($arGuia);
                            $em->flush();
                        }                        
                    }                                                                    
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                                        
                    break;      
            }            
        }        
        return $this->render('BrasaLogisticaBundle:Despachos:agregarGuia.html.twig', array('arGuias' => $arGuias, 'arDespacho' => $arDespacho));                                        
    }                   
}
