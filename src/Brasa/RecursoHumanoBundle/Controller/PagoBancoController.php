<?php
namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPagoBancoType;

class PagoBancoController extends Controller
{
    var $strSqlLista = "";
    var $strFecha = "";
    public function listaAction() {        
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $paginator  = $this->get('knp_paginator');
        $strSqlLista = $this->getRequest()->getSession();        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->listar();          
        if ($form->isValid()) {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if ($form->get('BtnEliminar')->isClicked()) {  
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoBanco) {
                        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
                        $em->remove($arPagoBanco);                        
                    }
                    $em->flush();
                }                
                return $this->redirect($this->generateUrl('brs_rhu_pago_banco_lista'));
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
        $arPagoBancos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoBanco/:lista.html.twig', array('arPagoBancos' => $arPagoBancos, 'form' => $form->createView()));
    } 
    
    public function nuevoAction($codigoPagoBanco) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        if($codigoPagoBanco != 0) {
            $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
        }
        $form = $this->createForm(new RhuPagoBancoType, $arPagoBanco);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arPagoBanco = $form->getData();
            $em->persist($arPagoBanco);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_pago_banco_nuevo', array('codigoPagoBanco' => 0)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoBanco:nuevo.html.twig', array(
            'arPagoBanco' => $arPagoBanco,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoPagoBanco) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();            
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);                
        $form = $this->formularioDetalle($arPagoBanco);
        $form->handleRequest($request);
        if($form->isValid()) {            
            if($form->get('BtnAutorizar')->isClicked()) {
                $arPagoBanco->setEstadoAutorizado(1);
                $em->persist($arPagoBanco);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_pago_banco_detalle', array('codigoPagoBanco' => $codigoPagoBanco)));           
            }            
            if($form->get('BtnDesAutorizar')->isClicked()) {
                $arPagoBanco->setEstadoAutorizado(0);
                $em->persist($arPagoBanco);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_pago_banco_detalle', array('codigoPagoBanco' => $codigoPagoBanco)));           
            }                        
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPagoBanco();
                $objFormatoPagoBancoDetalle->Generar($this, $codigoPagoBanco);
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {  
                $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoBancoDetalle) {
                        $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                        $arPagoBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->find($codigoPagoBancoDetalle);
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($arPagoBancoDetalle->getCodigoPagoFk());
                        $arPago->setEstadoPagadoBanco(0);
                        $em->persist($arPago);
                        $em->remove($arPagoBancoDetalle);
                    }
                    $em->flush();
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->liquidar($codigoPagoBanco);
                } 
                return $this->redirect($this->generateUrl('brs_rhu_pago_banco_detalle', array('codigoPagoBanco' => $codigoPagoBanco)));           
            }
            if($form->get('BtnArchivoBancolombia')->isClicked()) {
                if($arPagoBanco->getEstadoAutorizado() == 1) {
                    $this->generarArchivoBancolombia($arPagoBanco);
                }
            }
            
        }        
        $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagoBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array ('codigoPagoBancoFk' => $codigoPagoBanco));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoBanco:detalle.html.twig', array(
                    'arPagoBanco' => $arPagoBanco,        
                    'arPagoBancoDetalle' => $arPagoBancoDetalle,
                    'form' => $form->createView()
                    ));
    }
    
    public function detalleNuevoAction($codigoPagoBanco) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('estadoPagadoBanco' => 0));
        $arProgramacionesPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionesPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoPagado' => 1, 'estadoPagadoBanco' => 0));        
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) { 
            if ($form->get('BtnGuardar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionarProgramacion');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {                           
                        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                                
                        if($arProgramacionPago->getEstadoPagadoBanco() == 0) {
                            $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                            $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago, 'estadoPagadoBanco' => 0));
                            foreach ($arPagos as $arPago) {
                                if($arPago->getEstadoPagadoBanco() == 0) {
                                    $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                                    $arPagoBancoDetalle->setPagoBancoRel($arPagoBanco);
                                    $arPagoBancoDetalle->setPagoRel($arPago);
                                    $arPagoBancoDetalle->setNumeroIdentificacion($arPago->getEmpleadoRel()->getNumeroIdentificacion());
                                    $arPagoBancoDetalle->setNombreCorto($arPago->getEmpleadoRel()->getNombreCorto());
                                    $arPagoBancoDetalle->setCuenta($arPago->getEmpleadoRel()->getCuenta());
                                    $arPagoBancoDetalle->setVrPago($arPago->getVrNeto());                        
                                    $em->persist($arPagoBancoDetalle); 
                                    $arPago->setEstadoPagadoBanco(1);
                                    $em->persist($arPago);                            
                                }
                            }                            
                        }
                        $arProgramacionPago->setEstadoPagadoBanco(1);
                        $em->persist($arProgramacionPago);
                    }
                    $em->flush();
                }                
                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPago) {   
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
                        if($arPago->getEstadoPagadoBanco() == 0) {
                            $arPagoBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
                            $arPagoBancoDetalle->setPagoBancoRel($arPagoBanco);
                            $arPagoBancoDetalle->setPagoRel($arPago);
                            $arPagoBancoDetalle->setNumeroIdentificacion($arPago->getEmpleadoRel()->getNumeroIdentificacion());
                            $arPagoBancoDetalle->setNombreCorto($arPago->getEmpleadoRel()->getNombreCorto());
                            $arPagoBancoDetalle->setCuenta($arPago->getEmpleadoRel()->getCuenta());
                            $arPagoBancoDetalle->setVrPago($arPago->getVrNeto());                        
                            $em->persist($arPagoBancoDetalle); 
                            $arPago->setEstadoPagadoBanco(1);
                            $em->persist($arPago);                            
                        }
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->liquidar($codigoPagoBanco);
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagoBanco:detalleNuevo.html.twig', array(
            'arPagos' => $arPagos,
            'arProgramacionesPago' => $arProgramacionesPago,
            'form' => $form->createView()));
    }    
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->listaDQL(
                $this->strFecha
                );        
    }
    
    private function filtrar ($form) {
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $controles = $request->request->get('form');
        $dateFecha = $form->get('fecha')->getData();
        if($dateFecha != null) {            
            $this->strFecha = $dateFecha->format('Y-m-d');
        } else {
            $this->strFecha = "";
        }
        
    }
    
    private function generarExcel() {
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
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'ENTIDAD')
                    ->setCellValue('C1', 'TOTAL');
                    
        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagoExamenes = $query->getResult();
        foreach ($arPagoExamenes as $arPagoExamen) {
            $strNombreEntidad = "";
            if($arPagoExamen->getEntidadExamenRel()) {
                $strNombreEntidad = $arPagoExamen->getEntidadExamenRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoExamen->getCodigoPagoExamenPk())
                    ->setCellValue('B' . $i, $strNombreEntidad)
                    ->setCellValue('C' . $i, $arPagoExamen->getVrTotal());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('PagoExamen');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="pagoExamanes.xlsx"');
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
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        /*$arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadExamen',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ee')                                        
                    ->orderBy('ee.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,  
                'empty_data' => "",
                'empty_value' => "TODOS",    
                'data' => ""
            );  
        if($session->get('filtroCodigoEntidadExamen')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadExamen", $session->get('filtroCodigoEntidadExamen'));                                    
        }  */      
        $form = $this->createFormBuilder()
            //->add('entidadExamenRel', 'entity', $arrayPropiedades) 
            ->add('fecha','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }          
    
    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonArchivoBancolombia = array('label' => 'Bancolombia', 'disabled' => false);        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarDetalle['disabled'] = true;
        } else {
            $arrBotonImprimir['disabled'] = true;
            $arrBotonDesAutorizar['disabled'] = true;
        }
        $form = $this->createFormBuilder()    
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)                                
                    ->add('BtnArchivoBancolombia', 'submit', $arrBotonArchivoBancolombia)
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)
                    ->getForm();  
        return $form;
    }    
    
    private function generarArchivoBancolombia ($arPagoBanco) {
        $em = $this->getDoctrine()->getManager();
        //$arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $strNombreArchivo = "ArchivoPagoBancolombia" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;                                    
        $ar = fopen($strArchivo,"a") or die("Problemas en la creacion del archivo plano");
        // Encabezado
        $strNitEmpresa = $this->RellenarNr($arConfiguracionGeneral->getNitEmpresa(),"0",10,"I");
        $strNombreEmpresa = $arConfiguracionGeneral->getNombreEmpresa();
        $strTipoPagoSecuencia = $arPagoBanco->getDescripcion();
        $strSecuencia = $arPagoBanco->getDescripcion();
        $strFechaCreacion = $arPagoBanco->getFechaTrasmision()->format('ymd');                                                                                            
        $strFechaAplicacion = $arPagoBanco->getFechaAplicacion()->format('ymd');
        $strNumeroRegistros = $this->RellenarNr($arPagoBanco->getNumeroRegistros(), "0", 6, "I");        
        $strValorTotal = $this->RellenarNr(round($arPagoBanco->getVrTotalPago()), "0", 24, "I");
        //Fin encabezado
        fputs($ar, "1" . $strNitEmpresa . $strNombreEmpresa . $strTipoPagoSecuencia . $strFechaCreacion . $strSecuencia . $strFechaAplicacion . $strNumeroRegistros . $strValorTotal . $arPagoBanco->getCuentaRel()->getCuenta() . $arPagoBanco->getCuentaRel()->getTipo() . "\r\n");
        //Inicio cuerpo
        $arPagosBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagosBancoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array ('codigoPagoBancoFk' => $arPagoBanco->getCodigoPagoBancoPk()));                        
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            fputs($ar, "6" . $this->RellenarNr($arPagoBancoDetalle->getNumeroIdentificacion(), "0", 15, "I"));
            $duoNombreCorto = substr($arPagoBancoDetalle->getNombreCorto(), 0, 18);
            fputs($ar, $this->RellenarNr($duoNombreCorto,"0", 18, "I"));
            fputs($ar, "005600078");
            fputs($ar, $this->RellenarNr($arPagoBancoDetalle->getCuenta(), "0", 17, "I"));
            fputs($ar, "S37");
            $duoValorNetoPagar = round($arPagoBancoDetalle->getVrPago());
            fputs($ar, ($this->RellenarNr($duoValorNetoPagar, "0", 10, "I")));
            fputs($ar, " ");
            fputs($ar, "\r\n");
        }
        $em->flush();
        //Fin cuerpo
        fclose($ar);                
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename='.basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;         
    }
    
    //Rellenar numeros
    private function RellenarNr($Nro, $Str, $NroCr, $strPosicion) {
                     $Longitud = strlen($Nro);
                     $Nc = $NroCr - $Longitud;
                     for ($i = 0; $i < $Nc; $i++) {
                         if($strPosicion == "I") {
                             $Nro = $Str . $Nro;
                         } else {
                             $Nro = $Nro . $Str;
                         }
                     }
                     return (string) $Nro;
                 }                
}