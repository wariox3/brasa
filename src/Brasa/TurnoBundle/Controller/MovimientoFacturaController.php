<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurFacturaType;
use Brasa\TurnoBundle\Form\Type\TurFacturaDetalleType;
class MovimientoFacturaController extends Controller
{
    var $strListaDql = "";
    var $codigoFactura = "";
    
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
                $em->getRepository('BrasaTurnoBundle:TurFactura')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_pedido_lista'));                 
                
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

        $arFacturas = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:lista.html.twig', array(
            'arFacturas' => $arFacturas,
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoFactura) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        if($codigoFactura != 0) {
            $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        }else{
            $arFactura->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new TurFacturaType, $arFactura);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFactura = $form->getData();            
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {
                $arTercero = new \Brasa\GeneralBundle\Entity\GenTercero();
                $arTercero = $em->getRepository('BrasaGeneralBundle:GenTercero')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arTercero) > 0) {
                    $arFactura->setTerceroRel($arTercero);
                    $em->persist($arFactura);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_factura_nuevo', array('codigoFactura' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_factura_detalle', array('codigoFactura' => $arFactura->getCodigoFacturaPk())));
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

    public function detalleAction($codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $form = $this->formularioDetalle($arFactura);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arFactura->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->numeroRegistros($codigoFactura) > 0) {
                        $arFactura->setEstadoAutorizado(1);
                        $em->persist($arFactura);
                        $em->flush();                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al pedido', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_tur_pedido_detalle', array('codigoFactura' => $codigoFactura)));                
            }    
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arFactura->getEstadoAutorizado() == 1) {
                    $arFactura->setEstadoAutorizado(0);
                    $em->persist($arFactura);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_pedido_detalle', array('codigoFactura' => $codigoFactura)));                
                }
            }   
            if($form->get('BtnAprobar')->isClicked()) {            
                if($arFactura->getEstadoAutorizado() == 1) {
                    $arFactura->setEstadoAprobado(1);
                    $em->persist($arFactura);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_pedido_detalle', array('codigoFactura' => $codigoFactura)));                
                }
            }            
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {
                    $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                    $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($intCodigo);
                    $arFacturaDetalle->setCantidad($arrControles['TxtCantidad'.$intCodigo]);
                    $arFacturaDetalle->setCantidadRecurso($arrControles['TxtCantidadRecurso'.$intCodigo]);
                    $arFacturaDetalle->setFechaDesde(date_create($arrControles['TxtFechaDesde'.$intCodigo]));
                    $arFacturaDetalle->setFechaHasta(date_create($arrControles['TxtFechaHasta'.$intCodigo]));
                    
                    if(isset($arrControles['chkLunes'.$intCodigo])) {
                        $arFacturaDetalle->setLunes(1);
                    } else {
                        $arFacturaDetalle->setLunes(0);
                    }
                    if(isset($arrControles['chkMartes'.$intCodigo])) {
                        $arFacturaDetalle->setMartes(1);
                    } else {
                        $arFacturaDetalle->setMartes(0);
                    }
                    if(isset($arrControles['chkMiercoles'.$intCodigo])) {
                        $arFacturaDetalle->setMiercoles(1);
                    } else {
                        $arFacturaDetalle->setMiercoles(0);
                    }
                    if(isset($arrControles['chkJueves'.$intCodigo])) {
                        $arFacturaDetalle->setJueves(1);
                    } else {
                        $arFacturaDetalle->setJueves(0);
                    }
                    if(isset($arrControles['chkViernes'.$intCodigo])) {
                        $arFacturaDetalle->setViernes(1);
                    } else {
                        $arFacturaDetalle->setViernes(0);
                    }
                    if(isset($arrControles['chkSabado'.$intCodigo])) {
                        $arFacturaDetalle->setSabado(1);
                    } else {
                        $arFacturaDetalle->setSabado(0);
                    }
                    if(isset($arrControles['chkDomingo'.$intCodigo])) {
                        $arFacturaDetalle->setDomingo(1);
                    } else {
                        $arFacturaDetalle->setDomingo(0);
                    }
                    if(isset($arrControles['chkFestivo'.$intCodigo])) {
                        $arFacturaDetalle->setFestivo(1);
                    } else {
                        $arFacturaDetalle->setFestivo(0);
                    }                    
                    $em->persist($arFacturaDetalle);
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
                return $this->redirect($this->generateUrl('brs_tur_pedido_detalle', array('codigoFactura' => $codigoFactura)));
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
                return $this->redirect($this->generateUrl('brs_tur_pedido_detalle', array('codigoFactura' => $codigoFactura)));
            }
            if($form->get('BtnImprimir')->isClicked()) {
                if($arFactura->getEstadoAutorizado() == 1) {
                    $objFactura = new \Brasa\TurnoBundle\Formatos\FormatoFactura();
                    $objFactura->Generar($this, $codigoFactura);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una cotizacion sin estar autorizada", $this);
                }
            }            
        }

        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array ('codigoFacturaFk' => $codigoFactura));
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalle.html.twig', array(
                    'arFactura' => $arFactura,
                    'arFacturaDetalle' => $arFacturaDetalle,
                    'form' => $form->createView()
                    ));
    }

    public function detalleNuevoAction($codigoFactura, $codigoFacturaDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        if($codigoFacturaDetalle != 0) {
            $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigoFacturaDetalle);
        } else {
            $arFacturaDetalle->setFechaDesde(new \DateTime('now'));
            $arFacturaDetalle->setFechaHasta(new \DateTime('now'));
        }
        $form = $this->createForm(new TurFacturaDetalleType, $arFacturaDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFacturaDetalle = $form->getData();
            $arFacturaDetalle->setFacturaRel($arFactura);
            $em->persist($arFacturaDetalle);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_pedido_detalle_nuevo', array('codigoFactura' => $codigoFactura, 'codigoFacturaDetalle' => 0 )));
            } else {
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleNuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }

    public function detalleNuevoCotizacionAction($codigoFactura, $codigoFacturaDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
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
                            $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
                            $arFacturaDetalle->setFacturaRel($arFactura);
                            $arFacturaDetalle->setModalidadServicioRel($arCotizacionDetalle->getModalidadServicioRel());
                            $arFacturaDetalle->setPeriodoRel($arCotizacionDetalle->getPeriodoRel());
                            $arFacturaDetalle->setTurnoRel($arCotizacionDetalle->getTurnoRel());
                            $arFacturaDetalle->setDias($arCotizacionDetalle->getDias());
                            $arFacturaDetalle->setLunes($arCotizacionDetalle->getLunes());
                            $arFacturaDetalle->setMartes($arCotizacionDetalle->getMartes());
                            $arFacturaDetalle->setMiercoles($arCotizacionDetalle->getMiercoles());
                            $arFacturaDetalle->setJueves($arCotizacionDetalle->getJueves());
                            $arFacturaDetalle->setViernes($arCotizacionDetalle->getViernes());
                            $arFacturaDetalle->setSabado($arCotizacionDetalle->getSabado());
                            $arFacturaDetalle->setDomingo($arCotizacionDetalle->getDomingo());
                            $arFacturaDetalle->setFestivo($arCotizacionDetalle->getFestivo());                            
                            $arFacturaDetalle->setCantidad($arCotizacionDetalle->getCantidad());
                            $arFacturaDetalle->setFechaDesde($arCotizacionDetalle->getFechaDesde());
                            $arFacturaDetalle->setFechaHasta($arCotizacionDetalle->getFechaHasta());
                            $em->persist($arFacturaDetalle);
                        }                       
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurFactura')->liquidar($codigoFactura);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arCotizaciones = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->pendientes($arFactura->getCodigoTerceroFk());
        return $this->render('BrasaTurnoBundle:Movimientos/Factura:detalleNuevoCotizacion.html.twig', array(
            'arFactura' => $arFactura,
            'arCotizaciones' => $arCotizaciones,
            'form' => $form->createView()));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurFactura')->listaDql($this->codigoFactura);
    }

    private function filtrar ($form) {                
        $this->codigoFactura = $form->get('TxtCodigo')->getData();
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->codigoFactura))            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);                
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
 
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                                     
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'CLIENTE');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arFacturas = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFacturas = $query->getResult();

        foreach ($arFacturas as $arFactura) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFactura->getCodigoFacturaPk())
                    ->setCellValue('B' . $i, $arFactura->getTerceroRel()->getNombreCorto());

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