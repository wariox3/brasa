<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurPedidoType;
use Brasa\TurnoBundle\Form\Type\TurPedidoDetalleType;
class PedidoController extends Controller
{
    var $strListaDql = "";

    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurPedido')->eliminarExamen($arrSeleccionados);
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
        }

        $arPedidos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:lista.html.twig', array(
            'arPedidos' => $arPedidos,
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoPedido) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        if($codigoPedido != 0) {
            $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
        }else{
            $arPedido->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new TurPedidoType, $arPedido);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPedido = $form->getData();
            $em->persist($arPedido);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_pedido_nuevo', array('codigoPedido' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_pedido_detalle', array('codigoPedido' => $arPedido->getCodigoPedidoPk())));
            }
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:nuevo.html.twig', array(
            'arPedido' => $arPedido,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoPedido) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
        $form = $this->formularioDetalle($arPedido);
        $form->handleRequest($request);
        if($form->isValid()) {
            if ($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {
                    $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($intCodigo);
                    $arPedidoDetalle->setCantidad($arrControles['TxtCantidad'.$intCodigo]);
                    $arPedidoDetalle->setFechaDesde(date_create($arrControles['TxtFechaDesde'.$intCodigo]));
                    $arPedidoDetalle->setFechaHasta(date_create($arrControles['TxtFechaHasta'.$intCodigo]));
                    $arPedidoDetalle->setLunes($arrControles['cboLunes'.$intCodigo]);
                    $arPedidoDetalle->setMartes($arrControles['cboMartes'.$intCodigo]);
                    $arPedidoDetalle->setMiercoles($arrControles['cboMiercoles'.$intCodigo]);
                    $arPedidoDetalle->setJueves($arrControles['cboJueves'.$intCodigo]);
                    $arPedidoDetalle->setViernes($arrControles['cboViernes'.$intCodigo]);
                    $arPedidoDetalle->setSabado($arrControles['cboSabado'.$intCodigo]);
                    $arPedidoDetalle->setDomingo($arrControles['cboDomingo'.$intCodigo]);
                    $arPedidoDetalle->setFestivo($arrControles['cboFestivo'.$intCodigo]);
                    $em->persist($arPedidoDetalle);
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($codigoPedido);
                return $this->redirect($this->generateUrl('brs_tur_pedido_detalle', array('codigoPedido' => $codigoPedido)));
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($codigoPedido);
                return $this->redirect($this->generateUrl('brs_tur_pedido_detalle', array('codigoPedido' => $codigoPedido)));
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

    public function detalleNuevoAction($codigoPedido, $codigoPedidoDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        if($codigoPedidoDetalle != 0) {
            $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);
        } else {
            $arPedidoDetalle->setFechaDesde(new \DateTime('now'));
            $arPedidoDetalle->setFechaHasta(new \DateTime('now'));
        }
        $form = $this->createForm(new TurPedidoDetalleType, $arPedidoDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPedidoDetalle = $form->getData();
            $arPedidoDetalle->setPedidoRel($arPedido);
            $em->persist($arPedidoDetalle);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_pedido_detalle_nuevo', array('codigoPedido' => $codigoPedido, 'codigoPedidoDetalle' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:detalleNuevo.html.twig', array(
            'arPedido' => $arPedido,
            'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurPedido')->listaDQL();
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroAprobadoExamen', $form->get('estadoAprobado')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('estadoAprobado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAprobadoExamen')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {
            $arrBotonDetalleActualizar['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
                    ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'NOMBRES Y APELLIDOS')
                    ->setCellValue('D1', 'EDAD')
                    ->setCellValue('E1', 'SEXO')
                    ->setCellValue('F1', 'CARGO')
                    ->setCellValue('G1', 'CENTRO COSTOS')
                    ->setCellValue('H1', 'ENTIDAD / LABORATORIO')
                    ->setCellValue('I1', 'CIUDAD')
                    ->setCellValue('J1', 'FECHA EXAMEN')
                    ->setCellValue('K1', 'AÑO EXAMEN')
                    ->setCellValue('L1', 'MES EXAMEN')
                    ->setCellValue('M1', 'DIA EXAMEN')
                    ->setCellValue('N1', 'TIPO EXAMEN')
                    ->setCellValue('O1', 'TOTAL')
                    ->setCellValue('P1', 'APROBADO')
                    ->setCellValue('Q1', 'COMENTARIOS GENERALES')
                    ->setCellValue('R1', 'EXAMEN 1')
                    ->setCellValue('S1', 'ESTADO')
                    ->setCellValue('T1', 'OBSERVACIONES')
                    ->setCellValue('U1', 'EXAMEN 2')
                    ->setCellValue('V1', 'ESTADO')
                    ->setCellValue('W1', 'OBSERVACIONES')
                    ->setCellValue('X1', 'EXAMEN 3')
                    ->setCellValue('Y1', 'ESTADO')
                    ->setCellValue('Z1', 'OBSERVACIONES')
                    ->setCellValue('AA1', 'EXAMEN 4')
                    ->setCellValue('AB1', 'ESTADO')
                    ->setCellValue('AC1', 'OBSERVACIONES')
                    ->setCellValue('AD1', 'EXAMEN 5')
                    ->setCellValue('AE1', 'ESTADO')
                    ->setCellValue('AF1', 'OBSERVACIONES')
                    ->setCellValue('AG1', 'EXAMEN 6')
                    ->setCellValue('AH1', 'ESTADO')
                    ->setCellValue('AI1', 'OBSERVACIONES');

        $i = 2;

        $query = $em->createQuery($this->strListaDql);
                $arPedidos = new \Brasa\TurnoBundle\Entity\RhuDotacion();
                $arPedidos = $query->getResult();

        foreach ($arPedidos as $arPedido) {
            $strNombreCentroCosto = "";
            if($arPedido->getCentroCostoRel()) {
                $strNombreCentroCosto = $arPedido->getCentroCostoRel()->getNombre();
            }
            $strNombreEntidad = "SIN ENTIDAD";
            if($arPedido->getEntidadExamenRel()) {
                $strNombreEntidad = $arPedido->getEntidadExamenRel()->getNombre();
            }
            if ($arPedido->getEstadoAprobado() == 1){
                $aprobado = "SI";
            } else {
                $aprobado = "NO";
            }
            //Calculo edad
            $varFechaNacimientoAnio = $arPedido->getFechaNacimiento()->format('Y');
            $varFechaNacimientoMes =  $arPedido->getFechaNacimiento()->format('m');
            $varMesActual = date('m');
            if ($varMesActual >= $varFechaNacimientoMes){
                $varEdad = date('Y') - $varFechaNacimientoAnio;
            } else {
                $varEdad = date('Y') - $varFechaNacimientoAnio -1;
            }
            //Fin calculo edad
            $arDetalleExamen = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoExamenFk' => $arPedido->getCodigoExamenPk()));
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedido->getCodigoExamenPk())
                    ->setCellValue('B' . $i, $arPedido->getIdentificacion())
                    ->setCellValue('C' . $i, $arPedido->getNombreCorto())
                    ->setCellValue('D' . $i, $varEdad)
                    ->setCellValue('E' . $i, $arPedido->getCodigoSexoFk())
                    ->setCellValue('F' . $i, $arPedido->getCargoDescripcion())
                    ->setCellValue('G' . $i, $arPedido->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $strNombreEntidad)
                    ->setCellValue('I' . $i, $arPedido->getCiudadRel()->getNombre())
                    ->setCellValue('J' . $i, $arPedido->getFecha())
                    ->setCellValue('K' . $i, $arPedido->getFecha()->format('Y'))
                    ->setCellValue('L' . $i, $arPedido->getFecha()->format('m'))
                    ->setCellValue('M' . $i, $arPedido->getFecha()->format('d'))
                    ->setCellValue('N' . $i, $arPedido->getExamenClaseRel()->getNombre())
                    ->setCellValue('O' . $i, $arPedido->getVrTotal())
                    ->setCellValue('P' . $i, $aprobado)
                    ->setCellValue('Q' . $i, $arPedido->getComentarios());
                    $array = array();
                    foreach ($arDetalleExamen as $arDetalleExamen){
                        $array[] = $arDetalleExamen->getCodigoExamenTipoFk();
                        $array[] = $arDetalleExamen->getEstadoAprobado();
                        $array[] = $arDetalleExamen->getComentarios();
                    }


                    foreach ($array as $posicion=>$jugador){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('R' . $i, $jugador)
                            ->setCellValue('S' . $i, $jugador)
                            ->setCellValue('T' . $i, $jugador)
                            ->setCellValue('U' . $i, $jugador)
                            ->setCellValue('V' . $i, $jugador);
                    }

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Examen');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Examenes.xlsx"');
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