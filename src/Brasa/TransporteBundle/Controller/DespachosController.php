<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\TransporteBundle\Form\Type\TteDespachosType;
class DespachosController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();                
        $arDespachos = new \Brasa\TransporteBundle\Entity\TteDespachos();            
        $form = $this->createFormBuilder()
            ->add('TxtCodigoDespacho', 'text', array('label'  => 'Codigo'))
            ->add('TxtNumeroDespacho', 'text')            
            ->add('ChkMostrarDescargados', 'checkbox', array('label'=> '', 'required'  => false,)) 
            ->add('ChkMostrarAnulados', 'checkbox', array('label'=> '', 'required'  => false,))                 
            ->add('TxtFechaDesde', 'date', array('widget' => 'single_text', 'label'  => 'Desde:', 'format' => 'yyyy-MM-dd'))
            ->add('TxtFechaHasta', 'date', array('widget' => 'single_text', 'label'  => 'Hasta:', 'format' => 'yyyy-MM-dd'))
            ->add('Buscar', 'submit')
            ->getForm();
        $form->handleRequest($request);
        $query = $em->getRepository('BrasaTransporteBundle:TteDespachos')->ListaDespachos(0, 0, "", "", "", "");
        if($form->isValid()) {            
            $query = $em->getRepository('BrasaTransporteBundle:TteDespachos')->ListaDespachos(
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
                        $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
                        $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuias')->find($codigoGuia);
                        if($arGuia->getEstadoImpreso() == 0 && $arGuia->getEstadoDespachada() == 0 && $arGuia->getNumeroGuia() == 0) {
                            $em->remove($arGuia);
                            $em->flush();                            
                        }
                    }
                    break;

            }
        } 
        $paginator = $this->get('knp_paginator');        
        $arDespachos = new \Brasa\TransporteBundle\Entity\TteDespachos();
        $arDespachos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);

        return $this->render('BrasaTransporteBundle:Despachos:lista.html.twig', array(
            'arDespachos' => $arDespachos,
            'form' => $form->createView()));
    }
    
    /**
     * Crear un nuevo movimiento
     * @return type
     */
    public function nuevoAction($codigoDespacho = 0) {
        $em = $this->getDoctrine()->getManager();        
        $request = $this->getRequest();
        $arDespacho = new \Brasa\TransporteBundle\Entity\TteDespachos();
        if($codigoDespacho != 0) {
            $arDespacho = $em->getRepository('BrasaTransporteBundle:TteDespachos')->find($codigoDespacho);
        }
        $form = $this->createForm(new TteDespachosType(), $arDespacho);
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arDespacho = $form->getData();                        
            $arUsuarioConfiguracion = $em->getRepository('BrasaTransporteBundle:TteUsuariosConfiguracion')->find($this->getUser()->getId());                        
            $arDespacho->setFecha(date_create(date('Y-m-d H:i:s')));
            $arDespacho->setPuntoOperacionRel($arUsuarioConfiguracion->getPuntoOperacionRel());                                    
            $em->persist($arDespacho);
            $em->flush();            
            //$em->getRepository('BrasaTransporteBundle:TteGuias')->Liquidar($arGuia->getCodigoGuiaPk());            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tte_despachos_nuevo', array('codigoGuia' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_tte_despachos_detalle', array('codigoDespacho' => $arDespacho->getCodigoDespachoPk())));
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
        
        $arDespacho = new \Brasa\TransporteBundle\Entity\TteDespachos();
        $arDespacho = $em->getRepository('BrasaTransporteBundle:TteDespachos')->find($codigoDespacho);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrDescuentosFinancierosSeleccionados = $request->request->get('ChkSeleccionarDescuentoFinanciero');
            switch ($request->request->get('OpSubmit')) {
                case "OpGenerar";
                    $strResultado = $em->getRepository('BrasaTransporteBundle:TteDespachos')->Generar($codigoDespacho);
                    if ($strResultado != "") {
                        $objMensaje->Mensaje("error", "No se genero el despacho: " . $strResultado, $this);
                    }                        
                    break;

                case "OpAnular";
                    $varAnular = $em->getRepository('BrasaTransporteBundle:TteDespachos')->Anular($codigoDespacho);
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
                        $intUnidades = $arDespacho->getCtUnidades();
                        $intPesoReal = $arDespacho->getCtPesoReal();
                        $intPesoVolumen = $arDespacho->getCtPesoVolumen();
                        $intGuias = $arDespacho->getCtGuias();
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
                            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuias')->find($codigoGuia);
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
                        $arDespacho->setCtUnidades($intUnidades);
                        $arDespacho->setCtPesoReal($intPesoReal);
                        $arDespacho->setCtPesoVolumen($intPesoVolumen);
                        $arDespacho->setCtGuias($intGuias);
                        $em->persist($arDespacho);
                        $em->flush();                        
                    }
                    break;                                                          
            }
        }
        
        $query = $em->getRepository('BrasaTransporteBundle:TteGuias')->GuiasDespachoDetalle($codigoDespacho);
        $paginator = $this->get('knp_paginator');        
        $arGuias = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);                
        return $this->render('BrasaTransporteBundle:Despachos:detalle.html.twig', array(
                    'arDespacho' => $arDespacho,
                    'arGuias' => $arGuias,
                    'paginator' => $paginator,));
    }    
        
}
