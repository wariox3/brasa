<?php

namespace Brasa\ContabilidadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\ContabilidadBundle\Form\Type\CtbAsientoType;
use Brasa\ContabilidadBundle\Form\Type\CtbAsientoDetalleType;


class AsientoController extends Controller
{
    var $strListaDql = "";
    var $codigoAsiento = "";
    var $codigoComprobante = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $this->listar();
        if($form->isValid()) {
            $arrSelecionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()){
                if(count($arrSelecionados) > 0) {
                    foreach ($arrSelecionados AS $codigoAsiento) {
                        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
                        $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
                        if ($arAsiento->getEstadoAutorizado() == 1){
                            $objMensaje->Mensaje("error", "El asiento ". $codigoAsiento ." ya fue autorizada, no se pude eliminar", $this);
                        }else{
                            $arRegistros = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->validarAsientosDQL($codigoAsiento);
                            if ($arRegistros){
                                $objMensaje->Mensaje("error", "El asiento ". $codigoAsiento ." contiene registros asignados", $this);
                            }else{
                                $em->remove($arAsiento);
                            }
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_lista'));
                }
            }

            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
        }

        $arAsientos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaContabilidadBundle:Movimientos/Asientos:lista.html.twig', array('arAsientos' => $arAsientos, 'form' => $form->createView()));
    }

