<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoContrato extends \FPDF_FPDF {
    public static $em;
    public static $codigoContrato;
    public function Generar($miThis, $codigoContrato) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoContrato = $codigoContrato;
        $pdf = new FormatoContrato();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Contrato$codigoContrato.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find(self::$codigoContrato);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(150, 20);
        $this->Cell(50, 6, "Contrato " . $arContrato->getCodigoContratoPk(), 1, 0, 'L', 1);
        
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
        $w = array(72, 40, 12, 14, 12, 20, 20);
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
        /*$arContratoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoDetalle();
        $arContratoDetalle = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContratoDetalle')->findBy(array('codigoContratoFk' => self::$codigoContrato));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arContratoDetalle as $arContratoDetalle) {            
            $pdf->Cell(72, 4, $arContratoDetalle->getContratoConceptoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(40, 4, $arContratoDetalle->getDetalle(), 1, 0, 'L');
            $pdf->Cell(12, 4, number_format($arContratoDetalle->getNumeroHoras(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(14, 4, number_format($arContratoDetalle->getVrHora(), 2, '.', ','), 1, 0, 'R');
            $pdf->Cell(12, 4, number_format($arContratoDetalle->getPorcentajeAplicado(), 2, '.', ','), 1, 0, 'R');
            if($arContratoDetalle->getOperacion() == -1) {
                $pdf->Cell(20, 4, number_format($arContratoDetalle->getVrContrato(), 2, '.', ','), 1, 0, 'R');    
            } else {
                $pdf->Cell(20, 4, number_format(0, 2, '.', ','), 1, 0, 'R');    
            }            
            if($arContratoDetalle->getOperacion() == 1) {
                $pdf->Cell(20, 4, number_format($arContratoDetalle->getVrContrato(), 2, '.', ','), 1, 0, 'R');    
            } else {
                $pdf->Cell(20, 4, number_format(0, 2, '.', ','), 1, 0, 'R');    
            }            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
        }  */      
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
