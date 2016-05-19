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
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $form = $this->formularioDetalle($arProgramacion);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arProgramacion->getEstadoAutorizado() == 0) {
                    $arrControles = $request->request->All();
                    $this->actualizarDetalle($arrControles, $codigoProgramacion);                    
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
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoProgramacion);
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
                return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
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
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->pendientesCliente($arProgramacion->getCodigoClienteFk());
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
            } else {
                $arProgramacionDetalle->setRecursoRel(NULL);
            }
            if($arrControles['TxtPuesto'.$intCodigo] != '') {
                $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
                $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($arrControles['TxtPuesto'.$intCodigo]);
                if($arPuesto) {
                    $arProgramacionDetalle->setPuestoRel($arPuesto);
                }
            }
            if($arrControles['TxtDia01D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia01D'.$intCodigo]);
                $arProgramacionDetalle->setDia1($strTurno);
            } else {
                $arProgramacionDetalle->setDia1(null);
            }
            if($arrControles['TxtDia02D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia02D'.$intCodigo]);
                $arProgramacionDetalle->setDia2($strTurno);
            } else {
                $arProgramacionDetalle->setDia2(null);
            }
            if($arrControles['TxtDia03D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia03D'.$intCodigo]);
                $arProgramacionDetalle->setDia3($strTurno);
            } else {
                $arProgramacionDetalle->setDia3(null);
            }
            if($arrControles['TxtDia04D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia04D'.$intCodigo]);
                $arProgramacionDetalle->setDia4($strTurno);
            } else {
                $arProgramacionDetalle->setDia4(null);
            }
            if($arrControles['TxtDia05D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia05D'.$intCodigo]);
                $arProgramacionDetalle->setDia5($strTurno);
            } else {
                $arProgramacionDetalle->setDia5(null);
            }
            if($arrControles['TxtDia06D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia06D'.$intCodigo]);
                $arProgramacionDetalle->setDia6($strTurno);
            } else {
                $arProgramacionDetalle->setDia6(null);
            }
            if($arrControles['TxtDia07D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia07D'.$intCodigo]);
                $arProgramacionDetalle->setDia7($strTurno);
            } else {
                $arProgramacionDetalle->setDia7(null);
            }
            if($arrControles['TxtDia08D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia08D'.$intCodigo]);
                $arProgramacionDetalle->setDia8($strTurno);
            } else {
                $arProgramacionDetalle->setDia8(null);
            }
            if($arrControles['TxtDia09D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia09D'.$intCodigo]);
                $arProgramacionDetalle->setDia9($strTurno);
            } else {
                $arProgramacionDetalle->setDia9(null);
            }
            if($arrControles['TxtDia10D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia10D'.$intCodigo]);
                $arProgramacionDetalle->setDia10($strTurno);
            } else {
                $arProgramacionDetalle->setDia10(null);
            }
            if($arrControles['TxtDia11D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia11D'.$intCodigo]);
                $arProgramacionDetalle->setDia11($strTurno);
            } else {
                $arProgramacionDetalle->setDia11(null);
            }
            if($arrControles['TxtDia12D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia12D'.$intCodigo]);
                $arProgramacionDetalle->setDia12($strTurno);
            } else {
                $arProgramacionDetalle->setDia12(null);
            }
            if($arrControles['TxtDia13D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia13D'.$intCodigo]);
                $arProgramacionDetalle->setDia13($strTurno);
            } else {
                $arProgramacionDetalle->setDia13(null);
            }
            if($arrControles['TxtDia14D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia14D'.$intCodigo]);
                $arProgramacionDetalle->setDia14($strTurno);
            } else {
                $arProgramacionDetalle->setDia14(null);
            }
            if($arrControles['TxtDia15D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia15D'.$intCodigo]);
                $arProgramacionDetalle->setDia15($strTurno);
            } else {
                $arProgramacionDetalle->setDia15(null);
            }
            if($arrControles['TxtDia16D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia16D'.$intCodigo]);
                $arProgramacionDetalle->setDia16($strTurno);
            } else {
                $arProgramacionDetalle->setDia16(null);
            }
            if($arrControles['TxtDia17D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia17D'.$intCodigo]);
                $arProgramacionDetalle->setDia17($strTurno);
            } else {
                $arProgramacionDetalle->setDia17(null);
            }
            if($arrControles['TxtDia18D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia18D'.$intCodigo]);
                $arProgramacionDetalle->setDia18($strTurno);
            } else {
                $arProgramacionDetalle->setDia18(null);
            }
            if($arrControles['TxtDia19D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia19D'.$intCodigo]);
                $arProgramacionDetalle->setDia19($strTurno);
            } else {
                $arProgramacionDetalle->setDia19(null);
            }
            if($arrControles['TxtDia20D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia20D'.$intCodigo]);
                $arProgramacionDetalle->setDia20($strTurno);
            } else {
                $arProgramacionDetalle->setDia20(null);
            }
            if($arrControles['TxtDia21D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia21D'.$intCodigo]);
                $arProgramacionDetalle->setDia21($strTurno);
            } else {
                $arProgramacionDetalle->setDia21(null);
            }
            if($arrControles['TxtDia22D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia22D'.$intCodigo]);
                $arProgramacionDetalle->setDia22($strTurno);
            } else {
                $arProgramacionDetalle->setDia22(null);
            }
            if($arrControles['TxtDia23D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia23D'.$intCodigo]);
                $arProgramacionDetalle->setDia23($strTurno);
            } else {
                $arProgramacionDetalle->setDia23(null);
            }
            if($arrControles['TxtDia24D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia24D'.$intCodigo]);
                $arProgramacionDetalle->setDia24($strTurno);
            } else {
                $arProgramacionDetalle->setDia24(null);
            }
            if($arrControles['TxtDia25D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia25D'.$intCodigo]);
                $arProgramacionDetalle->setDia25($strTurno);
            } else {
                $arProgramacionDetalle->setDia25(null);
            }
            if($arrControles['TxtDia26D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia26D'.$intCodigo]);
                $arProgramacionDetalle->setDia26($strTurno);
            } else {
                $arProgramacionDetalle->setDia26(null);
            }
            if($arrControles['TxtDia27D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia27D'.$intCodigo]);
                $arProgramacionDetalle->setDia27($strTurno);
            } else {
                $arProgramacionDetalle->setDia27(null);
            }
            if($arrControles['TxtDia28D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia28D'.$intCodigo]);
                $arProgramacionDetalle->setDia28($strTurno);
            } else {
                $arProgramacionDetalle->setDia28(null);
            }
            if($arrControles['TxtDia29D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia29D'.$intCodigo]);
                $arProgramacionDetalle->setDia29($strTurno);
            } else {
                $arProgramacionDetalle->setDia29(null);
            }
            if($arrControles['TxtDia30D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia30D'.$intCodigo]);
                $arProgramacionDetalle->setDia30($strTurno);
            } else {
                $arProgramacionDetalle->setDia30(null);
            }
            if($arrControles['TxtDia31D'.$intCodigo] != '') {
                $strTurno = $this->validarTurno($arrControles['TxtDia31D'.$intCodigo]);
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