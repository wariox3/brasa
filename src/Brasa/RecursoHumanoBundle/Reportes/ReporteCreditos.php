<?php
namespace Brasa\RecursoHumanoBundle\Reportes;
class ReporteCreditos extends \FPDF_FPDF {
    public static $em;
    public static $strDql;    
    
    public function Generar($miThis, $dql) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$strDql = $dql;
        $pdf = new ReporteCreditos();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("ReporteCreditos.pdf", 'D');        
        
    } 
    public function Header() {

        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 10);
        $this->Cell(185, 7, 'REPORTE DE CREDITOS', 0, 0, 'C', 1);                                                
        $this->Ln(12);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        
        $header = array('IDENTIFICACION', 'EMPLEADO', 'FECHA','VR. CREDITO', 'VR. CUOTA', 'VR. SALDO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(25, 80, 20, 20, 20, 20);
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
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $query->getResult();     
        $douTotalSaldo = 0;
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arCreditos as $arCredito) {            
            $pdf->Cell(25, 4, $arCredito->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(80, 4, $arCredito->getEmpleadoRel()->getNombreCorto(), 1, 0, 'L');            
            $pdf->Cell(20, 4, $arCredito->getFecha()->format('y-m-d'), 1, 0, 'L');            
            $pdf->Cell(20, 4, number_format($arCredito->getVrPagar(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arCredito->getVrCuota(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arCredito->getSaldo(), 0, '.', ','), 1, 0, 'R');                
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
            $douTotalSaldo += $arCredito->getSaldo();
        }    
        $pdf->Cell(25, 4, "", 1, 0, 'L');
        $pdf->Cell(80, 4, "", 1, 0, 'L');            
        $pdf->Cell(20, 4, "", 1, 0, 'L');            
        $pdf->Cell(20, 4, number_format($douTotalSaldo, 2, '.', ','), 1, 0, 'R');
        $pdf->Cell(20, 4, "", 1, 0, 'L');                
        $pdf->Cell(20, 4, "", 1, 0, 'L');                
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
