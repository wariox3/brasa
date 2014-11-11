<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\TransporteBundle\Form\Type\TteFacturasType;

class FacturasController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();                
        $arFacturas = new \Brasa\TransporteBundle\Entity\TteFacturas();            
        $form = $this->createFormBuilder()
            ->add('TxtCodigoFactura', 'text', array('label'  => 'Codigo'))
            ->add('TxtNumeroFactura', 'text')
            ->add('TxtCodigoTercero', 'text')
            ->add('TxtNombreTercero', 'text')             
            ->add('ChkMostrarAnuladas', 'checkbox', array('label'=> '', 'required'  => false,)) 
            ->add('TxtFechaDesde', 'date', array('widget' => 'single_text', 'label'  => 'Desde:', 'format' => 'yyyy-MM-dd'))
            ->add('TxtFechaHasta', 'date', array('widget' => 'single_text', 'label'  => 'Hasta:', 'format' => 'yyyy-MM-dd'))
            ->add('Buscar', 'submit')
            ->getForm();
        $form->handleRequest($request);
        $query = $em->getRepository('BrasaTransporteBundle:TteFacturas')->ListaFacturas(0, "", "", "", "");        
        if($form->isValid()) {            
            $query = $em->getRepository('BrasaTransporteBundle:TteFacturas')->ListaFacturas(                    
                    $form->get('ChkMostrarAnuladas')->getData(),
                    $form->get('TxtCodigoGuia')->getData(),
                    $form->get('TxtNumeroGuia')->getData(),
                    $form->get('TxtFechaDesde')->getData(),
                    $form->get('TxtFechaHasta')->getData());                        
        }        
        if ($request->getMethod() == 'POST') {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            switch ($request->request->get('OpSubmit')) {
                case "OpEliminar";
                    foreach ($arrSeleccionados AS $codigoFactura) {
                        $arFactura = new \Brasa\TransporteBundle\Entity\TteFacturas();
                        $arFactura = $em->getRepository('BrasaTransporteBundle:TteFacturas')->find($codigoFactura);

                    }
                    break;
            }
        }                 
        
        $paginator = $this->get('knp_paginator');        
        $arFacturas = new \Brasa\TransporteBundle\Entity\TteFacturas();
        $arFacturas = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100); 
        return $this->render('BrasaTransporteBundle:Facturas:lista.html.twig', array(
            'arFacturas' => $arFacturas,
            'form' => $form->createView()));
    }
    
    public function nuevoAction($codigoFactura = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arFactura = new \Brasa\TransporteBundle\Entity\TteFacturas();
        if($codigoFactura != 0) {
            $arFactura = $em->getRepository('BrasaTransporteBundle:TteFacturas')->find($codigoFactura);
        }
        $form = $this->createForm(new TteFacturasType(), $arFactura);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFactura = $form->getData();                        
            $arFactura->setFecha(date_create(date('Y-m-d H:i:s')));
            $em->persist($arFactura);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tte_facturas_nuevo', array('codigoFactura' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_tte_facturas_detalle', array('codigoFactura' => $arFactura->getCodigoFacturaPk())));
            }    
            
        }
        return $this->render('BrasaTransporteBundle:Facturas:nuevo.html.twig', array(
            'form' => $form->createView()));
    }  
    
    public function detalleAction($codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');
        
        $arFactura = new \Brasa\TransporteBundle\Entity\TteFacturas();
        $arFactura = $em->getRepository('BrasaTransporteBundle:TteFacturas')->find($codigoFactura);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrDescuentosFinancierosSeleccionados = $request->request->get('ChkSeleccionarDescuentoFinanciero');
            switch ($request->request->get('OpSubmit')) {
                case "OpGenerar";
                    $strResultado = $em->getRepository('BrasaTransporteBundle:TteFacturas')->Generar($codigoDespacho);
                    if ($strResultado != "") {
                        $objMensaje->Mensaje("error", "No se genero el despacho: " . $strResultado, $this);
                    }                        
                    break;

                case "OpAnular";
                    $varAnular = $em->getRepository('BrasaTransporteBundle:TteFacturas')->Anular($codigoDespacho);
                    if ($varAnular != "") {
                        $objMensaje->Mensaje("error", "No se anulo el despacho: " . $varAnular, $this);
                    }                        
                    break;

                case "OpImprimir";   
                    if($arDespacho->getEstadoGenerado() == 0) {
                        $reporte = new \Brasa\TransporteBundle\Formatos\FormatoManifiesto();
                        $reporte->Generar($this);                        
                    }
                    break;

                case "OpRetirar";
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
                            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuias')->find($codigoGuia);
                            if($arGuia->getCodigoDespachoFk() == NULL) {
                                $arGuia->setCodigoDespachoFk(NULL);
                                $em->persist($arGuia);
                                $em->flush();                                
                            }
                        }                        
                    }
                    break;                                                          
            }
        }
        
        $query = $em->getRepository('BrasaTransporteBundle:TteGuias')->GuiasFacturaDetalle($codigoFactura);
        $paginator = $this->get('knp_paginator');        
        $arGuias = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);                
        return $this->render('BrasaTransporteBundle:Facturas:detalle.html.twig', array(
                    'arFactura' => $arFactura,
                    'arGuias' => $arGuias,
                    'paginator' => $paginator));
    }    
        
}
