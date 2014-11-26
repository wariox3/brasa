<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\TransporteBundle\Form\Type\TtePlanesRecogidasType;

class RecogidasFuncionesController extends Controller
{    
    public function programadasAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');                                       
        $form = $this->createFormBuilder()
            ->add('Generar', 'submit')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoRecogidaProgramada) {                            
                    $arRecogidaProgramada = new \Brasa\TransporteBundle\Entity\TteRecogidasProgramadas();
                    $arRecogidaProgramada = $em->getRepository('BrasaTransporteBundle:TteRecogidasProgramadas')->find($codigoRecogidaProgramada);
                    $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogidas();
                    $arRecogida->setFechaAnuncio(date_create(date('Y-m-d H:i:s')));                                    
                    $arRecogida->setFechaRecogida(date_create(date('Y-m-d'). $arRecogidaProgramada->getHoraRecogida()->format('H:i')));                    
                    $arRecogida->setTerceroRel($arRecogidaProgramada->getTerceroRel());
                    $arRecogida->setPuntoOperacionRel($arRecogidaProgramada->getPuntoOperacionRel());
                    $arRecogida->setAnunciante($arRecogidaProgramada->getAnunciante());
                    $arRecogida->setDireccion($arRecogidaProgramada->getDireccion());
                    $arRecogida->setTelefono($arRecogidaProgramada->getTelefono());
                    $em->persist($arRecogida);
                    $em->flush();                            
                    //$arRedespacho->setFecha(date_create(date('Y-m-d H:i:s')));                                                        
                }                        
            }           
        }

        
        $arRecogidasProgramadas = new \Brasa\TransporteBundle\Entity\TteRecogidasProgramadas();
        $query = $em->getRepository('BrasaTransporteBundle:TteRecogidasProgramadas')->Lista();
        $paginator = $this->get('knp_paginator');                
        $arRecogidasProgramadas = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);         
        
        return $this->render('BrasaTransporteBundle:Recogidas/Funciones:programadas.html.twig', array(
            'arRecogidasProgramadas' => $arRecogidasProgramadas,
            'form' => $form->createView()));
    }    
    
    public function programacionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();        
        $form = $this->createFormBuilder()
            
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoRecogidaProgramada) {                            
                    $arRecogidaProgramada = new \Brasa\TransporteBundle\Entity\TteRecogidasProgramadas();
                    $arRecogidaProgramada = $em->getRepository('BrasaTransporteBundle:TteRecogidasProgramadas')->find($codigoRecogidaProgramada);
                    $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogidas();
                    $arRecogida->setFechaAnuncio(date_create(date('Y-m-d H:i:s')));                                    
                    $arRecogida->setFechaRecogida(date_create(date('Y-m-d'). $arRecogidaProgramada->getHoraRecogida()->format('H:i')));                    
                    $arRecogida->setTerceroRel($arRecogidaProgramada->getTerceroRel());
                    $arRecogida->setPuntoOperacionRel($arRecogidaProgramada->getPuntoOperacionRel());
                    $arRecogida->setAnunciante($arRecogidaProgramada->getAnunciante());
                    $arRecogida->setDireccion($arRecogidaProgramada->getDireccion());
                    $arRecogida->setTelefono($arRecogidaProgramada->getTelefono());
                    $em->persist($arRecogida);
                    $em->flush();                            
                    //$arRedespacho->setFecha(date_create(date('Y-m-d H:i:s')));                                                        
                }                        
            }           
        }

        $arPlanesRecogidas = new \Brasa\TransporteBundle\Entity\TtePlanesRecogidas();                
        $query = $em->getRepository('BrasaTransporteBundle:TtePlanesRecogidas')->Pendientes();
        $paginator = $this->get('knp_paginator');                
        $arPlanesRecogidas = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);         
        
        $query = $em->getRepository('BrasaTransporteBundle:TteRecogidas')->ListaPendientes();
        $paginator = $this->get('knp_paginator');        
        $arRecogidas = new \Brasa\TransporteBundle\Entity\TteRecogidas();
        $arRecogidas = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100); 
        
        return $this->render('BrasaTransporteBundle:Recogidas/Funciones:programacion.html.twig', array(
            'arPlanesRecogidas' => $arPlanesRecogidas,
            'form' => $form->createView(),
            'arRecogidas' => $arRecogidas));
    } 
    
    public function planRecogidaNuevoAction($codigoPlanRecogida = 0) {
        $em = $this->getDoctrine()->getManager();        
        $request = $this->getRequest();
        $arPlanRecogida = new \Brasa\TransporteBundle\Entity\TtePlanesRecogidas();
        if($codigoPlanRecogida != 0) {
            $arPlanRecogida = $em->getRepository('BrasaTransporteBundle:TtePlanesRecogidas')->find($codigoPlanRecogida);
        }
        $form = $this->createForm(new TtePlanesRecogidasType(), $arPlanRecogida);
        $form->handleRequest($request);        
        if ($form->isValid()) {            
            $arPlanRecogida = $form->getData();                        
            $arUsuarioConfiguracion = $em->getRepository('BrasaTransporteBundle:TteUsuariosConfiguracion')->find($this->getUser()->getId());                        
            $intUsu =$this->getUser()->getId();
            $arPlanRecogida->setFecha(date_create(date('Y-m-d H:i:s')));
            $arPlanRecogida->setPuntoOperacionRel($arUsuarioConfiguracion->getPuntoOperacionRel());                                    
            $em->persist($arPlanRecogida);
            $em->flush();            
            //$em->getRepository('BrasaTransporteBundle:TteGuias')->Liquidar($arGuia->getCodigoGuiaPk());            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tte_recogidas_planrecogida_nuevo', array('codigoPlanRecogida' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_tte_recogidas_funciones_programacion'));
            }    
            
        }                        
        

        
        return $this->render('BrasaTransporteBundle:Recogidas/Funciones:nuevoPlanRecogida.html.twig', array(
            'form' => $form->createView()));
    }        
}
