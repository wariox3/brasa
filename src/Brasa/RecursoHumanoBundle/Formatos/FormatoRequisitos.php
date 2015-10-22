<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoRequisitos extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoRequisito;
    
    public function Generar($miThis, $codigoRequisito) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoRequisito = $codigoRequisito;
        $pdf = new FormatoRequisitos();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Requisito.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisito();
        $arRequisito = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuRequisito')->find(self::$codigoRequisito);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 14);
        $this->Image('imagenes/logos/logo.jpg', 12, 15, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("VERIFICACION REQUISITOS"), 0, 0, 'C', 1);
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
        $this->Cell(23, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(22, 6, $arRequisito->getCodigoRequisitoPk() , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(18, 6, utf8_decode("FECHA:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(50, 6, $arRequisito->getFecha()->Format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',6);
        $this->Cell(30, 6, utf8_decode("EMPLEADO:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(50, 6, $arRequisito->getEmpleadoRel()->getNombreCorto() , 1, 0, 'L', 1);        

        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(10);
        $header = array(utf8_decode('CÓDIGO'), utf8_decode('CONCEPTO'), 'ENTREGADO', 'APLICA', 'ESTADO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(15, 110, 20, 20, 20);
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
        $pdf->SetFont('Arial', '', 8);
        $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
        $arRequisitoDetalle = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoDetalle')->findBy(array('codigoRequisitoFk' => self::$codigoRequisito));
        foreach ($arRequisitoDetalle as $arRequisitoDetalle) {            
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(15, 4, $arRequisitoDetalle->getCodigoRequisitoDetallePk(), 1, 0, 'L');
            $pdf->Cell(110, 4, $arRequisitoDetalle->getRequisitoConceptoRel()->getNombre(), 1, 0, 'L');
            if($arRequisitoDetalle->getEstadoEntregado() == 1) {
                $pdf->Cell(20, 4, 'SI', 1, 0, 'L');
            } else {
                $pdf->Cell(20, 4, 'NO', 1, 0, 'L');
            }
            if($arRequisitoDetalle->getEstadoNoAplica() == 1) {
                $pdf->Cell(20, 4, 'NO', 1, 0, 'L');
            } else {
                $pdf->Cell(20, 4, 'SI', 1, 0, 'L');
            }            
            if($arRequisitoDetalle->getEstadoPendiente() == 1) {
                $pdf->Cell(20, 4, 'PENDIENTE', 1, 0, 'L');
            } else {
                $pdf->Cell(20, 4, 'CERRADO', 1, 0, 'L');
            }            

            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
             
    }

    public function Footer() {
        
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisito();
        $arRequisito = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuRequisito')->find(self::$codigoRequisito);
        $this->SetFont('Arial', 'B', 9);
        
        $this->Text(10, 240, "FIRMA: _____________________________________________");
        $this->Text(10, 247, $arRequisito->getEmpleadoRel()->getNombreCorto());
        $this->Text(10, 254, "C.C.:     ______________________ de ____________________");
        $this->Text(105, 240, "FIRMA: _____________________________________________");
        $this->Text(105, 247, $arConfiguracion->getNombreEmpresa());
        $this->Text(105, 254, "NIT: ". $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');;
    }    
}

?>
