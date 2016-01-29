<?php
namespace Brasa\TurnoBundle\Formatos;
class FormatoFactura extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoFactura;
    
    public function Generar($miThis, $codigoFactura) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $pdf = new FormatoFactura();
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
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);

        $this->SetFont('Arial', 'B', 9);
        
        $this->SetMargins(10, 1, 10);
        //$this->Rect(4, 40, 130, 20);
        $this->ln(1);
        $this->SetY(48);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 3, 'CLIENTE', 0, 0, 'L');    
        $this->SetY(50);
        $this->Ln();
        $this->Ln(1);
        $this->SetFont('Arial', 'B', 9);
        $List1 = array('NOMBRE: ', 'NIT: ', 'TELEFONO:', 'DIRECCION:', 'SECTOR:', 'ESTRATO:');
        foreach ($List1 as $col) {
            $this->Cell(50, 4, $col, 0, 0, 'L');
            $this->Ln(4);
        }

        $Datos = array($arFactura->getClienteRel()->getNombreCorto(), 
            $arFactura->getClienteRel()->getNit(), 
            $arFactura->getClienteRel()->getTelefono(), 
            $arFactura->getClienteRel()->getDireccion(), 
            $arFactura->getClienteRel()->getSectorRel()->getNombre(), 
            $arFactura->getClienteRel()->getEstrato());
        $this->SetFont('Arial', '', 8);
        $this->SetY(54);

        foreach ($Datos as $col) {
            $this->SetX(33);
            $this->Cell(50, 4, $col, 0, 'L');
            $this->Ln(4);
        }
        
        //$this->SetMargins(4, 1, 10);
        //$this->Rect(135, 26, 73, 20);
        $this->ln(1);
        $this->SetY(27);
        
        $List1 = array('FACTURA DE VENTA','Fecha emision:','Fecha vencimiento:', 'Forma pago:', 'Plazo:', 'Soporte:');
        $this->SetFont('Arial', 'B', 8);
        foreach ($List1 as $col) {
            $this->SetX(150);
            $this->Cell(10, 3, $col, 0, 0, 'L');
            $this->Ln();
        }

        $List1 = array($arFactura->getNumero(),
            $arFactura->getFecha()->format('Y-m-d'), 
            $arFactura->getFechaVence()->format('Y-m-d'), 
            'CONTADO', 
            $arFactura->getClienteRel()->getPlazoPago(), 
            $arFactura->getSoporte());
        $this->SetFont('Arial', '', 8);
        $this->SetY(27);
        foreach ($List1 as $col) {
            $this->SetX(175);
            $this->Cell(30, 3, $col, 0, 0, 'R');
            $this->Ln();
        }
        $this->SetY(48);
        $arrayTexto = array('Direccion', "ciudad, departamento", "direccion", 'Barrio');
        $this->SetX(110);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 3, 'DIRECCION DE ENVIO', 0, 0, 'L');
        $this->SetY(50);
        $this->Ln();
        $this->Ln(1);
        $this->SetFont('Arial', '', 8);
        foreach ($arrayTexto as $col) {
            $this->SetX(110);
            $this->Cell(10, 4, $col, 0, 0, 'L');
            $this->Ln();
        }
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('CODIGO', 'SERVICIO', 'MES', 'DES', 'HAS', 'CANTIDAD', 'VALOR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(15, 85, 30, 10, 10, 15,30);
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
        $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arFacturaDetalles as $arFacturaDetalle) {            
            $pdf->Cell(15, 4, $arFacturaDetalle->getCodigoFacturaDetallePk(), 1, 0, 'L');
            $pdf->Cell(85, 4, $arFacturaDetalle->getConceptoServicioRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(30, 4, $arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getFechaProgramacion()->format('m'), 1, 0, 'L');
            $pdf->Cell(10, 4, $arFacturaDetalle->getPedidoDetalleRel()->getDiaDesde(), 1, 0, 'L');
            $pdf->Cell(10, 4, $arFacturaDetalle->getPedidoDetalleRel()->getDiaHasta(), 1, 0, 'L');            
            $pdf->Cell(15, 4, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 1, 0, 'R');                            
            $pdf->Cell(30, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 1, 0, 'R');                
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);        
        $this->SetY(180);
        $this->line(10, $this->GetY() + 5, 205, $this->GetY() + 5);

        $this->SetFont('Arial', 'B', 7.5);
        $this->ln(5);
        $totales = array('SUBTOTAL: ' . " " . " ",
            'BASE AIU: ' . " " . " ",
            '(+)IVA: ' . " " . " ",
            '(+)RTE FUENTE: ' . " " . " ",
            'TOTAL GENERAL: ' . " " . " "
        );

        $this->line(10, $this->GetY() + 40, 205, $this->GetY() + 40);

        $this->SetMargins(170, 2, 15);
        for ($i = 0; $i < count($totales); $i++) {
            $this->SetX(165);
            $this->Cell(20, 4, $totales[$i], 0, 0, 'R');
            $this->ln();
        }

        $totales2 = array(number_format($arFactura->getVrSubtotal(), 2, '.', ','),
            number_format($arFactura->getVrBaseAIU(), 2, '.', ','),
            number_format($arFactura->getVrIva(), 2, '.', ','),
            number_format($arFactura->getVrRetencionFuente(), 2, '.', ','),
            number_format($arFactura->getVrTotal(), 2, '.', ',')
        );

        $this->SetFont('Arial', '', 7.5);
        $this->SetXY(190, $this->GetY() - 32);
        $this->ln(12);
        for ($i = 0; $i < count($totales2); $i++) {
            $this->SetX(185);
            $this->Cell(20, 4, $totales2[$i], 0, 0, 'R');
            $this->ln();
        }

        $this->SetY($this->GetY() - 20);
        $this->SetFont('Arial', 'B', 8);
        $this->SetX(10);
        $this->Cell(20, 5, 'OBSERVACIONES:', 0, 'L');

        $this->ln();
        $this->SetX(10);
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(140, 3, $arFactura->getComentarios(), 0, 'L');
        
        $arrayNumero = explode(".", 0,2);
        $intCentavos = 0;
        if(count($arrayNumero) > 1)
            $intCentavos = substr($arrayNumero[1], 0, 2);
        $strLentras = "cero " . " con " . " cero centavos";
        $this->SetFont('Arial', 'B', 6);
        $this->Text(12, 224, "SON : " . substr(strtoupper($strLentras),0,96));
        $this->Ln();

        $Text = array(
            '* IVA REGIMEN COMUN ', 
            '* NO SOMOS AUTORETENEDORES', 
            '* NO SOMOS GRANDES CONTRIBUYENTES', 
            '* CODIGO CIIU 4645 - CREE 0.3%', 
            '* LA PRESENTE FACTURA PRESTA MERITO EJECUTIVO COMO TITULO VALOR', 
            '  SEGUN LO ESTABLECIDO EN EL ART. 3 DE LA LEY 1231 DE 2008',
            '*RESOLUCION DIAN DE AUTORIZACION PARA FACTURACION POR COMPUTADOR ',
            '  No 110000535878 DESDE 2013/06/21 HASTA 2015/06/21. FACTURAS 0001 al 1000');
        $this->SetFont('Arial', '', 6);
        $this->GetY($this->SetY(223));

        foreach ($Text as $col) {
            $this->SetX(12);
            $this->Text(12, $this->GetY() + 10, $col);
            $this->Ln(2);
        }        
        
        
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(0, 0, $this->line(100, $this->GetY() + 15, 150, $this->GetY() + 15) . $this->Text(100, $this->GetY() + 18, "AUTORIZADO"));
        $this->Cell(0, 0, $this->line(154, $this->GetY() + 15, 205, $this->GetY() + 15) . $this->Text(154, $this->GetY() + 18, "FIRMA DE RECIBIDO"));        

        $this->line(10, 260, 205, 260);
        $this->SetFont('Arial', '', 7);
        $this->SetY(-70);
        $this->Ln();
        $this->line(10, 269, 205, 269);

        
    
        $this->Ln(3);        
        $this->SetFont('Arial', 'B', 8);
        $this->Text(30, $this->GetY($this->SetY(264)), 'REALIZAR PAGO EN LA CUENTA DE AHORROS BANCOLOMBIA NUMERO 00000000000 A NOMBRE DE SEGURIDAD PLAZAS LTDA');                
        $this->SetFont('Arial', '', 7);
        $this->Text(60, $this->GetY($this->SetY(267)), 'CRA 78 NUMERO 32D-37 MEDELLIN TEL 4124586  e-mail: facturacion@seguridadplazas.com');
        
        //Número de página
        $this->Text(190, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }    
    
    public function GenerarEncabezadoFactura($em) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);          

        $this->SetFont('Arial', '', 5);
        $this->Text(170, 10, date('d-m-Y H:i:s') . ' [sogaApp - turnos]');
        $this->Image('imagenes/logos/logo.jpg', 15, 15, 35, 17);
        $this->ln(11);       
        $this->SetFont('Arial', 'B', 12);
        $this->ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->Text(21,35, "NIT " . $arConfiguracion->getNitEmpresa() . "-" . $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetXY(258, 18);
    }     
}

?>
