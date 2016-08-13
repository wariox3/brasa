<?php

namespace Brasa\ContabilidadBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class IntercambioDatosController extends Controller
{
    var $strDqlLista = "";      
    /**
     * @Route("/ctb/utilidades/intercambio/datos/exportar", name="brs_ctb_utilidades_intercambio_datos_exportar")
     */
    public function exportarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formulario();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExportar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoRegistro) {                        
                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                        $arRegistro = $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->find($codigoRegistro);
                        $arRegistroExportar = new \Brasa\ContabilidadBundle\Entity\CtbRegistroExportar();
                        $arRegistroExportar->setFecha($arRegistro->getFecha());
                        $arRegistroExportar->setComprobante($arRegistro->getCodigoComprobanteFk());
                        $arRegistroExportar->setNumero($arRegistro->getNumero());
                        $arRegistroExportar->setNumeroReferencia($arRegistro->getNumeroReferencia());
                        $arRegistroExportar->setCuenta($arRegistro->getCodigoCuentaFk());
                        $arRegistroExportar->setDebito($arRegistro->getDebito());
                        $arRegistroExportar->setCredito($arRegistro->getCredito());
                        $arRegistroExportar->setNit($arRegistro->getTerceroRel()->getNumeroIdentificacion());
                        $arRegistroExportar->setDigitoVerificacion($arRegistro->getTerceroRel()->getDigitoVerificacion());
                        if($arRegistro->getCodigoCentroCostoFk()) {
                            $arRegistroExportar->setCentroCosto($arRegistro->getCentroCostoRel()->getNombre());
                        }
                        
                        if($arRegistro->getDebito() > 0) {
                            $arRegistroExportar->setTipo(1);
                        } else {
                            $arRegistroExportar->setTipo(2);
                        }
                        $arRegistroExportar->setBase($arRegistro->getBase());
                        $arRegistroExportar->setDescripcionContable($arRegistro->getDescripcionContable());
                        $em->persist($arRegistroExportar);
                        $arRegistro->setExportado(1);
                    }
                    $em->flush();
                }
            } 
            
            if($form->get('BtnExportarTodo')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $query = $em->createQuery($this->strDqlLista)->setMaxResults(5000);
                $arRegistros = $query->getResult();
                foreach ($arRegistros as $arRegistro) {
                    $arRegistroExportar = new \Brasa\ContabilidadBundle\Entity\CtbRegistroExportar();
                    $arRegistroExportar->setFecha($arRegistro->getFecha());
                    $arRegistroExportar->setComprobante($arRegistro->getCodigoComprobanteFk());
                    $arRegistroExportar->setNumero($arRegistro->getNumero());
                    $arRegistroExportar->setNumeroReferencia($arRegistro->getNumeroReferencia());
                    $arRegistroExportar->setCuenta($arRegistro->getCodigoCuentaFk());
                    $arRegistroExportar->setDebito($arRegistro->getDebito());
                    $arRegistroExportar->setCredito($arRegistro->getCredito());
                    $arRegistroExportar->setNit($arRegistro->getTerceroRel()->getNumeroIdentificacion());
                    $arRegistroExportar->setDigitoVerificacion($arRegistro->getTerceroRel()->getDigitoVerificacion());
                    if($arRegistro->getCodigoCentroCostoFk()) {
                        $arRegistroExportar->setCentroCosto($arRegistro->getCentroCostoRel()->getNombre());
                    }

                    if($arRegistro->getDebito() > 0) {
                        $arRegistroExportar->setTipo(1);
                    } else {
                        $arRegistroExportar->setTipo(2);
                    }
                    $arRegistroExportar->setBase($arRegistro->getBase());
                    $arRegistroExportar->setDescripcionContable($arRegistro->getDescripcionContable());
                    $em->persist($arRegistroExportar);
                    $arRegistro->setExportado(1);                    
                }
                $em->flush();
            }
            
            if($form->get('BtnGenerarPlano')->isClicked()) {
                
                $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);                                    
                $strNombreArchivo = "ExpIlimitada" . date('YmdHis') . ".txt";
                $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;                
                
                $ar = fopen($strArchivo, "a") or
                        die("Problemas en la creacion del archivo plano");                
                //fputs($ar, "Cuenta\tComprobante\tFecha\tDocumento\tDocumento Ref.\tNit\tDetalle\tTipo\tValor\tBase\tCentro de Costo\tTrans. Ext\tPlazo" . "\n");
                $arRegistrosExportar = $em->getRepository('BrasaContabilidadBundle:CtbRegistroExportar')->findAll();                                    
                foreach ($arRegistrosExportar as $arRegistroExportar) {
                    $floValor = 0;
                    if($arRegistroExportar->getTipo() == 1) {
                        $floValor = $arRegistroExportar->getDebito();
                    } else {
                        $floValor = $arRegistroExportar->getCredito();
                    }
                    fputs($ar, $arRegistroExportar->getCuenta() . "\t");
                    fputs($ar, $this->RellenarNr($arRegistroExportar->getComprobante(), "0", 5) . "\t");
                    fputs($ar, $arRegistroExportar->getFecha()->format('m/d/Y') . "\t");
                    fputs($ar, $this->RellenarNr($arRegistroExportar->getNumero(), "0", 9) . "\t");
                    fputs($ar, $this->RellenarNr($arRegistroExportar->getNumero(), "0", 9) . "\t");
                    fputs($ar, $arRegistroExportar->getNit() . "\t");
                    fputs($ar, $arRegistroExportar->getDescripcionContable() . "\t");
                    fputs($ar, $arRegistroExportar->getTipo() . "\t");
                    fputs($ar, $floValor . "\t");
                    fputs($ar, $arRegistroExportar->getBase() . "\t");
                    fputs($ar, $arRegistroExportar->getCentroCosto() . "\t");
                    fputs($ar, "" . "\t");
                    fputs($ar, "" . "\t");
                    fputs($ar, "\n");
                }
                fclose($ar);

                //$strSql = "TRUNCATE TABLE ctb_registro_exportar";           
                //$em->getConnection()->executeQuery($strSql);                    
                
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
            
            if($form->get('BtnGenerarOfimatica')->isClicked()) {
                $this->generarExcelInterfaceOfimatica();
            }            
        }
        $arRegistros = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaContabilidadBundle:Utilidad/IntercambioDatos:exportar.html.twig', array(
            'arRegistros' => $arRegistros,
            'form' => $form->createView()
            ));
    }          
    
    private function listar() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->listaPendienteExportarDql();
    }                 
    
    private function formulario() {
        $em = $this->getDoctrine()->getManager();                
        $form = $this->createFormBuilder()
            ->add('TxtComprobante', 'text', array('label'  => 'Comprobante'))
            ->add('TxtNumero', 'text', array('label'  => 'Numero'))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExportar', 'submit', array('label'  => 'Exportar',))
            ->add('BtnExportarTodo', 'submit', array('label'  => 'Exportar todo (max 5000)',))
            ->add('BtnGenerarOfimatica', 'submit', array('label'  => 'Ofimatica',))    
            ->add('BtnGenerarPlano', 'submit', array('label'  => 'ilimitada',))
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'BASICO')
                    ->setCellValue('H1', 'TIEMPO EXTRA')
                    ->setCellValue('I1', 'VALORES ADICIONALES')
                    ->setCellValue('J1', 'AUX. TRANSPORTE')
                    ->setCellValue('K1', 'ARP')
                    ->setCellValue('L1', 'EPS')
                    ->setCellValue('M1', 'PENSION')
                    ->setCellValue('N1', 'CAJA')
                    ->setCellValue('O1', 'ICBF')
                    ->setCellValue('P1', 'SENA')
                    ->setCellValue('Q1', 'CESANTIAS')
                    ->setCellValue('R1', 'VACACIONES')
                    ->setCellValue('S1', 'ADMON')
                    ->setCellValue('T1', 'COSTO')
                    ->setCellValue('U1', 'TOTAL')
                    ->setCellValue('W1', 'NETO')
                    ->setCellValue('X1', 'IBC')
                    ->setCellValue('Y1', 'AUX. TRANSPORTE COTIZACION')
                    ->setCellValue('Z1', 'DIAS PERIODO')
                    ->setCellValue('AA1', 'SALARIO PERIODO')
                    ->setCellValue('AB1', 'SALARIO EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('C' . $i, $arPago->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getVrSalario())
                    ->setCellValue('H' . $i, $arPago->getVrAdicionalTiempo())
                    ->setCellValue('I' . $i, $arPago->getVrAdicionalValor())
                    ->setCellValue('J' . $i, $arPago->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arPago->getVrArp())
                    ->setCellValue('L' . $i, $arPago->getVrEps())
                    ->setCellValue('M' . $i, $arPago->getVrPension())
                    ->setCellValue('N' . $i, $arPago->getVrCaja())
                    ->setCellValue('O' . $i, $arPago->getVrIcbf())
                    ->setCellValue('P' . $i, $arPago->getVrSena())
                    ->setCellValue('Q' . $i, $arPago->getVrCesantias())
                    ->setCellValue('R' . $i, $arPago->getVrVacaciones())
                    ->setCellValue('S' . $i, $arPago->getVrAdministracion())
                    ->setCellValue('T' . $i, $arPago->getVrCosto())
                    ->setCellValue('U' . $i, $arPago->getVrTotalCobrar())
                    ->setCellValue('W' . $i, $arPago->getVrNeto())
                    ->setCellValue('X' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('Y' . $i, $arPago->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('Z' . $i, $arPago->getDiasPeriodo())
                    ->setCellValue('AA' . $i, $arPago->getVrSalarioPeriodo())
                    ->setCellValue('AB' . $i, $arPago->getVrSalarioEmpleado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('costos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Costos.xlsx"');
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
    
    private function generarExcelInterfaceOfimatica() {
        $em = $this->getDoctrine()->getManager();
        set_time_limit(0);
        ini_set("memory_limit", -1);        
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'BASE')
                    ->setCellValue('B1', 'CHEQUE')
                    ->setCellValue('C1', 'CODCC')
                    ->setCellValue('D1', 'CODCOMPROB')
                    ->setCellValue('E1', 'CODIGOCTA')
                    ->setCellValue('F1', 'CREDITO')
                    ->setCellValue('G1', 'DCTO')
                    ->setCellValue('H1', 'DEBITO')
                    ->setCellValue('I1', 'DESCRIPCIO')
                    ->setCellValue('J1', 'DETALLE')
                    ->setCellValue('K1', 'FECHAMVTO')
                    ->setCellValue('L1', 'NIT');
        $i = 2;
        $dql = $em->getRepository('BrasaContabilidadBundle:CtbRegistroExportar')->listaDql();
        $query = $em->createQuery($dql);
        $arRegistrosExportar = new \Brasa\ContabilidadBundle\Entity\CtbRegistroExportar();
        $arRegistrosExportar = $query->getResult();
        foreach ($arRegistrosExportar as $arRegistroExportar) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRegistroExportar->getBase())
                    ->setCellValue('B' . $i, $arRegistroExportar->getNumeroReferencia())
                    ->setCellValue('C' . $i, $arRegistroExportar->getCentroCosto())
                    ->setCellValue('D' . $i, $arRegistroExportar->getComprobante())
                    ->setCellValue('E' . $i, $arRegistroExportar->getCuenta())
                    ->setCellValue('F' . $i, $arRegistroExportar->getCredito())
                    ->setCellValue('G' . $i, $arRegistroExportar->getNumero())
                    ->setCellValue('H' . $i, $arRegistroExportar->getDebito())
                    ->setCellValue('I' . $i, $arRegistroExportar->getDescripcionContable())
                    ->setCellValue('J' . $i, $arRegistroExportar->getDescripcionContable())
                    ->setCellValue('K' . $i, $arRegistroExportar->getFecha()->format('Y/m/d'))
                    ->setCellValue('L' . $i, $arRegistroExportar->getNit()."-".$arRegistroExportar->getDigitoVerificacion());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('registros');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MovimientoContable.xlsx"');
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
    
    public static function RellenarNr($Nro, $Str, $NroCr) {
        $Longitud = strlen($Nro);

        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++)
            $Nro = $Str . $Nro;

        return (string) $Nro;
    }       
}
