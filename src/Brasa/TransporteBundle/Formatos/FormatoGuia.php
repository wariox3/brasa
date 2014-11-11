<?php
namespace Brasa\TransporteBundle\Formatos;
class FormatoGuia extends \FPDF_FPDF {
    public static $em;
    public static $codigoGuia;
    
    public function Generar($miThis, $codigoGuia) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoGuia = $codigoGuia;
        $pdf = new FormatoGuia();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Guia" . $codigoGuia . ".pdf", 'D');                
    } 
    
    public function Header() {
        $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
        $arGuia = self::$em->getRepository('BrasaTransporteBundle:TteGuias')->find(self::$codigoGuia);        
        $this->SetFillColor(255, 255, 255);        
        $this->SetFont('Arial','B',10);
        $this->SetXY(150, 10);
        $this->Cell(2, 6, "GUIA ", 0, 0, 'L', 1);        
        $this->SetXY(200, 10);
        $this->Cell(2, 6, $arGuia->getNumeroGuia(), 0, 0, 'R', 1);        
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
        $this->Text(11, $intY + 6, $arGuia->getFechaIngreso()->format('Y/m/d'));
        $this->Text(81, $intY + 6 , $arGuia->getCiudadOrigenRel()->getNombre());
        $this->Text(141, $intY + 6, $arGuia->getCiudadDestinoRel()->getNombre());

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
        $this->Text(11, $intY + 6, $arGuia->getTerceroRel()->getNombreCorto());
        $this->Text(81, $intY + 6 , $arGuia->getTerceroRel()->getDireccion());
        $this->Text(141, $intY + 6, $arGuia->getTerceroRel()->getTelefono());
        
        $intY = $intY + 8;
        // Linea3
        $this->SetFont('Arial','B', 8);
        $this->SetXY(10, $intY);
        $this->MultiCell(70, 4, "Destinatario\n ", 1, 'L',false);        
        $this->SetFont('Arial','B', 8);
        $this->SetXY(80, $intY);
        $this->MultiCell(60, 4, "Destinatario\n ", 1, 'L',false);
        $this->SetXY(140, $intY);
        $this->MultiCell(60, 4, "Telefono\n ", 1, 'L',false);
        $this->SetFont('Arial','', 8);                
        $this->Text(11, $intY + 6, $arGuia->getNombreDestinatario());
        $this->Text(81, $intY + 6 , $arGuia->getDireccionDestinatario());
        $this->Text(141, $intY + 6, $arGuia->getTelefonoDestinatario());        
        
        $intY = $intY + 8;
        // Linea4
        $this->SetFont('Arial','B', 8);
        $this->SetXY(10, $intY);
        $this->MultiCell(70, 6, "Tipo: \nDocumento:", 1, 'L',false);        
        $this->SetFont('Arial','B', 8);
        $this->SetXY(80, $intY);        
        $this->MultiCell(120, 6, "Observaciones:\n ", 1, 'L',false);
        $this->SetFont('Arial','', 8);                
        $this->Text(30, $intY + 3, $arGuia->getTipoPagoRel()->getNombre());
        $this->Text(30, $intY + 10, $arGuia->getDocumentoCliente());
        $this->Text(81, $intY + 7, $arGuia->getComentarios());        
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
