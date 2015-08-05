<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoLiquidacion extends \FPDF_FPDF {
    public static $em;
    public static $codigoLiquidacion;
    
    public function Generar($miThis, $codigoLiquidacion) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoLiquidacion = $codigoLiquidacion;
        $pdf = new FormatoLiquidacion();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Liquidacion$codigoLiquidacion.pdf", 'D');        
        
    } 
    
    public function Header() {                        
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find(self::$codigoLiquidacion);        
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 16);
        $this->Cell(185, 7, utf8_decode('LIQUIDACIÓN DE PRESTACIONES SOCIALES'), 1, 0, 'C', 1);        
        //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY(10, 25);        
        $this->Cell(35, 5, utf8_decode("LIQUIDACIÓN:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getCodigoLiquidacionPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "CENTRO COSTO:", 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 7);
        $this->Cell(95, 5, utf8_decode($arLiquidacion->getCentroCostoRel()->getNombre()), 1, 0, 'L', 1);
        //FILA 2
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, 30);        
        $this->Cell(35, 5, utf8_decode("DOCUMENTO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "EMPLEADO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(95, 5, utf8_decode($arLiquidacion->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L', 1);
        //FILA 3
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, 35);        
        $this->Cell(35, 5, utf8_decode("DESDE:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getFechaDesde()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "HASTA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, $arLiquidacion->getFechaHasta()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, utf8_decode("NÚMERO DÍAS:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, $arLiquidacion->getNumeroDias(), 1, 0, 'R', 1);
        //BLOQUE BASE LIQUIDACIÓN
        $intX = 120;
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY($intX, 50);        
        $this->Cell(43, 5, utf8_decode("BASE LIQUIDACIÓN:"), 1, 0, 'L', 1);
        $this->SetXY($intX, 56);
        $this->Cell(43, 5, "BASE OTRAS LIQUIDACINES:", 1, 0, 'L', 1);
        $this->SetXY($intX, 61);
        $this->Cell(43, 5, "AUXILIO TRANSPORTE:", 1, 0, 'L', 1);            
        $intX = 163;
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY($intX, 50);        
        $this->Cell(32, 5, number_format($arLiquidacion->getVrBasePrestaciones(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, 56);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrBasePrestacionesTotal(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, 61);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrAuxilioTransporte(), 2, '.', ','), 1, 0, 'R', 1);        
        //BLOQUE DÍAS PRESTACIONES
        $intX = 120;
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY($intX, 75);        
        $this->Cell(43, 5, utf8_decode("DÍAS VACACIONES:"), 1, 0, 'L', 1);
        $this->SetXY($intX, 81);
        $this->Cell(43, 5, utf8_decode("DÍAS CESANTIAS:"), 1, 0, 'L', 1);
        $this->SetXY($intX, 87);
        $this->Cell(43, 5, utf8_decode("DÍAS PRIMA:"), 1, 0, 'L', 1);            
        $intX = 163;
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY($intX, 75);        
        $this->Cell(32, 5, $arLiquidacion->getDiasVacaciones(), 1, 0, 'R', 1);
        $this->SetXY($intX, 81);
        $this->Cell(32, 5, $arLiquidacion->getDiasCesantias(), 1, 0, 'R', 1);
        $this->SetXY($intX, 87);
        $this->Cell(32, 5, $arLiquidacion->getDiasPrimas(), 1, 0, 'R', 1);
        //BLOQUE TOTALES
        $intX = 120;
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY($intX, 102);        
        $this->Cell(43, 5, utf8_decode("CESANTÍAS:"), 1, 0, 'L', 1);
        $this->SetXY($intX, 108);
        $this->Cell(43, 5, "INTERESES:", 1, 0, 'L', 1);
        $this->SetXY($intX, 114);
        $this->Cell(43, 5, "PRIMA SEMESTRAL:", 1, 0, 'L', 1);        
        $this->SetXY($intX, 120);
        $this->Cell(43, 5, "VACACIONES:", 1, 0, 'L', 1);    
        $intX = 163;
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY($intX, 102);        
        $this->Cell(32, 5, number_format($arLiquidacion->getVrCesantias(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, 108);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrInteresesCesantias(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, 114);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrPrima(), 2, '.', ','), 1, 0, 'R', 1);        
        $this->SetXY($intX, 120);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrVacaciones(), 2, '.', ','), 1, 0, 'R', 1);
        
        $this->Ln(15);
        
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(185, 7, "OTRAS DEDUCCIONES:", 1, 0, 'C', 1);
        $this->Ln();
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 8);
        $header = array('COD', 'CREDITO', 'NOMBRE', 'VR. DEDUCCION', 'DETALLES');
        
        //creamos la cabecera de la tabla.
        $w = array(12, 15, 45, 24, 89);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arLiquidacionDeduccion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion();
        $arLiquidacionDeduccion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionDeduccion')->findBy(array('codigoLiquidacionFk' => self::$codigoLiquidacion));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        foreach ($arLiquidacionDeduccion as $arLiquidacionDeduccion) {            
            $pdf->Cell(12, 4, $arLiquidacionDeduccion->getCodigoLiquidacionDeduccionPk(), 1, 0, 'L');
            $pdf->Cell(15, 4, $arLiquidacionDeduccion->getCodigoCreditoFk(), 1, 0, 'L');
            $pdf->Cell(45, 4, $arLiquidacionDeduccion->getCreditoRel()->getCreditoTipoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(24, 4, number_format($arLiquidacionDeduccion->getVrDeduccion(), 2,'.',','), 1, 0, 'R');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(89, 4, $arLiquidacionDeduccion->getDetalle(), 1, 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        
        $this->SetFont('Arial', 'B', 9);
        $this->Text(10, 240, "FIRMA: _____________________________________________");
        $this->Text(105, 240, "EMPRESA: __________________________________________");
        $this->Text(10, 247, "C.C.:     ______________________ de ____________________");
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
