<?php
namespace Brasa\TurnoBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurFacturaType;
use Brasa\TurnoBundle\Form\Type\TurFacturaDetalleType;
use Brasa\TurnoBundle\Form\Type\TurFacturaDetalleNuevoType;
class FacturaController extends Controller
{
    var $strListaDql = "";    
    var $boolMostrarTodo = "";
    
    /**
     * @Route("/tur/movimiento/factura", name="brs_tur_movimiento_factura")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {            
            if ($form->get('BtnContabilizar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurFactura')->contabilizar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura'));                                 
            }            
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurFactura')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura'));                                 
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
            if ($form->get('BtnInterfaz')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcelInterfaz();
            }            
        }

        $arFacturas = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:lista.html.twig', array(
            'arFacturas' => $arFacturas,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/factura/nuevo/{codigoFactura}", name="brs_tur_movimiento_factura_nuevo")
     */
    public function nuevoAction($codigoFactura) {
        $request = $this->getRequest();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        if($codigoFactura != 0) {
            $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        }else{
            $arFactura->setFecha(new \DateTime('now'));
            $arFactura->setFechaVence(new \DateTime('now'));
        }
        $form = $this->createForm(new TurFacturaType, $arFactura);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFactura = $form->getData();            
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {
                $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arFactura->setClienteRel($arCliente);
                    $arClienteDireccion = new \Brasa\TurnoBundle\Entity\TurClienteDireccion();
                    if($arrControles['txtCodigoDireccion'] != '') {                        
                        $arClienteDireccion = $em->getRepository('BrasaTurnoBundle:TurClienteDireccion')->find($arrControles['txtCodigoDireccion']);                
                        if(count($arClienteDireccion) > 0) {
                            if($arClienteDireccion->getCodigoClienteFk() == $arCliente->getCodigoClientePk()) {
                                $arFactura->setClienteDireccionRel($arClienteDireccion);                                
                            } else {
                                $objMensaje->Mensaje("error", "La direccion no pertenece al cliente", $this);
                            }                            
                        }                        
                    }
                    if($codigoFactura == 0) {
                        $arFactura->setImprimirAgrupada($arFactura->getClienteRel()->getFacturaAgrupada());
                    }
                    $dateFechaVence = $objFunciones->sumarDiasFecha($arCliente->getPlazoPago(), $arFactura->getFecha());
                    $arFactura->setFechaVence($dateFechaVence); 
                    $arUsuario = $this->getUser();
                    $arFactura->setUsuario($arUsuario->getUserName());
                    $arFactura->setOperacion($arFactura->getFacturaTipoRel()->getOperacion());
                    $em->persist($arFactura);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_nuevo', array('codigoFactura' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $arFactura->getCodigoFacturaPk())));
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "El tercero no existe", $this);
                }                             
            }            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/factura/detalle/{codigoFactura}", name="brs_tur_movimiento_factura_detalle")
     */
    public function detalleAction($codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $form = $this->formularioDetalle($arFactura);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {      
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoFactura);
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurFactura')->autorizar($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                
            }    
            if($form->get('BtnDesAutorizar')->isClicked()) {                            
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurFactura')->desAutorizar($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                
            }               
            if($form->get('BtnDetalleActualizar')->isClicked()) {   
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoFactura);                                 
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }           
            
            if($form->get('BtnAnular')->isClicked()) {                                 
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurFactura')->anular($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                
            }            
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
           
            if($form->get('BtnImprimir')->isClicked()) {
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurFactura')->imprimir($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                } else {
                    if($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                        $objFactura = new \Brasa\TurnoBundle\Formatos\Factura2();
                        $objFactura->Generar($this, $codigoFactura);                                            
                    } else {
                        $objNotaCredito = new \Brasa\TurnoBundle\Formatos\NotaCredito2();
                        $objNotaCredito->Generar($this, $codigoFactura);                        
                    }
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                                
            }
            if($form->get('BtnVistaPrevia')->isClicked()) {                                
                if($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                    $objFactura = new \Brasa\TurnoBundle\Formatos\Factura2();
                    $objFactura->Generar($this, $codigoFactura);                                            
                } 
                if($arFactura->getFacturaTipoRel()->getTipo() == 2) {
                    $objNotaCredito = new \Brasa\TurnoBundle\Formatos\NotaCredito2();
                    $objNotaCredito->Generar($this, $codigoFactura);                        
                } 
                if($arFactura->getFacturaTipoRel()->getTipo() == 3) {
                    $objNotaDebito = new \Brasa\TurnoBundle\Formatos\NotaDebito2();
                    $objNotaDebito->Generar($this, $codigoFactura);                        
                }                 
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                                
            }            
        }
        
        $dql = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->listaDql($codigoFactura);       
        $arFacturaDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 150);                
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalle.html.twig', array(
                    'arFactura' => $arFactura,
                    'arFacturaDetalle' => $arFacturaDetalle,
                    'form' => $form->createView()
                    ));
    }
     
    /**
     * @Route("/tur/movimiento/factura/detalle/nuevo/{codigoFactura}/{codigoFacturaDetalle}", name="brs_tur_movimiento_factura_detalle_nuevo")
     */    
    public function detalleNuevoAction($codigoFactura, $codigoFacturaDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        if($codigoFacturaDetalle != 0) {
            $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigoFacturaDetalle);
        } else {
            $arFacturaDetalle->setFacturaRel($arFactura);
        }
        $form = $this->createForm(new TurFacturaDetalleNuevoType, $arFacturaDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFacturaDetalle = $form->getData();   
            $arConceptoFactura = $form->get('conceptoServicioRel')->getData();
            $arFacturaDetalle->setPorIva($arConceptoFactura->getPorIva());
            $arFacturaDetalle->setPorBaseIva($arConceptoFactura->getPorBaseIva());
            $arFacturaDetalle->setFechaProgramacion($arFactura->getFecha());
            $arFacturaDetalle->setOperacion($arFactura->getOperacion());
            $em->persist($arFacturaDetalle);
            $em->flush();
            $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleNuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/movimiento/factura/detalle/pedido/nuevo/{codigoFactura}", name="brs_tur_movimiento_factura_detalle_pedido_nuevo")
     */
    public function detallePedidoNuevoAction($codigoFactura) {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);        
        $form = $this->formularioDetalleNuevo();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {    
                    foreach ($arrSeleccionados AS $codigo) {
                        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);                        
                        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                        $arFacturaDetalle->setFacturaRel($arFactura);                        
                        $arFacturaDetalle->setConceptoServicioRel($arPedidoDetalle->getConceptoServicioRel());
                        $arFacturaDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arFacturaDetalle->setModalidadServicioRel($arPedidoDetalle->getModalidadServicioRel());
                        $arFacturaDetalle->setGrupoFacturacionRel($arPedidoDetalle->getGrupoFacturacionRel());
                        $arFacturaDetalle->setPedidoDetalleRel($arPedidoDetalle);
                        $arFacturaDetalle->setCantidad($arPedidoDetalle->getCantidad());
                        $arFacturaDetalle->setVrPrecio($arPedidoDetalle->getVrPrecio());
                        $arFacturaDetalle->setPorIva($arPedidoDetalle->getConceptoServicioRel()->getPorIva());
                        $arFacturaDetalle->setPorBaseIva($arPedidoDetalle->getConceptoServicioRel()->getPorBaseIva());
                        $arFacturaDetalle->setFechaProgramacion($arPedidoDetalle->getPedidoRel()->getFechaProgramacion());
                        $arFacturaDetalle->setTipoPedido($arPedidoDetalle->getPedidoRel()->getPedidoTipoRel()->getTipo());
                        $arFacturaDetalle->setDetalle($arPedidoDetalle->getDetalle());
                        $arFacturaDetalle->setOperacion($arFactura->getOperacion());
                        $em->persist($arFacturaDetalle);   
                    }
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            } 
            if ($form->get('BtnFiltrar')->isClicked()) {            
                $this->filtrarDetalleNuevo($form);
            }
        }
        
        $arPedidoDetalles = $paginator->paginate($em->createQuery($this->listaDetalleNuevo($arFactura->getCodigoClienteFk())), $request->query->get('page', 1), 500);        
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleNuevoPedido.html.twig', array(
            'arFactura' => $arFactura,
            'arPedidoDetalles' => $arPedidoDetalles,
            'boolMostrarTodo' => $form->get('mostrarTodo')->getData(),
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/factura/detalle/factura/nuevo/{codigoFactura}/{tipoCruce}", name="brs_tur_movimiento_factura_detalle_factura_nuevo")
     */
    public function detalleFacturaNuevoAction($codigoFactura, $tipoCruce) {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);        
        $form = $this->createFormBuilder()            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar',))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {    
                    foreach ($arrSeleccionados AS $codigo) {
                        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                        $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigo);                        
                        $arFacturaDetalleNueva = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                        $arFacturaDetalleNueva->setFacturaRel($arFactura);                        
                        $arFacturaDetalleNueva->setConceptoServicioRel($arFacturaDetalle->getConceptoServicioRel());
                        $arFacturaDetalleNueva->setPuestoRel($arFacturaDetalle->getPuestoRel());
                        $arFacturaDetalleNueva->setModalidadServicioRel($arFacturaDetalle->getModalidadServicioRel());
                        $arFacturaDetalleNueva->setGrupoFacturacionRel($arFacturaDetalle->getGrupoFacturacionRel());
                        $arFacturaDetalleNueva->setPedidoDetalleRel($arFacturaDetalle->getPedidoDetalleRel());
                        $arFacturaDetalleNueva->setFacturaDetalleRel($arFacturaDetalle);
                        $arFacturaDetalleNueva->setCantidad($arFacturaDetalle->getCantidad());
                        $arFacturaDetalleNueva->setVrPrecio($arFacturaDetalle->getVrPrecio());
                        $arFacturaDetalleNueva->setPorIva($arFacturaDetalle->getConceptoServicioRel()->getPorIva());
                        $arFacturaDetalleNueva->setPorBaseIva($arFacturaDetalle->getConceptoServicioRel()->getPorBaseIva());
                        $arFacturaDetalleNueva->setFechaProgramacion($arFacturaDetalle->getFechaProgramacion());
                        $arFacturaDetalleNueva->setDetalle($arFacturaDetalle->getDetalle());
                        $arFacturaDetalleNueva->setOperacion($arFactura->getOperacion());
                        $em->persist($arFacturaDetalleNueva);   
                    }
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            } 
            if ($form->get('BtnFiltrar')->isClicked()) {            
                //$this->filtrarDetalleNuevo($form);
            }
        }
        
        $dql = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->listaCliente($arFactura->getCodigoClienteFk(), "", $tipoCruce);
        $arFacturaDetalles = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 500);        
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleNuevoFactura.html.twig', array(
            'arFactura' => $arFactura,
            'arFacturaDetalles' => $arFacturaDetalles,            
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/movimiento/factura/detalle/editar/{codigoFactura}/{codigoFacturaDetalle}", name="brs_tur_movimiento_factura_detalle_editar")
     */    
    public function detalleEditarAction($codigoFactura, $codigoFacturaDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        if($codigoFacturaDetalle != 0) {
            $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigoFacturaDetalle);
        } 
        $form = $this->createForm(new TurFacturaDetalleType, $arFacturaDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFacturaDetalle = $form->getData();                        
            $em->persist($arFacturaDetalle);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleEditar.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }          
    
    /**
     * @Route("/tur/movimiento/factura/detalle/concepto/pedido/nuevo/{codigoFactura}", name="brs_tur_movimiento_factura_detalle_concepto_pedido_nuevo")
     */     
    public function detalleNuevoConceptoPedidoAction($codigoFactura) {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionarServicioConcepto');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {   
                        $arPedidoDetalleConcepto = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto();
                        $arPedidoDetalleConcepto = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->find($codigo);
                        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                        $arFacturaDetalle->setFacturaRel($arFactura);
                        $arFacturaDetalle->setPuestoRel($arPedidoDetalleConcepto->getPuestoRel());
                        $arFacturaDetalle->setConceptoServicioRel($arPedidoDetalleConcepto->getConceptoServicioRel());                        
                        $arFacturaDetalle->setPedidoDetalleConceptoRel($arPedidoDetalleConcepto);
                        $arFacturaDetalle->setCantidad($arPedidoDetalleConcepto->getCantidad());
                        $arFacturaDetalle->setPorIva($arPedidoDetalleConcepto->getPorIva());                        
                        $arFacturaDetalle->setPorBaseIva($arPedidoDetalleConcepto->getPorBaseIva());                                                
                        $arFacturaDetalle->setVrPrecio($arPedidoDetalleConcepto->getPrecio());                                                                                                
                        $arFacturaDetalle->setFechaProgramacion($arPedidoDetalleConcepto->getPedidoRel()->getFechaProgramacion());
                        $arFacturaDetalle->setOperacion($arFactura->getOperacion());
                        $em->persist($arFacturaDetalle);
                        $arPedidoDetalleConcepto->setEstadoFacturado(1);
                        $em->persist($arPedidoDetalleConcepto);                        
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $dql = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->listaClienteDql($arFactura->getCodigoClienteFk());
        $arPedidoDetallesConceptos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 50);                
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleNuevoPedidoConcepto.html.twig', array(
            'arFactura' => $arFactura,
            'arPedidoDetalleConceptos' => $arPedidoDetallesConceptos,
            'form' => $form->createView()));
    }         
    
    /**
     * @Route("/tur/movimiento/factura/detalle/resumen/{codigoFacturaDetalle}", name="brs_tur_movimiento_factura_detalle_resumen")
     */    
    public function detalleResumenAction($codigoFacturaDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();        
        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigoFacturaDetalle);
        $arPedido = null;
        if($arFacturaDetalle->getCodigoPedidoDetalleFk()) {
            $arPedido = $arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel();
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleResumen.html.twig', array(
                    'arFacturaDetalle' => $arFacturaDetalle,
                    'arPedido' => $arPedido
                    ));
    }     
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroFacturaFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroFacturaFechaDesde');
            $strFechaHasta = $session->get('filtroFacturaFechaHasta');
        }
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurFactura')->listaDql(
                $session->get('filtroFacturaNumero'),
                $session->get('filtroCodigoCliente'),
                $session->get('filtroFacturaEstadoAutorizado'),
                $strFechaDesde,
                $strFechaHasta,
                $session->get('filtroFacturaEstadoAnulado'));
    }   
    
    private function listaDetalleNuevo($codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strDql =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->pendientesFacturarDql(
                $codigoCliente, 
                $this->boolMostrarTodo,
                $session->get('filtroPedidoNumero')
                );
        return $strDql;
    }

    private function filtrar ($form) {                
        $session = $this->getRequest()->getSession();        
        $session->set('filtroFacturaNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroFacturaEstadoAutorizado', $form->get('estadoAutorizado')->getData());          
        $session->set('filtroFacturaEstadoAnulado', $form->get('estadoAnulado')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroFacturaFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroFacturaFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroFacturaFiltrarFecha', $form->get('filtrarFecha')->getData());
    }

    private function filtrarDetalleNuevo ($form) {
        $session = $this->getRequest()->getSession();
        $this->boolMostrarTodo = $form->get('mostrarTodo')->getData();
        $session->set('filtroPedidoNumero', $form->get('TxtNumero')->getData());
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
        if($session->get('filtroFacturaFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroFacturaFechaDesde');
        }
        if($session->get('filtroFacturaFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroFacturaFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroFacturaNumero')))
            ->add('estadoAutorizado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroFacturaEstadoAutorizado')))                
            ->add('estadoAnulado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'ANULADO', '0' => 'SIN ANULAR'), 'data' => $session->get('filtroFacturaEstadoAnulado')))                                
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', 'checkbox', array('required'  => false, 'data' => $session->get('filtroFacturaFiltrarFecha')))                 
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnContabilizar', 'submit', array('label'  => 'Contabilizar',))
            ->add('BtnInterfaz', 'submit', array('label'  => 'Interfaz',))                
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);      
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonVistaPrevia = array('label' => 'Vista previa', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;            
            $arrBotonDetalleActualizar['disabled'] = true;           
            $arrBotonAnular['disabled'] = false; 
            if($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
            }            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
 
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                                     
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnVistaPrevia', 'submit', $arrBotonVistaPrevia)
                    ->add('BtnAnular', 'submit', $arrBotonAnular)                
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
                    ->getForm();
        return $form;
    }
    
    private function formularioDetalleNuevo() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();        
        $form = $this->createFormBuilder()
            ->add('mostrarTodo', 'checkbox', array('required'  => false))
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero'), 'required'  => false))                
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar',))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        return $form;
    }               
    
    private function actualizarDetalle($arrControles, $codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($intCodigo);
                $arFacturaDetalle->setVrPrecio($arrControles['TxtPrecio'.$intCodigo]); 
                $arFacturaDetalle->setDetalle($arrControles['TxtDetalle'.$intCodigo]); 
                $em->persist($arFacturaDetalle);
            }
            $em->flush();                
            $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);            
        }        
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'O'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'I'; $col !== 'O'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'NUMERO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'VENCE')
                    ->setCellValue('F1', 'NIT')                    
                    ->setCellValue('G1', 'CLIENTE')
                    ->setCellValue('H1', 'AUT')
                    ->setCellValue('I1', 'ANU')
                    ->setCellValue('J1', 'SUBTOTAL')    
                    ->setCellValue('K1', 'BASE AUI')
                    ->setCellValue('L1', 'IVA')
                    ->setCellValue('M1', 'RTEIVA')
                    ->setCellValue('N1', 'RTEFTE')
                    ->setCellValue('O1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arFacturas = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFacturas = $query->getResult();

        foreach ($arFacturas as $arFactura) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFactura->getCodigoFacturaPk())
                    ->setCellValue('B' . $i, $arFactura->getFacturaTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arFactura->getNumero())
                    ->setCellValue('D' . $i, $arFactura->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arFactura->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arFactura->getClienteRel()->getNit())
                    ->setCellValue('G' . $i, $arFactura->getClienteRel()->getNombreCorto())
                    ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arFactura->getEstadoAutorizado()))
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arFactura->getEstadoAnulado()))
                    ->setCellValue('J' . $i, $arFactura->getVrSubtotal())
                    ->setCellValue('K' . $i, $arFactura->getVrBaseAIU())
                    ->setCellValue('L' . $i, $arFactura->getVrIva())
                    ->setCellValue('M' . $i, $arFactura->getVrRetencionIva())
                    ->setCellValue('N' . $i, $arFactura->getVrRetencionFuente())
                    ->setCellValue('O' . $i, $arFactura->getVrTotal());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Facturas');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Facturas.xlsx"');
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
    
    private function generarExcelInterfaz() {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'O'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        /*for($col = 'I'; $col !== 'O'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        } */       
        $objPHPExcel->setActiveSheetIndex(0)                
                    ->setCellValue('A1', 'ORIGEN')
                    ->setCellValue('B1', 'TIPODCTO')
                    ->setCellValue('C1', 'NRODCTO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'FECHA1')
                    ->setCellValue('F1', 'NIT')
                    ->setCellValue('G1', 'CODIGOCTA')
                    ->setCellValue('H1', 'BRUTO')
                    ->setCellValue('I1', 'CODCC');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arFacturas = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFacturas = $query->getResult();
        foreach ($arFacturas as $arFactura) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, 'FAC')
                    ->setCellValue('B' . $i, $arFactura->getFacturaTipoRel()->getAbreviatura())
                    ->setCellValue('C' . $i, $arFactura->getNumero())
                    ->setCellValue('D' . $i, $arFactura->getFecha()->format('d/m/Y'))
                    ->setCellValue('E' . $i, $arFactura->getFechaVence()->format('d/m/Y'))
                    ->setCellValue('F' . $i, $arFactura->getClienteRel()->getNit())
                    ->setCellValue('G' . $i, 0)
                    ->setCellValue('H' . $i, $arFactura->getVrTotalNeto())
                    ->setCellValue('I' . $i, 0);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Facturas');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Facturas.xlsx"');
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

}