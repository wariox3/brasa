<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoContratoPracticante extends \FPDF_FPDF {
    public static $em;
    public static $codigoContrato;
    
    public function Generar($miThis, $codigoContrato) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoContrato = $codigoContrato;
        $pdf = new FormatoContratoPracticante();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("ContratoPracticante$codigoContrato.pdf", 'D');        
        
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
        $sustitucion1 = $arContrato->getEmpleadoRel()->getNombreCorto();
        $sustitucion2 = $arContrato->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion3 = $arContrato->getEmpleadoRel()->getCiudadExpedicionRel()->getNombre(); //municipio y departamento expedicion cedula
        $sustitucion4 = $arContrato->getCentroCostoRel()->getNombre();
        $sustitucion5 = $arContrato->getCargoRel()->getNombre();
        $sustitucion6 = $arContrato->getHorarioTrabajo();
        $sustitucion7 = $arContrato->getFechaDesde()->format('Y/m/d');
        $salarioLetras = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->numtoletras($arContrato->getVrSalario());
        $sustitucion8 = $salarioLetras." $(";
        $sustitucion8 .= number_format($arContrato->getVrSalario(), 2,'.',',');
        $sustitucion8 .= ")";
        $sustitucion9 = $arContrato->getCentroCostoRel()->getPeriodoPagoRel()->getNombre();
        //$sustitucion10 = "por definir"; dias a pagar
        //$sustitucion11 = "por definir"; dias a pagar
        $sustitucion12 = $arContrato->getFechaDesde()->format('Y/m/d');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $sustitucion12 = strftime("%d de %B de %Y", strtotime($sustitucion12)); 
        $sustitucion13 = $arContrato->getEmpleadoRel()->getCargoDescripcion();
        $sustitucion14 = $arContrato->getCentroCostoRel()->getNombre();
        $sustitucion15 = $arContrato->getFechaDesde()->format('Y/m/d');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $sustitucion15 = strftime("%d de %B de %Y", strtotime($sustitucion15)); 
        $sustitucion16 = $arContrato->getEmpleadoRel()->getNombreCorto();
        $sustitucion17 = $arContrato->getEmpleadoRel()->getNumeroIdentificacion()." de ". $arContrato->getEmpleadoRel()->getCiudadExpedicionRel()->getNombre();
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
        //$patron10 = '/#a/'; dias a pagar
        //$patron11 = '/#b/'; dias a pagar
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
        //$cadenaCambiada = preg_replace($patron10, $sustitucion10, $cadenaCambiada); dias a pagar
        //$cadenaCambiada = preg_replace($patron11, $sustitucion11, $cadenaCambiada); dias a pagar
        $cadenaCambiada = preg_replace($patron12, $sustitucion12, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron13, $sustitucion13, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron14, $sustitucion14, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron15, $sustitucion15, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron16, $sustitucion16, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron17, $sustitucion17, $cadenaCambiada);
        $pdf->MultiCell(0,4.5, $cadenaCambiada);        
   
    }

    public function Footer() {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C'); 
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
