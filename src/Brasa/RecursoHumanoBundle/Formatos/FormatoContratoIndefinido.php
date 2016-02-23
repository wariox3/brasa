<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoContratoIndefinido extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoContrato;
    
    public function Generar($miThis, $codigoContrato) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoContrato = $codigoContrato;
        $pdf = new FormatoContratoIndefinido();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("ContratoIndefinido$codigoContrato.pdf", 'D');        
        
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
        
        $this->Image('imagenes/logos/logo.jpg' , 90 ,5, 40 , 20,'JPG'); //cuadro para el logo
        
        $this->Ln(20);
    }

    public function Body($pdf) {
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find(self::$codigoContrato);        
        $arContenidoFormato = new \Brasa\RecursoHumanoBundle\Entity\RhuContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->find($arContrato->getCodigoContratoTipoFk());        
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(185, 7, utf8_decode($arContenidoFormato->getTitulo()), 0, 0, 'C');
        $pdf->SetXY(10, 37);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(190, 7, utf8_decode("N° ") . $arContrato->getCodigoContratoPk(), 0, 0, 'R');
        $pdf->SetXY(10, 40);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 7, utf8_decode($arConfiguracion->getNitEmpresa()), 0, 0, 'C');
        $pdf->SetXY(10, 47);
        $pdf->Cell(190, 7, utf8_decode("REGIONAL ANTIOQUIA"), 0, 0, 'C');
        //LINEA 1 CUADRO
        $pdf->SetXY(10, 60);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(95, 7, "NIT EMPRESA:" , 1, 0, 'L', 0);
        $pdf->Cell(95, 7, $arConfiguracion->getNitEmpresa() , 1, 0, 'L', 0);
        //LINEA 2 CUADRO
        $pdf->SetXY(10, 67);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(95, 7, "NOMBRE DEL EMPLEADOR:" , 1, 0, 'L', 0);
        $pdf->Cell(95, 7, $arConfiguracion->getNombreEmpresa() , 1, 0, 'L', 0);
        //LINEA 3 CUADRO
        $pdf->SetXY(10, 74);
        $pdf->Cell(95, 7, utf8_decode("DIRECCIÓN EMPRESA:") , 1, 0, 'L', 0);
        $pdf->Cell(95, 7, utf8_decode($arConfiguracion->getDireccionEmpresa()) , 1, 0, 'L', 0);
        //LINEA 4 CUADRO
        $pdf->SetXY(10, 81);
        $pdf->Cell(95, 7, utf8_decode("IDENTIFICACIÓN EMPLEADO:") , 1, 0, 'L', 0);
        $pdf->Cell(95, 7, $arContrato->getEmpleadoRel()->getNumeroIdentificacion() , 1, 0, 'L', 0);
        //LINEA 5 CUADRO
        $pdf->SetXY(10, 88);
        $pdf->Cell(95, 7, "EMPLEADO:" , 1, 0, 'L', 0);
        $pdf->Cell(95, 7, utf8_decode($arContrato->getEmpleadoRel()->getNombreCorto()) , 1, 0, 'L', 0);                            
        //LINEA 6 CUADRO
        $pdf->SetXY(10, 95);
        $pdf->Cell(95, 7, utf8_decode("DIRECCIÓN EMPLEADO:") , 1, 0, 'L', 0);
        $pdf->Cell(95, 7, utf8_decode($arContrato->getEmpleadoRel()->getDireccion()) , 1, 0, 'L', 0);
        //LINEA 7 CUADRO
        $pdf->SetXY(10, 102);
        $pdf->Cell(95, 7, utf8_decode("FECHA INGRESO:") , 1, 0, 'L', 0);
        $pdf->Cell(95, 7, $arContrato->getFechaDesde()->format('Y-m-d') , 1, 0, 'L', 0);
        //LINEA 8 CUADRO
        $pdf->SetXY(10, 109);
        $pdf->Cell(95, 7, utf8_decode("CARGO U OFICIO QUE DESEMPEÑARA EL TRABAJADOR:") , 1, 0, 'L', 0);
        $pdf->Cell(95, 7, utf8_decode($arContrato->getCargoRel()->getNombre()) , 1, 0, 'L', 0);
        //LINEA 9 CUADRO
        $pdf->SetXY(10, 116);
        $pdf->Cell(95, 7, utf8_decode("SALARIO:") , 1, 0, 'L', 0);
        $pdf->Cell(95, 7, number_format($arContrato->getVrSalario(), 2, '.', ','), 1, 0, 'L');
        //LINEA 10 CUADRO
        $pdf->SetXY(10, 123);
        $pdf->Cell(95, 7, utf8_decode("FECHA DE INICIACIÓN DE LABORES:") , 1, 0, 'L', 0);
        $pdf->Cell(95, 7, $arContrato->getFechaDesde()->format('Y-m-d') , 1, 0, 'L', 0);
        //LINEA 11 CUADRO
        $pdf->SetXY(10, 130);
        $pdf->Cell(95, 7, utf8_decode("CIUDAD DONDE HA SIDO CONTRATADO EL TRABAJADOR:") , 1, 0, 'L', 0);
        $pdf->Cell(95, 7, number_format($arContrato->getVrSalario(), 2, '.', ','), 1, 0, 'L');
        
        $pdf->SetXY(10, 140);
        $pdf->SetFont('Arial', '', 10);  
        //se reemplaza el contenido de la tabla tipo de proceso disciplinario
        $sustitucion1 = $arContrato->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion2 = $arContrato->getEmpleadoRel()->getNombreCorto();
        $sustitucion3 = $arContrato->getEmpleadoRel()->getDireccion();
        $sustitucion4 = $arContrato->getEmpleadoRel()->getBarrio();
        $sustitucion5 = $arContrato->getEmpleadoRel()->getFechaNacimiento()->format('Y/m/d');
        $sustitucion6 = $arContrato->getEmpleadoRel()->getCiudadRel()->getNombre();
        $sustitucion7 = $arContrato->getCargoRel()->getNombre();
        $sustitucion8 = number_format($arContrato->getVrSalario(), 2,'.',',');
        $sustitucion9 = $arContrato->getCentroCostoRel()->getPeriodoPagoRel()->getNombre();
        $sustitucion10 = $arContrato->getFechaDesde()->format('Y/m/d');
        $sustitucion11 = $arContrato->getCentroCostoRel()->getCiudadRel()->getNombre();
        $sustitucion12 = $arContrato->getFechaDesde()->format('Y/m/d');
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $sustitucion12 = strftime("%d de %B de %Y", strtotime($sustitucion12));
        $sustitucion13 = $arContrato->getEmpleadoRel()->getNombreCorto();
        $sustitucion14 = $arContrato->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion15 = $arContrato->getEmpleadoRel()->getCiudadExpedicionRel()->getNombre();
        $sustitucion16 = "";
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
   
    }

    public function Footer() {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C'); 
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
