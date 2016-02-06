<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurProgramacionType;
class MovimientoProgramacionController extends Controller
{
    var $strListaDql = "";
    var $numeroProgramacion = "";
    var $codigoCliente = "";
    var $estadoAutorizado = "";
    var $estadoAnulado = "";
    var $fechaDesde = "";
    var $fechaHasta = "";
    var $filtrarFecha = "";

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
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_programacion_lista'));
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

        $arProgramaciones = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:lista.html.twig', array(
            'arProgramaciones' => $arProgramaciones,
            'arCliente' => $arCliente,
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoProgramacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        if($codigoProgramacion != 0) {
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
                    $em->persist($arProgramacion);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_programacion_nuevo', array('codigoProgramacion' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $arProgramacion->getCodigoProgramacionPk())));
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

    public function detalleAction($codigoProgramacion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $form = $this->formularioDetalle($arProgramacion);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 0) {
                    $strResultados = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->validarAutorizar($codigoProgramacion);
                    if($strResultados == "") {
                        $arrControles = $request->request->All();
                        $this->actualizarDetalle($arrControles, $codigoProgramacion);
                        $arProgramacion->setEstadoAutorizado(1);
                        $em->persist($arProgramacion);
                        $em->flush();
                    } else {
                        $objMensaje->Mensaje('error', $strResultados, $this);
                    }
                }
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 1) {
                    $arProgramacion->setEstadoAutorizado(0);
                    $em->persist($arProgramacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
                }
            }
            if($form->get('BtnAprobar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 1) {
                    $arProgramacion->setEstadoAprobado(1);
                    $em->persist($arProgramacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
                }
            }
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoProgramacion);
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }
            if($form->get('BtnImprimir')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 1) {
                    $objProgramacion = new \Brasa\TurnoBundle\Formatos\FormatoProgramacion();
                    $objProgramacion->Generar($this, $codigoProgramacion);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir sin estar autorizada", $this);
                }
            }
            if($form->get('BtnAnular')->isClicked()) {
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->anular($codigoProgramacion);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }
        }
        $strAnioMes = $arProgramacion->getFecha()->format('Y/m');
        $arrDiaSemana = array();
        for($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana);
        }
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array ('codigoProgramacionFk' => $codigoProgramacion));
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalle.html.twig', array(
                    'arProgramacion' => $arProgramacion,
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'arrDiaSemana' => $arrDiaSemana,
                    'form' => $form->createView()
                    ));
    }

    public function detalleNuevoAction($codigoProgramacion, $codigoProgramacionDetalle = 0) {
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
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaCliente($arProgramacion->getCodigoClienteFk());
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleNuevo.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'arPedidosDetalle' => $arPedidosDetalle,
            'form' => $form->createView()));
    }

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
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->pendientesCliente($arProgramacion->getCodigoClienteFk());
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleNuevoPedido.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'arPedidosDetalle' => $arPedidosDetalle,
            'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $this->filtrarFecha;
        if($filtrarFecha) {
            $strFechaDesde = $this->fechaDesde;
            $strFechaHasta = $this->fechaHasta;
        }
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurProgramacion')->listaDQL(
                $this->numeroProgramacion,
                $this->codigoCliente,
                $this->estadoAutorizado,
                $strFechaDesde,
                $strFechaHasta,
                $this->estadoAnulado);
    }

    private function filtrar ($form) {
        $this->numeroPedido = $form->get('TxtNumero')->getData();
        $this->estadoAutorizado = $form->get('estadoAutorizado')->getData();
        $this->estadoAnulado = $form->get('estadoAnulado')->getData();
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $this->fechaDesde = $dateFechaDesde->format('Y/m/d');
        $this->fechaHasta = $dateFechaHasta->format('Y/m/d');
        $this->filtrarFecha = $form->get('filtrarFecha')->getData();
    }

    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($this->fechaDesde != "") {
            $strFechaDesde = $this->fechaDesde;
        }
        if($this->fechaHasta != "") {
            $strFechaDesde = $this->fechaHasta;
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $this->numeroProgramacion))
            ->add('estadoAutorizado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $this->estadoAutorizado))
            ->add('estadoAnulado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'ANULADO', '0' => 'SIN ANULAR'), 'data' => $this->estadoAnulado))
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMMMdd', 'data' => $dateFechaDesde))
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMMMdd', 'data' => $dateFechaHasta))
            ->add('filtrarFecha', 'checkbox', array('required'  => false))
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
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = false;
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonAnular['disabled'] = false;
            if($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
                $arrBotonAprobar['disabled'] = true;

            }
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
                    ->add('BtnAnular', 'submit', $arrBotonAnular)
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
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

    private function aplicaPlantilla ($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle) {
        $boolResultado = FALSE;
        if($i >= $intDiaInicial && $i <= $intDiaFinal) {
            $strFecha = $strMesAnio . '/' . $i;
            $dateNuevaFecha = date_create($strFecha);
            $diaSemana = $dateNuevaFecha->format('N');
            if($diaSemana == 1) {
                if($arPedidoDetalle->getLunes() == 1) {
                    $boolResultado = TRUE;
                }
            }
            if($diaSemana == 2) {
                if($arPedidoDetalle->getMartes() == 1) {
                    $boolResultado = TRUE;
                }
            }
            if($diaSemana == 3) {
                if($arPedidoDetalle->getMiercoles() == 1) {
                    $boolResultado = TRUE;
                }
            }
            if($diaSemana == 4) {
                if($arPedidoDetalle->getJueves() == 1) {
                    $boolResultado = TRUE;
                }
            }
            if($diaSemana == 5) {
                if($arPedidoDetalle->getViernes() == 1) {
                    $boolResultado = TRUE;
                }
            }
            if($diaSemana == 6) {
                if($arPedidoDetalle->getSabado() == 1) {
                    $boolResultado = TRUE;
                }
            }
            if($diaSemana == 7) {
                if($arPedidoDetalle->getDomingo() == 1) {
                    $boolResultado = TRUE;
                }
            }
        }
        return $boolResultado;
    }

    private function devuelveDiaSemanaEspaniol ($dateFecha) {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "l";
                break;
            case 2:
                $strDia = "m";
                break;
            case 3:
                $strDia = "i";
                break;
            case 4:
                $strDia = "j";
                break;
            case 5:
                $strDia = "v";
                break;
            case 6:
                $strDia = "s";
                break;
            case 7:
                $strDia = "d";
                break;
        }

        return $strDia;
    }

    private function actualizarDetalle ($arrControles, $codigoProgramacion) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        foreach ($arrControles['LblCodigo'] as $intCodigo) {
            $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($intCodigo);
            if($arrControles['TxtRecurso'.$intCodigo] != '') {
                $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrControles['TxtRecurso'.$intCodigo]);
                if($arRecurso) {
                    $arProgramacionDetalle->setRecursoRel($arRecurso);
                }
            }
            if($arrControles['TxtPuesto'.$intCodigo] != '') {
                $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
                $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($arrControles['TxtPuesto'.$intCodigo]);
                if($arPuesto) {
                    $arProgramacionDetalle->setPuestoRel($arPuesto);
                }
            }
            if($arrControles['TxtDia1'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia1'.$intCodigo]);
                $arProgramacionDetalle->setDia1($strTurno);
            } else {
                $arProgramacionDetalle->setDia1(null);
            }
            if($arrControles['TxtDia2'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia2'.$intCodigo]);
                $arProgramacionDetalle->setDia2($strTurno);
            } else {
                $arProgramacionDetalle->setDia2(null);
            }
            if($arrControles['TxtDia3'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia3'.$intCodigo]);
                $arProgramacionDetalle->setDia3($strTurno);
            } else {
                $arProgramacionDetalle->setDia3(null);
            }
            if($arrControles['TxtDia4'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia4'.$intCodigo]);
                $arProgramacionDetalle->setDia4($strTurno);
            } else {
                $arProgramacionDetalle->setDia4(null);
            }
            if($arrControles['TxtDia5'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia5'.$intCodigo]);
                $arProgramacionDetalle->setDia5($strTurno);
            } else {
                $arProgramacionDetalle->setDia5(null);
            }
            if($arrControles['TxtDia6'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia6'.$intCodigo]);
                $arProgramacionDetalle->setDia6($strTurno);
            } else {
                $arProgramacionDetalle->setDia6(null);
            }
            if($arrControles['TxtDia7'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia7'.$intCodigo]);
                $arProgramacionDetalle->setDia7($strTurno);
            } else {
                $arProgramacionDetalle->setDia7(null);
            }
            if($arrControles['TxtDia8'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia8'.$intCodigo]);
                $arProgramacionDetalle->setDia8($strTurno);
            } else {
                $arProgramacionDetalle->setDia8(null);
            }
            if($arrControles['TxtDia9'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia9'.$intCodigo]);
                $arProgramacionDetalle->setDia9($strTurno);
            } else {
                $arProgramacionDetalle->setDia9(null);
            }
            if($arrControles['TxtDia10'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia10'.$intCodigo]);
                $arProgramacionDetalle->setDia10($strTurno);
            } else {
                $arProgramacionDetalle->setDia10(null);
            }
            if($arrControles['TxtDia11'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia11'.$intCodigo]);
                $arProgramacionDetalle->setDia11($strTurno);
            } else {
                $arProgramacionDetalle->setDia11(null);
            }
            if($arrControles['TxtDia12'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia12'.$intCodigo]);
                $arProgramacionDetalle->setDia12($strTurno);
            } else {
                $arProgramacionDetalle->setDia12(null);
            }
            if($arrControles['TxtDia13'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia13'.$intCodigo]);
                $arProgramacionDetalle->setDia13($strTurno);
            } else {
                $arProgramacionDetalle->setDia13(null);
            }
            if($arrControles['TxtDia14'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia14'.$intCodigo]);
                $arProgramacionDetalle->setDia14($strTurno);
            } else {
                $arProgramacionDetalle->setDia14(null);
            }
            if($arrControles['TxtDia15'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia15'.$intCodigo]);
                $arProgramacionDetalle->setDia15($strTurno);
            } else {
                $arProgramacionDetalle->setDia15(null);
            }
            if($arrControles['TxtDia16'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia16'.$intCodigo]);
                $arProgramacionDetalle->setDia16($strTurno);
            } else {
                $arProgramacionDetalle->setDia16(null);
            }
            if($arrControles['TxtDia17'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia17'.$intCodigo]);
                $arProgramacionDetalle->setDia17($strTurno);
            } else {
                $arProgramacionDetalle->setDia17(null);
            }
            if($arrControles['TxtDia18'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia18'.$intCodigo]);
                $arProgramacionDetalle->setDia18($strTurno);
            } else {
                $arProgramacionDetalle->setDia18(null);
            }
            if($arrControles['TxtDia19'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia19'.$intCodigo]);
                $arProgramacionDetalle->setDia19($strTurno);
            } else {
                $arProgramacionDetalle->setDia19(null);
            }
            if($arrControles['TxtDia20'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia20'.$intCodigo]);
                $arProgramacionDetalle->setDia20($strTurno);
            } else {
                $arProgramacionDetalle->setDia20(null);
            }
            if($arrControles['TxtDia21'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia21'.$intCodigo]);
                $arProgramacionDetalle->setDia21($strTurno);
            } else {
                $arProgramacionDetalle->setDia21(null);
            }
            if($arrControles['TxtDia22'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia22'.$intCodigo]);
                $arProgramacionDetalle->setDia22($strTurno);
            } else {
                $arProgramacionDetalle->setDia22(null);
            }
            if($arrControles['TxtDia23'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia23'.$intCodigo]);
                $arProgramacionDetalle->setDia23($strTurno);
            } else {
                $arProgramacionDetalle->setDia23(null);
            }
            if($arrControles['TxtDia24'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia24'.$intCodigo]);
                $arProgramacionDetalle->setDia24($strTurno);
            } else {
                $arProgramacionDetalle->setDia24(null);
            }
            if($arrControles['TxtDia25'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia25'.$intCodigo]);
                $arProgramacionDetalle->setDia25($strTurno);
            } else {
                $arProgramacionDetalle->setDia25(null);
            }
            if($arrControles['TxtDia26'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia26'.$intCodigo]);
                $arProgramacionDetalle->setDia26($strTurno);
            } else {
                $arProgramacionDetalle->setDia26(null);
            }
            if($arrControles['TxtDia27'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia27'.$intCodigo]);
                $arProgramacionDetalle->setDia27($strTurno);
            } else {
                $arProgramacionDetalle->setDia27(null);
            }
            if($arrControles['TxtDia28'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia28'.$intCodigo]);
                $arProgramacionDetalle->setDia28($strTurno);
            } else {
                $arProgramacionDetalle->setDia28(null);
            }
            if($arrControles['TxtDia29'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia29'.$intCodigo]);
                $arProgramacionDetalle->setDia29($strTurno);
            } else {
                $arProgramacionDetalle->setDia29(null);
            }
            if($arrControles['TxtDia30'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia30'.$intCodigo]);
                $arProgramacionDetalle->setDia30($strTurno);
            } else {
                $arProgramacionDetalle->setDia30(null);
            }
            if($arrControles['TxtDia31'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia31'.$intCodigo]);
                $arProgramacionDetalle->setDia31($strTurno);
            } else {
                $arProgramacionDetalle->setDia31(null);
            }
            $em->persist($arProgramacionDetalle);
        }
        $em->flush();
        $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
    }

    private function validarTurno($strTurno) {
        $em = $this->getDoctrine()->getManager();
        $strTurnoDevolver = NUll;
        if($strTurno != "") {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno);
            if($arTurno) {
                $strTurnoDevolver = $strTurno;
            }
        }

        return $strTurnoDevolver;
    }


}