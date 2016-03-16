<?php
namespace Brasa\CarteraBundle\Formatos;
class FormatoRecibo extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoRecibo;
    
    public function Generar($miThis, $codigoRecibo) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoRecibo = $codigoRecibo;
        $pdf = new FormatoRecibo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Recibo$codigoRecibo.pdf", 'D');        
        
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
        $this->Cell(150, 7, utf8_decode("RECIBO"), 0, 0, 'C', 1);
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
        
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibo = self::$em->getRepository('BrasaCarteraBundle:CarRecibo')->find(self::$codigoRecibo);        
        
        $arReciboDetalles = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
        $arReciboDetalles = self::$em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => self::$codigoRecibo));
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        
        $intY = 40;
        //linea 1
        $this->SetFillColor(272, 272, 272); 
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(33, 4, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(33, 4, $arRecibo->getCodigoReciboPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(33, 4, utf8_decode("NÚMERO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(31, 4, $arRecibo->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(32, 4, utf8_decode("ANULADO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        if ($arRecibo->getEstadoAnulado() == 1){
            $estadoAnulado = "SI";
        } else {
            $estadoAnulado = "NO";
        }
        $this->Cell(32, 4, $estadoAnulado, 1, 0, 'L', 1);
        //linea 2
        $this->SetXY(10, $intY+4);
        $this->SetFont('Arial','B',8);
        $this->Cell(33, 4, utf8_decode("CLIENTE:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(33, 4, $arRecibo->getClienteRel()->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(33, 4, utf8_decode("NIT:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(31, 4, $arRecibo->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(32, 4, utf8_decode("IMPRESO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        if ($arRecibo->getEstadoAnulado() == 1){
            $estadoAnulado = "SI";
        } else {
            $estadoAnulado = "NO";
        }
        $this->Cell(32, 4, $estadoAnulado, 1, 0, 'L', 1);
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('COD', 'SERVICIO', 'MODALIDAD', 'PER', 'DESDE', 'HASTA', 'CANT', 'LU', 'MA', 'MI', 'JU', 'VI', 'SA', 'DO', 'FE', 'H', 'H.D', 'H.N', 'VALOR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 30, 20, 10, 15, 15, 10, 5, 5, 5, 5, 5, 5, 5, 5, 8, 8, 8, 15);
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
        $arReciboDetalles = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
        $arReciboDetalles = self::$em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => self::$codigoRecibo));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arReciboDetalles as $arReciboDetalle) {            
            $pdf->Cell(10, 4, $arReciboDetalle->getCodigoReciboDetallePk(), 1, 0, 'L');
            $pdf->Cell(30, 4, $arReciboDetalle->getCodigoReciboFk(), 1, 0, 'L');
            $pdf->Cell(20, 4, $arReciboDetalle->getCodigoReciboFk(), 1, 0, 'L');                
            $pdf->Cell(10, 4, $arReciboDetalle->getCodigoCuentaCobrarFk(), 1, 0, 'L');                
            $pdf->Cell(15, 4, $arReciboDetalle->getCodigoCuentaCobrarFk(), 1, 0, 'L');                
            $pdf->Cell(15, 4, $arReciboDetalle->getCodigoReciboFk(), 1, 0, 'L');                                  
            $pdf->Cell(10, 4, number_format($arReciboDetalle->getValor(), 0, '.', ','), 1, 0, 'R');                
            
                                             
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
