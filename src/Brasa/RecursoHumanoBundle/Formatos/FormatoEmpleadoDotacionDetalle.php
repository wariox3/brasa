<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoEmpleadoDotacionDetalle extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoEmpleadoDotacion;
    
    public function Generar($miThis, $codigoEmpleadoDotacion) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoEmpleadoDotacion = $codigoEmpleadoDotacion;
        $pdf = new FormatoEmpleadoDotacionDetalle();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("DotacionEmpleado.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arEmpleadoDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion();
        $arEmpleadoDotacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoDotacion')->find(self::$codigoEmpleadoDotacion);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(193, 10, utf8_decode("DOTACIÓN EMPLEADO ") , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);  
        //FILA 1
        $this->SetXY(10, 30);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(23, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(22, 6, $arEmpleadoDotacion->getCodigoEmpleadoDotacionPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(18, 6, utf8_decode("FECHA:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(50, 6, $arEmpleadoDotacion->getFecha()->Format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6);
        $this->Cell(30, 6, utf8_decode("N° INTERNO REFERENCIA:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(50, 6, $arEmpleadoDotacion->getCodigoInternoReferencia() , 1, 0, 'L', 1);
        
        //FILA 2
        $this->SetXY(10, 36);
        $this->SetFont('Arial','B',7);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(23, 6, utf8_decode("IDENTIFICACIÓN:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',8);
        $this->Cell(22, 6, $arEmpleadoDotacion->getEmpleadoRel()->getNumeroIdentificacion() , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(18, 6, utf8_decode("EMPLEADO:") , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',6.5);
        $this->Cell(50, 6, utf8_decode($arEmpleadoDotacion->getEmpleadoRel()->getNombreCorto()) , 1, 0, 'L', 1);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(30, 6, utf8_decode("CENTRO COSTOS:") , 1, 0, 'L', 1);                            
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',6);
        $this->Cell(50, 6, utf8_decode($arEmpleadoDotacion->getCentroCostoRel()->getNombre()) , 1, 0, 'L', 1);
        //FILA 3
        $this->SetXY(10, 42);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',7);
        $this->Cell(23, 6, "COMENTARIOS:" , 1, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial','',6);
        $this->Cell(170, 6, utf8_decode($arEmpleadoDotacion->getComentarios()) , 1, 0, 'L', 1);        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(10);
        $header = array(utf8_decode('CÓDIGO'), utf8_decode('ELEMENTO DOTACIÓN'), 'CANTIDAD ASIGANADA', 'CANTIDAD DEVUELTA', 'SERIE', 'LOTE');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(15, 58, 30, 30, 30, 30);
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
        $arEmpleadoDotacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacionDetalle();
        $arEmpleadoDotacionDetalle = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoDotacionDetalle')->findBy(array('codigoEmpleadoDotacionFk' => self::$codigoEmpleadoDotacion));
        foreach ($arEmpleadoDotacionDetalle as $arEmpleadoDotacionDetalle) {            
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(15, 4, $arEmpleadoDotacionDetalle->getCodigoEmpleadoDotacionDetallePk(), 1, 0, 'L');
            $pdf->Cell(58, 4, $arEmpleadoDotacionDetalle->getDotacionElementoRel()->getDotacion(), 1, 0, 'L');
            $pdf->Cell(30, 4, $arEmpleadoDotacionDetalle->getCantidadAsignada(), 1, 0, 'R');
            $pdf->Cell(30, 4, $arEmpleadoDotacionDetalle->getCantidadDevuelta(), 1, 0, 'R');
            $pdf->Cell(30, 4, $arEmpleadoDotacionDetalle->getSerie(), 1, 0, 'R');
            $pdf->Cell(30, 4, $arEmpleadoDotacionDetalle->getLote(), 1, 0, 'R');            
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
