<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoPago extends \FPDF_FPDF {
    public static $em;
    public static $codigoPago;
    public function Generar($miThis, $codigoPago) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoPago = $codigoPago;
        $pdf = new FormatoPago();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Pago$codigoPago.pdf", 'D');        
        
    } 
    public function Header() {
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find(self::$codigoPago);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(193, 10, "INFORMACION PROGAMACION PAGO " , 1, 0, 'L', 1);
        //FILA 1
        $this->SetXY(10, 30);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(22, 6, "PAGO:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',7);
        $this->Cell(78, 6, $arPago->getCodigoPagoPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(24, 6, $arPago->getFechaHasta()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "SALARIO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(21, 6, number_format($arPago->getEmpleadoRel()->getVrSalario(), 2, '.', ',') , 1, 0, 'R', 1);
        //FILA 2
        $this->SetXY(10, 35);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(22, 6, "EMPLEADO:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',6);
        $this->Cell(78, 6, $arPago->getEmpleadoRel()->getNombreCorto() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "IDENTIFICACION:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(24, 6, $arPago->getEmpleadoRel()->getNumeroIdentificacion() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "CUENTA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(21, 6, $arPago->getEmpleadoRel()->getCuenta() , 1, 0, 'L', 1);
        //FILA 3
        $this->SetXY(10, 40);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(22, 6, "CARGO:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',6);
        $this->Cell(78, 6, $arPago->getEmpleadoRel()->getCargoDescripcion() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "EPS:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',6);
        $this->Cell(24, 6, $arPago->getEmpleadoRel()->getEntidadSaludRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "SALUD :" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',6);
        $this->Cell(21, 6, $arPago->getEmpleadoRel()->getEntidadPensionRel()->getNombre() , 1, 0, 'L', 1);
        //FILA 4
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(22, 6, "CENTRO COSTOS:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',6);
        $this->Cell(78, 6, "FUNDACION CANES CENTRO REHABILITACION ECUENTE DE RISARALDA" , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "DESDE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(24, 6, $arPago->getFechaDesde()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 6, "HASTA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(21, 6, $arPago->getFechaHasta()->format('Y/m/d') , 1, 0, 'L', 1);
        //FILA 5
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(22, 5, "PERIODO PAGO:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',6.5);
        $this->Cell(78, 5, $arPago->getCentroCostoRel()->getPeriodoPagoRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 5, "FECHA IMPRESION:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(24, 5, date('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        $this->Cell(24, 5, "SALARIO PERIODO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(21, 5, number_format($arPago->getVrSalario(), 2, '.', ',') , 1, 0, 'R', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('CONCEPTO', 'DETALLE', 'HORAS', 'VR. HORA', '%', 'DEDUCCION', 'DEVENGADO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(75, 40, 12, 14, 12, 20, 20);
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
        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalle = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => self::$codigoPago));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arPagoDetalle as $arPagoDetalle) {            
            $pdf->Cell(75, 4, $arPagoDetalle->getPagoConceptoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(40, 4, $arPagoDetalle->getDetalle(), 1, 0, 'L');
            $pdf->Cell(12, 4, number_format($arPagoDetalle->getNumeroHoras(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(14, 4, number_format($arPagoDetalle->getVrHora(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(12, 4, number_format($arPagoDetalle->getPorcentajeAplicado(), 2, '.', ','), 1, 0, 'R');
            if($arPagoDetalle->getOperacion() == -1) {
                $pdf->Cell(20, 4, "-".number_format($arPagoDetalle->getVrPago(), 2, '.', ','), 1, 0, 'R');    
            } else {
                $pdf->Cell(20, 4, number_format(0, 2, '.', ','), 1, 0, 'R');    
            }            
            if($arPagoDetalle->getOperacion() == 1) {
                $pdf->Cell(20, 4, number_format($arPagoDetalle->getVrPago(), 2, '.', ','), 1, 0, 'R');    
            } else {
                $pdf->Cell(20, 4, number_format(0, 2, '.', ','), 1, 0, 'R');    
            }            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
        }
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find(self::$codigoPago);
        $pdf->SetXY(163, 78);
        $pdf->Cell(20, 4, "-".number_format($arPago->getVrDeducciones(), 2, '.', ','), 1, 0, 'R');
        $pdf->Cell(20, 4, number_format($arPago->getVrDevengado(), 2, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->SetXY(163, 82);
        $pdf->SetFont('', 'B', 7);
        $pdf->Cell(20, 4, "NETO PAGAR", 1, 0, 'R');
        $pdf->Cell(20, 4, number_format($arPago->getVrNeto(), 2, '.', ','), 1, 0, 'R');
    }

    public function Footer() {
        $this->SetFont('Arial','B', 9);    
        $this->Line(30, 271, 100, 271);        
        $this->Line(120, 271, 180, 271);        
        $this->Text(50, 275, "FIRMA RECIBIDO"); 
        $this->Text(140, 275, "FIRMA BODEGA");
        $this->SetFont('Arial','', 10);  
        $this->Text(170, 290, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }    
}

?>
