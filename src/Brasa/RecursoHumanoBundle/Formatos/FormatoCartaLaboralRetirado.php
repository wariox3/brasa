<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoCartaLaboralRetirado extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoContrato;
    
    public function Generar($miThis, $codigoContrato) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoContrato = $codigoContrato;
        $pdf = new FormatoCartaLaboralRetirado();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Contrato$codigoContrato.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 10);
        $this->Line(10, 10, 60, 10);
        $this->Line(10, 10, 10, 50);
        $this->Line(10, 50, 60, 50);
        $this->Cell(0, 0, $this->Image('imagenes/logos/logo.jpg' , 15 ,20, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
        $this->SetXY(60, 10);
        $this->Cell(90, 10, utf8_decode(""), 1, 0, 'C', 1); //cuardo mitad arriba
        $this->SetXY(60, 20);
        $this->SetFillColor(236, 236, 236);
        $this->Cell(90, 20, utf8_decode(""), 1, 0, 'C', 1); //cuardo mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(60, 40);
        $this->Cell(90, 10, utf8_decode(""), 1, 0, 'C', 1); //cuardo mitad abajo
        $this->SetXY(150, 10);
        $this->Cell(50, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(150, 20);
        $this->Cell(50, 20, utf8_decode(""), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(150, 40);
        $this->Cell(50, 5, utf8_decode("Versión 01"), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(150, 45);
        $this->Cell(50, 5, "Fecha: 01/09/2015", 1, 0, 'C', 1); //cuadro derecho abajo 2
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find(11);
        $this->SetFont('Arial','B','12');
        $this->Text(80, 70, utf8_decode("DEPTARTAMENTO NÓMINA"));
        $this->Text(98, 90, utf8_decode("CERTIFICA"));
        $this->Ln(35);
        $this->Cell(0, 0, $this->Image('imagenes/logos/firmanomina.jpg' , 15 ,150, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 100);
        $pdf->SetFont('Arial', '', 10);  
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find(self::$codigoContrato);
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find(10);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        //se reemplaza el contenido de la tabla contenido formato carta laboral
        $sustitucion1 = $arContrato->getEmpleadoRel()->getNombreCorto();
        $sustitucion2 = $arContrato->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion3 = $arConfiguracion->getNombreEmpresa();
        $sustitucion4 = $arContrato->getFechaDesde()->format('Y-m-d');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $sustitucion4 = strftime("%d de %B de %Y", strtotime($sustitucion4));
        $sustitucion5 = $arContrato->getFechaDesde()->format('Y-m-d');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $sustitucion5 = strftime("%d de %B de %Y", strtotime($sustitucion5));
        $sustitucion6 = $arContrato->getContratoTipoRel()->getNombre();
        $sustitucion7 = $arContrato->getCargoDescripcion();
        $sustitucion8 = $arConfiguracion->getNombreEmpresa();
        $sustitucion9 = strftime("%d de %B de %Y", strtotime(date('Y/m/d')));
        $sustitucion10 = $arConfiguracion->getNombreEmpresa();
        
        $cadena = $arContenidoFormato->getContenido();
        $patron1 = '/#1/';
        $patron2 = '/#2/';
        $patron3 = '/#3/';
        $patron4 = '/#4/';
        $patron5 = '/#5/';
        $patron6 = '/#6/';
        $patron7 = '/#7/';
        $patron8 = '/#8/';
        $patron9 = '/#9/';
        $patron10 = '/#a/';
        
        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron3, $sustitucion3, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron4, $sustitucion4, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron5, $sustitucion5, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron6, $sustitucion6, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron7, $sustitucion7, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron8, $sustitucion8, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron9, $sustitucion9, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron10, $sustitucion10, $cadenaCambiada);
        $pdf->MultiCell(0,5, $cadenaCambiada);
        
    }

    public function Footer() {
        
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFont('Arial', '', '9');
        $this->Text(10, 285, '____________________________________________________________________________________________________________');
        $this->Text(20,290, utf8_decode($arConfiguracion->getNombreEmpresa()." - ".$arConfiguracion->getDireccionEmpresa()." - Teléfono ".$arConfiguracion->getTelefonoEmpresa()." - ".$arConfiguracion->getPaginaWeb() ));
        $this->Text(180, 284, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
