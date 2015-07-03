<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoPagoExamenDetalle extends \FPDF_FPDF {
    public static $em;
    public static $codigoPagoExamen;
    public function Generar($miThis, $codigoPagoExamen) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoPagoExamen = $codigoPagoExamen;
        $pdf = new FormatoPagoExamenDetalle();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("PagoExamenDetalle$codigoPagoExamen.pdf", 'D');        
        
    } 
    public function Header() {
        $arPagoExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen();
        $arPagoExamen = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find(self::$codigoPagoExamen);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(190, 10, "DATOS PAGO ENTIDAD EXAMEN " , 1, 0, 'L', 1);
        $this->SetXY(10, 30);
        $this->SetFillColor(272, 272, 272); 
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "CODIGO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, $arPagoExamen->getCodigoPagoExamenPk(), 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "ENTIDAD:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(100, 6, utf8_decode($arPagoExamen->getEntidadExamenRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetXY(10, 35);
        $this->SetFont('Arial','B',8);
        $this->Cell(60, 5, "TOTAL:" , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(130, 5, number_format($arPagoExamen->getVrTotal(), 2, '.', ',') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('CODIGO', 'IDENTIFICACION', 'NOMBRE', 'PRECIO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(20, 25, 130, 15);
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
        $arPagoExamenDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle();
        $arPagoExamenDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->findBy(array('codigoPagoExamenFk' => self::$codigoPagoExamen));
        
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $var = 0;
        foreach ($arPagoExamenDetalles as $arPagoExamenDetalle) {            
            $pdf->Cell(20, 4, $arPagoExamenDetalle->getCodigoPagoExamenDetallePk(), 1, 0, 'L');
            $pdf->Cell(25, 4, $arPagoExamenDetalle->getExamenRel()->getIdentificacion(), 1, 0, 'L');
            $pdf->Cell(130, 4, utf8_decode($arPagoExamenDetalle->getExamenRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arPagoExamenDetalle->getVrPrecio(), 2, '.', ','), 1, 0, 'R');
            $var += $arPagoExamenDetalle->getVrPrecio();
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            
        }
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(175, 5, "TOTAL: ", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(15, 5, number_format($var,2, '.', ','), 1, 0, 'R');
        
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
