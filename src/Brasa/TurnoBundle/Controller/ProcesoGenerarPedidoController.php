<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class ProcesoGenerarPedidoController extends Controller
{
    var $strListaDql = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();                     
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser()->getId(), 3, $this->getUser()->getRoles())) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }                    
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $dateFechaDesde = $form->get('fechaDesde')->getData();
            $dateFechaHasta = $form->get('fechaHasta')->getData();            
            if($request->request->get('OpGenerar')) {
                $codigoServicio = $request->request->get('OpGenerar');
                $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
                $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);                
                $arPedidoTipo = $em->getRepository('BrasaTurnoBundle:TurPedidoTipo')->find(2);
                $arPedidoNuevo = new \Brasa\TurnoBundle\Entity\TurPedido();                    
                $arPedidoNuevo->setPedidoTipoRel($arPedidoTipo);
                $arPedidoNuevo->setClienteRel($arServicio->getClienteRel());
                $arPedidoNuevo->setSectorRel($arServicio->getSectorRel());
                $arPedidoNuevo->setFecha($dateFechaDesde);
                $arPedidoNuevo->setFechaProgramacion($dateFechaDesde);                                        
                $em->persist($arPedidoNuevo);                    

                $arServicioDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                $arServicioDetalles =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $arServicio->getCodigoServicioPk())); 
                foreach ($arServicioDetalles as $arServicioDetalle) {
                    $arPedidoDetalleNuevo = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalleNuevo->setPedidoRel($arPedidoNuevo);
                                                
                    $intDiaInicial = 0;
                    $intDiaFinal = 0;

                    if($dateFechaDesde < $arServicioDetalle->getFechaHasta()) {
                        $dateFechaProceso = $dateFechaDesde;
                        if($arServicioDetalle->getFechaDesde() <= $dateFechaHasta) {
                            if($arServicioDetalle->getFechaDesde() > $dateFechaProceso) {
                                $dateFechaProceso = $arServicioDetalle->getFechaDesde();
                                if($dateFechaProceso <= $arServicioDetalle->getFechaHasta()) {
                                    $intDiaInicial = $dateFechaProceso->format('j');
                                }
                            } else {
                               $intDiaInicial = $dateFechaProceso->format('j'); 
                            }                            
                        } 
                        $dateFechaProceso = $dateFechaHasta;
                        if($dateFechaHasta >= $arServicioDetalle->getFechaDesde()) {
                            if($arServicioDetalle->getFechaHasta() < $dateFechaProceso) {
                                $dateFechaProceso = $arServicioDetalle->getFechaHasta();
                                if($dateFechaProceso >= $arServicioDetalle->getFechaHasta()) {
                                    $intDiaFinal =  $dateFechaProceso->format('j');                                
                                }                                                        
                            } else {
                                $intDiaFinal =  $dateFechaProceso->format('j');
                            }                            
                        }                        
                    }
                           
                    $arPedidoDetalleNuevo->setDiaDesde($intDiaInicial);
                    $arPedidoDetalleNuevo->setDiaHasta($intDiaFinal); 
                    
                    $strUltimoDiaMes = date("j",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                    $arPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();
                    if($intDiaInicial != 1 || $intDiaFinal != $strUltimoDiaMes) {
                        $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(2);
                    } else {
                        $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
                    }
                    $arPedidoDetalleNuevo->setPeriodoRel($arPeriodo);
                    $arPedidoDetalleNuevo->setCantidad($arServicioDetalle->getCantidad());
                    $arPedidoDetalleNuevo->setConceptoServicioRel($arServicioDetalle->getConceptoServicioRel());
                    $arPedidoDetalleNuevo->setModalidadServicioRel($arServicioDetalle->getModalidadServicioRel());                    
                    $arPedidoDetalleNuevo->setPuestoRel($arServicioDetalle->getPuestoRel());
                    $arPedidoDetalleNuevo->setPlantillaRel($arServicioDetalle->getPlantillaRel());
                    $arPedidoDetalleNuevo->setServicioDetalleRel($arServicioDetalle);
                    $arPedidoDetalleNuevo->setLunes($arServicioDetalle->getLunes());
                    $arPedidoDetalleNuevo->setMartes($arServicioDetalle->getMartes());
                    $arPedidoDetalleNuevo->setMiercoles($arServicioDetalle->getMiercoles());
                    $arPedidoDetalleNuevo->setJueves($arServicioDetalle->getJueves());
                    $arPedidoDetalleNuevo->setViernes($arServicioDetalle->getViernes());
                    $arPedidoDetalleNuevo->setSabado($arServicioDetalle->getSabado());
                    $arPedidoDetalleNuevo->setDomingo($arServicioDetalle->getDomingo());
                    $arPedidoDetalleNuevo->setFestivo($arServicioDetalle->getFestivo());    
                    $arPedidoDetalleNuevo->setVrPrecioAjustado($arServicioDetalle->getVrPrecioAjustado());
                    $arPedidoDetalleNuevo->setFechaIniciaPlantilla($arServicioDetalle->getFechaIniciaPlantilla());
                    if($intDiaInicial != 0 && $intDiaFinal != 0) { 
                        $em->persist($arPedidoDetalleNuevo);                      
                        $arServicioDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
                        $arServicioDetalleRecursos =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->findBy(array('codigoServicioDetalleFk' => $arServicioDetalle->getCodigoServicioDetallePk())); 
                        foreach ($arServicioDetalleRecursos as $arServicioDetalleRecurso) {
                            $arPedidoDetalleRecursoNuevo = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                            $arPedidoDetalleRecursoNuevo->setPedidoDetalleRel($arPedidoDetalleNuevo);
                            $arPedidoDetalleRecursoNuevo->setRecursoRel($arServicioDetalleRecurso->getRecursoRel());
                            $arPedidoDetalleRecursoNuevo->setPosicion($arServicioDetalleRecurso->getPosicion());
                            $em->persist($arPedidoDetalleRecursoNuevo);
                        }                        
                    }
                }                   
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($arPedidoNuevo->getCodigoPedidoPk());
                $em->getRepository('BrasaTurnoBundle:TurPedido')->autorizar($arPedidoNuevo->getCodigoPedidoPk());               
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_pedido_lista'));                                                 
            }
            if ($form->get('BtnGenerar')->isClicked()) {                 
                $arServicios = new \Brasa\TurnoBundle\Entity\TurServicio();
                $strDql = $em->getRepository('BrasaTurnoBundle:TurServicio')->listaDql("", "", 1);
                $query = $em->createQuery($strDql);
                $arServicios = $query->getResult();
                foreach ($arServicios as $arServicio) {
                    $codigoServicio = $arServicio->getCodigoServicioPk();
                    $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
                    $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);                
                    $arPedidoTipo = $em->getRepository('BrasaTurnoBundle:TurPedidoTipo')->find(2);
                    $arPedidoNuevo = new \Brasa\TurnoBundle\Entity\TurPedido();                    
                    $arPedidoNuevo->setPedidoTipoRel($arPedidoTipo);
                    $arPedidoNuevo->setClienteRel($arServicio->getClienteRel());
                    $arPedidoNuevo->setSectorRel($arServicio->getSectorRel());
                    $arPedidoNuevo->setFecha($dateFechaDesde);
                    $arPedidoNuevo->setFechaProgramacion($dateFechaDesde);                                        
                    $em->persist($arPedidoNuevo);                    

                    $arServicioDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                    $arServicioDetalles =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $arServicio->getCodigoServicioPk())); 
                    foreach ($arServicioDetalles as $arServicioDetalle) {
                        $arPedidoDetalleNuevo = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalleNuevo->setPedidoRel($arPedidoNuevo);

                        $intDiaInicial = 0;
                        $intDiaFinal = 0;
                        $dateFechaProceso = $dateFechaDesde;
                        if($arServicioDetalle->getFechaDesde() <= $dateFechaHasta) {
                            if($arServicioDetalle->getFechaDesde() > $dateFechaProceso) {
                                $dateFechaProceso = $arServicioDetalle->getFechaDesde();
                                if($dateFechaProceso <= $arServicioDetalle->getFechaHasta()) {
                                    $intDiaInicial = $dateFechaProceso->format('j');
                                }
                            } else {
                               $intDiaInicial = $dateFechaProceso->format('j'); 
                            }                            
                        } 
                        $dateFechaProceso = $dateFechaHasta;
                        if($dateFechaHasta >= $arServicioDetalle->getFechaDesde()) {
                            if($arServicioDetalle->getFechaHasta() < $dateFechaProceso) {
                                $dateFechaProceso = $arServicioDetalle->getFechaHasta();
                                if($dateFechaProceso >= $arServicioDetalle->getFechaHasta()) {
                                    $intDiaFinal =  $dateFechaProceso->format('j');                                
                                }                                                        
                            } else {
                                $intDiaFinal =  $dateFechaProceso->format('j');
                            }                            
                        }                           
                        $arPedidoDetalleNuevo->setDiaDesde($intDiaInicial);
                        $arPedidoDetalleNuevo->setDiaHasta($intDiaFinal); 

                        $strUltimoDiaMes = date("j",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                        $arPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();
                        if($intDiaInicial != 1 || $intDiaFinal != $strUltimoDiaMes) {
                            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(2);
                        } else {
                            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
                        }
                        $arPedidoDetalleNuevo->setPeriodoRel($arPeriodo);
                        $arPedidoDetalleNuevo->setCantidad($arServicioDetalle->getCantidad());
                        $arPedidoDetalleNuevo->setConceptoServicioRel($arServicioDetalle->getConceptoServicioRel());
                        $arPedidoDetalleNuevo->setModalidadServicioRel($arServicioDetalle->getModalidadServicioRel());                    
                        $arPedidoDetalleNuevo->setPuestoRel($arServicioDetalle->getPuestoRel());
                        $arPedidoDetalleNuevo->setPlantillaRel($arServicioDetalle->getPlantillaRel());
                        $arPedidoDetalleNuevo->setServicioDetalleRel($arServicioDetalle);
                        $arPedidoDetalleNuevo->setLunes($arServicioDetalle->getLunes());
                        $arPedidoDetalleNuevo->setMartes($arServicioDetalle->getMartes());
                        $arPedidoDetalleNuevo->setMiercoles($arServicioDetalle->getMiercoles());
                        $arPedidoDetalleNuevo->setJueves($arServicioDetalle->getJueves());
                        $arPedidoDetalleNuevo->setViernes($arServicioDetalle->getViernes());
                        $arPedidoDetalleNuevo->setSabado($arServicioDetalle->getSabado());
                        $arPedidoDetalleNuevo->setDomingo($arServicioDetalle->getDomingo());
                        $arPedidoDetalleNuevo->setFestivo($arServicioDetalle->getFestivo());    
                        $arPedidoDetalleNuevo->setVrPrecioAjustado($arServicioDetalle->getVrPrecioAjustado());
                        $arPedidoDetalleNuevo->setFechaIniciaPlantilla($arServicioDetalle->getFechaIniciaPlantilla());
                        if($intDiaInicial != 0 && $intDiaFinal != 0) { 
                            $em->persist($arPedidoDetalleNuevo);                      
                            $arServicioDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
                            $arServicioDetalleRecursos =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->findBy(array('codigoServicioDetalleFk' => $arServicioDetalle->getCodigoServicioDetallePk())); 
                            foreach ($arServicioDetalleRecursos as $arServicioDetalleRecurso) {
                                $arPedidoDetalleRecursoNuevo = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                                $arPedidoDetalleRecursoNuevo->setPedidoDetalleRel($arPedidoDetalleNuevo);
                                $arPedidoDetalleRecursoNuevo->setRecursoRel($arServicioDetalleRecurso->getRecursoRel());
                                $arPedidoDetalleRecursoNuevo->setPosicion($arServicioDetalleRecurso->getPosicion());
                                $em->persist($arPedidoDetalleRecursoNuevo);
                            }                        
                        }
                    }                   
                    $em->flush();
                    $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($arPedidoNuevo->getCodigoPedidoPk());
                    $em->getRepository('BrasaTurnoBundle:TurPedido')->autorizar($arPedidoNuevo->getCodigoPedidoPk()); 
                }
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_pedido_lista'));                                 
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arServicios = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarPedido:lista.html.twig', array(
            'arServicios' => $arServicios, 
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurServicio')->listaDql("","","",0);
    }
    
    private function formularioLista() {  
        $dateDia = new \DateTime('now');
        $strDia = $dateDia->format('Y/m/') . "01";
        $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateDia->format('m')+1,1,$dateDia->format('Y'))-1));
        $strDiaFinal = $dateDia->format('Y/m/') . $strUltimoDiaMes;
        
        $dateFechaDesde = date_create($strDia);
        $dateFechaHasta = date_create($strDiaFinal);
        $form = $this->createFormBuilder()
            ->add('fechaDesde', 'date', array('data'  => $dateFechaDesde, 'format' => 'y MMMM d'))
            ->add('fechaHasta', 'date', array('data'  => $dateFechaHasta, 'format' => 'y MMMM d'))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->getForm();
        return $form;
    }        
    
}