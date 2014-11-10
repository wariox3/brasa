<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\TransporteBundle\Form\Type\TteGuiasType;
use Brasa\TransporteBundle\Form\Type\TteNovedadesType;
use Brasa\TransporteBundle\Form\Type\TteRecibosCajaType;

class GuiasController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arGuias = new \Brasa\TransporteBundle\Entity\TteGuias();
        $form = $this->createFormBuilder()
            ->add('TxtCodigoGuia', 'text', array('label'  => 'Codigo'))
            ->add('TxtNumeroGuia', 'text')
            ->add('TxtCodigoTercero', 'text')
            ->add('TxtNombreTercero', 'text') 
            ->add('ChkMostrarDespachadas', 'checkbox', array('label'=> '', 'required'  => false,)) 
            ->add('ChkMostrarAnuladas', 'checkbox', array('label'=> '', 'required'  => false,)) 
            ->add('TxtFechaDesde', 'date', array('widget' => 'single_text', 'label'  => 'Desde:', 'format' => 'yyyy-MM-dd'))
            ->add('TxtFechaHasta', 'date', array('widget' => 'single_text', 'label'  => 'Hasta:', 'format' => 'yyyy-MM-dd'))
            ->add('Buscar', 'submit')
            ->getForm();
        $form->handleRequest($request);
        $query = $em->getRepository('BrasaTransporteBundle:TteGuias')->ListaGuias(0, 0, "", "", "", "");
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
                case "OpBuscar";
                    $arMovimientos = new \Brasa\InventarioBundle\Entity\InvMovimientos();
                    $arMovimientos = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->DevMovimientosFiltro(
                            $codigoDocumento,
                            $arrControles['TxtCodigoMovimiento'],
                            $arrControles['TxtNumeroMovimiento'],
                            $arrControles['TxtCodigoTercero'],
                            $objChkFecha,
                            $arrControles['TxtFechaDesde'],
                            $arrControles['TxtFechaHasta'],
                            "",
                            "");
                    break;
            }
        }
        
        $paginator = $this->get('knp_paginator');        
        $arGuias = new \Brasa\TransporteBundle\Entity\TteGuias();
        $arGuias = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);        
        
        return $this->render('BrasaTransporteBundle:Guias:lista.html.twig', array(
            'arGuias' => $arGuias,
            'form' => $form->createView()));
    }

    /**
     * Crear un nueva guias
     * @return type
     */
    public function nuevoAction($codigoGuia = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
        $form = $this->createForm(new TteGuiasType(), $arGuia);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arGuia = $form->getData();                        
            $arUsuarioConfiguracion = $em->getRepository('BrasaTransporteBundle:TteUsuariosConfiguracion')->find($this->getUser()->getId());            
            $arCiudadDestino = $em->getRepository('BrasaGeneralBundle:GenCiudades')->find($arrControles['form']['ciudadDestinoRel']);
            $arGuia->setFechaIngreso(date_create(date('Y-m-d H:i:s')));
            $arGuia->setPuntoOperacionIngresoRel($arUsuarioConfiguracion->getPuntoOperacionRel());
            $arGuia->setPuntoOperacionActualRel($arUsuarioConfiguracion->getPuntoOperacionRel());
            $arGuia->setCiudadOrigenRel($arUsuarioConfiguracion->getPuntoOperacionRel()->getCiudadOrigenRel());
            $arGuia->setRutaRel($arCiudadDestino->getRutaRel());                        
            $em->persist($arGuia);
            $em->flush();            
            $em->getRepository('BrasaTransporteBundle:TteGuias')->Liquidar($arGuia->getCodigoGuiaPk());            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tte_guias_nuevo', array('codigoGuia' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_tte_guias_detalle', array('codigoGuia' => $arGuia->getCodigoGuiaPk())));
            }    
            
        }                
        return $this->render('BrasaTransporteBundle:Guias:nuevo.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * Lista los movimientos detalle (Detalles) segun encabezado - Filtro
     */
    public function detalleAction($codigoGuia) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $request = $this->getRequest();
        $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
        $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuias')->find($codigoGuia);        
        $arNovedad = new \Brasa\TransporteBundle\Entity\TteNovedades();
        $frmNovedad = $this->createForm(new TteNovedadesType(), $arNovedad);
        $frmNovedad->handleRequest($request);
        if ($frmNovedad->isValid()) {
            $arNovedad = $frmNovedad->getData(); 
            $arNovedad->setFechaRegistro(date_create(date('Y-m-d H:i:s')));
            $arNovedad->setGuiaRel($arGuia);
            $em->persist($arNovedad);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tte_guias_detalle', array('codigoGuia' => $codigoGuia)));            
        }

        $arReciboCaja = new \Brasa\TransporteBundle\Entity\TteRecibosCaja();
        $frmReciboCaja = $this->createForm(new TteRecibosCajaType, $arReciboCaja);
        $frmReciboCaja->handleRequest($request);
        if ($frmReciboCaja->isValid()) {
            $douAbonoFlete = $frmReciboCaja->get('vrFlete')->getData();
            $douAbonoManejo = $frmReciboCaja->get('vrManejo')->getData();
            if($arGuia->getCodigoTipoPagoFk() == 2 || $arGuia->getCodigoTipoPagoFk() == 3) {
                if(($arGuia->getVrAbonosFlete() + $douAbonoFlete) <= $arGuia->getVrFlete() ) {
                    if(($arGuia->getVrAbonosManejo() + $douAbonoManejo) <= $arGuia->getVrManejo() ) {
                        $arReciboCaja = $frmReciboCaja->getData();  
                        $arReciboCaja->setFecha(date_create(date('Y-m-d H:i:s')));
                        $arReciboCaja->setGuiaRel($arGuia);
                        $arReciboCaja->setVrTotal($douAbonoFlete+$douAbonoManejo);
                        $em->persist($arReciboCaja);
                        $em->flush();
                        $arGuia->setVrAbonosFlete($arGuia->getVrAbonosFlete() + $douAbonoFlete);
                        $arGuia->setVrAbonosManejo($arGuia->getVrAbonosManejo() + $douAbonoManejo);
                        $em->persist($arGuia);
                        $em->flush();
                    } else {
                        $objMensaje->Mensaje("error", "El valor del abono del manejo no puede superar el valor del manejo", $this);
                    }
                } else {
                    $objMensaje->Mensaje("error", "El valor del abono del flete no puede superar el valor del flete", $this);
                }                 
            } else {
                $objMensaje->Mensaje("error", "Solo se pueden realizar abonos a guias contado o destino", $this);
            }                           
            return $this->redirect($this->generateUrl('brs_tte_guias_detalle', array('codigoGuia' => $codigoGuia)));            
        }        
        
        $form = $this->createFormBuilder()
            ->add('BtnAutorizar', 'submit')
            ->getForm(); 

        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrDescuentosFinancierosSeleccionados = $request->request->get('ChkSeleccionarDescuentoFinanciero');
            switch ($request->request->get('OpSubmit')) {
                case "OpGenerar";
                    if($arGuia->getEstadoGenerada() == 0) {  
                        $em->getRepository('BrasaTransporteBundle:TteGuias')->Generar($codigoGuia);
                    }                    
                    break;

                case "OpImprimir";
                    if($arGuia->getEstadoImpreso() == 0 && $arGuia->getNumeroGuia() != 0 && $arGuia->getEstadoGenerada() == 1) {
                        $arGuia->setNumeroGuia($em->getRepository('BrasaTransporteBundle:TteConfiguraciones')->consecutivoGuia());
                        $arGuia->setEstadoImpreso(1);
                        $em->persist($arGuia);
                        $em->flush();
                    }                    
                    $objFormatoGuia = new \Brasa\TransporteBundle\Formatos\FormatoGuia();
                    $objFormatoGuia->Generar($this, $codigoGuia);
                    break;
            }
        }

        $query = $em->getRepository('BrasaTransporteBundle:TteNovedades')->NovedadesGuiasDetalle($codigoGuia);
        $paginator = $this->get('knp_paginator');        
        $arNovedades = new \Brasa\TransporteBundle\Entity\TteNovedades();
        $arNovedades = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);

        $query = $em->getRepository('BrasaTransporteBundle:TteRecibosCaja')->RecibosCajaGuiasDetalle($codigoGuia);
        $paginator = $this->get('knp_paginator');        
        $arRecibosCaja = new \Brasa\TransporteBundle\Entity\TteRecibosCaja();
        $arRecibosCaja = $paginator->paginate($query, $this->get('request')->query->get('page', 1),3);        
        
        return $this->render('BrasaTransporteBundle:Guias:detalle.html.twig', array(
            'arGuia' => $arGuia,
            'arNovedades' => $arNovedades,
            'arRecibosCaja' => $arRecibosCaja,
            'form' => $form->createView(),
            'frmNovedad' => $frmNovedad->createView(),
            'frmReciboCaja' => $frmReciboCaja->createView()));
    }

}
