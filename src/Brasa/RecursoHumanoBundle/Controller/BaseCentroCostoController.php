<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCentroCostoType;

class BaseCentroCostoController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombreCentroCosto')))
            ->add('BtnBuscar', 'submit', array('label'  => 'Buscar'))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnInactivar', 'submit', array('label'  => 'Activa / Inactiva',))
            ->getForm();
        $form->handleRequest($request);
        $arCentrosCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();        
        if($form->isValid()) {
            if($form->get('BtnBuscar')->isClicked() || $form->get('BtnExcel')->isClicked()) {
                $session->set('dqlCentroCosto', $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL(
                    $form->get('TxtNombre')->getData()
                    ));                
                $session->set('filtroNombreCentroCosto', $form->get('TxtNombre')->getData());                
            }
            
            if($form->get('BtnPdf')->isClicked()) {
                $objFormatoCentroCostos = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCentroCostos();
                $objFormatoCentroCostos->Generar($this);
            }
            
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
            if($form->get('BtnInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
                        $arContratosCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoCentroCostoFk' =>$codigoCentroCosto, 'estadoActivo' => 1)); 
                        $douNumeroContratoActivos = count($arContratosCentroCosto);
                        if($arCentroCosto->getEstadoActivo() == 1){
                            if ($douNumeroContratoActivos == 0){
                                $arCentroCosto->setEstadoActivo(0);
                            }else {
                                echo "<script>alert('No se  puede inactivar, el centro de costo tiene contrato(s) abierto(s)');</script>";
                            }
                        } else {
                            $arCentroCosto->setEstadoActivo(1);
                        }
                        $em->persist($arCentroCosto);
                    }
                    $em->flush();
                }
            }
        } else {
            $session->set('dqlCentroCosto', $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL(
                    $session->get('filtroNombreCentroCosto')
                    ));                          
        }        
        $arCentrosCostos = $paginator->paginate($em->createQuery($session->get('dqlCentroCosto')), $this->get('request')->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/CentroCosto:lista.html.twig', array(
            'arCentrosCostos' => $arCentrosCostos,
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoCentroCosto) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $mensaje = 0;
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto->setFechaUltimoPagoProgramado(new \DateTime('now'));
        if($codigoCentroCosto != 0) {
            $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        } else {
            $arCentroCosto->setEstadoActivo(1);
            
        }
        $form = $this->createForm(new RhuCentroCostoType(), $arCentroCosto);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $arCentroCosto = $form->getData();
            //PERIODO PAGO SEMANAL Y CATORCENAL
            if ($arCentroCosto->getPeriodoPagoRel()->getCodigoPeriodoPagoPk() == 1 || $arCentroCosto->getPeriodoPagoRel()->getCodigoPeriodoPagoPk() == 3) {
                $em->persist($arCentroCosto);
                $em->flush();
                if($request->request->get('ChkGenerarPeriodo')) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($arCentroCosto->getCodigoCentroCostoPk());
                }
                if($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_rhu_base_centros_costos_nuevo', array('codigoCentroCosto' => 0)));
                } else {
                    return $this->redirect($this->generateUrl('brs_rhu_base_centros_costos_lista'));
                }  
            }
            //PERIODO PAGO DECADAL
            $varFechaperiodoPago = $arCentroCosto->getFechaUltimoPagoProgramado()->format('d');
            //$duoPeriodoPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($arCentroCosto->getCodigoCentroCostoPk());
            if ($arCentroCosto->getPeriodoPagoRel()->getCodigoPeriodoPagoPk() == 2) {
                if ($varFechaperiodoPago == "10" || $varFechaperiodoPago == "20" || $varFechaperiodoPago == "30"){
                    $em->persist($arCentroCosto);
                    $em->flush();
                    if($request->request->get('ChkGenerarPeriodo')) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($arCentroCosto->getCodigoCentroCostoPk());
                    }
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_rhu_base_centros_costos_nuevo', array('codigoCentroCosto' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('brs_rhu_base_centros_costos_lista'));
                    }
                } else {
                            $mensaje = "la fecha de periodo pagado debe ser los 10, 20 o 30 de cada mes!";
                       }
            }
            //PERIODO PAGO QUINCENAL
            $varFechaperiodoPago = $arCentroCosto->getFechaUltimoPagoProgramado()->format('d');
            if ($arCentroCosto->getPeriodoPagoRel()->getCodigoPeriodoPagoPk() == 4) {
                if ($varFechaperiodoPago == "15" || $varFechaperiodoPago == "30"){
                    $em->persist($arCentroCosto);
                    $em->flush();
                    if($request->request->get('ChkGenerarPeriodo')) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($arCentroCosto->getCodigoCentroCostoPk());
                    }
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_rhu_base_centros_costos_nuevo', array('codigoCentroCosto' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('brs_rhu_base_centros_costos_lista'));
                    }
                } else {
                            $mensaje = "la fecha de periodo pagado debe ser los 15 o 30 de cada mes!";
                       }
            }
            //PERIODO PAGO MENSUAL
            $varFechaperiodoPago = $arCentroCosto->getFechaUltimoPagoProgramado()->format('d');
            if ($arCentroCosto->getPeriodoPagoRel()->getCodigoPeriodoPagoPk() == 5) {
                if ($varFechaperiodoPago == "30"){
                    $em->persist($arCentroCosto);
                    $em->flush();
                    if($request->request->get('ChkGenerarPeriodo')) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($arCentroCosto->getCodigoCentroCostoPk());
                    }
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_rhu_base_centros_costos_nuevo', array('codigoCentroCosto' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('brs_rhu_base_centros_costos_lista'));
                    }
                } else {
                            $mensaje = "la fecha de periodo pagado debe ser los 30 de cada mes!";
                       }
            }

        }

        return $this->render('BrasaRecursoHumanoBundle:Base/CentroCosto:nuevo.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'mensaje' => $mensaje,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago));
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrDescuentosFinancierosSeleccionados = $request->request->get('ChkSeleccionarDescuentoFinanciero');
            switch ($request->request->get('OpSubmit')) {
                case "OpGenerar";
                    $strResultado = $em->getRepository('BrasaTransporteBundle:TteDespacho')->Generar($codigoDespacho);
                    if ($strResultado != "") {
                        $objMensaje->Mensaje("error", "No se genero el despacho: " . $strResultado, $this);
                    }
                    break;

                case "OpAnular";
                    $varAnular = $em->getRepository('BrasaTransporteBundle:TteDespacho')->Anular($codigoDespacho);
                    if ($varAnular != "") {
                        $objMensaje->Mensaje("error", "No se anulo el despacho: " . $varAnular, $this);
                    }
                    break;

                case "OpImprimir";
                    $objFormatoManifiesto = new \Brasa\TransporteBundle\Formatos\FormatoManifiesto();
                    $objFormatoManifiesto->Generar($this, $codigoDespacho);
                    break;

                case "OpRetirar";
                    if (count($arrSeleccionados) > 0) {
                        $intUnidades = $arDespacho->getCtUnidades();
                        $intPesoReal = $arDespacho->getCtPesoReal();
                        $intPesoVolumen = $arDespacho->getCtPesoVolumen();
                        $intGuias = $arDespacho->getCtGuias();
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\TransporteBundle\Entity\TteGuia();
                            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuia')->find($codigoGuia);
                            if($arGuia->getCodigoDespachoFk() != NULL) {
                                $arGuia->setCodigoDespachoFk(NULL);
                                $arGuia->setEstadoDespachada(0);
                                $em->persist($arGuia);
                                $em->flush();
                                $intUnidades = $intUnidades - $arGuia->getCtUnidades();
                                $intPesoReal = $intPesoReal - $arGuia->getCtPesoReal();
                                $intPesoVolumen = $intPesoVolumen - $arGuia->getCtPesoVolumen();
                                $intGuias = $intGuias - 1;
                            }
                        }
                        $arDespacho->setCtUnidades($intUnidades);
                        $arDespacho->setCtPesoReal($intPesoReal);
                        $arDespacho->setCtPesoVolumen($intPesoVolumen);
                        $arDespacho->setCtGuias($intGuias);
                        $em->persist($arDespacho);
                        $em->flush();
                    }
                    break;
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Pagos:detalle.html.twig', array(
                    'arPago' => $arPago,
                    'arPagoDetalles' => $arPagoDetalles
                    ));
    }
    
     private function generarExcel() {
         $em = $this->getDoctrine()->getManager();
         $objPHPExcel = new \PHPExcel();
                // Set document properties
                $objPHPExcel->getProperties()->setCreator("JG Efectivos")
                    ->setLastModifiedBy("JG Efectivos")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Nombre')
                            ->setCellValue('C1', 'Ciudad')
                            ->setCellValue('D1', 'Periodo')
                            ->setCellValue('E1', 'Abierto');

                $i = 2;
                $arCentrosCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
                foreach ($arCentrosCostos as $arCentroCosto) {
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCentroCosto->getCodigoCentroCostoPk())
                            ->setCellValue('B' . $i, $arCentroCosto->getNombre())
                            ->setCellValue('C' . $i, $arCentroCosto->getCiudadRel()->getNombre())
                            ->setCellValue('D' . $i, $arCentroCosto->getPeriodoPagoRel()->getNombre())
                            ->setCellValue('E' . $i, $arCentroCosto->getPagoAbierto());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('ccostos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="CentrosCostos.xlsx"');
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