    public function nuevoAction($codigoAsiento = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();    
        if($codigoAsiento != 0) {
            $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
        } else {
            $arAsiento->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new CtbAsientoType, $arAsiento);         
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arAsiento = $form->getData();                
            //$arAsiento->setCentroCostoRel($arEmpleado->getCentroCostoRel());
            $em->persist($arAsiento);
            $em->flush();
            if($form->get('BtnGuardarNuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_nuevo', array('codigoAsiento' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $arAsiento->getCodigoAsientoPk() )));
            }                                    
        }

        return $this->render('BrasaContabilidadBundle:Movimientos/Asientos:nuevo.html.twig', array(
            'arAsiento' => $arAsiento,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoAsiento) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
        $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
        $form = $this->formularioDetalle($arAsiento);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                $autorizar = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->Autorizar($codigoAsiento);
                if ($autorizar != ""){
                    $objMensaje->Mensaje("error", $autorizar, $this);
                }
                return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                $autorizar = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->DesAutorizar($codigoAsiento);
                if ($autorizar != ""){
                    $objMensaje->Mensaje("error", $autorizar, $this);
                }
                return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
            }
            if($form->get('BtnAprobar')->isClicked()) {
                $aprobar = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->Aprobar($codigoAsiento);
                if ($aprobar != ""){
                    $objMensaje->Mensaje("error", $aprobar, $this);
                }
                return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                $arrSelecionados = $request->request->get('ChkSeleccionar');
                if(count($arrSelecionados) > 0) {
                    foreach ($arrSelecionados AS $codigoAsientoDetalle) {
                        $arAsientoDetalle = new \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle();
                        $arAsientoDetalle = $em->getRepository('BrasaContabilidadBundle:CtbAsientoDetalle')->find($codigoAsientoDetalle);
                        if ($arAsientoDetalle->getEstadoAutorizado() == 1){
                            $objMensaje->Mensaje("error", "El asiento detalle ". $codigoAsientoDetalle ." ya fue autorizada, no se pude eliminar", $this);
                        }else{
                            $em->remove($arAsientoDetalle);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
                }
            }
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigoGuia'] as $intCodigo) {
                    $arAsientoDetalle = new \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle();
                    $arAsientoDetalle = $em->getRepository('BrasaContabilidadBundle:CtbAsientoDetalle')->find($intCodigo);
                    
                    $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                    $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arrControles['TxtNumeroIdentificacion'.$intCodigo]));
                    $registros = count($arTercero);
                    $arCuenta = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arrControles['TxtCuenta'.$intCodigo]);
                    if ($registros == 0 ){
                        $objMensaje->Mensaje("error", "El sistema no modificó el registro ".$intCodigo.", por que el número de identificación ". $arrControles['TxtNumeroIdentificacion'.$intCodigo] . " no existe" , $this);
                    }else {
                        if ($arCuenta == null){
                            $objMensaje->Mensaje("error", "El sistema no modificó el registro ".$intCodigo.", por que el número de cuenta ". $arrControles['TxtCuenta'.$intCodigo] . " no existe" , $this);
                        }else {
                            $arAsientoDetalle->setTerceroRel($arTercero);
                            $arAsientoDetalle->setDocumentoReferente($arrControles['TxtDocumentoReferente'.$intCodigo]);
                            $arAsientoDetalle->setSoporte($arrControles['TxtSoporte'.$intCodigo]);
                            $arAsientoDetalle->setPlazo($arrControles['TxtPlazo'.$intCodigo]);
                            $arAsientoDetalle->setValorBase($arrControles['TxtValorBase'.$intCodigo]);
                            $arAsientoDetalle->setDebito($arrControles['TxtDebito'.$intCodigo]);
                            $arAsientoDetalle->setCredito($arrControles['TxtCredito'.$intCodigo]);
                            $em->persist($arAsientoDetalle);
                        }
                        
                    }
                    
                    
                    
                }
                $em->flush();
                $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->liquidar($codigoAsiento);
                return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle', array('codigoAsiento' => $codigoAsiento)));
            }
        }
        $arAsientoDetalles = new \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle();
        $arAsientoDetalles = $em->getRepository('BrasaContabilidadBundle:CtbAsientoDetalle')->FindBy(array('codigoAsientoFk' => $codigoAsiento));
        return $this->render('BrasaContabilidadBundle:Movimientos/Asientos:detalle.html.twig', array(
                    'arAsiento' => $arAsiento,
                    'arAsientoDetalles' => $arAsientoDetalles,
                    'form' => $form->createView()
                    ));
    }

    public function detalleNuevoAction($codigoAsiento, $codigoAsientoDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
        $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
        $arAsientoDetalle = new \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle();
        if($codigoAsientoDetalle != 0) {
            $arAsientoDetalle = $em->getRepository('BrasaContabilidadBundle:CtbAsientoDetalle')->find($codigoAsientoDetalle);
        } else {
            $arAsientoDetalle->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new CtbAsientoDetalleType, $arAsientoDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arAsientoDetalle = $form->getData();
            $arAsientoDetalle->setAsientoRel($arAsiento);
            if ($arAsientoDetalle->getAsientoTipoRel()->getCodigoAsientoTipoPk() == 1){
                $arAsientoDetalle->setDebito($arAsientoDetalle->getValorBase());
            }else {
                $arAsientoDetalle->setCredito($arAsientoDetalle->getValorBase());
            }
            $em->persist($arAsientoDetalle);
            $em->flush();
            if($form->get('BtnGuardarNuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_ctb_mov_asientos_detalle_nuevo', array('codigoAsiento' => $codigoAsiento, 'codigoAsientoDetalle' => 0 )));
            } else {
                $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->liquidar($codigoAsiento);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaContabilidadBundle:Movimientos/Asientos:detalleNuevo.html.twig', array(
            'arAsiento' => $arAsiento,
            'form' => $form->createView()));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();        
        $this->strListaDql = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->listaDQL(
                $this->codigoAsiento,
                $this->codigoComprobante                
                );
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
                    
        $form = $this->createFormBuilder()
            ->add('TxtCodigoAsiento', 'text', array('label'  => 'Código asiento'))
            ->add('comprobanteRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbComprobante',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $em = $this->getDoctrine()->getManager();
        $arComprobante = $form->get('comprobanteRel')->getData();
        if ($arComprobante == null){
            $intComprobante = "";
        }else {
            $intComprobante = $arComprobante->getCodigoComprobantePk();
        }
        $this->codigoAsiento = $form->get('TxtCodigoAsiento')->getData();
        $this->codigoComprobante = $intComprobante;
    }    
    
    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => false);
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => false);
        $arrBotonContabilizar = array('label' => 'Contabilizar', 'disabled' => false);
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonEliminarDetalle['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonContabilizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
            $arrBotonAnular['disabled'] = true;
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonContabilizar['disabled'] = true;
            $arrBotonAnular['disabled'] = true;
        }
        $form = $this->createFormBuilder()    
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnAnular', 'submit', $arrBotonAnular)
                    ->add('BtnContabilizar', 'submit', $arrBotonContabilizar)
                    ->add('BtnAprobar', 'submit', $arrBotonAprobar)
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->getForm();  
        return $form;
    }     
    
    private function generarExcel() {
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
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Fecha')
                            ->setCellValue('C1', 'Centro Centro')
                            ->setCellValue('D1', 'Identificacion')
                            ->setCellValue('E1', 'Empleado')
                            ->setCellValue('F1', 'Numero Interno Referencia');
                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arDotaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
                $arDotaciones = $query->getResult();

                foreach ($arDotaciones as $arDotacion) {

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arDotacion->getCodigoDotacionPk())
                            ->setCellValue('B' . $i, $arDotacion->getFecha()->format('Y/m/d'))
                            ->setCellValue('C' . $i, $arDotacion->getCentroCostoRel()->getNombre())
                            ->setCellValue('D' . $i, $arDotacion->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, $arDotacion->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $arDotacion->getCodigoInternoReferencia());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Dotacion');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Dotacion.xlsx"');
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