<?php

namespace Brasa\TurnoBundle\Formatos;

class Factura2 extends \FPDF_FPDF {

    public static $em;
    public static $codigoFactura;
    public static $strLetras;

    public function Generar($miThis, $codigoFactura) {

        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $arrayNumero = explode(".", 0, 2);
        $intCentavos = 0;
        if (count($arrayNumero) > 1)
            $intCentavos = substr($arrayNumero[1], $arFactura->getVrTotal(), 2);
        $strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($arFactura->getVrTotal());
        self::$strLetras = $strLetras;
        ob_clean();
        $pdf = new Factura2();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Factura$codigoFactura.pdf", 'D');
    }

    public function Header() {
        $this->GenerarEncabezadoFactura(self::$em);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionTurno = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracionTurno = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);
        
        
        $this->SetFont('Arial', '', 8);
        $this->Text(20, 70, "Fecha Factura");
        $this->Text(50, 70, "Junio 07 del 2016");
        $this->Text(140, 70, "Fecha Vence");
        $this->Text(170, 70, "Junio 07 del 2016");        
        $this->Text(20, 75, utf8_decode("Señores"));
        $this->Text(50, 75, "CONSTRUCTORA CONCONCRETO S.A");
        $this->Text(140, 75, "Nit");
        $this->Text(170, 75, "811010416");        
        $this->Text(20, 80, "Direccion");
        $this->Text(50, 80, "Cra 5 numero 35-25");
        $this->Text(140, 80, "Telefono");
        $this->Text(170, 80, "444444");                
        
        $this->SetXY(110, 75);
        $this->SetMargins(10, 1, 10);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $this->SetX(20);
        $header = array('CANT', 'DETALLE', 'Vr. UNITARIO', 'Vr. TOTAL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 100, 30, 30);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0)
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
        $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        $pdf->SetX(20);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $pdf->SetX(20);
            $strDetalle = "SERVICIO " . $arFacturaDetalle->getConceptoServicioRel()->getNombre() . " DESDE EL DIA " . $arFacturaDetalle->getPedidoDetalleRel()->getDiaDesde()
                    . " HASTA EL DIA " . $arFacturaDetalle->getPedidoDetalleRel()->getDiaHasta() . " DE " .
            $this->devuelveMes($arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getFechaProgramacion()->format('n')) . " " . $arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getFechaProgramacion()->format('Y');
            $pdf->Cell(10, 4, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 0, 0, 'R');                        
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(100, 4, $arFacturaDetalle->getPedidoDetalleRel()->getPuestoRel()->getNombre(), 0, 0, 'L');                        
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(30, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(30, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetX(20);
            $pdf->Cell(10, 4, '', 0, 0, 'R');                        
            $pdf->Cell(100, 4, '', 0, 0, 'L');                        
            $pdf->Cell(30, 4, '', 0, 0, 'R');
            $pdf->Cell(30, 4, '', 0, 0, 'R');            
            $pdf->Ln();
            $pdf->SetX(20);
            $pdf->Cell(10, 4, '', 0, 0, 'R');                        
            $pdf->Cell(100, 4, $arFacturaDetalle->getDetalle(), 0, 0, 'L');                        
            $pdf->Cell(30, 4, '', 0, 0, 'R');
            $pdf->Cell(30, 4, '', 0, 0, 'R');             
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function devuelveMes($intMes) {
        $strMes = "";
        switch ($intMes) {
            case 1:
                $strMes = "ENERO";
                break;
            case 2:
                $strMes = "FEBRERO";
                break;
            case 3:
                $strMes = "MARZO";
                break;
            case 4:
                $strMes = "ABRIL";
                break;
            case 5:
                $strMes = "MAYO";
                break;
            case 6:
                $strMes = "JUNIO";
                break;
            case 7:
                $strMes = "JULIO";
                break;
            case 8:
                $strMes = "AGOSTO";
                break;
            case 9:
                $strMes = "SEPTIEMBRE";
                break;
            case 10:
                $strMes = "OCTUBRE";
                break;
            case 11:
                $strMes = "NOVIEMBRE";
                break;
            case 12:
                $strMes = "DICIEMBRE";
                break;
        }
        return $strMes;
    }

    public function Footer() {
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);        
        $this->SetXY(20,180);
        $this->Cell(40, 21, '', 1, 0, 'R');        
        $this->Cell(70, 21, '', 1, 0, 'R'); 
        $this->SetXY(20,201);
        $this->Cell(110, 7, '', 1, 0, 'R');        
        $this->SetXY(130,180);
        $this->Cell(30, 7, 'SUB TOTAL', 1, 0, 'L');        
        $this->Cell(30, 7, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(130,187);
        $this->Cell(30, 7, 'Base Gravable', 1, 0, 'L');        
        $this->Cell(30, 7, number_format($arFactura->getVrBaseAIU(), 0, '.', ',') , 1, 0, 'R');
        $this->SetXY(130,194);
        $this->Cell(30, 7, 'IVA 16 %', 1, 0, 'L');        
        $this->Cell(30, 7, number_format($arFactura->getVrIva(), 0, '.', ','), 1, 0, 'R'); 
        $this->SetXY(130,201);
        $this->Cell(30, 7, 'TOTAL', 1, 0, 'L');        
        $this->Cell(30, 7, number_format($arFactura->getVrTotal(), 0, '.', ','), 1, 0, 'R');                    



        $this->Ln(4);
        $this->SetFont('Arial', '', 8);
        //$this->Text(20, $this->GetY($this->SetY(244)), $arConfiguracion->getInformacionPagoFactura());
        $this->SetXY(30,212);
        $this->MultiCell(110, 5, $arConfiguracion->getInformacionPagoFactura(), 0, 'L');                
        $this->Ln();
        $this->SetFont('Arial', 'B', 8);        
        $this->Text(30, 225, "Observacion: Si efectura retencion en la fuente, favor aplicar tarifa del 2% Sobre Base Gravable");
        //$this->MultiCell(100, 5, "Observacion: Si efectura retencion en la fuente, favor aplicar tarifa del 2% Sobre Base Gravable", 0, 'L');                
        $this->SetFont('Arial', '', 7);
        $this->Text(50, 235, "Favor remitir copia de la consignacion a los correos a.mona@seracis.com y d.mejia@seracis.com");

        //Número de página
        $this->Text(188, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }

    public function GenerarEncabezadoFactura($em) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);

        $this->SetFont('Arial', '', 5);
        $this->Text(188, 13, '');
        $this->Image('imagenes/logos/logo.jpg', 0, 0, 0.01, 0.01);
        $this->ln(11);
        $this->SetFont('Arial', 'B', 12);
        $this->ln(5);
        $this->SetFont('Arial', 'B', 10);
        //$this->Text(21, 35, "NIT " . $arConfiguracion->getNitEmpresa() . "-" . $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetXY(258, 18);
    }

}

?>
