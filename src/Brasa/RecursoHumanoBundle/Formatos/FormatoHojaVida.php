<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoHojaVida extends \FPDF_FPDF {
    public static $em;
    public static $codigoEmpleado;
    public function Generar($miThis, $codigoEmpleado) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoEmpleado = $codigoEmpleado;
        $pdf = new FormatoHojaVida();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Empleado$codigoEmpleado.pdf", 'D');        
        
    } 
    public function Header() {
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find(self::$codigoEmpleado);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(150, 20);
        $this->Cell(50, 6, "Empleado " . $arEmpleado->getCodigoEmpleadoPk(), 1, 0, 'L', 1);
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('ID', 'CONCEPTO', 'VALOR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(14, 14, 42);
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
    }

    public function Footer() {
        $this->SetFont('Arial','B', 9);    
        $this->Line(30, 271, 100, 271);        
        $this->Line(120, 271, 180, 271);        
        $this->Text(50, 275, "FIRMA RECIBIDO"); 
        $this->Text(140, 275, "FIRMA BODEGA");
        $this->SetFont('Arial','', 10);  
        $this->Text(170, 290, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }    
}

?>
