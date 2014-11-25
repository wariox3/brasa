<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\TransporteBundle\Form\Type\TteRecogidasType;

class RecogidasController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arRecogidas = new \Brasa\TransporteBundle\Entity\TteRecogidas();
        $form = $this->createFormBuilder()
            ->add('TxtCodigoRecogida', 'text', array('label'  => 'Codigo'))            
            ->add('TxtCodigoTercero', 'text')
            ->add('TxtNombreTercero', 'text')             
            ->add('ChkMostrarAnuladas', 'checkbox', array('label'=> '', 'required'  => false,)) 
            ->add('TxtFechaDesde', 'date', array('widget' => 'single_text', 'label'  => 'Desde:', 'format' => 'yyyy-MM-dd'))
            ->add('TxtFechaHasta', 'date', array('widget' => 'single_text', 'label'  => 'Hasta:', 'format' => 'yyyy-MM-dd'))
            ->add('Buscar', 'submit')
            ->getForm();
        $form->handleRequest($request);
        $query = $em->getRepository('BrasaTransporteBundle:TteRecogidas')->ListaRecogidas(0, "", "", "", "");
        if($form->isValid()) {            
            $query = $em->getRepository('BrasaTransporteBundle:TteRecogidas')->ListaRecogidas(
                    $form->get('ChkMostrarDespachadas')->getData(),
                    $form->get('ChkMostrarAnuladas')->getData(),
                    $form->get('TxtCodigoRecogida')->getData(),
                    $form->get('TxtNumeroRecogida')->getData(),
                    $form->get('TxtFechaDesde')->getData(),
                    $form->get('TxtFechaHasta')->getData(),
                    $form->get('TxtCodigoTercero')->getData());                        
        }        
        if ($request->getMethod() == 'POST') {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            switch ($request->request->get('OpSubmit')) {
                case "OpEliminar";
                    foreach ($arrSeleccionados AS $codigoRecogida) {
                        $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogidas();
                        $arRecogida = $em->getRepository('BrasaTransporteBundle:TteRecogidas')->find($codigoRecogida);
                        if($arRecogida->getEstadoImpreso() == 0 && $arRecogida->getEstadoDespachada() == 0 && $arRecogida->getNumeroRecogida() == 0) {
                            $em->remove($arRecogida);
                            $em->flush();                            
                        }
                    }
                    break;
            }
        }
        
        $paginator = $this->get('knp_paginator');        
        $arRecogidas = new \Brasa\TransporteBundle\Entity\TteRecogidas();
        $arRecogidas = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);        
        
        return $this->render('BrasaTransporteBundle:Recogidas:lista.html.twig', array(
            'arRecogidas' => $arRecogidas,
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoRecogida = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogidas();                
        
        if($codigoRecogida != 0) {
            $arRecogida = $em->getRepository('BrasaTransporteBundle:TteRecogidas')->find($codigoRecogida);
        }else {
            $arRecogida->setFechaRecogida(new \DateTime('now'));
        }        
        $form = $this->createForm(new TteRecogidasType(), $arRecogida);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arRecogida = $form->getData();                        
            $arUsuarioConfiguracion = $em->getRepository('BrasaTransporteBundle:TteUsuariosConfiguracion')->find($this->getUser()->getId());                        
            $arRecogida->setFechaAnuncio(date_create(date('Y-m-d H:i:s')));
            $arRecogida->setPuntoOperacionRel($arUsuarioConfiguracion->getPuntoOperacionRel());                        
            $em->persist($arRecogida);
            $em->flush();                        
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tte_recogidas_nuevo', array('codigoRecogida' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_tte_recogidas_detalle', array('codigoRecogida' => $arRecogida->getCodigoRecogidaPk())));
            }    
            
        }                
        return $this->render('BrasaTransporteBundle:Recogidas:nuevo.html.twig', array(
            'form' => $form->createView()));
    }

    public function detalleAction($codigoRecogida) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $request = $this->getRequest();
        $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogidas();
        $arRecogida = $em->getRepository('BrasaTransporteBundle:TteRecogidas')->find($codigoRecogida);             
        
        $form = $this->createFormBuilder()
            ->add('BtnAutorizar', 'submit')
            ->getForm(); 

        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrDescuentosFinancierosSeleccionados = $request->request->get('ChkSeleccionarDescuentoFinanciero');
            switch ($request->request->get('OpSubmit')) {
                case "OpGenerar";
                    if($arRecogida->getEstadoGenerada() == 0) {  
                        $em->getRepository('BrasaTransporteBundle:TteRecogidas')->Generar($codigoRecogida);
                    }                    
                    break;

                case "OpImprimir";                    
                    $objFormatoRecogida = new \Brasa\TransporteBundle\Formatos\FormatoRecogida();
                    $objFormatoRecogida->Generar($this, $codigoRecogida);
                    break;
                    
            }
        }

        
        return $this->render('BrasaTransporteBundle:Recogidas:detalle.html.twig', array(
            'arRecogida' => $arRecogida,
            'form' => $form->createView()));
    }

}
