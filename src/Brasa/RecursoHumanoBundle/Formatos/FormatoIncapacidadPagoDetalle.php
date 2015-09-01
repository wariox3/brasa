<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoIncapacidadPagoDetalle extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoIncapacidadPago;
    
    public function Generar($miThis, $codigoIncapacidadPago) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoIncapacidadPago = $codigoIncapacidadPago;
        $pdf = new FormatoIncapacidadPagoDetalle();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $pdf->SetFillColor(200, 200, 200);
        $this->Body($pdf);

        $pdf->Output("PagoDetalle$codigoIncapacidadPago.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arIncapacidadPago = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago();
        $arIncapacidadPago = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->find(self::$codigoIncapacidadPago);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, "INCAPACIDAD PAGO DETALLE", 0, 0, 'C', 1);
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
        //FILA 1
        $this->SetXY(10, 40);
        $this->SetFont('Arial','B',7);
        $this->Cell(22, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',7);
        $this->Cell(78, 6, $arIncapacidadPago->getCodigoIncapacidadPagoPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(24, 6, "ENTIDAD:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(69, 6, $arIncapacidadPago->getEntidadSaludRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6.5);
        //FILA 2
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(22, 6, "AUTORIZADO:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',7);
        $this->SetFillColor(255, 255, 255);
        if ($arIncapacidadPago->getEstadoAutorizado() == 1){
            $this->Cell(78, 6, "SI" , 1, 0, 'L', 1);
        }else {
            $this->Cell(78, 6, "NO" , 1, 0, 'L', 1);
        }
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(24, 6, "TOTAL:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(69, 6, number_format($arIncapacidadPago->getVrTotal(), 2, '.', ',') , 1, 0, 'R', 1);
        
        //FILA 3
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(22, 6, "COMENTARIOS:" , 1, 0, 'L', 1);                            
        $this->SetFont('Arial','',6);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(170, 6, $arIncapacidadPago->getComentarios() , 1, 0, 'L', 1);

        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('CONCEPTO', 'DETALLE', 'HORAS', 'VR. HORA', '%', 'DEDUCCION', 'DEVENGADO');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(40, 83, 11, 14, 9, 18, 18);
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
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetFillColor(200, 200, 200);

        // INFORMACION DETALLEDO
        $arIncapacidadPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle();
        $arIncapacidadPagoDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPagoDetalle')->findBy(array('codigoIncapacidadPagoFk' => self::$codigoIncapacidadPago));
            $pdf->Cell(193, 4, utf8_decode("INFORMACIÓN DETALLE INCAPACIDADES"), 1, 0, 'L',true);
            $pdf->Ln(4);
            $pdf->Cell(24, 4, utf8_decode("CÓDIGO"), 1, 0, 'L',true);
            $pdf->Cell(24, 4, "IDENTIFICACION", 1, 0, 'L',true);
            $pdf->Cell(24, 4, "EMPLEADO", 1, 0, 'L',true);
            $pdf->Cell(25, 4, "TIPO", 1, 0, 'L',true);
            $pdf->Cell(24, 4, "DIAGNOSTICO", 1, 0, 'L',true);
            $pdf->Cell(24, 4, "PAGO", 1, 0, 'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            foreach ($arIncapacidadPagoDetalles as $arIncapacidadPagoDetalles) {
                
                    $pdf->Cell(24, 4, $arIncapacidadPagoDetalles->getCodigoIncapacidadFk(), 1, 0, 'L');
                    $pdf->Cell(24, 4, $arIncapacidadPagoDetalles->getIncapacidadRel()->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
                    $pdf->Cell(25, 4, $arIncapacidadPagoDetalles->getIncapacidadRel()->getEmpleadoRel()->getNombreCorto(), 1, 0, 'L');
                    $pdf->Cell(24, 4, $arIncapacidadPagoDetalles->getIncapacidadRel()->getPagoAdicionalSubtipoRel()->getNombre(), 1, 0, 'L');
                    $pdf->Cell(24, 4, $arIncapacidadPagoDetalles->getIncapacidadRel()->getIncapacidadDiagnosticoRel()->getNombre(), 1, 0, 'L');
                    $pdf->Cell(24, 4, number_format($arIncapacidadPagoDetalles->getVrPago(), 2, '.', ','), 1, 0, 'R');
                    $pdf->Ln();
                

            }
            $pdf->Ln(8);
            $pdf->SetFont('Arial', 'B', 7);
            
                
                   
    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
