<?php
namespace Brasa\RecursoHumanoBundle\Reportes;
class ReporteProgramacionesPago extends \FPDF_FPDF {
    public static $em;
    
    public static $strDql;    
    
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new ReporteProgramacionesPago('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("ReporteProgramacionesPago.pdf", 'D');        
    } 
    
    public function Header() {

        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(2, 10);
        $this->Cell(289, 8, 'REPORTE PROGRAMACIONES PAGO DETALLE', 1, 0, 'C', 1);                                                
        $this->Ln(12);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->SetX(2);
        $header = array(utf8_decode('CÓDIGO'), utf8_decode('PROGRAM'),'CENTRO COSTOS', utf8_decode('IDENTIFICACIÓN'),'EMPLEADO','DESDE', 'HASTA', 'VR. SALARIO','HORAS',utf8_decode('DÍAS'),'VR. HORAS',utf8_decode('VR. DÍAS'),'VR. DEVENGADO','VR DEDUCCIONES',utf8_decode('VR CRÉDITOS'),'VR. NETO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6);

        //creamos la cabecera de la tabla.
        $w = array(11, 14, 42, 19, 45, 14,14,17,10,10,13,13,19,20,18,15);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $query = self::$em->createQuery(self::$strDql);
        $arReporteProgramacionesPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arReporteProgramacionesPagoDetalle = $query->getResult();     
        $douTotalNeto = 0;
        $douTotalCreditos = 0;
        $douTotalDevengado = 0;
        $douTotalDeducciones = 0;
        $pdf->SetX(5);
        $pdf->SetFont('Arial', '', 6.5);
        foreach ($arReporteProgramacionesPagoDetalle as $arReporteProgramacionesPagoDetalle) {
            $pdf->SetX(2);
            $pdf->Cell(11, 4, $arReporteProgramacionesPagoDetalle->getCodigoProgramacionPagoDetallePk(), 1, 0, 'L');
            $pdf->Cell(14, 4, $arReporteProgramacionesPagoDetalle->getCodigoProgramacionPagoFk(), 1, 0, 'L');
            $pdf->Cell(42, 4, utf8_decode($arReporteProgramacionesPagoDetalle->getProgramacionPagoRel()->getCentroCostoRel()->getNombre()), 1, 0, 'L');            
            $pdf->Cell(19, 4, $arReporteProgramacionesPagoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(45, 4, utf8_decode($arReporteProgramacionesPagoDetalle->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(14, 4, $arReporteProgramacionesPagoDetalle->getFechaDesde()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(14, 4, $arReporteProgramacionesPagoDetalle->getFechaHasta()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(17, 4, number_format($arReporteProgramacionesPagoDetalle->getVrSalario(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(10, 4, number_format($arReporteProgramacionesPagoDetalle->getHorasPeriodoReales(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(10, 4, number_format($arReporteProgramacionesPagoDetalle->getDiasReales(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arReporteProgramacionesPagoDetalle->getVrHora(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arReporteProgramacionesPagoDetalle->getVrDia(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(19, 4, number_format($arReporteProgramacionesPagoDetalle->getVrDevengado(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arReporteProgramacionesPagoDetalle->getVrDeducciones(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(18, 4, number_format($arReporteProgramacionesPagoDetalle->getVrCreditos(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($arReporteProgramacionesPagoDetalle->getVrNetoPagar(), 2, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            $douTotalDevengado += $arReporteProgramacionesPagoDetalle->getVrDevengado();
            $douTotalDeducciones += $arReporteProgramacionesPagoDetalle->getVrDeducciones();
            $douTotalCreditos += $arReporteProgramacionesPagoDetalle->getVrCreditos();
            $douTotalNeto += $arReporteProgramacionesPagoDetalle->getVrNetoPagar();
        }
        $pdf->SetX(2);
        $pdf->SetFont('Arial', 'B', 6.5);
        $pdf->Cell(11, 4, "", 1, 0, 'L');
        $pdf->Cell(14, 4, "", 1, 0, 'L');            
        $pdf->Cell(40, 4, "", 1, 0, 'L');            
        $pdf->Cell(19, 4, "", 1, 0, 'R');
        $pdf->Cell(45, 4, "", 1, 0, 'R');
        $pdf->Cell(14, 4, "", 1, 0, 'R');
        $pdf->Cell(14, 4, "", 1, 0, 'R');
        $pdf->Cell(17, 4, "", 1, 0, 'R');
        $pdf->Cell(10, 4, "", 1, 0, 'R');
        $pdf->Cell(10, 4, "", 1, 0, 'R');
        $pdf->Cell(13, 4, "", 1, 0, 'R');
        $pdf->Cell(13, 4, "", 1, 0, 'R');
        $pdf->Cell(19, 4, number_format($douTotalDevengado, 2, '.', ','), 1, 0, 'R');
        $pdf->Cell(19, 4, number_format($douTotalDeducciones, 2, '.', ','), 1, 0, 'R');
        $pdf->Cell(18, 4, number_format($douTotalCreditos, 2, '.', ','), 1, 0, 'R');
        $pdf->Cell(15, 4, number_format($douTotalNeto, 2, '.', ','), 1, 0, 'R');
        $pdf->Ln();        
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(250, 200, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
