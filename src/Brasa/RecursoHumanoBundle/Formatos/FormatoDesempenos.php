<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoDesempenos extends \FPDF_FPDF {
    public static $em;

    public static $codigoDesempeno;

    public function Generar($miThis, $codigoDesempeno) {
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoDesempeno = $codigoDesempeno;
        $pdf = new FormatoDesempenos();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Desempeno.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',10);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {

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
        $this->SetFillColor(200, 200, 200);
        $this->Cell(90, 20, utf8_decode("GESTIÓN DEL DESEMPEÑO"), 1, 0, 'C', 1); //cuardo mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(60, 40);
        $this->Cell(90, 10, utf8_decode(" "), 1, 0, 'C', 1); //cuardo mitad abajo
        $this->SetXY(150, 10);
        $this->Cell(53, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(150, 20);
        $this->Cell(53, 20, utf8_decode("Código FOR-GH-16.02"), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(150, 40);
        $this->Cell(53, 5, utf8_decode("Versión 02"), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(150, 45);
        $this->Cell(53, 5, "Fecha Marzo de 2014 ", 1, 0, 'C', 1); //cuadro derecho abajo 2

        //Restauracion de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {

        $arDesempeno = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno();
        $arDesempeno = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find(self::$codigoDesempeno);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetXY(10, 60);
        //titulo
        $pdf->Cell(193, 5, utf8_decode("EVALUACIÓN DEL COLABORADOR"), 1, 0, 'C',1);
        //linea 1
        $pdf->SetXY(10, 65);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(42, 5, utf8_decode("CÓDIGO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(54, 5, $arDesempeno->getCodigoDesempenoPk(), 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(48, 5, utf8_decode("FECHA:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(48, 5, $arDesempeno->getFecha()->format('Y-m-d'), 1, 0, 'L',1);
        //linea 2
        $pdf->SetXY(10, 70);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(48, 5, utf8_decode("CÓDIGO EMPLEADO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(48, 5, $arDesempeno->getCodigoEmpleadoFk(), 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(48, 5, utf8_decode("IDENTIFICACIÓN:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(48, 5, $arDesempeno->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L',1);
        //linea 3
        $pdf->SetXY(10, 75);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(48, 5, utf8_decode("EMPLEADO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(48, 5, $arDesempeno->getEmpleadoRel()->getNombreCorto(), 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(48, 5, utf8_decode("DEPENDECIA DEL EVALUADO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(48, 5, $arDesempeno->getDependenciaEvaluado(), 1, 0, 'L',1);
        //linea 4
        $pdf->SetXY(10, 80);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(48, 5, utf8_decode("CARGO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(48, 5, $arDesempeno->getCargoRel()->getNombre(), 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(48, 5, utf8_decode("JEFE QUE EVALUA:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(48, 5, $arDesempeno->getJefeEvalua(), 1, 0, 'L',1);
        //linea 6
        $pdf->SetXY(10, 85);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(48, 5, utf8_decode("CARGO JEFE QUE EVALUA:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(48, 5, $arDesempeno->getCargoJefeEvalua(), 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(48, 5, utf8_decode("DEPENDENCIA DEL QUE EVALUA:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Cell(48, 5, $arDesempeno->getDependenciaEvalua(), 1, 0, 'L',1);
        //linea 7
        $pdf->SetXY(10, 90);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(48, 5, utf8_decode("ESTADO AUTORIZADO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        if ($arDesempeno->getEstadoAutorizado() == 1){
            $estadoAutorizado = "SI";
        }else{
            $estadoAutorizado = "NO";
        }
        $pdf->Cell(48, 5, $estadoAutorizado, 1, 0, 'L',1);
        $pdf->SetFont('Arial','B',7.5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(48, 5, utf8_decode("ESTADO CERRADO:"), 1, 0, 'L',1);
        $pdf->SetFont('Arial','',7.5);
        $pdf->SetFillColor(272, 272, 272);
        if ($arDesempeno->getEstadoCerrado() == 1){
            $estadoCerrado = "SI";
        }else{
            $estadoCerrado = "NO";
        }
        $pdf->Cell(48, 5, $estadoCerrado, 1, 0, 'L',1);
    }

    public function Footer() {

        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');;
    }
}

?>
