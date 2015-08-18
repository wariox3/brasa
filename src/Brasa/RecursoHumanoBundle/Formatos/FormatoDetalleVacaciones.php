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

        $pdf->Output("Vacaciones_$codigoVacacion.pdf", 'D');        
        
    } 
    
    public function Header() {                        
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacaciones = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find(self::$codigoVacacion);        
        $this->SetFillColor(217, 217, 217);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 16);
        $this->Cell(185, 7, utf8_decode('COMPROBANTE DE VACACIONES'), 1, 0, 'C',True);        
        //FILA 1
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY(10, 25);        
        $this->Cell(25, 6, utf8_decode("CÓDIGO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, $arVacaciones->getCodigoVacacionPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(217, 217, 217);
        $this->Cell(30, 6, "FECHA:", 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, utf8_decode($arVacaciones->getFecha()->format('Y/m/d')), 1, 0, 'L', 1);
        //FILA 2
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY(10, 31);        
        $this->Cell(25, 6, utf8_decode("EMPLEADO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, utf8_decode($arVacaciones->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(217, 217, 217);
        $this->Cell(30, 6, utf8_decode("IDENTIFICACIÓN:"), 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, $arVacaciones->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        //FILA 3
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY(10, 37);        
        $this->Cell(25, 6, "DESDE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, $arVacaciones->getFechaDesde()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(217, 217, 217);
        $this->Cell(30, 6, "CENTRO COSTOS:", 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, utf8_decode($arVacaciones->getCentroCostoRel()->getNombre()), 1, 0, 'L', 1);
        //FILA 4
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY(10, 43);        
        $this->Cell(25, 6, "HASTA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, $arVacaciones->getFechaHasta()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(217, 217, 217);
        $this->Cell(30, 6, utf8_decode("DÍAS VACACIONES:"), 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(65, 6, utf8_decode($arVacaciones->getDiasVacaciones()), 1, 0, 'L', 1);
        //FILA 5
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(255, 255, 255);
        $this->SetXY(10, 49);        
        $this->Cell(90, 6, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(217, 217, 217);
        $this->Cell(30, 6, "PAGADA:", 1, 0, 'L', 1);         
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        if ($arVacaciones->getEstadoPagado() == 1){
            $this->Cell(65, 6, "SI", 1, 0, 'L', 1);
        }
        else {
            $this->Cell(65, 6, "NO", 1, 0, 'L', 1);
        }
        
        //FILA 6
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY(10, 55);        
        $this->Cell(25, 6, utf8_decode("COMENTARIOS:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(160, 6, $arVacaciones->getComentarios(), 1, 0, 'L', 1);
        //BLOQUE VACACIONES
        $intX = 120;
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(217, 217, 217);
        $this->SetXY($intX, 70);        
        $this->Cell(43, 5, utf8_decode("SALARIO:"), 1, 0, 'L', 1);
        $this->SetXY($intX, 76);
        $this->Cell(43, 5, "IBC:", 1, 0, 'L', 1);
        $this->SetXY($intX, 81);
        $this->Cell(43, 5, "VR. SALUD:", 1, 0, 'L', 1); 
        $this->SetXY($intX, 87);
        $this->Cell(43, 5, utf8_decode("VR. PENSIÓN:"), 1, 0, 'L', 1);
        $this->SetXY($intX, 93);
        $this->Cell(43, 5, "VR. VACACIONES:", 1, 0, 'L', 1);
        $intX = 163;
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->SetXY($intX, 70);        
        $this->Cell(32, 5, number_format($arVacaciones->getEmpleadoRel()->getVrSalario(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, 76);
        $this->Cell(32, 5, number_format($arVacaciones->getVrIbc(), 2, '.', ','), 1, 0, 'R', 1);
        $this->SetXY($intX, 81);
        $this->Cell(32, 5, "(".number_format($arVacaciones->getVrSalud(), 2, '.', ',').")", 1, 0, 'R', 1);
        $this->SetXY($intX, 87);
        $this->Cell(32, 5, "(".number_format($arVacaciones->getVrPension(), 2, '.', ',').")", 1, 0, 'R', 1);
        $this->SetXY($intX, 93);
        $this->Cell(32, 5, number_format($arVacaciones->getVrVacacion(), 2, '.', ','), 1, 0, 'R', 1);
        
        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        
    }

    public function Footer() {
        
        $this->SetFont('Arial', 'B', 9);
        $this->Text(10, 110, "FIRMA: _____________________________________________");
        $this->Text(105, 110, "EMPRESA: __________________________________________");
        $this->Text(10, 117, "C.C.:     ______________________ de ____________________");
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 130, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
