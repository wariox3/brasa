<?php
namespace Brasa\LogisticaBundle\Formatos;
class FormatoGuia extends \FPDF_FPDF {
    public static $em;
    
    public function Generar($miThis, $codigoGuia) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new FormatoGuia();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Guia" . $codigoGuia . ".pdf", 'D');                
    } 
    
    public function Header() {
        $arDespacho = new \Brasa\LogisticaBundle\Entity\LogDespachos();
        $arDespacho = self::$em->getRepository('BrasaLogisticaBundle:LogDespachos')->find(6);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(150, 10);
        $this->Cell(50, 6, "GUIA", 1, 0, 'L', 1);
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {

    }

    public function Body($pdf) {

    }

    public function Footer() {

    }    
}

?>
