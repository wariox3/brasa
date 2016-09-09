<?php
namespace Brasa\TurnoBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurProgramacionType;
class ProgramacionController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/tur/movimiento/programacion", name="brs_tur_movimiento_programacion")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();        
        if ($form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion'));
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

        $arProgramaciones = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:lista.html.twig', array(
            'arProgramaciones' => $arProgramaciones,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/programacion/nuevo/{codigoProgramacion}", name="brs_tur_movimiento_programacion_nuevo")
     */    
    public function nuevoAction($codigoProgramacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        if($codigoProgramacion != 0) {
            if($em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->numeroRegistros($codigoProgramacion) > 0) {
                $objMensaje->Mensaje("error", "La programacion tiene detalles y no se puede editar", $this);
            }
            $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
            
        }else{
            $arProgramacion->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new TurProgramacionType, $arProgramacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arProgramacion = $form->getData();
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {
                $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));
                if(count($arCliente) > 0) {
                    $arProgramacion->setClienteRel($arCliente);
                    $arUsuario = $this->getUser();
                    $arProgramacion->setUsuario($arUsuario->getUserName()); 
                    if($em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->numeroRegistros($codigoProgramacion) <= 0) {
                        $em->persist($arProgramacion);
                        $em->flush();
                    }


                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_nuevo', array('codigoProgramacion' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $arProgramacion->getCodigoProgramacionPk())));
                    }
                } else {
                    $objMensaje->Mensaje("error", "El cliente no existe", $this);
                }
            }

        }
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:nuevo.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/programacion/detalle/{codigoProgramacion}", name="brs_tur_movimiento_programacion_detalle")
     */    
    public function detalleAction($codigoProgramacion) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();        
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $form = $this->formularioDetalle($arProgramacion);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 0) {
                    //$arrControles = $request->request->All();
                    //$this->actualizarDetalle($arrControles, $codigoProgramacion);                    
                    $strResultados = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->validarAutorizar($codigoProgramacion);
                    if($strResultados == "") {
                        $em->getRepository('BrasaTurnoBundle:TurProgramacion')->autorizar($codigoProgramacion);                        
                    } else {
                        $objMensaje->Mensaje('error', $strResultados, $this);
                    }
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));                        
                }                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 1) {
                    $em->getRepository('BrasaTurnoBundle:TurProgramacion')->desAutorizar($codigoProgramacion);
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));                    
                }
            }
            if($form->get('BtnAprobar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 1) {
                    $arProgramacion->setEstadoAprobado(1);
                    $em->persist($arProgramacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
                }
            }
            if($form->get('BtnDetalleMarcar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->marcarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            } 
            if($form->get('BtnDetalleAjuste')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->ajustarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }            
            if($form->get('BtnDetalleEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $strResultado =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }
            if($form->get('BtnImprimir')->isClicked()) {
                $objProgramacion = new \Brasa\TurnoBundle\Formatos\FormatoProgramacion();
                $objProgramacion->Generar($this, $codigoProgramacion);
            }
            if($form->get('BtnAnular')->isClicked()) {
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->anular($codigoProgramacion);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }
        }
        $arrDiaSemana = $objFunciones->diasMes($arProgramacion->getFecha(), $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arProgramacion->getFecha()->format('Y-m-').'01', $arProgramacion->getFecha()->format('Y-m-').'31'));
        $formDetalle = $this->createFormBuilder()->getForm(); 
        $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->listaDql($codigoProgramacion);       
        $arProgramacionDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 1000);
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalle.html.twig', array(
                    'arProgramacion' => $arProgramacion,
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'arrDiaSemana' => $arrDiaSemana,
                    'form' => $form->createView(),
                    'formDetalle' => $form->createView()
                    ));
    }

    /**
     * @Route("/tur/movimiento/programacion/detalle/editar/{codigoPuesto}/{codigoProgramacion}/", name="brs_tur_movimiento_programacion_detalle_editar")
     */        
    public function detalleEditarAction($codigoPuesto, $codigoProgramacion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $form = $this->formularioDetalleEditar();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGuardar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 0) {
                    $arrControles = $request->request->All();
                    $this->actualizarDetalle($arrControles, $codigoProgramacion);                    
                    $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }                
            }
        }
        $strAnioMes = $arProgramacion->getFecha()->format('Y/m');
        $arrDiaSemana = $objFunciones->diasMes($arProgramacion->getFecha(), $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arProgramacion->getFecha()->format('Y-m-').'01', $arProgramacion->getFecha()->format('Y-m-').'31'));       
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        if($codigoPuesto == 0) {
            $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->listaDql($codigoProgramacion, "");            
            //$arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array ('codigoProgramacionFk' => $codigoProgramacion, 'codigoPuestoFk' => null));            
        } else {
            $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->listaDql($codigoProgramacion, $codigoPuesto);
            //$arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array ('codigoProgramacionFk' => $codigoProgramacion, 'codigoPuestoFk' => $codigoPuesto));            
        }
        $arProgramacionDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 15);
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleEditar.html.twig', array(
                    'arProgramacion' => $arProgramacion,
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'arrDiaSemana' => $arrDiaSemana,
                    'codigoPuesto' => $codigoPuesto,
                    'form' => $form->createView(),                    
                    ));
    } 
    
    /**
     * @Route("/tur/movimiento/programacion/detalle/nuevo/{codigoProgramacion}/{codigoPuesto}", name="brs_tur_movimiento_programacion_detalle_nuevo")
     */        
    public function detalleNuevoAction($codigoProgramacion, $codigoPuesto) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $intCantidad = $arrControles['TxtCantidad'.$codigo];
                        for($i = 1; $i <= $intCantidad; $i++) {
                            $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                            $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);
                            $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                            $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                            $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                            $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());                            
                            $arProgramacionDetalle->setAnio($arProgramacion->getFecha()->format('Y'));
                            $arProgramacionDetalle->setMes($arProgramacion->getFecha()->format('m'));
                            $em->persist($arProgramacionDetalle);
                        }
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaClienteFecha($arProgramacion->getCodigoClienteFk(), $codigoPuesto, '', $arProgramacion->getFecha()->format('Y'), $arProgramacion->getFecha()->format('m'));
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleNuevo.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'arPedidosDetalle' => $arPedidosDetalle,
            'codigoPuesto' => $codigoPuesto,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/movimiento/programacion/detalle/pedido/nuevo/{codigoProgramacion}/{codigoProgramacionDetalle}", name="brs_tur_movimiento_programacion_detalle_pedido_nuevo")
     */        
    public function detalleNuevoPedidoAction($codigoProgramacion, $codigoProgramacionDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->nuevo($codigo, $arProgramacion);
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaClienteFecha($arProgramacion->getCodigoClienteFk(), '', 0, $arProgramacion->getFecha()->format('Y'), $arProgramacion->getFecha()->format('m'));
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleNuevoPedido.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'arPedidosDetalle' => $arPedidosDetalle,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/programacion/detalle/resumen/{codigoProgramacionDetalle}", name="brs_tur_movimiento_programacion_detalle_resumen")
     */
    public function detalleResumenAction($codigoProgramacionDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();        
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigoProgramacionDetalle);
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();       
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arProgramacionDetalle->getCodigoPedidoDetalleFk());
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleResumen.html.twig', array(
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'arPedidoDetalle' => $arPedidoDetalle,
                    ));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroProgramacionFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroProgramacionFechaDesde');
            $strFechaHasta = $session->get('filtroProgramacionFechaHasta');
        }
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurProgramacion')->listaDQL(
                $session->get('filtroProgramacionCodigo'),
                $session->get('filtroCodigoCliente'),
                $session->get('filtroProgramacionEstadoAutorizado'),
                $strFechaDesde,
                $strFechaHasta,
                $session->get('filtroProgramacionEstadoAnulado'));
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();        
        $session->set('filtroProgramacionCodigo', $form->get('TxtCodigo')->getData());
        $session->set('filtroProgramacionEstadoAutorizado', $form->get('estadoAutorizado')->getData());          
        $session->set('filtroProgramacionEstadoAnulado', $form->get('estadoAnulado')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroProgramacionFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroProgramacionFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroProgramacionFiltrarFecha', $form->get('filtrarFecha')->getData());
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
        if($session->get('filtroProgramacionFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroProgramacionFechaDesde');
        }
        if($session->get('filtroProgramacionFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroProgramacionFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $session->get('filtroProgramacionCodigo')))
            ->add('estadoAutorizado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroProgramacionEstadoAutorizado')))                
            ->add('estadoAnulado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'ANULADO', '0' => 'SIN ANULAR'), 'data' => $session->get('filtroProgramacionEstadoAnulado')))                                
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', 'checkbox', array('required'  => false, 'data' => $session->get('filtroProgramacionFiltrarFecha')))                 
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => true);
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);        
        $arrBotonDetalleMarcar = array('label' => 'Marcar', 'disabled' => false);        
        $arrBotonDetalleAjuste = array('label' => 'Ajuste', 'disabled' => false);                
        
        if($ar->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = false;
            $arrBotonDetalleEliminar['disabled'] = true;            
            $arrBotonAnular['disabled'] = false;
            if($ar->getEstadoAnulado() == 1 || $ar->getCierreMes() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
                $arrBotonAprobar['disabled'] = true;
            }
            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
        }
        $form = $this->createFormBuilder(array(), array('csrf_protection' => false))
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
                    ->add('BtnAprobar', 'submit', $arrBotonAprobar)
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnAnular', 'submit', $arrBotonAnular)                    
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
                    ->add('BtnDetalleMarcar', 'submit', $arrBotonDetalleMarcar)
                    ->add('BtnDetalleAjuste', 'submit', $arrBotonDetalleAjuste)
                    ->getForm();
        return $form;
    }

    private function formularioDetalleEditar() {
        $form = $this->createFormBuilder(array(), array('csrf_protection' => false))                    
                    ->add('BtnGuardar', 'submit', array('label' => 'Guardar'))
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for($col = 'G'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'AÑO')
                    ->setCellValue('C1', 'MES')
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'AUT')
                    ->setCellValue('F1', 'ANU')
                    ->setCellValue('G1', 'HORAS');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arProgramaciones = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramaciones = $query->getResult();

        foreach ($arProgramaciones as $arProgramacion) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacion->getCodigoProgramacionPk())
                    ->setCellValue('B' . $i, $arProgramacion->getFecha()->format('Y'))
                    ->setCellValue('C' . $i, $arProgramacion->getFecha()->format('F'))
                    ->setCellValue('D' . $i, $arProgramacion->getClienteRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $objFunciones->devuelveBoolean($arProgramacion->getEstadoAutorizado()))
                    ->setCellValue('F' . $i, $objFunciones->devuelveBoolean($arProgramacion->getEstadoAnulado()))
                    ->setCellValue('G' . $i, $arProgramacion->getHoras());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Programaciones');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Programaciones.xlsx"');
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

    private function actualizarDetalle ($arrControles, $codigoProgramacion) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $validarHoras = $arConfiguracion->getValidarHorasProgramacion();
        $intIndice = 0;
        $boolTurnosSobrepasados = false;
        foreach ($arrControles['LblCodigo'] as $intCodigo) {            
            $horasDiurnas = 0;
            $horasNocturnas = 0;
            $horasDiurnasProgramacion = 0;
            $horasNocturnasProgramacion = 0;            
            $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($intCodigo);
            $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
            $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arProgramacionDetalle->getCodigoPedidoDetalleFk());            
            $horasDiurnas = $arPedidoDetalle->getHorasDiurnasProgramadas() - $arProgramacionDetalle->getHorasDiurnas();
            $horasNocturnas = $arPedidoDetalle->getHorasNocturnasProgramadas() - $arProgramacionDetalle->getHorasNocturnas();
            $horasDiurnasContratadas = $arPedidoDetalle->getHorasDiurnas();
            $horasNocturnasContratadas = $arPedidoDetalle->getHorasNocturnas();            
            if($arProgramacionDetalle->getPeriodoBloqueo() == 0) {
                if($arrControles['TxtRecurso'.$intCodigo] != '') {
                    $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                    $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrControles['TxtRecurso'.$intCodigo]);
                    if($arRecurso) {
                        $arProgramacionDetalle->setRecursoRel($arRecurso);
                    }
                } else {
                    $arProgramacionDetalle->setRecursoRel(NULL);
                }                
            }
            if($arrControles['TxtPuesto'.$intCodigo] != '') {
                $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
                $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($arrControles['TxtPuesto'.$intCodigo]);
                if($arPuesto) {
                    $arProgramacionDetalle->setPuestoRel($arPuesto);
                }
            }                  
            
            if($arProgramacionDetalle->getPeriodoBloqueo() < 15) {
                if($arrControles['TxtDia01D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia01D'.$intCodigo]);                    
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia1($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia1($arrTurno['turno']);
                        } else {                        
                            $arProgramacionDetalle->setDia1(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia1(null);
                }
                if($arrControles['TxtDia02D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia02D'.$intCodigo]);
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia2($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia2($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia2(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia2(null);
                }
                if($arrControles['TxtDia03D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia03D'.$intCodigo]);                    
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia3($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia3($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia3(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia3(null);
                }
                if($arrControles['TxtDia04D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia04D'.$intCodigo]);                    
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia4($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia4($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia4(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }
                } else {
                    $arProgramacionDetalle->setDia4(null);
                }
                if($arrControles['TxtDia05D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia05D'.$intCodigo]);   
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia5($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia5($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia5(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia5(null);
                }
                if($arrControles['TxtDia06D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia06D'.$intCodigo]); 
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia6($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia6($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia6(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia6(null);
                }
                if($arrControles['TxtDia07D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia07D'.$intCodigo]);  
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia7($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia7($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia7(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia7(null);
                }
                if($arrControles['TxtDia08D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia08D'.$intCodigo]);  
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia8($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia8($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia8(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia8(null);
                }
                if($arrControles['TxtDia09D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia09D'.$intCodigo]);  
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia9($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia9($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia9(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia9(null);
                }
                if($arrControles['TxtDia10D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia10D'.$intCodigo]);   
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia10($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia10($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia10(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia10(null);
                }
                if($arrControles['TxtDia11D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia11D'.$intCodigo]);  
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia11($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia11($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia11(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia11(null);
                }
                if($arrControles['TxtDia12D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia12D'.$intCodigo]);
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia12($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia12($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia12(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia12(null);
                }
                if($arrControles['TxtDia13D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia13D'.$intCodigo]); 
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia13($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia13($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia13(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia13(null);
                }
                if($arrControles['TxtDia14D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia14D'.$intCodigo]);    
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia14($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia14($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia14(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia14(null);
                }
                if($arrControles['TxtDia15D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia15D'.$intCodigo]);    
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia15($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia15($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia15(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia15(null);
                }                
            }
            if($arProgramacionDetalle->getPeriodoBloqueo() < 30) {
                if($arrControles['TxtDia16D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia16D'.$intCodigo]);    
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia16($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia16($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia16(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia16(null);
                }
                if($arrControles['TxtDia17D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia17D'.$intCodigo]); 
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia17($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia17($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia17(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia17(null);
                }
                if($arrControles['TxtDia18D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia18D'.$intCodigo]); 
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia18($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia18($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia18(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    

                } else {
                    $arProgramacionDetalle->setDia18(null);
                }
                if($arrControles['TxtDia19D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia19D'.$intCodigo]);  
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia19($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia19($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia19(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia19(null);
                }
                if($arrControles['TxtDia20D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia20D'.$intCodigo]); 
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia20($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia20($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia20(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia20(null);
                }
                if($arrControles['TxtDia21D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia21D'.$intCodigo]);
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia21($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia21($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia21(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia21(null);
                }
                if($arrControles['TxtDia22D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia22D'.$intCodigo]);  
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia22($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia22($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia22(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia22(null);
                }
                if($arrControles['TxtDia23D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia23D'.$intCodigo]);  
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia23($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia23($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia23(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia23(null);
                }
                if($arrControles['TxtDia24D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia24D'.$intCodigo]);
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia24($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia24($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia24(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia24(null);
                }
                if($arrControles['TxtDia25D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia25D'.$intCodigo]);
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia25($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia25($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia25(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia25(null);
                }
                if($arrControles['TxtDia26D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia26D'.$intCodigo]);
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia26($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia26($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia26(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia26(null);
                }
                if($arrControles['TxtDia27D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia27D'.$intCodigo]);  
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia27($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia27($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia27(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia27(null);
                }
                if($arrControles['TxtDia28D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia28D'.$intCodigo]);
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia28($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia28($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia28(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia28(null);
                }
                if($arrControles['TxtDia29D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia29D'.$intCodigo]); 
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia29($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia29($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia29(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia29(null);
                }
                if($arrControles['TxtDia30D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia30D'.$intCodigo]);
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia30($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia30($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia30(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    

                } else {
                    $arProgramacionDetalle->setDia30(null);
                }
                if($arrControles['TxtDia31D'.$intCodigo] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia31D'.$intCodigo]);   
                    if($validarHoras == false) {
                        $arProgramacionDetalle->setDia31($arrTurno['turno']);
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                    } else {
                        if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                            $horasDiurnas += $arrTurno['horasDiurnas'];
                            $horasNocturnas +=  $arrTurno['horasNocturnas'];
                            $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                            $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                            $arProgramacionDetalle->setDia31($arrTurno['turno']);
                        } else {
                            $arProgramacionDetalle->setDia31(null);
                            $boolTurnosSobrepasados = true;
                        }                        
                    }                    
                } else {
                    $arProgramacionDetalle->setDia31(null);
                }                
            }
            if($validarHoras == true) {
                $arPedidoDetalle->setHorasDiurnasProgramadas($horasDiurnas);
                $arPedidoDetalle->setHorasNocturnasProgramadas($horasNocturnas);
                $arPedidoDetalle->setHorasProgramadas($horasDiurnas+$horasNocturnas);               
                $em->persist($arPedidoDetalle);
            }
            $arProgramacionDetalle->setHorasDiurnas($horasDiurnasProgramacion);
            $arProgramacionDetalle->setHorasNocturnas($horasNocturnasProgramacion);
            $arProgramacionDetalle->setHoras($horasDiurnasProgramacion+$horasNocturnasProgramacion);
            $em->persist($arProgramacionDetalle);
        }
        $em->flush();
        if($boolTurnosSobrepasados == true) {
            $objMensaje->Mensaje('error', "Algunos turnos no fueron aplicados porque sobrepasaban las horas contratadas del pedido", $this);
        }
        $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
    }

    private function validarTurno($strTurno) {
        $em = $this->getDoctrine()->getManager();        
        $arrTurno = array('turno' => null, 'horasDiurnas' => 0, 'horasNocturnas' => 0);
        if($strTurno != "") {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno);
            if($arTurno) {
                $arrTurno['turno'] = $strTurno;
                $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas();
            }
        }

        return $arrTurno;
    }


}