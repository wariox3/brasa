<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoSeleccionGrupo extends \FPDF_FPDF {
    public static $em;
    public static $codigoSeleccionGrupo;
    public function Generar($miThis, $codigoSeleccionGrupo) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoSeleccionGrupo = $codigoSeleccionGrupo;
        $pdf = new FormatoSeleccionGrupo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("SeleccionGrupo$codigoSeleccionGrupo.pdf", 'D');        
        
    } 
    public function Header() {
        $arSeleccionGrupo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo();
        $arSeleccionGrupo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->find(self::$codigoSeleccionGrupo);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(150, 20);
        $this->Cell(50, 6, "Grupo seleccion " . $arSeleccionGrupo->getCodigoSeleccionGrupoPk(), 1, 0, 'L', 1);
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $header = array('COD', 'TIPO', 'IDENTIFICACION', 'NOMBRE', 'TELEFONO', 'CELULAR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 45, 22, 73, 20, 20);
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
        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSelecciones = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('codigoSeleccionGrupoFk' => self::$codigoSeleccionGrupo));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arSelecciones as $arSeleccion) {            
            $pdf->Cell(10, 4, $arSeleccion->getCodigoSeleccionPk(), 1, 0, 'L');
            $pdf->Cell(45, 4, $arSeleccion->getSeleccionTipoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(22, 4, $arSeleccion->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(73, 4, $arSeleccion->getNombreCorto(), 1, 0, 'L');
            $pdf->Cell(20, 4, $arSeleccion->getTelefono(), 1, 0, 'L');
            $pdf->Cell(20, 4, $arSeleccion->getCelular(), 1, 0, 'L');            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 33);
        }        
    }

    public function Footer() {
        $this->SetFont('Arial','B', 9);    
        $this->Line(30, 271, 100, 271);        
        $this->Line(120, 271, 180, 271);        
        $this->Text(50, 275, "FIRMA"); 
        $this->Text(140, 275, "FIRMA");
        $this->SetFont('Arial','', 10);  
        $this->Text(170, 290, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }    
}

?>
