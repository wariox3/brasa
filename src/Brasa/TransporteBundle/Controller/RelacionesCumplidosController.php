<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\TransporteBundle\Form\Type\TteRelacionCumplidoType;
class RelacionesCumplidosController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();                
        $arRelacionesCumplidos = new \Brasa\TransporteBundle\Entity\TteRelacionCumplido();            
        $form = $this->createFormBuilder()
            ->add('TxtCodigoRelacionCumplidos', 'text', array('label'  => 'Codigo'))
            ->add('ChkMostrarDescargadas', 'checkbox', array('label'=> '', 'required'  => false,)) 
            ->add('TxtFechaDesde', 'date', array('widget' => 'single_text', 'label'  => 'Desde:', 'format' => 'yyyy-MM-dd'))
            ->add('TxtFechaHasta', 'date', array('widget' => 'single_text', 'label'  => 'Hasta:', 'format' => 'yyyy-MM-dd'))
            ->add('Buscar', 'submit')
            ->getForm();
        $form->handleRequest($request);
        $query = $em->getRepository('BrasaTransporteBundle:TteRelacionCumplido')->ListaRelacionesCumplidos(0, "", "", "");
        if($form->isValid()) {            
            $query = $em->getRepository('BrasaTransporteBundle:TteRelacionCumplido')->ListaDespachos(
                    $form->get('ChkMostrarDescargados')->getData(),
                    $form->get('ChkMostrarAnulados')->getData(),
                    $form->get('TxtCodigoDespacho')->getData(),
                    $form->get('TxtNumeroDespacho')->getData(),
                    $form->get('TxtFechaDesde')->getData(),
                    $form->get('TxtFechaHasta')->getData());                        
        }        
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $objChkFecha = NULL;
            if (isset($arrControles['ChkFecha']))
                $objChkFecha = $arrControles['ChkFecha'];
            switch ($request->request->get('OpSubmit')) {
                case "OpEliminar";
                    foreach ($arrSeleccionados AS $codigoGuia) {
                        $arGuia = new \Brasa\TransporteBundle\Entity\TteGuia();
                        $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuia')->find($codigoGuia);
                        if($arGuia->getEstadoImpreso() == 0 && $arGuia->getEstadoDespachada() == 0 && $arGuia->getNumeroGuia() == 0) {
                            $em->remove($arGuia);
                            $em->flush();                            
                        }
                    }
                    break;

            }
        } 
        $paginator = $this->get('knp_paginator');        
        $arRelacionesCumplidos = new \Brasa\TransporteBundle\Entity\TteRelacionCumplido();
        $arRelacionesCumplidos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);

        return $this->render('BrasaTransporteBundle:RelacionesCumplidos:lista.html.twig', array(
            'arDespachos' => $arRelacionesCumplidos,
            'form' => $form->createView()));
    }
    
    /**
     * Crear un nuevo movimiento
     * @return type
     */
    public function nuevoAction($codigoDespacho = 0) {
        $em = $this->getDoctrine()->getManager();        
        $request = $this->getRequest();
        $arRelacionCumplidos = new \Brasa\TransporteBundle\Entity\TteRelacionCumplido();
        if($codigoDespacho != 0) {
            $arRelacionCumplidos = $em->getRepository('BrasaTransporteBundle:TteRelacionCumplido')->find($codigoDespacho);
        }
        $form = $this->createForm(new TteDespachoType(), $arRelacionCumplidos);
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arRelacionCumplidos = $form->getData();                        
            $arUsuarioConfiguracion = $em->getRepository('BrasaTransporteBundle:TteUsuarioConfiguracion')->find($this->getUser()->getId());                        
            $arRelacionCumplidos->setFecha(date_create(date('Y-m-d H:i:s')));
            $arRelacionCumplidos->setPuntoOperacionRel($arUsuarioConfiguracion->getPuntoOperacionRel());                                    
            $em->persist($arRelacionCumplidos);
            $em->flush();            
            //$em->getRepository('BrasaTransporteBundle:TteGuia')->Liquidar($arGuia->getCodigoGuiaPk());            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tte_despachos_nuevo', array('codigoGuia' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_tte_despachos_detalle', array('codigoDespacho' => $arRelacionCumplidos->getCodigoDespachoPk())));
            }    
            
        }                        
        
        return $this->render('BrasaTransporteBundle:Despachos:nuevo.html.twig', array(
            'form' => $form->createView()));
    }    
    
    /**
     * Lista los movimientos detalle (Detalles) segun encabezado - Filtro
     */
    public function detalleAction($codigoDespacho) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');
        
        $arRelacionCumplidos = new \Brasa\TransporteBundle\Entity\TteRelacionCumplido();
        $arRelacionCumplidos = $em->getRepository('BrasaTransporteBundle:TteRelacionCumplido')->find($codigoDespacho);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrDescuentosFinancierosSeleccionados = $request->request->get('ChkSeleccionarDescuentoFinanciero');
            switch ($request->request->get('OpSubmit')) {
                case "OpGenerar";
                    $strResultado = $em->getRepository('BrasaTransporteBundle:TteRelacionCumplido')->Generar($codigoDespacho);
                    if ($strResultado != "") {
                        $objMensaje->Mensaje("error", "No se genero el despacho: " . $strResultado, $this);
                    }                        
                    break;

                case "OpAnular";
                    $varAnular = $em->getRepository('BrasaTransporteBundle:TteRelacionCumplido')->Anular($codigoDespacho);
                    if ($varAnular != "") {
                        $objMensaje->Mensaje("error", "No se anulo el despacho: " . $varAnular, $this);
                    }                        
                    break;

                case "OpImprimir";   
                    if($arRelacionCumplidos->getEstadoGenerado() == 0) {
                        $reporte = new \Brasa\TransporteBundle\Formatos\FormatoManifiesto();
                        $reporte->Generar($this);                        
                    }
                    break;

                case "OpRetirar";
                    if (count($arrSeleccionados) > 0) {
                        $intUnidades = $arRelacionCumplidos->getCtUnidades();
                        $intPesoReal = $arRelacionCumplidos->getCtPesoReal();
                        $intPesoVolumen = $arRelacionCumplidos->getCtPesoVolumen();
                        $intGuias = $arRelacionCumplidos->getCtGuias();
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\TransporteBundle\Entity\TteGuia();
                            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuia')->find($codigoGuia);
                            if($arGuia->getCodigoDespachoFk() != NULL) {
                                $arGuia->setCodigoDespachoFk(NULL);
                                $arGuia->setEstadoDespachada(0);                                
                                $em->persist($arGuia);
                                $em->flush(); 
                                $intUnidades = $intUnidades - $arGuia->getCtUnidades();
                                $intPesoReal = $intPesoReal - $arGuia->getCtPesoReal();
                                $intPesoVolumen = $intPesoVolumen - $arGuia->getCtPesoVolumen();
                                $intGuias = $intGuias - 1;                                  
                            }                            
                        }
                        $arRelacionCumplidos->setCtUnidades($intUnidades);
                        $arRelacionCumplidos->setCtPesoReal($intPesoReal);
                        $arRelacionCumplidos->setCtPesoVolumen($intPesoVolumen);
                        $arRelacionCumplidos->setCtGuias($intGuias);
                        $em->persist($arRelacionCumplidos);
                        $em->flush();                        
                    }
                    break;                                                          
            }
        }
        
        $query = $em->getRepository('BrasaTransporteBundle:TteGuia')->GuiasDespachoDetalle($codigoDespacho);
        $paginator = $this->get('knp_paginator');        
        $arGuias = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);                
        return $this->render('BrasaTransporteBundle:Despachos:detalle.html.twig', array(
                    'arDespacho' => $arRelacionCumplidos,
                    'arGuias' => $arGuias,
                    'paginator' => $paginator,));
    }    
        
}
