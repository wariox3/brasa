<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoLiquidacion extends \FPDF_FPDF {
    public static $em;
    public static $codigoLiquidacion;
    
    public function Generar($miThis, $codigoLiquidacion) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoLiquidacion = $codigoLiquidacion;
        $pdf = new FormatoLiquidacion();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Liquidacion$codigoLiquidacion.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);                        
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find(self::$codigoLiquidacion);        
        $this->SetXY(10, 10);
        $this->Cell(185, 7, 'LIQUIDACION DE PRESTACIONES SOCIALES', 0, 0, 'C', 1);        
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY(10, 20);        
        $this->Cell(40, 5, "EMPLEADO:", 1, 0, 'L', 1);
        $this->SetXY(10, 25);
        $this->Cell(40, 5, "IDENTIFICACION:", 1, 0, 'L', 1);
        $this->SetXY(10, 30);
        $this->Cell(40, 5, "CENTRO COSTOS:", 1, 0, 'L', 1);        
        $this->SetXY(10, 35);
        $this->Cell(40, 5, "TIEMPO:", 1, 0, 'L', 1);       
        $this->SetXY(10, 20);
        
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial', '', 8);
        $intX = 50;
        $this->SetXY($intX, 20);        
        $this->Cell(145, 5, $arLiquidacion->getEmpleadoRel()->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetXY($intX, 25);
        $this->Cell(145, 5, $arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetXY($intX, 30);
        $this->Cell(145, 5, $arLiquidacion->getCentroCostoRel()->getNombre(), 1, 0, 'L', 1);        
        $this->SetXY($intX, 35);
        $this->Cell(145, 5, "INGRESO: " . $arLiquidacion->getFechaDesde()->format('Y-m-d') . " RETIRO:" .$arLiquidacion->getFechaHasta()->format('Y-m-d'), 1, 0, 'L', 1);               
        
        $intX = 120;
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(236, 236, 236);
        $this->SetXY($intX, 50);        
        $this->Cell(40, 5, "CESANTIAS:", 0, 0, 'L', 1);
        $this->SetXY($intX, 56);
        $this->Cell(40, 5, "INTERESES:", 0, 0, 'L', 1);
        $this->SetXY($intX, 62);
        $this->Cell(40, 5, "PRIMA SEMESTRAL:", 0, 0, 'L', 1);        
        $this->SetXY($intX, 68);
        $this->Cell(40, 5, "VACACIONES:", 0, 0, 'L', 1);    
        
        $intX = 164;
        $this->SetFont('Arial', '', 8);        
        $this->SetXY($intX, 50);        
        $this->Cell(30, 5, number_format($arLiquidacion->getVrCesantias(), 2, '.', ','), 0, 0, 'R', 1);
        $this->SetXY($intX, 56);
        $this->Cell(30, 5, number_format($arLiquidacion->getVrInteresesCesantias(), 2, '.', ','), 0, 0, 'R', 1);
        $this->SetXY($intX, 62);
        $this->Cell(30, 5, number_format($arLiquidacion->getVrPrima(), 2, '.', ','), 0, 0, 'R', 1);        
        $this->SetXY($intX, 68);
        $this->Cell(30, 5, number_format($arLiquidacion->getVrVacaciones(), 2, '.', ','), 0, 0, 'R', 1);         
        
        $this->SetXY(10, 100);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(236, 236, 236);        
        $this->Cell(185, 7, 'OTRAS DEDUCCIONES', 0, 0, 'C', 1);        

    }

    public function Body($pdf) {

    }

    public function Footer() {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C'); 
        $this->SetFont('Arial', '', 10);
        $this->Text(170, 290, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }    
}

?>
