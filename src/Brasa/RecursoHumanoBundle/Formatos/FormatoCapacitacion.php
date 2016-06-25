<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoCapacitacion extends \FPDF_FPDF {
    public static $em;

    public static $codigoCapacitacion;

    public function Generar($miThis, $codigoCapacitacion) {
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoCapacitacion = $codigoCapacitacion;
        $pdf = new FormatoCapacitacion();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("ActaCapacitacion.pdf", 'D');

    }

    public function Header() {
        $arCapacitacion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
        $arCapacitacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacion')->find(self::$codigoCapacitacion);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(16);
        $this->SetFillColor(200, 200, 200);
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
        $this->Cell(90, 20, utf8_decode("ACTA DE CAPACITACION"), 1, 0, 'C', 1); //cuardo mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(60, 40);
        $this->Cell(90, 10, utf8_decode(" "), 1, 0, 'C', 1); //cuardo mitad abajo
        $this->SetXY(150, 10);
        $this->Cell(53, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(150, 20);
        $this->Cell(53, 20, utf8_decode($arContenidoFormatoA->getCodigoFormatoIso()), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(150, 40);
        $this->Cell(53, 5, utf8_decode($arContenidoFormatoA->getVersion()), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(150, 45);
        $this->Cell(53, 5, $arContenidoFormatoA->getFechaVersion()->format('Y-m-d'), 1, 0, 'C', 1); //cuadro derecho abajo 2
              
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(10);
        $header = array(utf8_decode('NRO'), 'IDENTIFICACION', 'NOMBRE', utf8_decode('CARGO'),'ASISTIO', 'FIRMA');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 25, 60, 60,12,26);
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
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $arCapacitacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
        $arCapacitacionDetalle = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->findBy(array('codigoCapacitacionFk' => self::$codigoCapacitacion));
        
        foreach ($arCapacitacionDetalle as $arCapacitacionDetalle) {
            $asistencia = "NO";
            if ($arCapacitacionDetalle->getAsistencia() == 1){
                $asistencia = "SI";
            }
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(10, 8, '1', 1, 0, 'L');
            $pdf->Cell(25, 8, $arCapacitacionDetalle->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(60, 8, $arCapacitacionDetalle->getNombreCorto(), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(60, 8, $arCapacitacionDetalle->getEmpleadoRel()->getCargoRel()->getNombre(), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(12, 8, $asistencia, 1, 0, 'L');
            $pdf->Cell(26, 8, '', 1, 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }

    }

    public function Footer() {

        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');;
    }
}

?>
