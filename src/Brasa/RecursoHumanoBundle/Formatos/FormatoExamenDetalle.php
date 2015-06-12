<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoExamenDetalle extends \FPDF_FPDF {
    public static $em;
    public static $codigoExamen;
    public function Generar($miThis, $codigoExamen) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoExamen = $codigoExamen;
        $pdf = new FormatoExamenDetalle();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("ExamenDetalle$codigoExamen.pdf", 'D');        
        
    } 
    public function Header() {
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find(self::$codigoExamen);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(40, 6, "Examen " . $arExamen->getCodigoExamenPk(), 1, 0, 'L', 1);
        $this->SetXY(52, 20);
        $this->Cell(148, 6, $arExamen->getNombreCorto(), 1, 0, 'L', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(30);
        $header = array('COD', 'TIPO', 'TIPO EXAMEN', 'PRECIO', 'APROBADO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 10, 130, 20, 20);
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
        $arExamenDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
        $arExamenDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => self::$codigoExamen));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arExamenDetalles as $arExamenDetalle) {            
            $pdf->Cell(10, 4, $arExamenDetalle->getCodigoExamenDetallePk(), 1, 0, 'L');
            $pdf->Cell(10, 4, $arExamenDetalle->getExamenTipoRel()->getCodigoExamenTipoPk(), 1, 0, 'L');
            $pdf->Cell(130, 4, $arExamenDetalle->getExamenTipoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(20, 4, number_format($arExamenDetalle->getPrecio(), 2, '.', ','), 1, 0, 'L');
            $pdf->Cell(20, 4, $arExamenDetalle->getEstadoAprobado(), 1, 0, 'L');            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
        }        
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
