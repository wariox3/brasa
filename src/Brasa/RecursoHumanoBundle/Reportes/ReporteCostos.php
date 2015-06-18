<?php
namespace Brasa\RecursoHumanoBundle\Reportes;
class ReporteCostos extends \FPDF_FPDF {
    public static $em;
    public static $strDql;    
    
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new ReporteCostos();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("ReporteCostos.pdf", 'D');        
        
    } 
    public function Header() {

        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 10);
        $this->Cell(185, 7, 'REPORTE DE COSTOS', 0, 0, 'C', 1);                                                
        $this->Ln(12);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        
        $header = array('IDENTIF', 'EMPLEADO', 'PERIODO','IBC', 'AUX. TRANS', 'CESANTIAS');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 5);

        //creamos la cabecera de la tabla.
        $w = array(11, 40, 20, 13, 13, 13);
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
        
        $query = self::$em->createQuery(self::$strDql);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();     
        $douTotalIBC = 0;
        $douTotalAuxilioTransporte = 0;
        $douTotalCesantias = 0;
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 5);
        foreach ($arPagos as $arPago) {            
            $pdf->Cell(11, 4, $arPago->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(40, 4, $arPago->getEmpleadoRel()->getNombreCorto(), 1, 0, 'L');            
            $pdf->Cell(20, 4, $arPago->getFechaDesde()->format('y-m-d') . "_" . $arPago->getFechaHasta()->format('y-m-d'), 1, 0, 'L');            
            $pdf->Cell(13, 4, number_format($arPago->getVrIngresoBaseCotizacion(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arPago->getVrAuxilioTransporte(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arPago->getVrCesantias(), 0, '.', ','), 1, 0, 'R');                
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
            $douTotalIBC += $arPago->getVrIngresoBaseCotizacion();
            $douTotalAuxilioTransporte += $arPago->getVrAuxilioTransporte();
            $douTotalCesantias += $arPago->getVrCesantias();
        }    
        $pdf->Cell(11, 4, "", 1, 0, 'L');
        $pdf->Cell(40, 4, "", 1, 0, 'L');            
        $pdf->Cell(20, 4, "", 1, 0, 'L');            
        $pdf->Cell(13, 4, number_format($douTotalIBC, 2, '.', ','), 1, 0, 'R');
        $pdf->Cell(13, 4, number_format($douTotalAuxilioTransporte, 2, '.', ','), 1, 0, 'R');
        $pdf->Cell(13, 4, number_format($douTotalCesantias, 2, '.', ','), 1, 0, 'R');                
        $pdf->Ln();        
    }

    public function Footer() {
        $this->SetFont('Arial','B', 9);    
        $this->Line(30, 271, 100, 271);        
        $this->Line(120, 271, 180, 271);        
        $this->Text(50, 275, "FIRMA"); 
        $this->Text(140, 275, "FIRMA");
        $this->SetFont('Arial','', 10);  
        $this->Text(170, 290, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }    
}

?>
