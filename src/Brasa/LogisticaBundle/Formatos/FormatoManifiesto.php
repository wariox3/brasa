<?php
namespace Brasa\LogisticaBundle\Formatos;
class FormatoManifiesto extends \FPDF_FPDF {
    public static $em;
    public function Generar($miThis) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new FormatoManifiesto();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("prueba.pdf", 'D');        
        
        /*$pdf = new \FPDF_FPDF();                    
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,'¡Hola, Mundo!');
        $pdf->Output('pruebamario.pdf','D');
         * 
         */
    } 
    public function Header() {
        $arDespacho = new \Brasa\LogisticaBundle\Entity\LogDespachos();
        $arDespacho = self::$em->getRepository('BrasaLogisticaBundle:LogDespachos')->find(6);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(150, 10);
        $this->Cell(50, 6, "MANIFIESTO ELECTRONICO", 1, 0, 'L', 1);
        
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
