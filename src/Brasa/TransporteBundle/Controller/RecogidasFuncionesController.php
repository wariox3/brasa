<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
