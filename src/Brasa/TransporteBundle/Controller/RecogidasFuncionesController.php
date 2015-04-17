<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\TransporteBundle\Form\Type\TtePlanRecogidaType;

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
                    $arRecogidaProgramada = new \Brasa\TransporteBundle\Entity\TteRecogidaProgramada();
                    $arRecogidaProgramada = $em->getRepository('BrasaTransporteBundle:TteRecogidaProgramada')->find($codigoRecogidaProgramada);
                    $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogida();
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

        
        $arRecogidasProgramadas = new \Brasa\TransporteBundle\Entity\TteRecogidaProgramada();
        $query = $em->getRepository('BrasaTransporteBundle:TteRecogidaProgramada')->Lista();
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
            $arrControles = $request->request->All();
            $arrRecogidasSeleccionadas = $request->request->get('ChkSeleccionarRecogida');
            $arrPlanesRecogidasSeleccionadas = $request->request->get('ChkSeleccionarPlanesRecogida');
            switch ($request->request->get('OpSubmit')) {
                case "OpRetirar";
                    if (count($arrRecogidasSeleccionadas) > 0) {
                        foreach ($arrRecogidasSeleccionadas AS $codigoRecogida) {
                            $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogida();
                            $arRecogida = $em->getRepository('BrasaTransporteBundle:TteRecogida')->find($codigoRecogida);
                            $arPlanRecogida = new \Brasa\TransporteBundle\Entity\TtePlanRecogida();
                            $arPlanRecogida = $em->getRepository('BrasaTransporteBundle:TtePlanRecogida')->find($arRecogida->getCodigoPlanRecogidaFk());                           
                            $arPlanRecogida->setCtUnidades($arPlanRecogida->getCtUnidades() - $arRecogida->getCtUnidades());
                            $arPlanRecogida->setCtPesoReal($arPlanRecogida->getCtPesoReal() - $arRecogida->getCtPesoReal());
                            $arPlanRecogida->setCtPesoVolumen($arPlanRecogida->getCtPesoVolumen() - $arRecogida->getCtPesoVolumen());
                            $arPlanRecogida->setCtRecogidas($arPlanRecogida->getCtRecogidas() - 1);
                            $arRecogida->setEstadoAsignada(0);
                            $arRecogida->setPlanRecogidaRel(NULL);
                            $em->persist($arRecogida);
                            $em->flush();
                            $em->persist($arPlanRecogida);
                            $em->flush();                                                        
                        }                         
                    }
                    break;
                    
                case "OpEliminar";
                    if (count($arrPlanesRecogidasSeleccionadas) > 0) {
                        foreach ($arrPlanesRecogidasSeleccionadas AS $codigoPlanRecogida) {
                            $arRecogidas = new \Brasa\TransporteBundle\Entity\TteRecogida();
                            $arRecogidas = $em->getRepository('BrasaTransporteBundle:TteRecogida')->findBy(array('codigoPlanRecogidaFk' => $codigoPlanRecogida));
                            if(count($arRecogidas) <= 0) {
                                $arPlanRecogida = new \Brasa\TransporteBundle\Entity\TtePlanRecogida();
                                $arPlanRecogida = $em->getRepository('BrasaTransporteBundle:TtePlanRecogida')->find($codigoPlanRecogida);                                                           
                                $em->remove($arPlanRecogida);                                
                                $em->flush();
                            }                            
                        }                         
                    }                    
                    break;
            }            
           
        }

        $arPlanesRecogidas = new \Brasa\TransporteBundle\Entity\TtePlanRecogida();                
        $query = $em->getRepository('BrasaTransporteBundle:TtePlanRecogida')->Pendientes();
        $paginator = $this->get('knp_paginator');                
        $arPlanesRecogidas = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);         
        
        $query = $em->getRepository('BrasaTransporteBundle:TteRecogida')->ListaPendientes();
        $paginator = $this->get('knp_paginator');        
        $arRecogidas = new \Brasa\TransporteBundle\Entity\TteRecogida();
        $arRecogidas = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100); 

        $query = $em->getRepository('BrasaTransporteBundle:TteRecogida')->ListaAsignadas();
        $paginator = $this->get('knp_paginator');        
        $arRecogidasAsignacion = new \Brasa\TransporteBundle\Entity\TteRecogida();
        $arRecogidasAsignacion = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);         
        
        return $this->render('BrasaTransporteBundle:Recogidas/Funciones:programacion.html.twig', array(
            'arPlanesRecogidas' => $arPlanesRecogidas,
            'form' => $form->createView(),
            'arRecogidas' => $arRecogidas,
            'arRecogidasAsignacion' => $arRecogidasAsignacion));
    } 
    
    public function planRecogidaNuevoAction($codigoPlanRecogida = 0) {
        $em = $this->getDoctrine()->getManager();        
        $request = $this->getRequest();
        $arPlanRecogida = new \Brasa\TransporteBundle\Entity\TtePlanRecogida();
        if($codigoPlanRecogida != 0) {
            $arPlanRecogida = $em->getRepository('BrasaTransporteBundle:TtePlanRecogida')->find($codigoPlanRecogida);
        }
        $form = $this->createForm(new TtePlanRecogidaType(), $arPlanRecogida);
        $form->handleRequest($request);        
        if ($form->isValid()) {            
            $arPlanRecogida = $form->getData();                        
            $arUsuarioConfiguracion = $em->getRepository('BrasaTransporteBundle:TteUsuarioConfiguracion')->find($this->getUser()->getId());                        
            $intUsu =$this->getUser()->getId();
            $arPlanRecogida->setFecha(date_create(date('Y-m-d H:i:s')));
            $arPlanRecogida->setPuntoOperacionRel($arUsuarioConfiguracion->getPuntoOperacionRel());                                    
            $em->persist($arPlanRecogida);
            $em->flush();            
            //$em->getRepository('BrasaTransporteBundle:TteGuia')->Liquidar($arGuia->getCodigoGuiaPk());            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tte_recogidas_planrecogida_nuevo', array('codigoPlanRecogida' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_tte_recogidas_funciones_programacion'));
            }    
            
        }                        
        

        
        return $this->render('BrasaTransporteBundle:Recogidas/Funciones:nuevoPlanRecogida.html.twig', array(
            'form' => $form->createView()));
    }        
    
    public function agregarRecogidaAction($codigoPlanRecogida) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
                ->add('Agregar', 'submit')
                ->getForm();

        $form->handleRequest($request);
        $arPlanRecogida = new \Brasa\TransporteBundle\Entity\TtePlanRecogida();
        $arPlanRecogida = $em->getRepository('BrasaTransporteBundle:TtePlanRecogida')->find($codigoPlanRecogida);
        
        if ($form->isValid()) {
            if($form->get('Agregar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    $intUnidades = $arPlanRecogida->getCtUnidades();
                    $intPesoReal = $arPlanRecogida->getCtPesoReal();
                    $intPesoVolumen = $arPlanRecogida->getCtPesoVolumen();
                    $intRecogidas = $arPlanRecogida->getCtRecogidas();
                    foreach ($arrSeleccionados AS $codigoRecogida) {
                        $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogida();
                        $arRecogida = $em->getRepository('BrasaTransporteBundle:TteRecogida')->find($codigoRecogida);
                        $arRecogida->setEstadoAsignada(1);
                        $arRecogida->setPlanRecogidaRel($arPlanRecogida);
                        $em->persist($arRecogida);
                        $em->flush();
                        $intUnidades = $intUnidades + $arRecogida->getCtUnidades();
                        $intPesoReal = $intPesoReal + $arRecogida->getCtPesoReal();
                        $intPesoVolumen = $intPesoVolumen + $arRecogida->getCtPesoVolumen();
                        $intRecogidas = $intRecogidas + 1;
                    }

                    $arPlanRecogida->setCtUnidades($intUnidades);
                    $arPlanRecogida->setCtPesoReal($intPesoReal);
                    $arPlanRecogida->setCtPesoVolumen($intPesoVolumen);
                    $arPlanRecogida->setCtRecogidas($intRecogidas);
                    $em->persist($arPlanRecogida);
                    $em->flush();
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }               
        }        
        
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
                        $intRecogidas = $arDespacho->getCtGuias();
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
                            $intRecogidas = $intRecogidas + 1;
                        }

                        $arDespacho->setCtUnidades($intUnidades);
                        $arDespacho->setCtPesoReal($intPesoReal);
                        $arDespacho->setCtPesoVolumen($intPesoVolumen);
                        $arDespacho->setCtGuias($intRecogidas);
                        $em->persist($arDespacho);
                        $em->flush();
                    }
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    break;
            }
        }
        
        $query = $em->getRepository('BrasaTransporteBundle:TteRecogida')->ListaPendientes();
        $paginator = $this->get('knp_paginator');        
        $arRecogidas = new \Brasa\TransporteBundle\Entity\TteRecogida();
        $arRecogidas = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);
        
        return $this->render('BrasaTransporteBundle:Recogidas/Funciones:agregarRecogida.html.twig', array(
            'arRecogidas' => $arRecogidas, 
            'arPlanRecogida' => $arPlanRecogida,
            'form' => $form->createView()));
    }    
}
