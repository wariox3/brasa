<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoDetalleVacaciones extends \FPDF_FPDF {
    public static $em;
    public static $codigoVacacion;
    
    public function Generar($miThis, $codigoVacacion) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoVacacion = $codigoVacacion;
        $pdf = new FormatoDetalleVacaciones();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $this->Header();    
        $pdf->Output("Vacaciones_$codigoVacacion.pdf", 'D');        
        
    } 
    
    public function Header() {                        
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(143, 7, utf8_decode("PAGO DE VACACIONES"), 0, 0, 'C', 1);
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
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacaciones = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find(self::$codigoVacacion);        
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
        $arCreditos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionCredito')->findBy(array('codigoVacacionFk' => self::$codigoVacacion));
        $duoRegistrosCreditos = count($arCreditos);       
        $intY = 40;
        //FILA 1
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY(10, $intY);        
        $this->Cell(31, 6, utf8_decode("CÓDIGO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(63, 6, $arVacaciones->getCodigoVacacionPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(217, 217, 217);
        $this->Cell(26, 6, "FECHA:", 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, utf8_decode($arVacaciones->getFecha()->format('Y/m/d')), 1, 0, 'L', 1);
        //FILA 2
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY(10, $intY + 6);        
        $this->Cell(31, 6, utf8_decode("EMPLEADO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7.5);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(63, 6, utf8_decode($arVacaciones->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(217, 217, 217);
        $this->Cell(26, 6, utf8_decode("IDENTIFICACIÓN:"), 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, $arVacaciones->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        //FILA 3
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY(10, $intY + 12);        
        $this->Cell(31, 6, "DESDE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(63, 6, $arVacaciones->getFechaDesdePeriodo()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(217, 217, 217);
        $this->Cell(26, 6, "CENTRO COSTOS:", 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, utf8_decode($arVacaciones->getCentroCostoRel()->getNombre()), 1, 0, 'L', 1);
        //FILA 4
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY(10, $intY + 18);        
        $this->Cell(31, 6, "HASTA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(63, 6, $arVacaciones->getFechaHastaPeriodo()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(217, 217, 217);
        $this->Cell(26, 6, utf8_decode("DÍAS VACACIONES:"), 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, $arVacaciones->getDiasVacaciones(), 1, 0, 'R', 1);
        
        //FILA 5
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY(10, $intY + 24);        
        $this->Cell(31, 6, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(63, 6, '', 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(217, 217, 217);
        $this->Cell(26, 6, utf8_decode("SALARIO:"), 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, number_format($arVacaciones->getEmpleadoRel()->getVrSalario(), 0, '.', ','), 1, 0, 'R', 1);        
        
        //FILA 6
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY(10, $intY + 30);        
        $this->Cell(31, 6, utf8_decode("COMENTARIOS:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(154, 6, $arVacaciones->getComentarios(), 1, 0, 'L', 1);
        
        //BLOQUE VACACIONES
        $intX = 120;
        $intY = 80;
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(217, 217, 217);                
        $this->SetXY($intX, $intY);
        $this->Cell(43, 5, "TOTAL VACACIONES:", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 6);
        $this->Cell(43, 5, "VR. SALUD:", 1, 0, 'L', 1); 
        $this->SetXY($intX, $intY + 12);
        $this->Cell(43, 5, utf8_decode("VR. PENSIÓN:"), 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 18);
        $this->Cell(43, 5, utf8_decode("VR. DEDUCCION CREDITOS:"), 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 24);
        $this->Cell(43, 5, utf8_decode("TOTAL DEDUCCIONES:"), 1, 0, 'L', 1);        
        $this->SetXY($intX, $intY + 30);
        $this->Cell(43, 5, "TOTAL A PAGAR:", 1, 0, 'L', 1);
        $intX = 163;
        $intY = 80;
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);        
        $this->SetXY($intX, $intY);
        $this->Cell(32, 5, number_format($arVacaciones->getVrVacacion() + $arVacaciones->getVrDeduccion()+ $arVacaciones->getVrSalud()+ $arVacaciones->getVrPension(), 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, $intY + 6);
        $this->Cell(32, 5, "(".number_format($arVacaciones->getVrSalud(), 0, '.', ',').")", 1, 0, 'R', 1);
        $this->SetXY($intX, $intY + 12);
        $this->Cell(32, 5, "(".number_format($arVacaciones->getVrPension(), 0, '.', ',').")", 1, 0, 'R', 1);
        $this->SetXY($intX, $intY + 18);
        $this->Cell(32, 5, "(".number_format($arVacaciones->getVrDeduccion(), 0, '.', ',').")", 1, 0, 'R', 1);
        $floTotalDeducciones = $arVacaciones->getVrSalud() + $arVacaciones->getVrPension() + $arVacaciones->getVrDeduccion();
        $this->SetXY($intX, $intY + 24);        
        $this->Cell(32, 5, number_format($floTotalDeducciones, 0, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, $intY + 30);
        $this->Cell(32, 5, number_format($arVacaciones->getVrVacacion(), 0, '.', ','), 1, 0, 'R', 1);
        //DEDUCCIONES CREDITOS TIPO VACACIÓN
        if ($duoRegistrosCreditos > 0){
            $intX = 10;
            $this->SetXY($intX, 103);
            $this->SetFillColor(217, 217, 217);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(185, 5, utf8_decode("CRÉDITOS TIPO VACACIÓN:"), 1, 0, 'C', 1);

            $intY = 103 + 5;
            $this->SetXY($intX, $intY);

            $this->SetFont('Arial', 'B', 8);
            $this->Cell(30, 4, utf8_decode("CÓDIGO"), 1, 0, 'C', 1);
            $this->Cell(110, 4, utf8_decode("TIPO"), 1, 0, 'L', 1);
            $this->Cell(45, 4, utf8_decode("VALOR"), 1, 0, 'R', 1);
            $incremento = 4;
            foreach ($arCreditos as $arCreditos) {
                $intY = $intY + $incremento;
                $this->SetXY($intX, $intY);
                $this->SetFillColor(255, 255, 255);
                $this->SetFont('Arial', '', 8);
                $this->Cell(30, 4, $arCreditos->getCodigoCreditoFk(), 1, 0, 'L', 1);
                $this->Cell(110, 4, utf8_decode($arCreditos->getCreditoRel()->getCreditoTipoRel()->getNombre()), 1, 0, 'L', 1);
                $this->Cell(45, 4, number_format($arCreditos->getVrDeduccion(), 2, '.', ','), 1, 0, 'R', 1);
                $incremento = $incremento + 4;
            }
        }
        
        
        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        
    }

    public function Footer() {
        
        $this->SetFont('Arial', 'B', 9);
        $this->Text(10, 130, "FIRMA: _____________________________________________");
        $this->Text(105, 130, "EMPRESA: __________________________________________");
        $this->Text(10, 137, "C.C.:     ______________________ de ____________________");
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 140, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
