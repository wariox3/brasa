<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurServicioType;
use Brasa\TurnoBundle\Form\Type\TurServicioDetalleType;
class MovimientoServicioController extends Controller
{
    var $strListaDql = "";    
    var $codigoServicio = "";    
    
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
                $em->getRepository('BrasaTurnoBundle:TurServicio')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_servicio_lista'));                 
                
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
        }

        $arServicios = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:lista.html.twig', array(
            'arServicios' => $arServicios,
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoServicio) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
        if($codigoServicio != 0) {
            $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);
        }
        $form = $this->createForm(new TurServicioType, $arServicio);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arServicio = $form->getData();            
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {                
                $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arServicio->setClienteRel($arCliente);
                    $em->persist($arServicio);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_servicio_nuevo', array('codigoServicio' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_servicio_detalle', array('codigoServicio' => $arServicio->getCodigoServicioPk())));
                    }                       
                } else {
                    $objMensaje->Mensaje("error", "El cliente no existe", $this);
                }                             
            }            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:nuevo.html.twig', array(
            'arServicio' => $arServicio,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoServicio) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
        $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);
        $form = $this->formularioDetalle($arServicio);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arServicio->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->numeroRegistros($codigoServicio) > 0) {
                        $arServicio->setEstadoAutorizado(1);
                        $em->persist($arServicio);
                        $em->flush();                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al servicio', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_tur_servicio_detalle', array('codigoServicio' => $codigoServicio)));                
            }    
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arServicio->getEstadoAutorizado() == 1) {
                    $arServicio->setEstadoAutorizado(0);
                    $em->persist($arServicio);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_servicio_detalle', array('codigoServicio' => $codigoServicio)));                
                }
            }   
            if($form->get('BtnAprobar')->isClicked()) {            
                if($arServicio->getEstadoAutorizado() == 1) {
                    $arServicio->setEstadoAprobado(1);
                    $em->persist($arServicio);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_servicio_detalle', array('codigoServicio' => $codigoServicio)));                
                }
            }            
            if($form->get('BtnDetalleActualizar')->isClicked()) {                
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoServicio);                                
                return $this->redirect($this->generateUrl('brs_tur_servicio_detalle', array('codigoServicio' => $codigoServicio)));
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);
                return $this->redirect($this->generateUrl('brs_tur_servicio_detalle', array('codigoServicio' => $codigoServicio)));
            }
            if($form->get('BtnImprimir')->isClicked()) {
                if($arServicio->getEstadoAutorizado() == 1) {
                    $objServicio = new \Brasa\TurnoBundle\Formatos\FormatoServicio();
                    $objServicio->Generar($this, $codigoServicio);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una cotizacion sin estar autorizada", $this);
                }
            }            
        }

        $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array ('codigoServicioFk' => $codigoServicio));
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:detalle.html.twig', array(
                    'arServicio' => $arServicio,
                    'arServicioDetalle' => $arServicioDetalle,
                    'form' => $form->createView()
                    ));
    }

    public function detalleNuevoAction($codigoServicio, $codigoServicioDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
        $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);
        $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        if($codigoServicioDetalle != 0) {
            $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigoServicioDetalle);
        } else {
            $arServicioDetalle->setServicioRel($arServicio);
        }
        $form = $this->createForm(new TurServicioDetalleType, $arServicioDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arServicioDetalle = $form->getData();
            $em->persist($arServicioDetalle);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_servicio_detalle_nuevo', array('codigoServicio' => $codigoServicio, 'codigoServicioDetalle' => 0 )));
            } else {
                $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:detalleNuevo.html.twig', array(
            'arServicio' => $arServicio,
            'form' => $form->createView()));
    }

    public function detalleNuevoCotizacionAction($codigoServicio, $codigoServicioDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
        $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio);
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
                            $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                            $arServicioDetalle->setServicioRel($arServicio);
                            $arServicioDetalle->setModalidadServicioRel($arCotizacionDetalle->getModalidadServicioRel());
                            $arServicioDetalle->setPeriodoRel($arCotizacionDetalle->getPeriodoRel());
                            $arServicioDetalle->setTurnoRel($arCotizacionDetalle->getTurnoRel());
                            $arServicioDetalle->setDias($arCotizacionDetalle->getDias());
                            $arServicioDetalle->setLunes($arCotizacionDetalle->getLunes());
                            $arServicioDetalle->setMartes($arCotizacionDetalle->getMartes());
                            $arServicioDetalle->setMiercoles($arCotizacionDetalle->getMiercoles());
                            $arServicioDetalle->setJueves($arCotizacionDetalle->getJueves());
                            $arServicioDetalle->setViernes($arCotizacionDetalle->getViernes());
                            $arServicioDetalle->setSabado($arCotizacionDetalle->getSabado());
                            $arServicioDetalle->setDomingo($arCotizacionDetalle->getDomingo());
                            $arServicioDetalle->setFestivo($arCotizacionDetalle->getFestivo());                            
                            $arServicioDetalle->setCantidad($arCotizacionDetalle->getCantidad());
                            $arServicioDetalle->setFechaDesde($arCotizacionDetalle->getFechaDesde());
                            $arServicioDetalle->setFechaHasta($arCotizacionDetalle->getFechaHasta());
                            $em->persist($arServicioDetalle);
                        }                       
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arCotizaciones = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->pendientes($arServicio->getCodigoClienteFk());
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:detalleNuevoCotizacion.html.twig', array(
            'arServicio' => $arServicio,
            'arCotizaciones' => $arCotizaciones,
            'form' => $form->createView()));
    }    
    
    public function recursoAction($codigoServicioDetalle = 0) {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $em = $this->getDoctrine()->getManager();
        $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($codigoServicioDetalle);        
        $form = $this->formularioRecurso();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if($form->get('guardar')->isClicked()) {   
                $arrControles = $request->request->All();
                if($arrControles['txtNumeroIdentificacion'] != '') {
                    $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                    $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));                
                    if(count($arRecurso) > 0) {
                        $intPosicion = $form->get('TxtPosicion')->getData();
                        $arServicioDetalleRecurso = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
                        $arServicioDetalleRecurso->setServicioDetalleRel($arServicioDetalle);
                        $arServicioDetalleRecurso->setRecursoRel($arRecurso);
                        $arServicioDetalleRecurso->setPosicion($intPosicion);
                        $em->persist($arServicioDetalleRecurso);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_tur_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                
                    } else {
                        $objMensaje->Mensaje("error", "El recurso no existe", $this);
                    }
                }                
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->eliminarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            } 
            if($form->get('BtnDetalleActualizar')->isClicked()) {                
                $arrControles = $request->request->All();
                $this->actualizarDetalleRecurso($arrControles);                                
                return $this->redirect($this->generateUrl('brs_tur_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            }            
        }
        $strLista = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->listaDql($codigoServicioDetalle);
        $arServicioDetalleRecursos = $paginator->paginate($em->createQuery($strLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:recurso.html.twig', array(
            'arServicioDetalleRecursos' => $arServicioDetalleRecursos,
            'form' => $form->createView()));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurServicio')->listaDQL($this->codigoServicio);
    }    

    private function filtrar ($form) {                
        $this->codigoServicio = $form->get('TxtCodigo')->getData();
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->codigoServicio))            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);        
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);        
        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = false;            
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = true;            
        } 
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                 
                    ->add('BtnAprobar', 'submit', $arrBotonAprobar)                 
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
                    ->getForm();
        return $form;
    }

    private function formularioRecurso() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtPosicion', 'text', array('label'  => 'Codigo','data' => 0))            
            ->add('BtnDetalleEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnDetalleActualizar', 'submit', array('label'  => 'Actualizar',))            
            ->add('guardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        return $form;
    }    
    
    private function generarExcel() {
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'NÚMERO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'SECTOR')
                    ->setCellValue('G1', 'PROGRAMADO')
                    ->setCellValue('H1', 'HORAS')
                    ->setCellValue('I1', 'H.DIURNAS')
                    ->setCellValue('J1', 'H.NOCTURNAS')
                    ->setCellValue('K1', 'VALOR');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arServicios = new \Brasa\TurnoBundle\Entity\TurServicio();
        $arServicios = $query->getResult();

        foreach ($arServicios as $arServicio) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServicio->getCodigoServicioPk())
                    ->setCellValue('B' . $i, $arServicio->getServicioTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arServicio->getNumero())
                    ->setCellValue('D' . $i, $arServicio->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arServicio->getClienteRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arServicio->getSectorRel()->getNombre())
                    ->setCellValue('G' . $i, $arServicio->getEstadoProgramado()*1)
                    ->setCellValue('H' . $i, $arServicio->getHoras())
                    ->setCellValue('I' . $i, $arServicio->getHorasDiurnas())
                    ->setCellValue('J' . $i, $arServicio->getHorasNocturnas())
                    ->setCellValue('K' . $i, $arServicio->getVrTotal());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Servicios');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Servicios.xlsx"');
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
    
    private function actualizarDetalle($arrControles, $codigoServicio) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        foreach ($arrControles['LblCodigo'] as $intCodigo) {
            $arServicioDetalle = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
            $arServicioDetalle = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->find($intCodigo);
            $arServicioDetalle->setCantidad($arrControles['TxtCantidad'.$intCodigo]);
            $arServicioDetalle->setCantidadRecurso($arrControles['TxtCantidadRecurso'.$intCodigo]);
            $arServicioDetalle->setDiaDesde($arrControles['TxtDiaDesde'.$intCodigo]);
            $arServicioDetalle->setDiaHasta($arrControles['TxtDiaHasta'.$intCodigo]);
            if($arrControles['TxtPuesto'.$intCodigo] != '') {
                $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
                $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($arrControles['TxtPuesto'.$intCodigo]);
                if($arPuesto) {
                    $arServicioDetalle->setPuestoRel($arPuesto);
                }
            }
            if($arrControles['TxtValorAjustado'.$intCodigo] != '') {
                $arServicioDetalle->setVrTotalAjustado($arrControles['TxtValorAjustado'.$intCodigo]);                
            }            
            if(isset($arrControles['chkLunes'.$intCodigo])) {
                $arServicioDetalle->setLunes(1);
            } else {
                $arServicioDetalle->setLunes(0);
            }
            if(isset($arrControles['chkMartes'.$intCodigo])) {
                $arServicioDetalle->setMartes(1);
            } else {
                $arServicioDetalle->setMartes(0);
            }
            if(isset($arrControles['chkMiercoles'.$intCodigo])) {
                $arServicioDetalle->setMiercoles(1);
            } else {
                $arServicioDetalle->setMiercoles(0);
            }
            if(isset($arrControles['chkJueves'.$intCodigo])) {
                $arServicioDetalle->setJueves(1);
            } else {
                $arServicioDetalle->setJueves(0);
            }
            if(isset($arrControles['chkViernes'.$intCodigo])) {
                $arServicioDetalle->setViernes(1);
            } else {
                $arServicioDetalle->setViernes(0);
            }
            if(isset($arrControles['chkSabado'.$intCodigo])) {
                $arServicioDetalle->setSabado(1);
            } else {
                $arServicioDetalle->setSabado(0);
            }
            if(isset($arrControles['chkDomingo'.$intCodigo])) {
                $arServicioDetalle->setDomingo(1);
            } else {
                $arServicioDetalle->setDomingo(0);
            }
            if(isset($arrControles['chkFestivo'.$intCodigo])) {
                $arServicioDetalle->setFestivo(1);
            } else {
                $arServicioDetalle->setFestivo(0);
            }                    
            $em->persist($arServicioDetalle);
        }
        $em->flush();                
        $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($codigoServicio);        
    }
    
    private function actualizarDetalleRecurso($arrControles) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        foreach ($arrControles['LblCodigo'] as $intCodigo) {
            $arServicioDetalleRecurso = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
            $arServicioDetalleRecurso = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->find($intCodigo);
            $arServicioDetalleRecurso->setPosicion($arrControles['TxtPosicion'.$intCodigo]);
            $em->persist($arServicioDetalleRecurso);
        }
        $em->flush();                        
    }

}