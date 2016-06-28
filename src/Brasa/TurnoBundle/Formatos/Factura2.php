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
        
        
        $this->SetFont('Arial', '', 9);
        $this->Text(15, 65, "Fecha Factura");
        $this->Text(50, 65, ucwords(strtolower($this->devuelveMes($arFactura->getFecha()->format('m')))) . " " . $arFactura->getFecha()->format('d') . " de " . $arFactura->getFecha()->format('Y'));
        $this->Text(140, 65, "Fecha Vence");
        $this->Text(170, 65, ucwords(strtolower($this->devuelveMes($arFactura->getFechaVence()->format('m')))) . " " . $arFactura->getFechaVence()->format('d') . " de " . $arFactura->getFechaVence()->format('Y'));        
        $this->Text(15, 70, utf8_decode("Señores"));
        $this->Text(50, 70, $arFactura->getClienteRel()->getNombreCorto());
        $this->Text(140, 70, "Nit");
        $this->Text(170, 70, $arFactura->getClienteRel()->getNit());        
        $this->Text(15, 80, "Direccion");
        $this->Text(50, 80, $arFactura->getClienteRel()->getDireccion());
        $this->Text(140, 80, "Telefono");
        $this->Text(170, 80, $arFactura->getClienteRel()->getTelefono());                
        
        $this->SetXY(110, 75);
        $this->SetMargins(10, 1, 10);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $this->SetX(15);
        $header = array('CANT', 'DETALLE', 'Vr. UNITARIO', 'Vr. TOTAL');
        //$this->SetFillColor(236, 236, 236);
        //$this->SetTextColor(0);
        //$this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 9);

        //creamos la cabecera de la tabla.
        $w = array(10, 110, 30, 30);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0)
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'L');
            else
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(8);
    }

    public function Body($pdf) {
        $pdf->Rect(15, 96, 10, 84);
        $pdf->Rect(25, 96, 110, 84);
        $pdf->Rect(135, 96, 30, 84);
        $pdf->Rect(165, 96, 30, 84);
        $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        $pdf->SetX(15);
        $pdf->Cell(10, 4, '', 0, 0, 'R');                        
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(110, 4, "SERVICIOS DE SEGURIDAD", 0, 0, 'L');                        
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(30, 4, '', 0, 0, 'R');
        $pdf->Cell(30, 4, '', 0, 0, 'R'); 
        $pdf->Ln(8);
        $pdf->SetFont('Arial', '', 9);
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $pdf->SetX(15);
            $strDetalle = "SERVICIO " . $arFacturaDetalle->getConceptoServicioRel()->getNombre() . " DESDE EL DIA " . $arFacturaDetalle->getPedidoDetalleRel()->getDiaDesde()
                    . " HASTA EL DIA " . $arFacturaDetalle->getPedidoDetalleRel()->getDiaHasta() . " DE " .
            $this->devuelveMes($arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getFechaProgramacion()->format('n')) . " " . $arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getFechaProgramacion()->format('Y');
            $strDetalle2 = " del " . $arFacturaDetalle->getPedidoDetalleRel()->getDiaDesde()
                    . " al " . $arFacturaDetalle->getPedidoDetalleRel()->getDiaHasta() . " DE " .
            $this->devuelveMes($arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getFechaProgramacion()->format('n')) . " " . $arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getFechaProgramacion()->format('Y');            
            $pdf->Cell(10, 4, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 0, 0, 'R');                        
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(110, 4, substr($arFacturaDetalle->getPedidoDetalleRel()->getPuestoRel()->getNombre(), 0, 55), 0, 0, 'L');                        
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(30, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetX(15);
            $pdf->Cell(10, 4, '', 0, 0, 'R');                                   
            $strCampo = $arFacturaDetalle->getPedidoDetalleRel()->getConceptoServicioRel()->getNombreFacturacion() . " " . $arFacturaDetalle->getDetalle();            
            $pdf->MultiCell(110, 4, $strCampo, 0, 'L'); 
            //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');                        
            $pdf->Cell(30, 4, '', 0, 0, 'R');
            $pdf->Cell(30, 4, '', 0, 0, 'R');            
            //$pdf->Ln();
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
        $this->SetXY(15,180);
        $this->Cell(50, 21, '', 1, 0, 'R');        
        $this->Cell(70, 21, '', 1, 0, 'R'); 
        $this->SetXY(15,201);
        $this->Cell(120, 7, '', 1, 0, 'R');        
        $this->SetXY(135,180);
        $this->Cell(30, 7, 'SUB TOTAL', 1, 0, 'L');        
        $this->Cell(30, 7, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(135,187);
        $this->Cell(30, 7, 'Base Gravable', 1, 0, 'L');        
        $this->Cell(30, 7, number_format($arFactura->getVrBaseAIU(), 0, '.', ',') , 1, 0, 'R');
        $this->SetXY(135,194);
        $this->Cell(30, 7, 'IVA 16 %', 1, 0, 'L');        
        $this->Cell(30, 7, number_format($arFactura->getVrIva(), 0, '.', ','), 1, 0, 'R'); 
        $this->SetXY(135,201);
        $this->Cell(30, 7, 'TOTAL', 1, 0, 'L');        
        $this->Cell(30, 7, number_format($arFactura->getVrTotal(), 0, '.', ','), 1, 0, 'R');                    
        $this->SetFont('Arial', '', 8);
        $plazoPago = $arFactura->getClienteRel()->getPlazoPago();
        $this->Text(66, 185, "CONDICIONES DE PAGO: A $plazoPago DIAS A PARTIR");
        $this->Text(66, 189, "DE LA FECHA DE EXPEDICION");
        $this->SetFont('Arial', '', 9);
        $this->Text(20, 185, "Recibi conforme:");
        $this->Text(20, 190, "Fecha y Nombre:");
        $this->Text(20, 195, "Sello:");
        $this->Text(20, 205, "Actividad Comercial");
        $this->Text(60, 205, "Construcion");
        $this->Text(90, 205, "Estrato =");
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
        //$this->Text(188, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
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
