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
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY(10, 25);        
        $this->Cell(40, 5, "EMPLEADO:", 1, 0, 'L', 1);
        $this->SetXY(10, 30);
        $this->Cell(40, 5, utf8_decode("IDENTIFICACIÓN:"), 1, 0, 'L', 1);
        $this->SetXY(10, 35);
        $this->Cell(40, 5, "CENTRO COSTOS:", 1, 0, 'L', 1);        
        $this->SetXY(10, 40);
        $this->Cell(40, 5, "TIEMPO:", 1, 0, 'L', 1);       
        $this->SetXY(10, 25);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial', '', 8);
        $intX = 50;
        $this->SetXY($intX, 25);        
        $this->Cell(145, 5, utf8_decode($arLiquidacion->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetXY($intX, 30);
        $this->Cell(145, 5, $arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetXY($intX, 35);
        $this->Cell(145, 5, utf8_decode($arLiquidacion->getCentroCostoRel()->getNombre()), 1, 0, 'L', 1);        
        $this->SetXY($intX, 40);
        $this->Cell(145, 5, "INGRESO: " . $arLiquidacion->getFechaDesde()->format('Y-m-d') . " RETIRO:" .$arLiquidacion->getFechaHasta()->format('Y-m-d'), 1, 0, 'L', 1);               
        
        $intX = 120;
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY($intX, 50);        
        $this->Cell(35, 5, utf8_decode("CESANTÍAS:"), 1, 0, 'L', 1);
        $this->SetXY($intX, 56);
        $this->Cell(35, 5, "INTERESES:", 1, 0, 'L', 1);
        $this->SetXY($intX, 62);
        $this->Cell(35, 5, "PRIMA SEMESTRAL:", 1, 0, 'L', 1);        
        $this->SetXY($intX, 68);
        $this->Cell(35, 5, "VACACIONES:", 1, 0, 'L', 1);    
        
        $intX = 155;
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY($intX, 50);        
        $this->Cell(40, 5, number_format($arLiquidacion->getVrCesantias(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, 56);
        $this->Cell(40, 5, number_format($arLiquidacion->getVrInteresesCesantias(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, 62);
        $this->Cell(40, 5, number_format($arLiquidacion->getVrPrima(), 2, '.', ','), 1, 0, 'R', 1);        
        $this->SetXY($intX, 68);
        $this->Cell(40, 5, number_format($arLiquidacion->getVrVacaciones(), 2, '.', ','), 1, 0, 'R', 1);         
        
        $this->Ln(16);
        
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(185, 7, "OTRAS DEDUCCIONES:", 1, 0, 'C', 1);
        $this->Ln();
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 8);
        $header = array('ID', 'CREDITO', 'NOMBRE', 'VR. DEDUCCION', 'DETALLES');
        
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
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
