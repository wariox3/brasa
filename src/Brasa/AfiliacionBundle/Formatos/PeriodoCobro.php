<?php
namespace Brasa\AfiliacionBundle\Formatos;

class PeriodoCobro extends \FPDF_FPDF {
    public static $em;
    public static $codigoPeriodo;
    
    public function Generar($miThis, $codigoPeriodo) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoPeriodo = $codigoPeriodo;
        $pdf = new PeriodoCobro();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("PeriodoCobro$codigoPeriodo.pdf", 'D');                
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        
        $this->Image('imagenes/logos/logo.jpg', 12, 13, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->SetXY(50, 10);
        $this->Cell(150, 7, utf8_decode("RELACION COBRO"), 0, 0, 'C', 1);
        $this->SetXY(50, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);        
        //FORMATO ISO
        $this->SetXY(168, 18);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(35, 8, "FECHA: 01/09/2015", 1, 0, 'L', 1);
        $this->SetXY(168, 26);
        $this->Cell(35, 8, utf8_decode("VERSIÓN: 01"), 1, 0, 'L', 1);
        //
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodo = self::$em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find(self::$codigoPeriodo);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200); 
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272); 
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, $arPeriodo->getCodigoPeriodoPk(), 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "CLIENTE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(100, 6, $arPeriodo->getClienteRel()->getNombreCorto(), 1, 0, 'L', 1);
        //linea 2
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("SOPORTE:") , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272); 
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, '', 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(100, 6, utf8_decode(""), 1, 0, 'L', 1);         
        //linea 3
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, '' , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "TOTAL:" , 1, 0, 'R', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(100, 5, number_format($arPeriodo->getTotal(), 0, '.', ',') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);      
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array(utf8_decode('IDENTIF.'), 'NOMBRE', 'PENSION', 'SALUD', 'RIESGOS', 'CAJA', 'SENA', 'ICBF', 'ADMIN', 'SUBTOTAL', 'IVA', 'TOTAL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6);

        //creamos la cabecera de la tabla.
        $w = array(20, 44, 13, 13, 13, 13, 8, 8, 13, 13, 13, 19);
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
        $arPeriodoDetalles = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle();
        $arPeriodoDetalles = self::$em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->findBy(array('codigoPeriodoFk' => self::$codigoPeriodo));
        
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $var = 0;
        foreach ($arPeriodoDetalles as $arPeriodoDetalle) {                        
            $pdf->Cell(20, 4, $arPeriodoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(44, 4, utf8_decode($arPeriodoDetalle->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');                            
            $pdf->Cell(13, 4, number_format($arPeriodoDetalle->getPension(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arPeriodoDetalle->getSalud(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arPeriodoDetalle->getRiesgos(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arPeriodoDetalle->getCaja(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(8, 4, number_format($arPeriodoDetalle->getSena(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(8, 4, number_format($arPeriodoDetalle->getIcbf(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arPeriodoDetalle->getAdministracion(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arPeriodoDetalle->getSubtotal(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arPeriodoDetalle->getIva(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(19, 4, number_format($arPeriodoDetalle->getTotal(), 0, '.', ','), 1, 0, 'R');
            $var += $arPeriodoDetalle->getTotal();
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            
        }
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(171, 5, "TOTAL: ", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(19, 5, number_format($var,0, '.', ','), 1, 0, 'R');
        
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
