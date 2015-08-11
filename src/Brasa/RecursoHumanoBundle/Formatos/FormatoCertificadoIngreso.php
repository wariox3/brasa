<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoCertificadoIngreso extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoEmpleado;
    
    public function Generar($miThis, $codigoEmpleado,$strFechaExpedicion) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoEmpleado = $codigoEmpleado;
        $pdf = new FormatoCertificadoIngreso();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("CertificadoIngreso_$codigoEmpleado.pdf", 'D');        
        
    } 
    
    public function Header() {
        
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find(self::$codigoEmpleado);        
        $this->SetFillColor(255, 255, 255);        
        $this->SetFont('Arial','B',12);
        //logo dian
        $this->SetXY(5, 5);
        $this->Line(5, 5, 35, 5);
        $this->Line(5, 5, 5, 20);
        $this->Line(5, 20, 35, 20);
        $this->Line(35, 5, 35, 20);
        $this->Image('imagenes/logos/dian.png', 6, 6, 28, 12);
        $this->SetXY(35, 5);
        $this->Line(35, 5, 145, 5);
        $this->Cell(110, 7.5, "Certificado de ingresos y Retenciones para Personas" , 0, 0, 'C', 1);
        $this->SetXY(35, 13);
        $this->Line(35, 20, 145, 20);
        $this->Cell(110, 6.5, "Naturales Empleados Año Gravable 2014" , 0, 0, 'C', 1);
        //logo muisca
        $this->Line(145, 5, 175, 5);
        $this->Line(145, 5, 145, 20);
        $this->Line(145, 20,175, 20);
        $this->Line(175, 5, 175, 20);
        $this->Image('imagenes/logos/muisca.png', 146, 6, 28, 12);
        //logo 220
        $this->Line(175, 5, 205, 5);
        $this->Line(175, 5, 175, 20);
        $this->Line(175, 20, 205, 20);
        $this->Line(205, 5, 205, 20);
        $this->Image('imagenes/logos/220.png', 175, 5, 30, 15);
        
        
        
            
        
    }

    public function Body($pdf) {
               
    }

    public function Footer() {
        
        
    }    
}

?>
