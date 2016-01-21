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
    var $codigoCliente = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        if ($form->isValid()) {    
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {                
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));
                if($arCliente) {
                    $this->codigoCliente = $arCliente->getCodigoClientePk();
                }
            }            
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
            'arCliente' => $arCliente,
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
                            $arServicioDetalle->setConceptoServicioRel($arCotizacionDetalle->getConceptoServicioRel());
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
                            $arServicioDetalle->setDiaDesde($arCotizacionDetalle->getDiaDesde());
                            $arServicioDetalle->setDiaHasta($arCotizacionDetalle->getDiaHasta());
                            $arServicioDetalle->setVrTotalAjustado($arCotizacionDetalle->getVrTotalAjustado());
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
        $form = $this->formularioRecurso($arServicioDetalle->getDiasSecuencia());
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
            if($form->get('BtnPlantillaNuevo')->isClicked()) {   
                $arServicioDetallePlantilla = new \Brasa\TurnoBundle\Entity\TurServicioDetallePlantilla();
                $arServicioDetallePlantilla->setServicioDetalleRel($arServicioDetalle);
                $em->persist($arServicioDetallePlantilla);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            }
            if($form->get('BtnPlantillaActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetallePlantilla($arrControles);
                return $this->redirect($this->generateUrl('brs_tur_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            }
            if($form->get('BtnPlantillaEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionarPlantilla');
                $em->getRepository('BrasaTurnoBundle:TurServicioDetallePlantilla')->eliminar($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            }    
            if($form->get('BtnGuardarServicioDetalle')->isClicked()) {   
                $intDiasSecuencia = $form->get('TxtDiasSecuencia')->getData();     
                $arServicioDetalle->setDiasSecuencia($intDiasSecuencia);
                $em->persist($arServicioDetalle);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_servicio_detalle_recurso', array('codigoServicioDetalle' => $codigoServicioDetalle)));                                
            }            
        }
        $strLista = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->listaDql($codigoServicioDetalle);
        $strListaPlantilla = $em->getRepository('BrasaTurnoBundle:TurServicioDetallePlantilla')->listaDql($codigoServicioDetalle);
        $arServicioDetalleRecursos = $paginator->paginate($em->createQuery($strLista), $request->query->get('page', 1), 20);
        $arServicioDetallePlantilla = $paginator->paginate($em->createQuery($strListaPlantilla), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Servicio:recurso.html.twig', array(
            'arServicioDetalleRecursos' => $arServicioDetalleRecursos,
            'arServicioDetallePlantilla' => $arServicioDetallePlantilla,
            'form' => $form->createView()));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurServicio')->listaDQL($this->codigoServicio, $this->codigoCliente);
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

    private function formularioRecurso($intDiasSecuencia) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtPosicion', 'text', array('label'  => 'Codigo','data' => 0))   
            ->add('TxtDiasSecuencia', 'text', array('label'  => 'Codigo','data' => $intDiasSecuencia)) 
            ->add('BtnDetalleEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnDetalleActualizar', 'submit', array('label'  => 'Actualizar',))            
            ->add('BtnPlantillaNuevo', 'submit', array('label'  => 'Nuevo',))                
            ->add('BtnPlantillaEliminar', 'submit', array('label'  => 'Eliminar',))                
            ->add('BtnPlantillaActualizar', 'submit', array('label'  => 'Actualizar',))                
            ->add('BtnGuardarServicioDetalle', 'submit', array('label'  => 'Guardar',))                
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

    private function actualizarDetallePlantilla($arrControles) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigoDetallePlantilla'])) {
            foreach ($arrControles['LblCodigoDetallePlantilla'] as $intCodigo) {
                $arServicioDetallePlantilla = new \Brasa\TurnoBundle\Entity\TurServicioDetallePlantilla();
                $arServicioDetallePlantilla = $em->getRepository('BrasaTurnoBundle:TurServicioDetallePlantilla')->find($intCodigo);
                if ($arrControles['TxtPosicion' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setPosicion($arrControles['TxtPosicion' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setPosicion(0);
                }
                if ($arrControles['TxtDia1' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia1($arrControles['TxtDia1' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia1(null);
                }
                if ($arrControles['TxtDia2' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia2($arrControles['TxtDia2' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia2(null);
                }
                if ($arrControles['TxtDia3' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia3($arrControles['TxtDia3' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia3(null);
                }
                if ($arrControles['TxtDia4' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia4($arrControles['TxtDia4' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia4(null);
                }
                if ($arrControles['TxtDia5' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia5($arrControles['TxtDia5' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia5(null);
                }
                if ($arrControles['TxtDia6' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia6($arrControles['TxtDia6' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia6(null);
                }
                if ($arrControles['TxtDia7' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia7($arrControles['TxtDia7' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia7(null);
                }
                if ($arrControles['TxtDia8' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia8($arrControles['TxtDia8' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia8(null);
                }
                if ($arrControles['TxtDia9' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia9($arrControles['TxtDia9' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia9(null);
                }
                if ($arrControles['TxtDia10' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia10($arrControles['TxtDia10' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia10(null);
                }
                if ($arrControles['TxtDia11' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia11($arrControles['TxtDia11' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia11(null);
                }
                if ($arrControles['TxtDia12' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia12($arrControles['TxtDia12' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia12(null);
                }
                if ($arrControles['TxtDia13' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia13($arrControles['TxtDia13' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia13(null);
                }
                if ($arrControles['TxtDia14' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia14($arrControles['TxtDia14' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia14(null);
                }
                if ($arrControles['TxtDia15' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia15($arrControles['TxtDia15' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia15(null);
                }
                if ($arrControles['TxtDia16' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia16($arrControles['TxtDia16' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia16(null);
                }
                if ($arrControles['TxtDia17' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia17($arrControles['TxtDia17' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia17(null);
                }
                if ($arrControles['TxtDia18' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia18($arrControles['TxtDia18' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia18(null);
                }
                if ($arrControles['TxtDia19' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia19($arrControles['TxtDia19' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia19(null);
                }
                if ($arrControles['TxtDia20' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia20($arrControles['TxtDia20' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia20(null);
                }
                if ($arrControles['TxtDia21' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia21($arrControles['TxtDia21' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia21(null);
                }
                if ($arrControles['TxtDia22' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia22($arrControles['TxtDia22' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia22(null);
                }
                if ($arrControles['TxtDia23' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia23($arrControles['TxtDia23' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia23(null);
                }
                if ($arrControles['TxtDia24' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia24($arrControles['TxtDia24' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia24(null);
                }
                if ($arrControles['TxtDia25' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia25($arrControles['TxtDia25' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia25(null);
                }
                if ($arrControles['TxtDia26' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia26($arrControles['TxtDia26' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia26(null);
                }
                if ($arrControles['TxtDia27' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia27($arrControles['TxtDia27' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia27(null);
                }
                if ($arrControles['TxtDia28' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia28($arrControles['TxtDia28' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia28(null);
                }
                if ($arrControles['TxtDia29' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia29($arrControles['TxtDia29' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia29(null);
                }
                if ($arrControles['TxtDia30' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia30($arrControles['TxtDia30' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia30(null);
                }
                if ($arrControles['TxtDia31' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDia31($arrControles['TxtDia31' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDia31(null);
                }
                if ($arrControles['TxtLunes' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setLunes($arrControles['TxtLunes' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setLunes(null);
                }
                if ($arrControles['TxtMartes' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setMartes($arrControles['TxtMartes' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setMartes(null);
                }
                if ($arrControles['TxtMiercoles' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setMiercoles($arrControles['TxtMiercoles' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setMiercoles(null);
                }
                if ($arrControles['TxtJueves' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setJueves($arrControles['TxtJueves' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setJueves(null);
                }
                if ($arrControles['TxtViernes' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setViernes($arrControles['TxtViernes' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setViernes(null);
                }
                if ($arrControles['TxtSabado' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setSabado($arrControles['TxtSabado' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setSabado(null);
                }
                if ($arrControles['TxtDomingo' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setDomingo($arrControles['TxtDomingo' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setDomingo(null);
                }
                if ($arrControles['TxtFestivo' . $intCodigo] != '') {
                    $arServicioDetallePlantilla->setFestivo($arrControles['TxtFestivo' . $intCodigo]);
                } else {
                    $arServicioDetallePlantilla->setFestivo(null);
                }
                $em->persist($arServicioDetallePlantilla);
            }            
        }

        $em->flush();
    }    
}