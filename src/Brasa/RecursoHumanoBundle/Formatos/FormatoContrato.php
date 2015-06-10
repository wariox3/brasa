<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoContrato extends \FPDF_FPDF {
    public static $em;
    public static $codigoContrato;
    
    public function Generar($miThis, $codigoContrato) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoContrato = $codigoContrato;
        $pdf = new FormatoContrato();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Contrato$codigoContrato.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);                        
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find(self::$codigoContrato);        
        $arContenidoFormato = new \Brasa\RecursoHumanoBundle\Entity\RhuContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->find(1);        
        $this->SetXY(10, 10);
        $this->Cell(185, 7, utf8_decode($arContenidoFormato->getTitulo()), 0, 0, 'C', 1);
        $this->Text(10, 25, "Contrato numero: " . $arContrato->getCodigoContratoPk());
        $this->Ln(20);
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 30);
        $pdf->SetFont('Arial', '', 10);  
        $arContenidoFormato = new \Brasa\RecursoHumanoBundle\Entity\RhuContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->find(1);
        $pdf->MultiCell(0,5, $arContenidoFormato->getContenido());        
   
    }

    public function Footer() {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C'); 
        $this->Text(170, 290, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }    
}

?>
