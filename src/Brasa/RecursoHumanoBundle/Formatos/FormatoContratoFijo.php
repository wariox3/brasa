<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoContratoFijo extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoContrato;
    
    public function Generar($miThis, $codigoContrato) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoContrato = $codigoContrato;
        $pdf = new FormatoContratoFijo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("ContratoFijo$codigoContrato.pdf", 'D');        
        
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
        $arContenidoFormato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->find($arContrato->getCodigoContratoTipoFk());        
        $this->SetXY(10, 10);
        $this->Cell(185, 7, utf8_decode($arContenidoFormato->getTitulo()), 0, 0, 'C', 1);
        $this->Text(10, 25, "Contrato numero: " . $arContrato->getCodigoContratoPk());
        $this->Ln(20);
    }

    public function Body($pdf) {
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find(self::$codigoContrato);        
        $arContenidoFormato = new \Brasa\RecursoHumanoBundle\Entity\RhuContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->find($arContrato->getCodigoContratoTipoFk());        
        $pdf->SetXY(10, 30);
        $pdf->SetFont('Arial', '', 10);  
        //se reemplaza el contenido de la tabla tipo de proceso disciplinario
        $sustitucion1 = $arContrato->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion2 = $arContrato->getEmpleadoRel()->getNombreCorto();
        $sustitucion3 = $arContrato->getEmpleadoRel()->getDireccion();
        $sustitucion4 = $arContrato->getEmpleadoRel()->getBarrio();
        $sustitucion5 = $arContrato->getEmpleadoRel()->getFechaNacimiento()->format('Y/m/d');
        $sustitucion6 = $arContrato->getEmpleadoRel()->getCiudadNacimientoRel()->getNombre();
        $sustitucion7 = $arContrato->getCargoRel()->getNombre();
        $sustitucion8 = number_format($arContrato->getVrSalario(), 2,'.',',');
        $sustitucion9 = $arContrato->getCentroCostoRel()->getPeriodoPagoRel()->getNombre();
        $sustitucion10 = $arContrato->getFechaDesde()->format('Y/m/d');
        $sustitucion11 = $arContrato->getCentroCostoRel()->getCiudadRel()->getNombre();
        $sustitucion13 = $arContrato->getFechaHasta()->format('Y/m/d');
        //calculo meses
        $aniodesde = substr($sustitucion10, 0,-6);
        $mesdesde = substr($sustitucion10, -5,-3);
        $aniohasta = substr($sustitucion13, 0,-6);
        $meshasta = substr($sustitucion13, -5,-3);
        $anioresta = $aniohasta - $aniodesde;
        $mesresta = $meshasta - $mesdesde + 1 +($anioresta * 12);
        $sustitucion12 = $mesresta;
        $sustitucion14 = $arContrato->getFechaDesde()->format('Y/m/d');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $sustitucion14 = strftime("%d de %B de %Y", strtotime($sustitucion14));
        $sustitucion15 = $arContrato->getEmpleadoRel()->getNombreCorto();
        $sustitucion16 = $arContrato->getEmpleadoRel()->getNumeroIdentificacion()." de ".$arContrato->getEmpleadoRel()->getCiudadExpedicionRel()->getNombre();
        $sustitucion17 = $arContrato->getCentroCostoRel()->getDiasPago();
        //contenido de la cadena
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
        $patron11 = '/#b/';
        $patron12 = '/#c/';
        $patron13 = '/#d/';
        $patron14 = '/#e/';
        $patron15 = '/#f/';
        $patron16 = '/#g/';
        $patron17 = '/#h/';
        //reemplazar en la cadena
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
        $cadenaCambiada = preg_replace($patron11, $sustitucion11, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron12, $sustitucion12, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron13, $sustitucion13, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron14, $sustitucion14, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron15, $sustitucion15, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron16, $sustitucion16, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron17, $sustitucion17, $cadenaCambiada);
        $pdf->MultiCell(0,5, $cadenaCambiada);
        
        $pdf->SetAutoPageBreak(true, 15);
   
    }

    public function Footer() {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C'); 
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
