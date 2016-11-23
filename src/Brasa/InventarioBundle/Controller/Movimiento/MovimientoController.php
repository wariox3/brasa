<?php
namespace Brasa\InventarioBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\InventarioBundle\Form\Type\InvMovimientoType;
use Brasa\InventarioBundle\Form\Type\InvMovimientoDetalleType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use PHPExcel_Style_Border;


class MovimientoController extends Controller
{
    var $strListaDql = "";

    /**
     * @Route("/inv/movimiento/movimiento/ingreso", name="brs_inv_movimiento_movimiento_ingreso")
     */
    public function ingresoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            /*if ($form->get('BtnInterfaz')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcelInterfaz();
            }*/
        }

        $arDocumentos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 40);
        return $this->render('BrasaInventarioBundle:Movimiento/Movimiento:ingreso.html.twig', array(
            'arDocumentos' => $arDocumentos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/inv/movimiento/movimiento/lista/{codigoDocumento}", name="brs_inv_movimiento_movimiento_lista")
     */
    public function movimientoAction(Request $request,$codigoDocumento) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumento();
        $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumento')->find($codigoDocumento);
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioMovimiento($arDocumento);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnExcel')->isClicked()) {
                //$this->filtrar($form);
                //$this->lista();
                $this->generarExcel($codigoDocumento);
            }
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $respuesta = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->eliminar($arrSeleccionados);
                if ($respuesta == true){
                    $objMensaje->Mensaje("error", "No se puede eliminar el movimiento, esta autorizado", $this);
                } else {
                    return $this->redirect($this->generateUrl('brs_inv_movimiento_movimiento_lista', array('codigoDocumento' => $codigoDocumento)));
                }
            }
        }
        $arMovimientos = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimientos = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->findBy(array('codigoDocumentoFk' => $codigoDocumento));
        $arMovimientos = $paginator->paginate($arMovimientos, $this->get('request')->query->get('page', 1),40);
        return $this->render('BrasaInventarioBundle:Movimiento/Movimiento:lista.html.twig', array(
            'arMovimientos' => $arMovimientos,
            'arDocumento' => $arDocumento,
            'form' => $form->createView()));
    }

    /**
     * @Route("/inv/movimiento/movimiento/nuevo/{codigoDocumento}/{codigoMovimiento}", name="brs_inv_movimiento_movimiento_nuevo")
     */
    public function nuevoAction(Request $request,$codigoDocumento, $codigoMovimiento) {
        $em = $this->getDoctrine()->getManager();
        $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumento();
        $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumento')->find($codigoDocumento);
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        if($codigoMovimiento != 0) {
            $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
        } else {
            $arMovimiento->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new InvMovimientoType, $arMovimiento);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arMovimiento->setDocumentoRel($arDocumento);
            $em->persist($arMovimiento);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_inv_movimiento_movimiento_nuevo', array('codigoDocumento' => $codigoDocumento, 'codigoMovimiento' => 0 )));
            } else {
                
                return $this->redirect($this->generateUrl('brs_inv_movimiento_movimiento_lista', array('codigoDocumento' => $codigoDocumento)));
            }
        }
        return $this->render('BrasaInventarioBundle:Movimiento/Movimiento:nuevo.html.twig', array(
            'arMovimiento' => $arMovimiento,
            'form' => $form->createView()));
    }

    /**
     * @Route("/inv/movimiento/movimiento/detalle/{codigoMovimiento}", name="brs_inv_movimiento_movimiento_detalle")
     */
    public function detalleAction(Request $request,$codigoMovimiento) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
        $form = $this->formularioDetalle($arMovimiento);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arMovimiento->getEstadoAutorizado() == 0) {
                    $arMovimiento->setEstadoAutorizado(1);
                    $em->persist($arMovimiento);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_inv_movimiento_movimiento_detalle', array('codigoMovimiento' => $codigoMovimiento)));
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arMovimiento->getEstadoAutorizado() == 1) {
                    $arMovimiento->setEstadoAutorizado(0);
                    $em->persist($arMovimiento);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_inv_movimiento_movimiento_detalle', array('codigoMovimiento' => $codigoMovimiento)));
                }
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->eliminarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_inv_movimiento_movimiento_detalle', array('codigoMovimiento' => $codigoMovimiento)));
            }
            if($form->get('BtnImprimir')->isClicked()) {
                if($arMovimiento->getEstadoAutorizado() == 1) {
                    $objMovimiento = new \Brasa\InventarioBundle\Formatos\FormatoMovimiento();
                    $objMovimiento->Generar($this, $codigoMovimiento);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir el movimiento sin estar autorizada", $this);
                }
            }
        }

        $arMovimientosDetalles = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
        $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->FindBy(array('codigoMovimientoFk' => $codigoMovimiento));
        return $this->render('BrasaInventarioBundle:Movimiento/Movimiento:detalle.html.twig', array(
                    'arMovimiento' => $arMovimiento,
                    'arMovimientoDetalle' => $arMovimientosDetalles,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/inv/movimiento/movimiento/detalle/nuevo/{codigoMovimiento}", name="brs_inv_movimiento_movimiento_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $codigoMovimiento) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
        $arBodega = new \Brasa\InventarioBundle\Entity\InvBodega();
        $arBodega = $em->getRepository('BrasaInventarioBundle:InvBodega')->find(1);
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
                        $arItem = new \Brasa\InventarioBundle\Entity\InvItem();
                        $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($codigo);
                        $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
                        $arMovimientoDetalle->setVrPrecio($arItem->getVrPrecioPredeterminado());
                        $arMovimientoDetalle->setCantidad($arItem->getCantidadDisponible());
                        $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                        $arMovimientoDetalle->setItemRel($arItem);
                        $arMovimientoDetalle->setCodigoBodegaFk($arBodega->getCodigoBodegaPk());
                        $em->persist($arMovimientoDetalle);
                    }
                    $em->persist($arMovimientoDetalle);
                    $em->flush();
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
        return $this->render('BrasaInventarioBundle:Movimiento/Movimiento:detalleNuevo.html.twig', array(
            'arItem' => $arItem,
            'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaInventarioBundle:InvDocumento')->listaDql();
    }

    private function filtrar ($form) {

    }

    private function formularioMovimiento() {
        $form = $this->createFormBuilder()
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Desautorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonEliminar = array('label' => 'Eliminar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminar['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }
        $form = $this->createFormBuilder()
            ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
            ->add('BtnImprimir', 'submit', $arrBotonImprimir)
            ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)
            ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminar)
            ->getForm();
        return $form;
    }

    private function formularioFiltro() {
        $form = $this->createFormBuilder()

            ->getForm();
        return $form;
    }

    private function generarExcel($codigoDocumento) {
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
        for($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        /*for($col = 'I'; $col !== 'O'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }*/
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'NUMERO')
                    ->setCellValue('D1', 'TERCERO')
                    ->setCellValue('E1', 'AUTORIZADO');

        $i = 2;

        $arMovimientos = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimientos = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->findBy(array('codigoDocumentoFk' => $codigoDocumento));

        foreach ($arMovimientos as $arMovimiento) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arMovimiento->getCodigoMovimientoPk())
                    ->setCellValue('B' . $i, $arMovimiento->getFecha()->format('Y/m/d'))
                    ->setCellValue('C' . $i, $arMovimiento->getNumero())
                    ->setCellValue('D' . $i, $arMovimiento->getTerceroRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $objFunciones->devuelveBoolean($arMovimiento->getEstadoAutorizado()));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Movimientos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Movimientos.xlsx"');
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