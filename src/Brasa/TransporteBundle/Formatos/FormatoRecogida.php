<?php
namespace Brasa\TransporteBundle\Formatos;

class FormatoRecogida extends \FPDF_FPDF {
    public static $em;
    public static $codigoRecogida;
    
    public function Generar($miThis, $codigoRecogida) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoRecogida = $codigoRecogida;
        $pdf = new FormatoRecogida();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Recogida" . $codigoRecogida . ".pdf", 'D');                
    } 
    
    public function Header() {
        $arRecogida = new \Brasa\TransporteBundle\Entity\TteRecogidas();
        $arRecogida = self::$em->getRepository('BrasaTransporteBundle:TteRecogidas')->find(self::$codigoRecogida);        
        $this->SetFillColor(255, 255, 255);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(150, 10);
        $this->Cell(2, 6, "RECOGIDA ", 0, 0, 'L', 1);        
        $this->SetXY(200, 10);
        $this->Cell(2, 6, $arRecogida->getCodigoRecogidaPk(), 0, 0, 'R', 1);        
        $this->Ln();
        $intY = 20;
        // Linea1
        $this->SetFont('Arial','B', 8);
        $this->SetXY(10, $intY);
        $this->MultiCell(70, 4, "Fecha\n ", 1, 'L',false);        
        $this->SetFont('Arial','B', 8);
        $this->SetXY(80, $intY);
        $this->MultiCell(60, 4, "Origen\n ", 1, 'L',false);
        $this->SetXY(140, $intY);
        $this->MultiCell(60, 4, "Destino\n ", 1, 'L',false);
        $this->SetFont('Arial','', 8);                
        $this->Text(11, $intY + 6, $arRecogida->getFechaAnuncio()->format('Y/m/d'));
        $this->Text(81, $intY + 6 , $arRecogida->getCodigoRecogidaPk());
        $this->Text(141, $intY + 6, $arRecogida->getCodigoRecogidaPk());

        $intY = $intY + 8;
        // Linea2
        $this->SetFont('Arial','B', 8);
        $this->SetXY(10, $intY);
        $this->MultiCell(70, 4, "Remite\n ", 1, 'L',false);        
        $this->SetFont('Arial','B', 8);
        $this->SetXY(80, $intY);
        $this->MultiCell(60, 4, "Direccion\n ", 1, 'L',false);
        $this->SetXY(140, $intY);
        $this->MultiCell(60, 4, "Telefono\n ", 1, 'L',false);
        $this->SetFont('Arial','', 8);                
        $this->Text(11, $intY + 6, $arRecogida->getCodigoRecogidaPk());
        $this->Text(81, $intY + 6 , $arRecogida->getCodigoRecogidaPk());
        $this->Text(141, $intY + 6, $arRecogida->getCodigoRecogidaPk());
        
        
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
