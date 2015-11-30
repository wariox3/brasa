<?php
namespace Brasa\ContabilidadBundle\Formatos;
class FormatoAsientoDetalle extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoAsiento;
    
    public function Generar($miThis, $codigoAsiento) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoAsiento = $codigoAsiento;
        $pdf = new FormatoAsientoDetalle();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("AsientoDetalle.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
        $arAsiento = self::$em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find(self::$codigoAsiento);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 14);
        $this->Image('imagenes/logos/logo.jpg', 12, 15, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("DOTACIÓN EMPLEADO"), 0, 0, 'C', 1);
        $this->SetXY(53, 22);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 34);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0); 
        //FILA 1
        $this->SetXY(10, 40);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(33, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $this->Cell(15, 6, $arAsiento->getCodigoAsientoPk() , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("FECHA:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $this->Cell(71, 6, $arAsiento->getFecha()->Format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, utf8_decode("TOTAL DÉBITO:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $this->Cell(25, 6, number_format($arAsiento->getTotalDebito(), 0, '.', ','), 1, 0, 'R');
        //FILA 2
        $this->SetXY(10, 46);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(33, 6, utf8_decode("CÓDIGO COMPROBANTE:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $this->Cell(15, 6, $arAsiento->getCodigoComprobanteFk() , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("COMPROBANTE:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',6);
        $this->Cell(71, 6, utf8_decode($arAsiento->getComprobanteRel()->getNombre()) , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, utf8_decode("TOTAL CRÉDITOS:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $this->Cell(25, 6, number_format($arAsiento->getTotalCredito(), 0, '.', ','), 1, 0, 'R');
        //FILA 3
        $this->SetXY(10, 51);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(33, 6, utf8_decode("NÚMERO ASIENTO:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $this->Cell(15, 6, utf8_decode($arAsiento->getNumeroAsiento()) , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("SOPORTE:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $this->Cell(71, 6, utf8_decode($arAsiento->getSoporte()) , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, utf8_decode("AUTORIZADO") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        if ($arAsiento->getEstadoAutorizado() == 1 ){
            $this->Cell(25, 6, "SI", 1, 0, 'L', 1);
        }else {
            $this->Cell(25, 6, "NO", 1, 0, 'L', 1);
        }
        
        //FILA 4
        $this->SetXY(10, 57);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(33, 6, "COMENTARIOS:" , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',6);
        $this->Cell(160, 6, utf8_decode($arAsiento->getComentarios()) , 1, 0, 'L', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(10);
        $header = array(utf8_decode('CÓDIGO'), 'CUENTA', utf8_decode('IDENTIFICACIÓN'), 'FECHA', 'TIPO ASIENTO','DOC REFERENTE','SOPORTE','PLAZO','VR BASE',utf8_decode('DÉBITO'),utf8_decode('CRÉDITO'));
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(14, 19, 22, 18, 20, 22, 15, 12, 17, 17, 17);
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
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        $arAsientoDetalle = new \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle();
        $arAsientoDetalle = self::$em->getRepository('BrasaContabilidadBundle:CtbAsientoDetalle')->findBy(array('codigoAsientoFk' => self::$codigoAsiento));
        foreach ($arAsientoDetalle as $arAsientoDetalle) {            
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(14, 4, $arAsientoDetalle->getCodigoAsientoDetallePk(), 1, 0, 'L');
            $pdf->Cell(19, 4, $arAsientoDetalle->getCodigoCuentaFk(), 1, 0, 'L');
            $pdf->Cell(22, 4, $arAsientoDetalle->getTerceroRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(18, 4, $arAsientoDetalle->getFecha()->format('Y-m-d'), 1, 0, 'L');
            $pdf->Cell(20, 4, utf8_decode($arAsientoDetalle->getAsientoTipoRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(22, 4, $arAsientoDetalle->getDocumentoReferente(), 1, 0, 'L');
            $pdf->Cell(15, 4, $arAsientoDetalle->getSoporte(), 1, 0, 'L');
            $pdf->Cell(12, 4, $arAsientoDetalle->getPlazo(), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(17, 4, number_format($arAsientoDetalle->getValorBase(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(17, 4, number_format($arAsientoDetalle->getDebito(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(17, 4, number_format($arAsientoDetalle->getCredito(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
             
    }

    public function Footer() {
        
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');;
    }    
}

?>
