<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoCarta extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoTipoCarta;
    public static $fechaProceso;
    public static $fechaOpcional;
    public static $codigoContrato;
    
    public function Generar($miThis, $codigoTipoCarta,$fechaProceso,$fechaOpcional,$codigoContrato) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoTipoCarta = $codigoTipoCarta;
        self::$fechaProceso = $fechaProceso;
        self::$fechaOpcional = $fechaOpcional;
        self::$codigoContrato = $codigoContrato;
        $pdf = new FormatoCarta();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $this->Body($pdf);
        $pdf->Output("Carta$codigoContrato.pdf", 'D');        
        
    } 
    
    public function Header() {
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        //$this->SetXY(10, 5);
        $this->Image('imagenes/logos/logo.jpg' , 10 ,5, 50 , 30,'JPG');
        $this->Image('imagenes/logos/encabezado.jpg' , 115 ,5, 90 , 40,'JPG');
        
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);        
        $this->SetFont('Arial','','9');
        $this->Text(10, 50, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()). " ". self::$fechaProceso);
        $this->Ln(20);
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 65);
        $pdf->SetFont('Arial', '', 10);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);        
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find(self::$codigoContrato);        
        $arCartaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCartaTipo();
        $arCartaTipo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCartaTipo')->find(self::$codigoTipoCarta);
        $codigoCartaTipo = $arCartaTipo->getCodigoCartaTipoPk();
        $codigoContenidoFormato = $arCartaTipo->getCodigoContenidoFormatoFk();
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        if ($codigoCartaTipo == null){
           $cadena = "La carta no tiene asociado un formato tipo carta"; 
        } else {
           if ($codigoContenidoFormato == null){
               $cadena = "La carta no tiene un formato creado en el sistema";
           } else {
               $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find($arCartaTipo->getCodigoContenidoFormatoFk());
               $cadena = $arContenidoFormato->getContenido();
             }
          }            
        //se reemplaza el contenido de la tabla tipo de proceso disciplinario
        $sustitucion1 = $arContrato->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion2 = $arContrato->getEmpleadoRel()->getNombreCorto();
        $sustitucion3 = $arContrato->getEmpleadoRel()->getCargoRel()->getNombre();
        $sustitucion4 = $arContrato->getFechaHasta()->format('Y-m-a');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $sustitucion4 = strftime("%d de %B de %Y", strtotime($sustitucion4));
        $sustitucion5 = $arConfiguracion->getNombreEmpresa();
        if (self::$fechaOpcional == null){
            $sustitucion6 = "";
        } else {
            $sustitucion6 = self::$fechaOpcional;
            setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
            $sustitucion6 = strftime("%d de %B de %Y", strtotime($sustitucion6->format('Y-m-d')));
        }
        $sustitucion7 = $arContrato->getFechaHasta()->format('Y-m-a');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $sustitucion7 = strftime("%d de %B de %Y", strtotime($sustitucion7));
        $sustitucion8 = $arContrato->getContratoTipoRel()->getNombre();
        $salarioLetras = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->numtoletras($arContrato->getVrSalario());
        $sustitucion9 = $salarioLetras." $(";
        $sustitucion9 .= number_format($arContrato->getVrSalario(), 2,'.',',');
        $sustitucion9 .= ")";
        $sustitucion10 = self::$fechaProceso;
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $sustitucion10 = strftime("%d de %B de %Y", strtotime($sustitucion10));
        //$cadena = $arContenidoFormato->getContenido();
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
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
        $this->Image('imagenes/logos/piedepagina.jpg' , 65 ,208, 150 , 90,'JPG');
    }    
}

?>