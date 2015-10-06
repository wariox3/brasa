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
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 13, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(143, 7, utf8_decode("PAGO LIQUIDACIÓN"), 0, 0, 'C', 1);
        $this->SetXY(53, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);
        $this->Ln(1);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find(self::$codigoLiquidacion);
        $intY = 42;
        //FILA 1
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY(10, $intY);
        $this->Cell(35, 5, utf8_decode("LIQUIDACIÓN:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getCodigoLiquidacionPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "CENTRO COSTO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(95, 5, utf8_decode($arLiquidacion->getCentroCostoRel()->getNombre()), 1, 0, 'L', 1);
        //FILA 2
        $intY += 5;
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, $intY);
        $this->Cell(35, 5, utf8_decode("DOCUMENTO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "EMPLEADO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(95, 5, utf8_decode($arLiquidacion->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L', 1);
        //FILA 3
        $intY += 5;
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, $intY);
        $this->Cell(35, 5, utf8_decode("FECHA INGRESO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getFechaDesde()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, utf8_decode("NÚMERO CUENTA:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(27, 5, $arLiquidacion->getEmpleadoRel()->getCuenta(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(38, 5, utf8_decode("DIAS LABORADOS:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, $arLiquidacion->getNumeroDias(), 1, 0, 'R', 1);
        //Fila 4
        $intY += 5;
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, $intY);
        $this->Cell(35, 5, utf8_decode("FECHA RETIRO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $arLiquidacion->getFechaHasta()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(35, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(27, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(38, 5, utf8_decode("DÍAS NÓMINA ADICIONAL:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(30, 5, $arLiquidacion->getDiasAdicionalesIBP(), 1, 0, 'R', 1);
        //FILA 5
        $intY += 5;
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, $intY);
        $this->Cell(35, 5, utf8_decode("MOTIVO RETIRO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(150, 5, $arLiquidacion->getComentarios(), 1, 0, 'L', 1);
        //BLOQUE BASE LIQUIDACIÓN
        $intX = 123;
        $intY = 70;
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY($intX, $intY);
        $this->Cell(40, 5, utf8_decode("BASE:"), 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 6);
        $this->Cell(40, 5, "BASE PRESTACIONES:", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 12);
        $this->Cell(40, 5, "AUXILIO TRANSPORTE:", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 18);
        $this->Cell(40, 5, "TOTAL BASE:", 1, 0, 'L', 1);
        $intX = 163;
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY($intX, $intY);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrIngresoBasePrestacionTotal(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, $intY + 6);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrBasePrestaciones(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, $intY + 12);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrAuxilioTransporte(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, $intY + 18);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrBasePrestacionesTotal(), 0, '.', ','), 1, 0, 'R', 1);
        //BLOQUE TOTALES
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $intX = 50;
        $this->SetXY($intX + 73, 96);
        $this->Cell(15, 5, utf8_decode("DIAS"), 1, 0, 'R', 1);
        $this->SetXY($intX + 88, 96);
        $this->Cell(25, 5, utf8_decode("DESDE"), 1, 0, 'L', 1);
        $this->SetXY($intX + 113, 96);
        $this->Cell(32, 5, utf8_decode("TOTAL"), 1, 0, 'R', 1);

        $this->SetXY($intX + 28, 102);
        $this->Cell(43, 5, utf8_decode("CESANTÍAS:"), 1, 0, 'L', 1);
        $this->SetXY($intX + 28, 108);
        $this->Cell(43, 5, "INTERESES:", 1, 0, 'L', 1);
        $this->SetXY($intX + 28, 114);
        $this->Cell(43, 5, "PRIMA SEMESTRAL:", 1, 0, 'L', 1);
        $this->SetXY($intX + 28, 120);
        $this->Cell(43, 5, "VACACIONES:", 1, 0, 'L', 1);
        $this->SetXY($intX + 28, 126);
        $this->Cell(43, 5, "DEDUCCIONES CREDITOS:", 1, 0, 'L', 1);
        $this->SetXY($intX + 28, 132);
        $this->Cell(43, 5, "DEDUCCIONES PRIMAS:", 1, 0, 'L', 1);

        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY($intX + 73, 102);
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 73, 108);
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 73, 114);
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasPrimas(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 73, 120);
        $this->Cell(15, 5, number_format($arLiquidacion->getDiasVacaciones(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 73, 126);
        $this->Cell(15, 5, "", 1, 0, 'R', 1);
        $this->SetXY($intX + 73, 132);
        $this->Cell(15, 5, "", 1, 0, 'R', 1);

        $this->SetXY($intX + 88, 102);
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoCesantias()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetXY($intX + 88, 108);
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoCesantias()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetXY($intX + 88, 114);
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoPrimas()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetXY($intX + 88, 120);
        $this->Cell(25, 5, $arLiquidacion->getFechaUltimoPagoVacaciones()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetXY($intX + 88, 126);
        $this->Cell(25, 5, "", 1, 0, 'L', 1);
        $this->SetXY($intX + 88, 132);
        $this->Cell(25, 5, "", 1, 0, 'L', 1);

        //$intX = 163;
        $this->SetXY($intX + 113, 102);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 113, 108);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrInteresesCesantias(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 113, 114);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrPrima(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 113, 120);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrVacaciones(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 113, 126);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrDeducciones(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX + 113, 132);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrDeduccionPrima(), 0, '.', ','), 1, 0, 'R', 1);

        $this->SetFont('Arial', 'B', 8);
        $this->SetXY($intX + 113, 138);
        $this->Cell(32, 5, number_format($arLiquidacion->getVrTotal(), 0, '.', ','), 1, 0, 'R', 1);

        $this->Ln(15);

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(185, 7, "OTRAS DEDUCCIONES:", 1, 0, 'C', 1);
        $this->Ln();
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 8);
        $header = array('COD', 'CONCEPTO', 'NOMBRE', 'DETALLES', 'VALOR');

        //creamos la cabecera de la tabla.
        $w = array(12, 15, 45, 89, 24);
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
        $this->Footer($arLiquidacion);
    }

    public function Body($pdf) {
        $arLiquidacionDeduccion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion();
        $arLiquidacionDeduccion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionDeduccion')->findBy(array('codigoLiquidacionFk' => self::$codigoLiquidacion));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        foreach ($arLiquidacionDeduccion as $arLiquidacionDeduccion) {
            $pdf->Cell(12, 4, $arLiquidacionDeduccion->getCodigoLiquidacionDeduccionPk(), 1, 0, 'L');

            if($arLiquidacionDeduccion->getCodigoCreditoFk()) {
                $pdf->Cell(15, 4, $arLiquidacionDeduccion->getCodigoCreditoFk(), 1, 0, 'L');
                $pdf->Cell(45, 4, $arLiquidacionDeduccion->getCreditoRel()->getCreditoTipoRel()->getNombre(), 1, 0, 'L');
            } else {
                $pdf->Cell(15, 4, $arLiquidacionDeduccion->getCodigoLiquidacionDeduccionConceptoFk(), 1, 0, 'L');
                $pdf->Cell(45, 4, $arLiquidacionDeduccion->getLiquidacionDeduccionConceptoRel()->getNombre(), 1, 0, 'L');
            }

            $pdf->Cell(89, 4, $arLiquidacionDeduccion->getDetalle(), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(24, 4, number_format($arLiquidacionDeduccion->getVrDeduccion(), 0,'.',','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find(self::$codigoLiquidacion);
        $this->SetFont('Arial', 'B', 9);
        $this->Text(10, 240, "FIRMA: _____________________________________________");
        $this->Text(10, 247, $arLiquidacion->getEmpleadoRel()->getNombreCorto());
        $this->Text(10, 254, "C.C.:     ______________________ de ____________________");
        $this->Text(105, 240, "FIRMA: _____________________________________________");
        $this->Text(105, 247, $arConfiguracion->getNombreEmpresa());
        $this->Text(105, 254, "NIT: ". $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>
