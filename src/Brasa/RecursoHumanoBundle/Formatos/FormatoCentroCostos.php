<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoCentroCostos extends \FPDF_FPDF {
    public static $em;
   
    public function Generar($miThis) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new FormatoCentroCostos('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        
        $this->Body($pdf);
        $pdf->Output("Lista_centro_costos.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',12);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 15);
        $this->Cell(275, 8, "LISTADO DE CENTROS DE COSTOS " , 1, 0, 'C', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('ID', 'NOMBRE', 'PERIODO', 'HASTA', 'PAGO AUTOMATICO', 'HORA', 'ABIERTO', 'ACTIVO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(8, 120, 30, 25, 32, 20, 20, 20);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arCentroCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCostos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);
        foreach ($arCentroCostos as $arCentroCostos) {            
            $pdf->Cell(8, 4, $arCentroCostos->getCodigoCentroCostoPk(), 1, 0, 'L');
            $pdf->Cell(120, 4, utf8_decode($arCentroCostos->getNombre()), 1, 0, 'L');
            $pdf->Cell(30, 4, $arCentroCostos->getPeriodoPagoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(25, 4, $arCentroCostos->getFechaUltimoPagoProgramado()->format('Y/m/d'), 1, 0, 'L');
            if ($arCentroCostos->getGenerarPagoAutomatico() == 1) {    
                $pdf->Cell(32, 4, "SI", 1, 0, 'L');
            }
            else {
                $pdf->Cell(32, 4, "NO", 1, 0, 'L');
            }
            
            if ($arCentroCostos->getHoraPagoAutomatico() <> ""){
                $pdf->Cell(20, 4, $arCentroCostos->getHoraPagoAutomatico()->format('H:i'), 1, 0, 'L');
            }
            else {
                $pdf->Cell(20, 4, "00:00", 1, 0, 'L');
            }
            if ($arCentroCostos->getPagoAbierto() == 1) {    
                $pdf->Cell(20, 4, "SI", 1, 0, 'L');
            }
            else {
                $pdf->Cell(20, 4, "NO", 1, 0, 'L');
            }
            if ($arCentroCostos->getEstadoActivo() == 1) {    
                $pdf->Cell(20, 4, "SI", 1, 0, 'L');
            }
            else {
                $pdf->Cell(20, 4, "NO", 1, 0, 'L');
            }
            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);//33
        }        
    }

    public function Footer() {
        $this->SetXY(245, 190);
        $this->Cell(30, 35, utf8_decode('JG Efectivos S.A.S.   Página ') . $this->PageNo() . ' de {nb}' , 0, 0, 'L', 0);          
    }    
}
