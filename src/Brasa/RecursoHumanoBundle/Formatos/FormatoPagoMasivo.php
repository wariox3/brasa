<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoPagoMasivo extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoProgramacionPago;
    
    public function Generar($miThis, $codigoProgramacionPago, $strRuta = "") {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoProgramacionPago = $codigoProgramacionPago;
        $pdf = new FormatoPagoMasivo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $pdf->SetFillColor(200, 200, 200);
        $this->Body($pdf);
        if($strRuta == "") {
            $pdf->Output("PagoMasivo$codigoProgramacionPago.pdf", 'D');        
        } else {
            $pdf->Output($strRuta."PagoMasivo$codigoProgramacionPago.pdf", 'F');        
        }
        
        
    } 
    
    public function Header() {
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find(self::$codigoProgramacionPago);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(5);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 14);
        $this->Image('imagenes/logos/logo.jpg', 12, 15, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, "COMPROBANTE PAGO NOMINA", 0, 0, 'C', 1);//$this->Cell(150, 7, utf8_decode("COMPROBANTE PAGO ". $arPago->getPagoTipoRel()->getNombre().""), 0, 0, 'C', 1);
        $this->SetXY(53, 22);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 34);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);
        
        
        //$this->SetFillColor(255, 255, 255);
        //$this->Cell(45, 5, "" , 1, 0, 'R', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->SetXY(10, 73);
        //$this->Ln(45);
        $header = array('CODIGO', 'CONCEPTO DE PAGO', 'HORAS', 'VR. HORA', '%', 'DEVENGADO', 'DEDUCCION');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6.8);

        //creamos la cabecera de la tabla.
        $w = array(13,87, 10, 22, 7, 27, 27);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetFillColor(200, 200, 200);
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);        
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => self::$codigoProgramacionPago));   
        foreach ($arPagos as $arPago){
            //FILA 1
            $pdf->SetXY(10, 40);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->Cell(22, 6, "NUMERO:" , 1, 0, 'L', 1);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFont('Arial','',7);
            $pdf->Cell(78, 6, $arPago->getNumero() , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "FECHA:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(24, 6, $arPago->getFechaHasta()->format('Y/m/d') , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "CUENTA:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(21, 6, $arPago->getEmpleadoRel()->getCuenta() , 1, 0, 'L', 1);
            //FILA 2
            $pdf->SetXY(10, 45);
            $pdf->SetFont('Arial','B',7);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(22, 6, "EMPLEADO:" , 1, 0, 'L', 1);                            
            $pdf->SetFont('Arial','',6);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(78, 6, utf8_decode($arPago->getEmpleadoRel()->getNombreCorto()) , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "IDENTIFICACION:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(24, 6, $arPago->getEmpleadoRel()->getNumeroIdentificacion() , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "BANCO:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',6.5);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(21, 6, utf8_decode($arPago->getEmpleadoRel()->getBancoRel()->getNombre()), 1, 0, 'L', 1);
            //FILA 3
            $pdf->SetXY(10, 50);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(22, 6, "CARGO:" , 1, 0, 'L', 1);                            
            $pdf->SetFont('Arial','',6);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(78, 6, $arPago->getEmpleadoRel()->getCargoRel()->getNombre() , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "EPS:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',6);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(24, 6, utf8_decode($arPago->getContratoRel()->getEntidadSaludRel()->getNombre()) , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, utf8_decode("PENSIÓN :") , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',6);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(21, 6, utf8_decode($arPago->getContratoRel()->getEntidadPensionRel()->getNombre()) , 1, 0, 'L', 1);
            //FILA 4
            $pdf->SetXY(10, 55);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(22, 6, "CENTRO COSTOS:" , 1, 0, 'L', 1);                            
            $pdf->SetFont('Arial','',6);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(78, 6, $arPago->getCentroCostoRel()->getNombre() , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "DESDE:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(24, 6, $arPago->getFechaDesde()->format('Y/m/d') , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "SALARIO:" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(21, 6, number_format($arPago->getEmpleadoRel()->getVrSalario(), 0, '.', ',') , 1, 0, 'R', 1);
            //FILA 5
            $pdf->SetXY(10, 60);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(22, 5, "PERIODO PAGO:" , 1, 0, 'L', 1);                            
            $pdf->SetFont('Arial','',6.5);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(78, 5, $arPago->getCentroCostoRel()->getPeriodoPagoRel()->getNombre() , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 5, "HASTA" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(24, 5, $arPago->getFechaHasta()->format('Y/m/d') , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(24, 6, "" , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','',7);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(21, 6, '' , 1, 0, 'R', 1);        
            //FILA 6
            $pdf->SetXY(10, 65);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(22, 5, "COMENTARIO:" , 1, 0, 'L', 1);                            
            $pdf->SetFont('Arial','',6.5);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(171, 5, '' , 1, 0, 'L', 1);
            $pdf->SetFont('Arial','B',6.5);
            $pdf->Ln(12);
            $totalExtras = 0;
            $totalCompensado = 0;
            $totalHorasCompensado = 0;            
            $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();        
            $dql = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->listaDql($arPago->getCodigoPagoPk());                
            $query = self::$em->createQuery($dql);
            $arPagoDetalles = $query->getResult();

            foreach ($arPagoDetalles as $arPagoDetalle) { 
                if($arPagoDetalle->getCodigoPagoConceptoFk() >= 3 && $arPagoDetalle->getCodigoPagoConceptoFk() <= 6) {
                    $totalExtras += $arPagoDetalle->getNumeroHoras();
                }
            }
            if($totalExtras > ($arPago->getDiasLaborados() * 2)){
                $tope = $arPago->getDiasLaborados() * 2;
                $tope = $tope - 8;
                $porCompensar = $totalExtras - $tope;            
                foreach ($arPagoDetalles as $arPagoDetalle) { 
                    if($arPagoDetalle->getCodigoPagoConceptoFk() >= 3 && $arPagoDetalle->getCodigoPagoConceptoFk() <= 6) {
                        $porcentaje = $arPagoDetalle->getNumeroHoras() / $totalExtras;
                        $horas = $porcentaje * $porCompensar;                    
                        $horas = round($horas);                    
                        $horasCompensadas =  $arPagoDetalle->getNumeroHoras() - $horas;
                        $valor = $horasCompensadas * $arPagoDetalle->getVrHora();    
                        $arPagoDetalle->setNumeroHoras($horas);
                        $arPagoDetalle->setVrPago($valor);
                        $totalCompensado += $horas * $arPagoDetalle->getVrHora();
                        $totalHorasCompensado += $horas;
                    }
                }
            }            
            foreach ($arPagoDetalles as $arPagoDetalle) {            
                $pdf->SetFont('Arial', '', 5.4);
                $pdf->Cell(13, 4, $arPagoDetalle->getCodigoPagoConceptoFk(), 1, 0, 'L');
                $pdf->Cell(87, 4, utf8_decode($arPagoDetalle->getPagoConceptoRel()->getNombre()), 1, 0, 'L');
                $pdf->SetFont('Arial', '', 5.5);            
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(10, 4, number_format($arPagoDetalle->getNumeroHoras(), 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(22, 4, number_format($arPagoDetalle->getVrHora(), 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(7, 4, number_format($arPagoDetalle->getPorcentajeAplicado(), 0, '.', ','), 1, 0, 'R');
                if($arPagoDetalle->getOperacion() == 1) {
                    $pdf->Cell(27, 4, number_format($arPagoDetalle->getVrPago(), 0, '.', ','), 1, 0, 'R');    
                } else {
                    $pdf->Cell(27, 4, number_format(0, 0, '.', ','), 1, 0, 'R');    
                }
                if($arPagoDetalle->getOperacion() == -1) {
                    $pdf->Cell(27, 4, "-".number_format($arPagoDetalle->getVrPago(), 0, '.', ','), 1, 0, 'R');    
                } else {
                    $pdf->Cell(27, 4, number_format(0, 0, '.', ','), 1, 0, 'R');    
                }
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);
            }
            if($totalCompensado > 0) {              
                $pdf->SetFont('Arial', '', 5.4);
                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                $arPagoConcepto = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(71);                    
                $pdf->Cell(13, 4, '71', 1, 0, 'L');
                $pdf->Cell(87, 4, utf8_decode($arPagoConcepto->getNombre()), 1, 0, 'L');
                $pdf->SetFont('Arial', '', 5.5);            
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(10, 4, '', 1, 0, 'R');
                $pdf->Cell(22, 4, '', 1, 0, 'R');
                $pdf->Cell(7, 4, '', 1, 0, 'R');
                $pdf->Cell(27, 4, number_format($totalCompensado, 0, '.', ','), 1, 0, 'R');    
                $pdf->Cell(27, 4, number_format(0, 0, '.', ','), 1, 0, 'R');    
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);            
            }
            
            //TOTALES
                $pdf->Ln(4);
                $pdf->Cell(143, 4, "", 0, 0, 'R');
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->SetFillColor(200, 200, 200);
                $pdf->Cell(30, 4, "TOTAL DEVENGADO:", 1, 0, 'R',true);
                $pdf->Cell(20, 4, number_format($arPago->getVrDevengado(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->Cell(143, 4, "", 0, 0, 'R');
                $pdf->Cell(30, 4, "TOTAL DEDUCCIONES:", 1, 0, 'R',true);
                $pdf->Cell(20, 4, "-".number_format($arPago->getVrDeducciones(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->Cell(143, 4, "", 0, 0, 'R');
                $pdf->Cell(30, 4, "NETO PAGAR", 1, 0, 'R',true);
                $pdf->Cell(20, 4, number_format($arPago->getVrNeto(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln(8);
                
            $pdf->AddPage();
        }
        
        
        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 7);
            
                    
    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
