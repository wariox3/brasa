<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCreditoType;
use Doctrine\ORM\EntityRepository;

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
        $mensaje = 0;
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        if ($arCreditos->getaprobado() == 1 or $arCreditos->getEstadoPagado() == 1) {
                            $mensaje = "No se puede Eliminar el registro, por que el credito ya esta aprobado o cancelado!";
                        }
                        else {    
                            $em->remove($arCreditos);
                            $em->flush();
                        }
                    }
                }
                $this->filtrarLista($form);
                $this->listar();
            }
            
            if($form->get('BtnAprobar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        $arCreditos->setAprobado(1);
                        $em->persist($arCreditos);
                        $em->flush();
                    }
                }
                $this->filtrarLista($form);
                $this->listar();  
            }
            
            if($form->get('BtnDesaprobar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        $arCreditos->setAprobado(0);
                        $em->persist($arCreditos);
                        $em->flush();
                    }
                }
                $this->filtrarLista($form);
                $this->listar();  
            }
            
            if($form->get('BtnSuspender')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        if ($arCreditos->getEstadoSuspendido() == 0){
                            $arCreditos->setEstadoSuspendido(1);
                        } else {
                            $arCreditos->setEstadoSuspendido(0);
                        }
                        $em->persist($arCreditos);
                        $em->flush();
                    }
                }
                $this->filtrarLista($form);
                $this->listar();   
            }
            
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCredito();
                $objFormatoCredito->Generar($this, $this->strSqlLista);
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arCreditos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                        
        return $this->render('BrasaRecursoHumanoBundle:Creditos:lista.html.twig', array(
            'arCreditos' => $arCreditos,
            'mensaje' => $mensaje,
            'form' => $form->createView()
            ));
    } 
    
    public function refinanciarAction($codigoCredito) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $formCredito = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_creditos_refinanciar', array('codigoCredito' => $codigoCredito)))
            ->add('numeroCuotas', 'text', array('label'  => 'Numero cuotas'))                            
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $formCredito->handleRequest($request);        
        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);                
        if ($formCredito->isValid()) {
            $intCuotas = $formCredito->get('numeroCuotas')->getData();            
            $douVrCuota = $arCredito->getSaldoTotal() / $intCuotas;
            $arCredito->setVrCuota($douVrCuota);
            $arCredito->setNumeroCuotaActual(0);
            $arCredito->setNumeroCuotas($intCuotas);
            $em->persist($arCredito);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_credito_detalle', array('codigoCreditoPk' => $codigoCredito)));
        }
        $strErrores = "";
        if($arCredito->getVrCuotaTemporal() > 0) {
            $strErrores = "No se puede refinanciar el credito porque tiene periodos generados pendientes por pagar.";
        }
        return $this->render('BrasaRecursoHumanoBundle:Creditos:refinanciar.html.twig', array(
            'arCredito' => $arCredito,
            'formCredito' => $formCredito->createView(),
            'errores' => $strErrores
        ));
    }       
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->listaCreditoDQL(
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        
        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnAprobar', 'submit', array('label'  => 'Aprobar',))
            ->add('BtnDesaprobar', 'submit', array('label'  => 'Desaprobar',))
            ->add('BtnSuspender', 'submit', array('label'  => 'Suspender / No suspender',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))    
            ->getForm();
        return $form;
    } 
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
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
            if ($form->get('vrPagar')->getData() == 0 || $form->get('numeroCuotas')->getData() == 0){
                $mensaje = "El total a pagar y/o las cuotas no pueden estar en cero";
            } else {
                $arCredito = $form->getData();
                $douVrPagar = $form->get('vrPagar')->getData();
                $intCuotas = $form->get('numeroCuotas')->getData();
                $vrSeguro = $form->get('seguro')->getData();
                $vrSaltoTotal = $douVrPagar;
                $douVrCuota = $douVrPagar / $intCuotas;
                $arCredito->setVrCuota($douVrCuota);
                $arSeleccion = $request->request->get('ChkSeleccionar');  
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
                            
        }                
        
            return $this->render('BrasaRecursoHumanoBundle:Creditos:nuevo.html.twig', array(
            'arCredito' => $arCredito,
            'PeriodoPago' => $PeriodoPago,
            'mensaje' => $mensaje,
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
            ->add('vrCuota', 'text', array('data' => round($arCredito->getVrCuota(),2), 'attr' => array('readonly' => 'readonly')))
            ->add('saldo', 'text', array('data' => round($arCredito->getSaldo(),2), 'attr' => array('readonly' => 'readonly')))    
            ->add('saldoTotal', 'text', array('data' => round($arCredito->getSaldoTotal(),2), 'attr' => array('readonly' => 'readonly')))        
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
                $arPagoCredito->setCodigoCreditoTipoPagoFk(2);
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
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'TIPO CRÉDITO')
                            ->setCellValue('C1', 'FECHA')
                            ->setCellValue('D1', 'EMPLEADO')
                            ->setCellValue('E1', 'VALOR CRÉDITO')
                            ->setCellValue('F1', 'VALOR CUOTA')
                            ->setCellValue('G1', 'VALOR SEGURO')
                            ->setCellValue('H1', 'CUOTAS')
                            ->setCellValue('I1', 'CUOTA ACTUAL')
                            ->setCellValue('J1', 'PAGADO')
                            ->setCellValue('K1', 'APROBADO')
                            ->setCellValue('L1', 'SUSPENDIDO');

                $i = 2;
                $query = $em->createQuery($this->strSqlLista);
                $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                $arCreditos = $query->getResult();
                
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
            }
    
}
