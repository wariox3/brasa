<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoDisciplinarioLlamadoAtencion extends \FPDF_FPDF {
    public static $em;
    public static $codigoDisciplinario;
    
    public function Generar($miThis, $codigoDisciplinario) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoDisciplinario = $codigoDisciplinario;
        $pdf = new FormatoDisciplinarioLlamadoAtencion();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("ProcesoLlamadoAtencion$codigoDisciplinario.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 10);
        $this->Cell(0, 0, $this->Image('imagenes/logos/imagen2.jpg' , 15 ,20, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
        $this->SetXY(60, 10);
        $this->Cell(90, 10, utf8_decode("PROCESO GESTIÓN HUMANA"), 1, 0, 'C', 1); //cuardo mitad arriba
        $this->SetXY(60, 20);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(90, 20, utf8_decode("PROCESOS DE RÉGIMEN DISCIPLINARIO"), 1, 0, 'C', 1); //cuardo mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(60, 40);
        $this->Cell(90, 10, utf8_decode("Régimen Organizacional Interno "), 1, 0, 'C', 1); //cuardo mitad abajo
        $this->SetXY(150, 10);
        $this->Cell(50, 10, 'Pagina ' . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(150, 20);
        $this->Cell(50, 20, utf8_decode("Código FOR-GH-16.02"), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(150, 40);
        $this->Cell(50, 5, utf8_decode("Versión 02"), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(150, 45);
        $this->Cell(50, 5, "Fecha Marzo de 2014 ", 1, 0, 'C', 1); //cuadro derecho abajo 2
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        $arDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arDisciplinario = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find(self::$codigoDisciplinario);        
        $arContenidoFormato = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo();
        $arContenidoFormato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioTipo')->find($arDisciplinario->getCodigoDisciplinarioTipoFk());        
        $this->SetFont('Arial','','9');
        $this->Text(10, 55, utf8_decode("MEDELLÍN - ANTIOQUIA ") .  $arDisciplinario->getFecha()->format('Y-m-d'));
        $this->SetFont('Arial','B','10');
        $this->Text(170, 65, utf8_decode("N°: ") .  $arDisciplinario->getCodigoDisciplinarioPk());
        $this->Ln(20);
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 60);
        $pdf->SetFont('Arial', '', 10);  
        $arDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arDisciplinario = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find(self::$codigoDisciplinario);        
        $arContenidoFormato = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo();
        $arContenidoFormato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioTipo')->find($arDisciplinario->getCodigoDisciplinarioTipoFk());        
        $pdf->MultiCell(0,5, $arContenidoFormato->getContenido());        
   
    }

    public function Footer() {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
        $this->SetFont('Arial', 'B', '10');
        $this->Text(10, 277, '_________________________________________________________________________________________________');
        $this->SetFont('Arial', '', '7');
        $this->Text(10, 280, 'Documento de confidencialidad alta ' );
        $this->Text(130, 280, 'JGEFECTIVOS S.A.S por el cuidado del Medio Ambiente  ');
        $this->Text(10, 285, 'COPIA CONTROLADA. Uso exclusiva de GH  ' );
        $this->Text(170, 290, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }    
}

?>
