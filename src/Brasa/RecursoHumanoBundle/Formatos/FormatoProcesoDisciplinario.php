<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoProcesoDisciplinario extends \FPDF_FPDF {
    
    public static $em;
    
    public static $codigoProcesoDisciplinarioTipo;
    public static $codigoProcesoDisciplinario;
    
    public function Generar($miThis, $codigoProcesoDisciplinarioTipo,$codigoProcesoDisciplinario) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoProcesoDisciplinarioTipo = $codigoProcesoDisciplinarioTipo;
        self::$codigoProcesoDisciplinario = $codigoProcesoDisciplinario;
        $pdf = new FormatoProcesoDisciplinario();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $this->Body($pdf);
        $pdf->Output("ProcesoDisciplinario$codigoProcesoDisciplinarioTipo.pdf", 'D');        
        
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
        $this->Text(10, 50, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()). " ". date('Y-m-d'));
        $this->Ln(20);
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 65);
        $pdf->SetFont('Arial', '', 10);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);        
        $arProcesoDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arProcesoDisciplinario = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find(self::$codigoProcesoDisciplinario);
        $arProcesoDisciplinarioTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo();
        $arProcesoDisciplinarioTipo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioTipo')->find(self::$codigoProcesoDisciplinarioTipo);
        $codigoProcesoDisciplinarioTipo = $arProcesoDisciplinarioTipo->getCodigoDisciplinarioTipoPk();
        $codigoContenidoFormato = $arProcesoDisciplinarioTipo->getCodigoContenidoFormatoFk();
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        if ($codigoProcesoDisciplinarioTipo == null){
           $cadena = "El proceso disciplinario no tiene asociado un formato tipo proceso disciplinario"; 
        } else {
           if ($codigoContenidoFormato == null){
               $cadena = "El proceso disciplinario no tiene un formato creado en el sistema";
           } else {
               $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find($arProcesoDisciplinarioTipo->getCodigoContenidoFormatoFk());
               $cadena = $arContenidoFormato->getContenido();
             }
          }            
        //se reemplaza el contenido de la tabla tipo de proceso disciplinario
        $sustitucion1 = $arProcesoDisciplinario->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion2 = $arProcesoDisciplinario->getEmpleadoRel()->getNombreCorto();
        $sustitucion3 = $arProcesoDisciplinario->getEmpleadoRel()->getCargoRel()->getNombre();
        $sustitucion4 = $arProcesoDisciplinario->getSuspension();
        $sustitucion5 = $arConfiguracion->getNombreEmpresa();
        $sustitucion6 = $arProcesoDisciplinario->getAsunto();
        $sustitucion7 = $arProcesoDisciplinario->getAsunto();
        $sustitucion8 = $arProcesoDisciplinario->getDescargos();
        //$cadena = $arContenidoFormato->getContenido();
        $patron1 = '/#1/';
        $patron2 = '/#2/';
        $patron3 = '/#3/';
        $patron4 = '/#4/';
        $patron5 = '/#5/';
        $patron6 = '/#6/';
        $patron7 = '/#7/';
        $patron8 = '/#8/';
        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron3, $sustitucion3, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron4, $sustitucion4, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron5, $sustitucion5, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron6, $sustitucion6, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron7, $sustitucion7, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron8, $sustitucion8, $cadenaCambiada);
        $pdf->MultiCell(0,5, $cadenaCambiada);
    }

    public function Footer() {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
        $this->Image('imagenes/logos/piedepagina.jpg' , 65 ,208, 150 , 90,'JPG');
    }    
}

?>
