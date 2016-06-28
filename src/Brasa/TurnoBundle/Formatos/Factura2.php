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
        $this->SetXY(50, 60);
        
        $this->SetFont('Arial', '', 8);
        $this->Text(20, 50, "Fecha Factura");
        $this->Text(50, 50, "Junio 07 del 2016");
        $this->Text(140, 50, "Fecha Vence");
        $this->Text(170, 50, "Junio 07 del 2016");        
        $this->Text(20, 55, utf8_decode("Señores"));
        $this->Text(50, 55, "CONSTRUCTORA CONCONCRETO S.A");
        $this->Text(140, 55, "Nit");
        $this->Text(170, 55, "811010416");        
        $this->Text(20, 60, "Direccion");
        $this->Text(50, 60, "Cra 5 numero 35-25");
        $this->Text(140, 60, "Telefono");
        $this->Text(170, 60, "444444");                
        
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
        $w = array(10, 120, 30, 30);
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
            $pdf->Cell(10, 4, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 1, 0, 'R');                        
            $pdf->Cell(120, 4, $strDetalle, 1, 0, 'L');                        
            $pdf->Cell(30, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(30, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 1, 0, 'R');
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
        $this->Cell(50, 21, '', 1, 0, 'R');        
        $this->Cell(80, 21, '', 1, 0, 'R'); 
        $this->SetXY(20,201);
        $this->Cell(130, 7, '', 1, 0, 'R');        
        $this->SetXY(150,180);
        $this->Cell(30, 7, 'SUB TOTAL', 1, 0, 'L');        
        $this->Cell(30, 7, $arFactura->getVrSubtotal(), 1, 0, 'R');
        $this->SetXY(150,187);
        $this->Cell(30, 7, 'Base Gravable', 1, 0, 'L');        
        $this->Cell(30, 7, $arFactura->getVrBaseAIU(), 1, 0, 'R');
        $this->SetXY(150,194);
        $this->Cell(30, 7, 'IVA 16 %', 1, 0, 'L');        
        $this->Cell(30, 7, $arFactura->getVrIva(), 1, 0, 'R'); 
        $this->SetXY(150,201);
        $this->Cell(30, 7, 'TOTAL', 1, 0, 'L');        
        $this->Cell(30, 7, '', 1, 0, 'R');                    



        $this->Ln(3);
        $this->SetFont('Arial', 'B', 8);
        $this->Text(20, $this->GetY($this->SetY(264)), $arConfiguracion->getInformacionPagoFactura());
        //$this->MultiCell(20, $this->GetY($this->SetY(264)), $arConfiguracion->getInformacionPagoFactura(), 0, 'L');        
        $this->SetFont('Arial', '', 7);
        $this->Text(60, $this->GetY($this->SetY(267)), $arConfiguracion->getInformacionContactoFactura());

        //Número de página
        $this->Text(188, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }

    public function GenerarEncabezadoFactura($em) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);

        $this->SetFont('Arial', '', 5);
        $this->Text(188, 13, '');
        $this->Image('imagenes/logos/logo.jpg', 15, 15, 35, 17);
        $this->ln(11);
        $this->SetFont('Arial', 'B', 12);
        $this->ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->Text(21, 35, "NIT " . $arConfiguracion->getNitEmpresa() . "-" . $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetXY(258, 18);
    }

}

?>
