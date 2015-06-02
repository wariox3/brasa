<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoDetalleCredito extends \FPDF_FPDF {
    public static $em;
    public static $codigoCredito;
    public function Generar($miThis, $codigoCredito) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoCredito = $codigoCredito;
        $pdf = new FormatoDetalleCredito();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("DetalleCredito_$codigoCredito.pdf", 'D');        
        
    } 
    public function Header() {
        $arDetallePago = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arDetallePago = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find(self::$codigoCredito);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',13);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(190, 10, "Información del credito " , 1, 0, 'L', 1);
        $this->SetXY(10, 30);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Código Credito:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getCodigoCreditoPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Fecha:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getFecha()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetXY(10, 35);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Empleado:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 6, $arDetallePago->getEmpleadoRel()->getNombreCorto() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Credito:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 6, $arDetallePago->getCreditoTipoRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetXY(10, 40);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Valor Credito:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getVrPagar(), 2, '.', ',') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Valor Cuota:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getVrCuota() - $arDetallePago->getSeguro(), 2, '.', ',') , 1, 0, 'L', 1);
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Nro Cuotas:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getNumeroCuotas() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Nro Cuota Actual:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getNumeroCuotaActual() , 1, 0, 'L', 1);
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Saldo:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getSaldo(), 2, '.', ','), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Estado:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arDetallePago->getEstadoPagado()== 1)
        {    
            $this->Cell(65, 6, "PAGADO" , 1, 0, 'L', 1);
        }
        else
        {
            $this->Cell(65, 6, "PENDIENTE" , 1, 0, 'L', 1);
        }    
        $this->SetXY(10, 55);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Tipo Pago:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, $arDetallePago->getTipoPago() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Aprobado:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if ($arDetallePago->getAprobado()== 1)
        {    
            $this->Cell(65, 6, "SI" , 1, 0, 'L', 1);
        }
        else
        {
            $this->Cell(65, 6, "NO" , 1, 0, 'L', 1);
        }
        $this->SetXY(10, 60);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Comentarios:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 6, $arDetallePago->getComentarios() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "Seguro:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 6, number_format($arDetallePago->getSeguro(), 2, '.', ','),1, 0, 'L', 1);
        $this->SetXY(10, 75);
        $this->SetFont('Arial','B',12);
        $this->Cell(190, 10, "Pagos" , 1, 0, 'L', 1);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('CODIGO PAGO', 'VR. CUOTA', 'VR. SEGURO', 'VR. PAGO', 'FECHA PAGO', 'TIPO PAGO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(25, 30, 30, 30, 30, 45);
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
        $arCreditoPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
        $arCreditoPagos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoPago')->findBy(array('codigoCreditoFk' => self::$codigoCredito));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        foreach ($arCreditoPagos as $arCreditoPago) {            
            $pdf->Cell(25, 4, $arCreditoPago->getCodigoPagoCreditoPk(), 1, 0, 'L');
            $pdf->Cell(30, 4, number_format($arCreditoPago->getVrCuota() - $arCreditoPago->getSeguro(), 2, '.', ','), 1, 0, 'L');
            $pdf->Cell(30, 4, number_format($arCreditoPago->getSeguro(), 2, '.', ','), 1, 0, 'L');
            $pdf->Cell(30, 4, number_format($arCreditoPago->getVrCuota(), 2, '.', ','), 1, 0, 'L');
            $pdf->Cell(30, 4, $arCreditoPago->getFechaPago()->format('Y/m/d'), 1, 0, 'C');
            $pdf->Cell(45, 4, $arCreditoPago->getTipoPago(), 1, 0, 'C');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
        }        
    }

    public function Footer() {
                
        $this->SetFont('Arial','', 10);  
        $this->Text(170, 290, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }    
}

?>
