<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCreditoType;
class CreditosController extends Controller
{    
    var $strSqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();        
        if ($form->isValid())
        {
            /*$arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked())
            {    
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $id) {
                    $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                    $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($id);
                    if ($arCreditos->getaprobado() == 1 or $arCreditos->getEstadoPagado() == 1)
                    {
                        $mensaje = "No se puede Eliminar el registro, por que el credito ya esta aprobado o cancelado!";
                    }
                    else
                    {    
                        $em->remove($arCreditos);
                        $em->flush();
                    }
                }
            }
            }*/
            if ($form->get('BtnEliminar')->isClicked()) {    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditos')->eliminarCredito($arrSeleccionados);
            }
            /*if($form->get('BtnAprobar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($id);
                        $arCreditos->setAprobado(1);
                        $em->persist($arCreditos);
                        $em->flush();
                        
                    }
                }  
            }*/
            if ($form->get('BtnAprobar')->isClicked()) {    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->aprobarCredito($arrSeleccionados);
                $this->filtrar($form);
                $this->listar();
            }
            /*if($form->get('BtnDesaprobar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($id);
                        $arCreditos->setAprobado(0);
                        $em->persist($arCreditos);
                        $em->flush();
                        
                    }
                }  
            }*/
            if ($form->get('BtnDesAprobar')->isClicked()) {    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->aprobarCredito($arrSeleccionados);
                $this->filtrar($form);
                $this->listar();
            }
            /*if($form->get('BtnSuspender')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($id);
                        if ($arCreditos->getEstadoSuspendido() == 0){
                            $arCreditos->setEstadoSuspendido(1);
                        } else {
                            $arCreditos->setEstadoSuspendido(0);
                        }
                        
                        $em->persist($arCreditos);
                        $em->flush();
                        
                    }
                }  
            }*/
            if ($form->get('BtnSuspender')->isClicked()) {    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->aprobarCredito($arrSeleccionados);
                $this->filtrar($form);
                $this->listar();
            }
            /*if($form->get('BtnExcel')->isClicked()) {
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
                            ->setCellValue('A1', 'Codigo_Credito')
                            ->setCellValue('B1', 'Tipo_Credito')
                            ->setCellValue('C1', 'Fecha_Credito')
                            ->setCellValue('D1', 'Empleado')
                            ->setCellValue('E1', 'Valor_Credito')
                            ->setCellValue('F1', 'Valor_Cuota')
                            ->setCellValue('G1', 'Valor_Seguro')
                            ->setCellValue('H1', 'Cuotas')
                            ->setCellValue('I1', 'Cuota_Actual')
                            ->setCellValue('J1', 'Pagado')
                            ->setCellValue('K1', 'Aprobado')
                            ->setCellValue('L1', 'Suspendido');

                $i = 2;
                $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findAll();
                
                foreach ($arCreditos as $arCredito) {
                    if ($arCredito->getEstadoPagado() == 1)
                    {
                        $Estado = "SI";
                    }
                    else
                    {
                        $Estado = "NO"; 
                    }
                    if ($arCredito->getAprobado() == 1)
                    {
                        $Aprobado = "SI";
                    }
                    else
                    {
                        $Aprobado = "NO"; 
                    }
                    if ($arCredito->getEstadoSuspendido() == 1)
                    {
                        $Suspendido = "SI";
                    }
                    else
                    {
                        $Suspendido = "NO"; 
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCredito->getCodigoCreditoPk())
                            ->setCellValue('B' . $i, $arCredito->getCreditoTipoRel()->getNombre())
                            ->setCellValue('C' . $i, $arCredito->getFecha())
                            ->setCellValue('D' . $i, $arCredito->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('E' . $i, $arCredito->getVrPagar())
                            ->setCellValue('F' . $i, $arCredito->getVrCuota())
                            ->setCellValue('G' . $i, $arCredito->getSeguro())
                            ->setCellValue('H' . $i, $arCredito->getNumeroCuotas())
                            ->setCellValue('I' . $i, $arCredito->getNumeroCuotaActual())
                            ->setCellValue('J' . $i, $Estado)
                            ->setCellValue('K' . $i, $Aprobado)
                            ->setCellValue('L' . $i, $Suspendido);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Creditos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Creditos.xlsx"');
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
            }*/
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnPdf')->isClicked()) {
                $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCredito();
                $objFormatoPago->Generar($this);
            }
            
        }
        $arCreditos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);        
        //$arCreditos = $paginator->paginate($query, $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Creditos:lista.html.twig', array(
            'arCreditos' => $arCreditos,
            'mensaje' => $mensaje,
            'form' => $form->createView()
            ));
    } 
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaDQL(
                    "",
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion')
                    );
    }
    
    public function nuevoAction($codigoCredito, $codigoEmpleado) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $mensaje = 0;
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito(); 
        if($codigoCredito != 0) {
            $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);    
        } else {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        }
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $PeriodoPago = $arEmpleado->getCentroCostoRel()->getPeriodoPagoRel()->getNombre();
        $form = $this->createForm(new RhuCreditoType(), $arCredito);       
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arCredito = $form->getData();
            $douVrPagar = $form->get('vrPagar')->getData();
            $intCuotas = $form->get('numeroCuotas')->getData();
            $vrSeguro = $form->get('seguro')->getData();
            $vrSaltoTotal = $douVrPagar;
            $douVrCuota = $douVrPagar / $intCuotas;
            $arCredito->setVrCuota($douVrCuota);
            $arCredito->setVrCuotaTemporal($douVrCuota);
            $arSeleccion = $request->request->get('ChkSeleccionar');
            //$arCredito->setCreditoTipoPagoRel($arCredito);   
            $arCredito->setFecha(new \DateTime('now'));
            $arCredito->setSaldo($vrSaltoTotal);
            $arCredito->setSaldoTotal($vrSaltoTotal);
            $arCredito->setNumeroCuotaActual(0);
            $arCredito->setEmpleadoRel($arEmpleado);
            $em->persist($arCredito);
            $em->flush();                            
            echo "<script languaje='javascript' type='text/javascript'>opener.location.reload();</script>";
            echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";                
        }                

        return $this->render('BrasaRecursoHumanoBundle:Creditos:nuevo.html.twig', array(
            'arCredito' => $arCredito,
            'PeriodoPago' => $PeriodoPago,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoCreditoPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()    
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        $codigoCreditoFk = $codigoCreditoPk;
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCreditoPk);
        $arCreditoPago = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
        $arCreditoPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoPago')->findBy(array('codigoCreditoFk' => $codigoCreditoFk));
        if($form->isValid()) {
                      
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoDetalleCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDetalleCredito();
                $objFormatoDetalleCredito->Generar($this, $codigoCreditoFk);
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Creditos:detalle.html.twig', array(
                    'arCreditoPago' => $arCreditoPago,
                    'arCreditos' => $arCreditos,
                    'form' => $form->createView()
                    ));
    }
    
    public function nuevoDetalleAction($codigoCreditoPk) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $mensaje = 0;
        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCreditoPk);
        $arPagoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
        $form = $this->createFormBuilder()
            ->add('creditoRel', 'text', array('data' => $codigoCreditoPk, 'attr' => array('readonly' => 'readonly')))
            ->add('vrCuota', 'text', array('data' => round($arCredito->getVrCuota() - $arCredito->getSeguro(),2), 'attr' => array('readonly' => 'readonly')))
            ->add('saldo', 'text', array('data' => round($arCredito->getSaldo(),2), 'attr' => array('readonly' => 'readonly')))    
            ->add('vrAbono','text')
            ->add('tipoPago','hidden', array('data' => 'ABONO'))    
            ->add('save', 'submit', array('label' => 'Guardar'))    
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
            $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCreditoPk);
            $saldoA = $arCredito->getSaldo();
            $Abono = $form->get('vrAbono')->getData();
            
            if ($Abono > $arCredito->getSaldoTotal()){
                $mensaje = "El abono no puede ser superior al saldo!";
            } else {    
                $arCredito->setSaldo($saldoA - $Abono);
                $arCredito->setSaldoTotal($arCredito->getSaldo() - $arCredito->getVrCuotaTemporal());
                if ($arCredito->getSaldo() <= 0){
                   $arCredito->setEstadoPagado(1); 
                }
                $nroACuotas = $arCredito->getNumeroCuotaActual();
                $seguro = $arCredito->getSeguro();
                $arCredito->setNumeroCuotaActual($nroACuotas + 1);
                $arPagoCredito->setCreditoRel($arCredito);
                $arPagoCredito->setvrCuota($form->get('vrAbono')->getData());
                $arPagoCredito->setfechaPago(new \ DateTime("now"));    
                $arPagoCredito->settipoPago('ABONO');
                $arPagoCredito->setCreditoRel($arCredito);
                $em->persist($arPagoCredito);
                $em->persist($arCredito);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>opener.location.reload();</script>";
                echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
            }    
                
        }                
        return $this->render('BrasaRecursoHumanoBundle:Creditos:nuevoDetalle.html.twig', array(
            'arPagoCredito' => $arPagoCredito,
            'arCredito' => $arCredito,
            'mensaje' => $mensaje,
            'form' => $form->createView()));
    }
    
}
