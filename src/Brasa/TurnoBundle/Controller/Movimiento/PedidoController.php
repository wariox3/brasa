<?php
namespace Brasa\TurnoBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurPedidoType;
use Brasa\TurnoBundle\Form\Type\TurPedidoDetalleType;
class PedidoController extends Controller
{
    var $strListaDql = ""; 
    var $strListaDqlRecurso = "";   
    var $codigoRecurso = "";
    var $nombreRecurso = "";    
    
    /**
     * @Route("/tur/movimiento/pedido", name="brs_tur_movimiento_pedido")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $this->estadoAnulado = 0;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {             
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurPedido')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido'));                 
                
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
        }
        $arPedidos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 50);
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:lista.html.twig', array(
            'arPedidos' => $arPedidos,            
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/pedido/nuevo/{codigoPedido}", name="brs_tur_movimiento_pedido_nuevo")
     */     
    public function nuevoAction($codigoPedido) {
        $request = $this->getRequest();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        if($codigoPedido != 0) {
            $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
        }else{
            $arPedido->setFecha(new \DateTime('now'));            
            $arPedido->setFechaProgramacion(new \DateTime('now'));            
        }
        $form = $this->createForm(new TurPedidoType, $arPedido);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPedido = $form->getData();            
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {                
                $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arPedido->setClienteRel($arCliente);
                    $fechaProgramacion = $arPedido->getFechaProgramacion()->format('Y/m/');
                    $arPedido->setFechaProgramacion(date_create($fechaProgramacion . '01'));
                    $arUsuario = $this->getUser();
                    $arPedido->setUsuario($arUsuario->getUserName());
                    $em->persist($arPedido);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_nuevo', array('codigoPedido' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $arPedido->getCodigoPedidoPk())));
                    }                       
                } else {
                    $objMensaje->Mensaje("error", "El cliente no existe", $this);
                }                             
            }            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:nuevo.html.twig', array(
            'arPedido' => $arPedido,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/pedido/detalle/{codigoPedido}", name="brs_tur_movimiento_pedido_detalle")
     */     
    public function detalleAction($codigoPedido) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
        $form = $this->formularioDetalle($arPedido);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) { 
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoPedido);                
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurPedido')->autorizar($codigoPedido);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                
            }    
            if($form->get('BtnAnular')->isClicked()) {                                 
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurPedido')->anular($codigoPedido);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                
            }            
            
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arPedido->getEstadoAutorizado() == 1) {
                    $arPedido->setEstadoAutorizado(0);
                    $em->persist($arPedido);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                
                }
            } 
            if($form->get('BtnProgramar')->isClicked()) {            
                if($arPedido->getEstadoProgramado() == 0 && $arPedido->getEstadoAutorizado() == 1) {                    
                    $codigoProgramacion = $this->programar($codigoPedido);
                    if($codigoProgramacion != 0) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));                                        
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                                        
                    }                    
                }
            }    
            if($form->get('BtnFacturar')->isClicked()) {            
                if($arPedido->getEstadoFacturado() == 0 && $arPedido->getEstadoAutorizado() == 1) {                    
                    $codigoFactura = $em->getRepository('BrasaTurnoBundle:TurPedido')->facturar($codigoPedido,  $this->getUser()->getUsername());
                    if($codigoFactura != 0) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                        
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                                        
                    }                    
                }
            }            
            if($form->get('BtnDesprogramar')->isClicked()) {            
                if($arPedido->getEstadoProgramado() == 1) {
                    $arPedido->setEstadoProgramado(0);
                    $em->persist($arPedido);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                
                }
            }               
            if($form->get('BtnDetalleActualizar')->isClicked()) {                
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoPedido);                                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($codigoPedido);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));
            }
            if($form->get('BtnDetalleDesprogramar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados AS $codigo) {                
                    $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);                
                    $arPedidoDetalle->setEstadoProgramado(0);
                    $em->persist($arPedidoDetalle);                  
                }                                         
                $em->flush();  
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));
            }            
            if($form->get('BtnImprimir')->isClicked()) {
                if($arPedido->getEstadoAutorizado() == 1) {
                    $objPedido = new \Brasa\TurnoBundle\Formatos\FormatoPedido();
                    $objPedido->Generar($this, $codigoPedido);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una cotizacion sin estar autorizada", $this);
                }
            }            
        }

        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array ('codigoPedidoFk' => $codigoPedido));
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:detalle.html.twig', array(
                    'arPedido' => $arPedido,
                    'arPedidoDetalle' => $arPedidoDetalle,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/tur/movimiento/pedido/detalle/nuevo/{codigoPedido}/{codigoPedidoDetalle}", name="brs_tur_movimiento_pedido_detalle_nuevo")
     */    
    public function detalleNuevoAction($codigoPedido, $codigoPedidoDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        if($codigoPedidoDetalle != 0) {
            $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);
        } else {
            $arPedidoDetalle->setFechaIniciaPlantilla($arPedido->getFechaProgramacion());
            $arPedidoDetalle->setPedidoRel($arPedido);
            $arPedidoDetalle->setCantidad(1);
            $arPedidoDetalle->setLunes(true);
            $arPedidoDetalle->setMartes(true);
            $arPedidoDetalle->setMiercoles(true);
            $arPedidoDetalle->setJueves(true);
            $arPedidoDetalle->setViernes(true);
            $arPedidoDetalle->setSabado(true);
            $arPedidoDetalle->setDomingo(true);
            $arPedidoDetalle->setFestivo(true);            
        }
        $form = $this->createForm(new TurPedidoDetalleType, $arPedidoDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPedidoDetalle = $form->getData();
            $arPeriodo = $form->get('periodoRel')->getData();
            if($arPeriodo->getCodigoPeriodoPk() == 1) {
                $intAnio = $arPedido->getFechaProgramacion()->format('Y');                
                $intMes = $arPedido->getFechaProgramacion()->format('m');
                $intDiaFinalMes = date("d",(mktime(0,0,0,$intMes+1,1,$intAnio)-1));
                $arPedidoDetalle->setDiaDesde(1);
                $arPedidoDetalle->setDiaHasta($intDiaFinalMes);
            }
            $em->persist($arPedidoDetalle);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle_nuevo', array('codigoPedido' => $codigoPedido, 'codigoPedidoDetalle' => 0 )));
            } else {
                $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($codigoPedido);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:detalleNuevo.html.twig', array(
            'arPedido' => $arPedido,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/pedido/detalle/cotizacion/nuevo/{codigoPedido}/{codigoPedidoDetalle}", name="brs_tur_movimiento_pedido_detalle_cotizacion_nuevo")
     */    
    public function detalleNuevoCotizacionAction($codigoPedido, $codigoPedidoDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arCotizacion = new \Brasa\TurnoBundle\Entity\TurCotizacion();
                        $arCotizacion = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->find($codigo);                                                
                        $arCotizacionDetalles = new \Brasa\TurnoBundle\Entity\TurCotizacionDetalle();
                        $arCotizacionDetalles = $em->getRepository('BrasaTurnoBundle:TurCotizacionDetalle')->findBy(array('codigoCotizacionFk' => $arCotizacion->getCodigoCotizacionPk()));
                        foreach($arCotizacionDetalles as $arCotizacionDetalle) {
                            $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                            $arPedidoDetalle->setPedidoRel($arPedido);
                            $arPedidoDetalle->setModalidadServicioRel($arCotizacionDetalle->getModalidadServicioRel());
                            $arPedidoDetalle->setPeriodoRel($arCotizacionDetalle->getPeriodoRel());
                            $arPedidoDetalle->setConceptoServicioRel($arCotizacionDetalle->getConceptoServicioRel());
                            $arPedidoDetalle->setDias($arCotizacionDetalle->getDias());
                            $arPedidoDetalle->setLunes($arCotizacionDetalle->getLunes());
                            $arPedidoDetalle->setMartes($arCotizacionDetalle->getMartes());
                            $arPedidoDetalle->setMiercoles($arCotizacionDetalle->getMiercoles());
                            $arPedidoDetalle->setJueves($arCotizacionDetalle->getJueves());
                            $arPedidoDetalle->setViernes($arCotizacionDetalle->getViernes());
                            $arPedidoDetalle->setSabado($arCotizacionDetalle->getSabado());
                            $arPedidoDetalle->setDomingo($arCotizacionDetalle->getDomingo());
                            $arPedidoDetalle->setFestivo($arCotizacionDetalle->getFestivo());                            
                            $arPedidoDetalle->setCantidad($arCotizacionDetalle->getCantidad());
                            $arPedidoDetalle->setVrPrecioAjustado($arCotizacionDetalle->getVrPrecioAjustado());
                    
                            $strAnioMes = $arPedido->getFechaProgramacion()->format('Y/m/');
                            $dateFechaDesde = date_create($strAnioMes . "1");
                            $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                            $dateFechaHasta = date_create($strAnioMes . $strUltimoDiaMes);
                            $intDiaInicial = 0;
                            $intDiaFinal = 0;
                            if($dateFechaDesde < $arCotizacionDetalle->getFechaHasta()) {
                                $dateFechaProceso = $dateFechaDesde;
                                if($arCotizacionDetalle->getFechaDesde() <= $dateFechaHasta) {
                                    if($arCotizacionDetalle->getFechaDesde() > $dateFechaProceso) {
                                        $dateFechaProceso = $arCotizacionDetalle->getFechaDesde();
                                        if($dateFechaProceso <= $arCotizacionDetalle->getFechaHasta()) {
                                            $intDiaInicial = $dateFechaProceso->format('j');
                                        }
                                    } else {
                                       $intDiaInicial = $dateFechaProceso->format('j'); 
                                    }                            
                                } 
                                $dateFechaProceso = $dateFechaHasta;
                                if($dateFechaHasta >= $arCotizacionDetalle->getFechaDesde()) {
                                    if($arCotizacionDetalle->getFechaHasta() < $dateFechaProceso) {
                                        $dateFechaProceso = $arCotizacionDetalle->getFechaHasta();
                                        if($dateFechaProceso >= $arCotizacionDetalle->getFechaHasta()) {
                                            $intDiaFinal =  $dateFechaProceso->format('j');                                
                                        }                                                        
                                    } else {
                                        $intDiaFinal =  $dateFechaProceso->format('j');
                                    }                            
                                }                                
                            }
                           
                            $arPedidoDetalle->setDiaDesde($intDiaInicial);
                            $arPedidoDetalle->setDiaHasta($intDiaFinal); 

                            $strUltimoDiaMes = date("j",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                            $arPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();
                            if($intDiaInicial != 1 || $intDiaFinal != $strUltimoDiaMes) {
                                $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(2);
                            } else {
                                $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
                            }                            
                            $arPedidoDetalle->setPeriodoRel($arPeriodo);                            
                            if($intDiaInicial != 0 && $intDiaFinal != 0) {
                                $em->persist($arPedidoDetalle);
                            }                                                      
                        }                       
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($codigoPedido);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arCotizaciones = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->pendientes($arPedido->getCodigoClienteFk());
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:detalleNuevoCotizacion.html.twig', array(
            'arPedido' => $arPedido,
            'arCotizaciones' => $arCotizaciones,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/movimiento/pedido/detalle/Servicio/nuevo/{codigoPedido}/{codigoPedidoDetalle}", name="brs_tur_movimiento_pedido_detalle_servicio_nuevo")
     */     
    public function detalleNuevoServicioAction($codigoPedido, $codigoPedidoDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {                                                
                        $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                        $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigo);
                        
                        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalle->setPedidoRel($arPedido);
                        $arPedidoDetalle->setModalidadServicioRel($arServicioDetalle->getModalidadServicioRel());
                        $arPedidoDetalle->setPeriodoRel($arServicioDetalle->getPeriodoRel());
                        $arPedidoDetalle->setConceptoServicioRel($arServicioDetalle->getConceptoServicioRel());
                        $arPedidoDetalle->setPlantillaRel($arServicioDetalle->getPlantillaRel());                        
                        $arPedidoDetalle->setPuestoRel($arServicioDetalle->getPuestoRel());
                        $arPedidoDetalle->setServicioDetalleRel($arServicioDetalle);
                        $arPedidoDetalle->setDias($arServicioDetalle->getDias());
                        $arPedidoDetalle->setLunes($arServicioDetalle->getLunes());
                        $arPedidoDetalle->setMartes($arServicioDetalle->getMartes());
                        $arPedidoDetalle->setMiercoles($arServicioDetalle->getMiercoles());
                        $arPedidoDetalle->setJueves($arServicioDetalle->getJueves());
                        $arPedidoDetalle->setViernes($arServicioDetalle->getViernes());
                        $arPedidoDetalle->setSabado($arServicioDetalle->getSabado());
                        $arPedidoDetalle->setDomingo($arServicioDetalle->getDomingo());
                        $arPedidoDetalle->setFestivo($arServicioDetalle->getFestivo());                            
                        $arPedidoDetalle->setCantidad($arServicioDetalle->getCantidad());
                        $arPedidoDetalle->setVrPrecioAjustado($arServicioDetalle->getVrPrecioAjustado());
                        $arPedidoDetalle->setFechaIniciaPlantilla($arServicioDetalle->getFechaIniciaPlantilla());
                        
                        $strAnioMes = $arPedido->getFechaProgramacion()->format('Y/m/');
                        $dateFechaDesde = date_create($strAnioMes . "1");
                        $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                        $dateFechaHasta = date_create($strAnioMes . $strUltimoDiaMes);
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
                           
                        $arPedidoDetalle->setDiaDesde($intDiaInicial);
                        $arPedidoDetalle->setDiaHasta($intDiaFinal); 

                        $strUltimoDiaMes = date("j",(mktime(0,0,0,$dateFechaDesde->format('m')+1,1,$dateFechaDesde->format('Y'))-1));
                        $arPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();
                        if($intDiaInicial != 1 || $intDiaFinal != $strUltimoDiaMes) {
                            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(2);
                        } else {
                            $arPeriodo = $em->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
                        }                            
                        $arPedidoDetalle->setPeriodoRel($arPeriodo);                         
                        
                        if($intDiaInicial != 0 && $intDiaFinal != 0) {
                            $em->persist($arPedidoDetalle);                                               
                            $arServicioDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
                            $arServicioDetalleRecursos = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->findBy(array('codigoServicioDetalleFk' => $arServicioDetalle->getCodigoServicioDetallePk()));
                            foreach ($arServicioDetalleRecursos as $arServicioDetalleRecurso) {
                                $arPedidoDetalleRecurso = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                                $arPedidoDetalleRecurso->setPedidoDetalleRel($arPedidoDetalle);
                                $arPedidoDetalleRecurso->setRecursoRel($arServicioDetalleRecurso->getRecursoRel());
                                $arPedidoDetalleRecurso->setPosicion($arServicioDetalleRecurso->getPosicion());
                                $em->persist($arPedidoDetalleRecurso);
                            }                            
                        }
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($codigoPedido);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arServicioDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->pendientesCliente($arPedido->getCodigoClienteFk());
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:detalleNuevoServicio.html.twig', array(
            'arPedido' => $arPedido,
            'arServicioDetalles' => $arServicioDetalles,
            'form' => $form->createView()));
    }        
        
    /**
     * @Route("/tur/movimiento/pedido/detalle/recurso/{codigoPedidoDetalle}", name="brs_tur_movimiento_pedido_detalle_recurso")
     */    
    public function detalleRecursoAction($codigoPedidoDetalle = 0) {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $em = $this->getDoctrine()->getManager();
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);        
        $form = $this->formularioRecurso($arPedidoDetalle->getFechaIniciaPlantilla(), $arPedidoDetalle->getPlantillaRel());
        $form->handleRequest($request);
        if ($form->isValid()) {
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleRecurso')->eliminarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle_recurso', array('codigoPedidoDetalle' => $codigoPedidoDetalle)));                                
            } 
            if($form->get('BtnDetalleActualizar')->isClicked()) {                
                $arrControles = $request->request->All();
                $this->actualizarDetalleRecurso($arrControles);                                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle_recurso', array('codigoPedidoDetalle' => $codigoPedidoDetalle)));                                
            }       
            if($form->get('BtnGuardarPedidoDetalle')->isClicked()) {                   
                $fechaIniciaPlantilla = $form->get('fechaIniciaPlantilla')->getData();                
                $arPedidoDetalle->setFechaIniciaPlantilla($fechaIniciaPlantilla);
                $arPedidoDetalle->setPlantillaRel($form->get('plantillaRel')->getData());
                $em->persist($arPedidoDetalle);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle_recurso', array('codigoPedidoDetalle' => $codigoPedidoDetalle)));                                
            }            
        }
        $arPlantillaDetalles = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
        if($arPedidoDetalle->getPlantillaRel()) {            
            $arPlantillaDetalles = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array('codigoPlantillaFk' => $arPedidoDetalle->getCodigoPlantillaFk()));
        }
        $strLista = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleRecurso')->listaDql($codigoPedidoDetalle);
        $arPedidoDetalleRecursos = $paginator->paginate($em->createQuery($strLista), $request->query->get('page', 1), 20);        
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:detalleRecurso.html.twig', array(
            'arPedidoDetalle' => $arPedidoDetalle,
            'arPedidoDetalleRecursos' => $arPedidoDetalleRecursos,
            'arPlantillaDetalle' => $arPlantillaDetalles,
            'form' => $form->createView()));
    }        
        
    /**
     * @Route("/tur/movimiento/pedido/detalle/recurso/nuevo/{codigoPedidoDetalle}", name="brs_tur_movimiento_pedido_detalle_recurso_nuevo")
     */    
    public function detalleRecursoNuevoAction($codigoPedidoDetalle = 0) {
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);        
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->nombreRecurso))
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->codigoRecurso))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))                
                
            ->getForm();        
        $form->handleRequest($request);
        $this->listaRecurso();
        if ($form->isValid()) {    
            if($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigo) {
                        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigo);
                        $arPedidoDetalleRecurso = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                        $arPedidoDetalleRecurso->setPedidoDetalleRel($arPedidoDetalle);
                        $arPedidoDetalleRecurso->setRecursoRel($arRecurso);
                        $em->persist($arPedidoDetalleRecurso);
                    }
                    $em->flush();
                } 
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarRecurso($form);
                $this->listaRecurso();
            }                        
        }
        $arRecurso = $paginator->paginate($em->createQuery($this->strListaDqlRecurso), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:detalleRecursoNuevo.html.twig', array(
            'arPedidoDetalle' => $arPedidoDetalle,
            'arRecursos' => $arRecurso,
            'form' => $form->createView()));
    }    

    /**
     * @Route("/tur/movimiento/pedido/detalle/resumen/{codigoPedidoDetalle}", name="brs_tur_movimiento_pedido_detalle_resumen")
     */    
    public function detalleResumenAction($codigoPedidoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();        
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);
        $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();       
        $arFacturaDetalles = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle));
        $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();       
        $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle));        
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:detalleResumen.html.twig', array(
                    'arPedidoDetalle' => $arPedidoDetalle,
                    'arFacturaDetalles' => $arFacturaDetalles,
                    'arProgramacionDetalles' => $arProgramacionDetalles
                    ));
    }    
    
    private function lista() {   
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";        
        $filtrarFecha = $session->get('filtroPedidoFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroPedidoFechaDesde');
            $strFechaHasta = $session->get('filtroPedidoFechaHasta');                    
        }
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurPedido')->listaDQL(
                $session->get('filtroPedidoNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroPedidoEstadoAutorizado'), 
                $session->get('filtroPedidoEstadoProgramado'),
                $session->get('filtroPedidoEstadoFacturado'),
                $session->get('filtroPedidoEstadoAnulado'),
                $strFechaDesde,
                $strFechaHasta);
    }    

    private function listaRecurso() {        
        $em = $this->getDoctrine()->getManager();
        $this->strListaDqlRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->listaDQL(
                $this->nombreRecurso,                
                $this->codigoRecurso   
                ); 
    }    
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();        
        $session->set('filtroPedidoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroPedidoEstadoAutorizado', $form->get('estadoAutorizado')->getData());          
        $session->set('filtroPedidoEstadoProgramado', $form->get('estadoProgramado')->getData());          
        $session->set('filtroPedidoEstadoFacturado', $form->get('estadoFacturado')->getData());          
        $session->set('filtroPedidoEstadoAnulado', $form->get('estadoAnulado')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroPedidoFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroPedidoFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroPedidoFiltrarFecha', $form->get('filtrarFecha')->getData());
        
    }

    private function filtrarRecurso($form) {       
        $this->nombreRecurso = $form->get('TxtNombre')->getData();
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }       
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroPedidoFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroPedidoFechaDesde');
        }
        if($session->get('filtroPedidoFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroPedidoFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))
            ->add('estadoAutorizado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroPedidoEstadoAutorizado')))                
            ->add('estadoProgramado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'PROGRAMADO', '0' => 'SIN PROGRAMAR'), 'data' => $session->get('filtroPedidoEstadoProgramado')))                                
            ->add('estadoFacturado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'FACTURADO', '0' => 'SIN FACTURAR'), 'data' => $session->get('filtroPedidoEstadoFacturado')))                                
            ->add('estadoAnulado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'ANULADO', '0' => 'SIN ANULAR'), 'data' => $session->get('filtroPedidoEstadoAnulado')))                                
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', 'checkbox', array('required'  => false, 'data' => $session->get('filtroPedidoFiltrarFecha')))                 
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);        
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);        
        $arrBotonProgramar = array('label' => 'Programar', 'disabled' => true);        
        $arrBotonFacturar = array('label' => 'Facturar', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);        
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleDesprogramar = array('label' => 'Desprogramar', 'disabled' => false);
        $arrBotonDesprogramar = array('label' => 'Desprogramar', 'disabled' => true);        
        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonProgramar['disabled'] = false;
            $arrBotonAnular['disabled'] = false; 
            if($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;                
                $arrBotonDetalleDesprogramar['disabled'] = true;
            } else {
                if($ar->getEstadoFacturado() == 0) {
                    $arrBotonFacturar['disabled'] = false;
                }
            }
        } else {            
            $arrBotonDesAutorizar['disabled'] = true;                        
            $arrBotonImprimir['disabled'] = true;            
        }
        if($ar->getEstadoAprobado() == 1 && $ar->getEstadoAnulado() == 0) {
            $arrBotonDesAutorizar['disabled'] = true;                        
        } 
        if($ar->getEstadoProgramado() == 1 && $ar->getEstadoAnulado() == 0) {
            $arrBotonDesprogramar['disabled'] = false;
            $arrBotonProgramar['disabled'] = true;
            $arrBotonDesAutorizar['disabled'] = true; 
        } 
        $form = $this->createFormBuilder()
                    ->add('BtnFacturar', 'submit', $arrBotonFacturar)                
                    ->add('BtnProgramar', 'submit', $arrBotonProgramar)            
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                 
                    ->add('BtnAnular', 'submit', $arrBotonAnular)                                     
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
                    ->add('BtnDetalleDesprogramar', 'submit', $arrBotonDetalleDesprogramar)
                    ->add('BtnDesprogramar', 'submit', $arrBotonDesprogramar)                 
                    ->getForm();
        return $form;
    }

    private function formularioRecurso($fechaIniciaPlantilla, $arPlantilla) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('plantillaRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPlantilla',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'data' => $arPlantilla,
                'required' => false))                 
            ->add('fechaIniciaPlantilla', 'date', array('data'  => $fechaIniciaPlantilla, 'format' => 'y MMMM d'))                            
            ->add('BtnDetalleEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnDetalleActualizar', 'submit', array('label'  => 'Actualizar',))            
            ->add('BtnGuardarPedidoDetalle', 'submit', array('label'  => 'Guardar',))                            
            ->getForm();
        return $form;
    }    
    
    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        $em = $this->getDoctrine()->getManager();        
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }     
        for($col = 'M'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'NÚMERO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'AÑO')
                    ->setCellValue('F1', 'MES')
                    ->setCellValue('G1', 'CLIENTE')
                    ->setCellValue('H1', 'SECTOR')
                    ->setCellValue('I1', 'AUT')
                    ->setCellValue('J1', 'PRO')
                    ->setCellValue('K1', 'FAC')
                    ->setCellValue('L1', 'ANU')
                    ->setCellValue('M1', 'HORAS')
                    ->setCellValue('N1', 'H.DIURNAS')
                    ->setCellValue('O1', 'H.NOCTURNAS')
                    ->setCellValue('P1', 'P.MINIMO')
                    ->setCellValue('Q1', 'P.AJUSTADO')
                    ->setCellValue('R1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arPedidos = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedidos = $query->getResult();

        foreach ($arPedidos as $arPedido) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedido->getCodigoPedidoPk())
                    ->setCellValue('B' . $i, $arPedido->getPedidoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arPedido->getNumero())
                    ->setCellValue('D' . $i, $arPedido->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arPedido->getFechaProgramacion()->format('Y'))
                    ->setCellValue('F' . $i, $arPedido->getFechaProgramacion()->format('F'))                    
                    ->setCellValue('G' . $i, $arPedido->getClienteRel()->getNombreCorto())
                    ->setCellValue('H' . $i, $arPedido->getSectorRel()->getNombre())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arPedido->getEstadoAutorizado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arPedido->getEstadoProgramado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arPedido->getEstadoFacturado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arPedido->getEstadoAnulado()))
                    ->setCellValue('M' . $i, $arPedido->getHoras())
                    ->setCellValue('N' . $i, $arPedido->getHorasDiurnas())
                    ->setCellValue('O' . $i, $arPedido->getHorasNocturnas())
                    ->setCellValue('P' . $i, $arPedido->getVrTotalPrecioMinimo())
                    ->setCellValue('Q' . $i, $arPedido->getVrTotalPrecioAjustado())
                    ->setCellValue('R' . $i, $arPedido->getVrTotal());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pedidos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pedidos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
    
    private function actualizarDetalle($arrControles, $codigoPedido) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($intCodigo);
                $arPedidoDetalle->setCantidad($arrControles['TxtCantidad'.$intCodigo]);            
                $arPedidoDetalle->setDiaDesde($arrControles['TxtDiaDesde'.$intCodigo]);
                $arPedidoDetalle->setDiaHasta($arrControles['TxtDiaHasta'.$intCodigo]);
                if($arrControles['TxtValorAjustado'.$intCodigo] != '') {
                    $arPedidoDetalle->setVrPrecioAjustado($arrControles['TxtValorAjustado'.$intCodigo]);                
                }            
                if(isset($arrControles['chkLunes'.$intCodigo])) {
                    $arPedidoDetalle->setLunes(1);
                } else {
                    $arPedidoDetalle->setLunes(0);
                }
                if(isset($arrControles['chkMartes'.$intCodigo])) {
                    $arPedidoDetalle->setMartes(1);
                } else {
                    $arPedidoDetalle->setMartes(0);
                }
                if(isset($arrControles['chkMiercoles'.$intCodigo])) {
                    $arPedidoDetalle->setMiercoles(1);
                } else {
                    $arPedidoDetalle->setMiercoles(0);
                }
                if(isset($arrControles['chkJueves'.$intCodigo])) {
                    $arPedidoDetalle->setJueves(1);
                } else {
                    $arPedidoDetalle->setJueves(0);
                }
                if(isset($arrControles['chkViernes'.$intCodigo])) {
                    $arPedidoDetalle->setViernes(1);
                } else {
                    $arPedidoDetalle->setViernes(0);
                }
                if(isset($arrControles['chkSabado'.$intCodigo])) {
                    $arPedidoDetalle->setSabado(1);
                } else {
                    $arPedidoDetalle->setSabado(0);
                }
                if(isset($arrControles['chkDomingo'.$intCodigo])) {
                    $arPedidoDetalle->setDomingo(1);
                } else {
                    $arPedidoDetalle->setDomingo(0);
                }
                if(isset($arrControles['chkFestivo'.$intCodigo])) {
                    $arPedidoDetalle->setFestivo(1);
                } else {
                    $arPedidoDetalle->setFestivo(0);
                }                    
                $em->persist($arPedidoDetalle);
            }
            $em->flush();                
            $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($codigoPedido);            
        }        
    }
    
    private function actualizarDetalleRecurso($arrControles) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arPedidoDetalleRecurso = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                $arPedidoDetalleRecurso = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleRecurso')->find($intCodigo);
                $arPedidoDetalleRecurso->setPosicion($arrControles['TxtPosicion'.$intCodigo]);
                $em->persist($arPedidoDetalleRecurso);
            }
            $em->flush();                                    
        }
    }
    
    private function programar($codigoPedido) {
        $em = $this->getDoctrine()->getManager();
        $codigoProgramacion = 0;
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido); 
        $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido, 'estadoProgramado' => 0));         
        if(count($arPedidoDetalles) > 0) {            
            $arPedido->setEstadoProgramado(1);
            $em->persist($arPedido);            
            $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
            $arProgramacion->setClienteRel($arPedido->getClienteRel());
            $arProgramacion->setFecha($arPedido->getFechaProgramacion());
            $arProgramacion->setAnio($arPedido->getFechaProgramacion()->format('Y'));
            $arProgramacion->setMes($arPedido->getFechaProgramacion()->format('m'));
            $arUsuario = $this->getUser();
            $arProgramacion->setUsuario($arUsuario->getUserName()); 
            $em->persist($arProgramacion);
            $em->flush();
            $codigoProgramacion = $arProgramacion->getCodigoProgramacionPk();
            foreach ($arPedidoDetalles as $arPedidoDetalle) {            
                $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->nuevo($arPedidoDetalle->getCodigoPedidoDetallePk(), $arProgramacion);
            }                    
        }
        return $codigoProgramacion;
    }    
}