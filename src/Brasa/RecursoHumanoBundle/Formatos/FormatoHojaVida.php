<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoHojaVida extends \FPDF_FPDF {
    public static $em;
    public static $codigoEmpleado;
    public function Generar($miThis, $codigoEmpleado) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoEmpleado = $codigoEmpleado;
        $pdf = new FormatoHojaVida();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Empleado$codigoEmpleado.pdf", 'D');        
        
    } 
    public function Header() {
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find(self::$codigoEmpleado);
        $this->SetFillColor(272, 272, 272);        
        $this->SetFont('Arial','b',8);
        $this->SetXY(20, 5);
        $this->Line(20, 5, 60, 5);
        $this->Line(20, 5, 20, 26);
        $this->Line(20, 26, 60, 26);
        $this->Image('imagenes/logos/logo.jpg', 22, 7, 35, 17);
        $this->SetXY(60, 5);
        //$this->Cell(40, 21, "" , 1, 0, 'C', 1);
        $this->SetFont('Arial','b',12);
        $this->Cell(90, 21, "HOJA DE VIDA EMPRESARIAL" , 1, 0, 'C', 1);
        $this->SetFont('Arial','b',8);
        $this->Cell(40, 7, "codigo:FOR-SS-02.03" , 1, 0, 'C', 1);
        $this->SetXY(150, 12);
        $this->Cell(40, 7, "Version: 03" , 1, 0, 'C', 1);
        $this->SetXY(150, 19);
        $this->Cell(40, 7, "Fecha: Abril de 2014" , 1, 0, 'C', 1);
        $this->SetXY(190, 5);
        $this->Cell(9, 7, "SS" , 1, 0, 'C', 1);
        $this->SetXY(190, 12);
        $this->Cell(9, 7, "N1" , 1, 0, 'C', 1);
        $this->SetXY(190, 19);
        $this->Cell(9, 7, "N2" , 1, 0, 'C', 1);
        
        
        $this->SetXY(164, 35);
        $this->Cell(35, 45, "foto" , 1, 0, 'C', 1);
        $this->SetXY(25, 20);
        $this->Cell(30, 35, "FECHA DE INGRESO" , 0, 0, 'L', 0);
        $this->Cell(100, 35, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 20);
        $this->Cell(100, 35, $arEmpleado->getFechaContrato()->format('Y/m/d'), 0, 0, 'C', 0);
        $this->SetXY(25, 26);
        $this->Cell(30, 40, "EMPRESA USUARIA" , 0, 0, 'L', 0);
        $this->Cell(100, 40, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 26);
        $this->SetFont('Arial','b',7);
        $this->Cell(100, 40, $arEmpleado->getCentroCostoRel()->getNombre() , 0, 0, 'C', 0);
        $this->SetXY(25, 32);
        $this->SetFont('Arial','b',8);
        $this->Cell(30, 45, "CARGO" , 0, 0, 'L', 0);
        $this->Cell(100, 45, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 32);
        $this->Cell(100, 45, $arEmpleado->getCargoDescripcion() , 0, 0, 'C', 0);
        $this->SetXY(25, 38);
        $this->Cell(30, 50, "SALARIO" , 0, 0, 'L', 0);
        $this->Cell(100, 50, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 38);
        $this->Cell(100, 50, number_format($arEmpleado->getVrSalario(), 2,'.',',') , 0, 0, 'C', 0);
        $this->SetXY(25, 44);
        $this->Cell(30, 55, "BONIFICACION" , 0, 0, 'L', 0);
        $this->Cell(100, 55, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 44);
        $this->Cell(100, 55, "0.00" , 0, 0, 'C', 0);
        $this->SetXY(25, 50);
        $this->Cell(30, 60, "RIESGO ARP" , 0, 0, 'L', 0);
        $this->Cell(100, 60, "______________________________________________________________" , 0, 0, 'C', 0);
        $this->SetXY(55, 50);
        $this->Cell(100, 60, $arEmpleado->getClasificacionRiesgoRel()->getNombre() , 0, 0, 'C', 0);
        
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find(self::$codigoEmpleado);
        $this->Ln(8);        
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);        
        $intX = 20;
        $intY = 90;
        $this->SetXY($intX, $intY);
        $this->Cell(68, 5, "APELLIDOS", 1, 0, 'C', 0);
        $this->Cell(69, 5, "NOMBRES COMPLETOS", 1, 0, 'C', 0);
        $this->Cell(42, 5, "CEDULA", 1, 0, 'C', 0);
        $this->SetXY($intX, $intY + 5);
        $this->SetFont('Arial','',8);
        $this->Cell(68, 8, $arEmpleado->getApellido1()." ".$arEmpleado->getApellido2(), 1, 0, 'C', 0);
        $this->Cell(69, 8, $arEmpleado->getNombre1()." ".$arEmpleado->getNombre2(), 1, 0, 'C', 0);
        $this->Cell(42, 8, $arEmpleado->getNumeroIdentificacion(), 1, 0, 'C', 0);
        $this->SetXY($intX, $intY + 13);
        $this->SetFont('Arial','B',8);
        $this->Cell(39, 5, "FECHA DE NACIMIENTO", 1, 0, 'C', 0);
        $this->Cell(41, 5, "CIUDAD DE NACIMIENTO", 1, 0, 'C', 0);
        $this->Cell(24, 5, "GENERO", 1, 0, 'C', 0);
        $this->Cell(33, 5, "LIBRETA MILITAR N", 1, 0, 'C', 0);
        $this->Cell(42, 5, "LUGAR DE EXPEDICION CC", 1, 0, 'C', 0);
        $this->SetXY($intX, $intY + 18);
        $this->SetFont('Arial','',8);
        $this->Cell(39, 8, $arEmpleado->getFechaNacimiento()->format('Y/m/d'), 1, 0, 'C', 1);
        $this->Cell(41, 8, "por implementar", 1, 0, 'C', 1);
        if ($arEmpleado->getCodigoSexoFk() == "F") { 
            $this->Cell(24, 8, "FEMENINO", 1, 0, 'C', 1);
        }
        else {
            $this->Cell(24, 8, "MASCULINO", 1, 0, 'C', 1);
        }
        $this->Cell(33, 8, "por implementar", 1, 0, 'C', 1);
        $this->Cell(42, 8, "por implementar", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 26);
        $this->SetFont('Arial','B',8);
        $this->Cell(39, 5, "DIRECCION DE RESIDENCIA", 1, 0, 'C', 1);
        $this->Cell(34, 5, "DEPARTAMENTO", 1, 0, 'C', 1);
        $this->Cell(31, 5, "CUIDAD", 1, 0, 'C', 1);
        $this->Cell(46, 5, "BARRIO", 1, 0, 'C', 1);
        $this->Cell(29, 5, "TELEFONO", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 31);
        $this->SetFont('Arial','',8);
        $this->Cell(39, 10, $arEmpleado->getDireccion(), 1, 0, 'C', 1);
        $this->Cell(34, 10, $arEmpleado->getCiudadRel()->getDepartamentoRel()->getNombre(), 1, 0, 'C', 1);
        $this->Cell(31, 10, $arEmpleado->getCiudadRel()->getNombre(), 1, 0, 'C', 1);
        $this->Cell(46, 10, $arEmpleado->getBarrioRel()->getNombre(), 1, 0, 'C', 1);
        $this->Cell(29, 10, $arEmpleado->getTelefono(), 1, 0, 'C', 1); 
        $this->SetXY($intX, $intY + 41);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 5, "CELULAR", 1, 0, 'C', 1);
        $this->Cell(25, 5, "PADRE FAMILIA", 1, 0, 'C', 1);
        $this->Cell(26, 5, "CABEZA FAMILIA", 1, 0, 'C', 1);
        $this->Cell(19, 5, "E.CIVIL", 1, 0, 'C', 1);
        $this->Cell(14, 5, "GRUPO S.", 1, 0, 'C', 1);
        $this->Cell(9, 5, "RH", 1, 0, 'C', 1);
        $this->Cell(56, 5, "CUENTA DE AHORROS N", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 46);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 8, $arEmpleado->getCelular(), 1, 0, 'C', 1);
        $this->Cell(25, 8, "por implementar", 1, 0, 'C', 1);
        $this->Cell(26, 8, "por implementar", 1, 0, 'C', 1);
        $this->Cell(19, 8, $arEmpleado->getEstadoCivilRel()->getNombre(), 1, 0, 'C', 1);
        $this->Cell(14, 8, $arEmpleado->getRhRel()->getTipo(), 1, 0, 'C', 1);
        $this->Cell(9, 8, $arEmpleado->getRhRel()->getTipo(), 1, 0, 'C', 1);
        $this->Cell(56, 8, $arEmpleado->getCuenta(), 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 54);
        $this->SetFont('Arial','B',8);
        $this->Cell(55, 5, "EPS", 1, 0, 'C', 1);
        $this->Cell(68, 5, "AFP", 1, 0, 'C', 1);
        $this->Cell(56, 5, "CCF", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 59);
        $this->SetFont('Arial','',8);
        $this->Cell(55, 8, $arEmpleado->getEntidadSaludRel()->getNombre(), 1, 0, 'C', 1);
        $this->Cell(68, 8, $arEmpleado->getEntidadPensionRel()->getNombre(), 1, 0, 'C', 1);
        $this->Cell(56, 8, $arEmpleado->getEntidadCajaRel()->getNombre(), 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 67);
        $this->SetFont('Arial','B',8);
        $this->Cell(39, 5, "NIVEL DE ESTUDIO", 1, 0, 'C', 1);
        $this->Cell(11, 5, "EDAD", 1, 0, 'C', 1);
        $this->Cell(60, 5, "CORREO ELECTRONICO", 1, 0, 'C', 1);
        $this->Cell(20, 5, "CAMISA", 1, 0, 'C', 1); 
        $this->Cell(20, 5, "JEANS", 1, 0, 'C', 1);
        $this->Cell(29, 5, "CALZADO", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 72);
        $this->SetFont('Arial','',8);
        $this->Cell(39, 8, "por implementar", 1, 0, 'L', 1);
        $this->Cell(11, 8, "por implementar", 1, 0, 'L', 1);
        $this->Cell(60, 8, $arEmpleado->getCorreo(), 1, 0, 'L', 1);
        $this->Cell(20, 8, "por implementar", 1, 0, 'L', 1); 
        $this->Cell(20, 8, "por implementar", 1, 0, 'L', 1);
        $this->Cell(29, 8, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 82);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, "ESTUDIOS", 1, 0, 'C', 1);
        $this->Cell(57, 6, "INSTITUCION", 1, 0, 'C', 1);
        $this->Cell(26, 6, "A. APROVADOS", 1, 0, 'C', 1);
        $this->Cell(25, 6, "CUIDAD", 1, 0, 'C', 1);
        $this->Cell(40, 6, "TITULO OBTENIDO", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 90);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, "SECUNDARIA", 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(57, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(26, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(40, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 96);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, "TECNICO", 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(57, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(26, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(40, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 102);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, "TECNOLOGIA", 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(57, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(26, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(40, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 108);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, "UNIVERSATARIO", 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(57, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(26, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(40, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 114);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, "OTROS", 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(57, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(26, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(40, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 120);
        $this->SetFont('Arial','B',8);
        $this->Cell(31, 6, "OTROS", 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(57, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(26, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(40, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 130);
        $this->SetFont('Arial','b',8);
        $this->Cell(25, 5, "PARENTESCO", 1, 0, 'L', 1);
        $this->Cell(50, 5, "NOMBRES Y APELLIDOS", 1, 0, 'C', 1);
        $this->Cell(10, 5, "EPS", 1, 0, 'C', 1);
        $this->Cell(10, 5, "CCF", 1, 0, 'C', 1);
        $this->Cell(27, 5, "FECHA NAC", 1, 0, 'C', 1);
        $this->Cell(32, 5, "OCUPACION", 1, 0, 'C', 1);
        $this->Cell(25, 5, "TELEFONO", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 135);
        $this->SetFont('Arial','B',8);
        $this->Cell(25, 6, "CONYUGE", 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(50, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(27, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(32, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 141);
        $this->SetFont('Arial','B',8);
        $this->Cell(25, 6, "MADRE", 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(50, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(27, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(32, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 147);
        $this->SetFont('Arial','B',8);
        $this->Cell(25, 6, "PADRE", 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(50, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(27, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(32, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 153);
        $this->SetFont('Arial','b',8);
        $this->Cell(59, 6, "NOMBRE BENEFICIARIOS", 1, 0, 'C', 1);
        $this->Cell(8, 6, "HIJO", 1, 0, 'C', 1);
        $this->Cell(8, 6, "HT", 1, 0, 'C', 1);
        $this->Cell(10, 6, "EPS", 1, 0, 'C', 1);
        $this->Cell(10, 6, "CCF", 1, 0, 'C', 1);
        $this->Cell(27, 6, "FECHA NAC", 1, 0, 'C', 1);
        $this->Cell(32, 6, "OCUPACION", 1, 0, 'C', 1);
        $this->Cell(25, 6, "TELEFONO", 1, 0, 'C', 1);
        $this->SetXY($intX, $intY + 159);
        $this->SetFont('Arial','',8);
        $this->Cell(59, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(27, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(32, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 165);
        $this->Cell(59, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(27, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(32, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 171);
        $this->Cell(59, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(27, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(32, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 177);
        $this->Cell(59, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(27, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(32, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 183);
        $this->Cell(59, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(27, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(32, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        $this->SetXY($intX, $intY + 189);
         $this->Cell(59, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(8, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(10, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(27, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(32, 6, "por implementar", 1, 0, 'L', 1);
        $this->Cell(25, 6, "por implementar", 1, 0, 'L', 1);
        


//creamos la cabecera de la tabla.
           
            
        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);        
    }

    public function Footer() {
        $this->SetFont('Arial','B', 9);    
        $this->SetFont('Arial','', 10);  
        
    }    
}

?>
