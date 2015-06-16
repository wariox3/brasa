<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoExamenDetalle extends \FPDF_FPDF {
    public static $em;
    public static $codigoExamen;
    public function Generar($miThis, $codigoExamen) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoExamen = $codigoExamen;
        $pdf = new FormatoExamenDetalle();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("ExamenDetalle$codigoExamen.pdf", 'D');        
        
    } 
    public function Header() {
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find(self::$codigoExamen);
        $codigoEntidadExamen = $arExamen->getEntidadExamenRel()->getCodigoEntidadExamenPk();
        $direccionEntidad = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen();
        $direccionEntidad = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadExamen')->find($codigoEntidadExamen);
        $direccion = $direccionEntidad->getDireccion();
        $telefono = $direccionEntidad->getTelefono();
        $arExamenDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
        $arExamenDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => self::$codigoExamen));
        $precioTipoExamen = 0;
        $totalExamen = 0;
        foreach ($arExamenDetalles as $arExamenDetalle) {
           $precioTipoExamen = $arExamenDetalle->getVrPrecio();
           $totalExamen += $precioTipoExamen;
        }
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //$this->Image('imagenes/logos/LogoCotrascal.jpg', 10, 10, 35, 17);        
        $this->SetXY(10, 20);
        $this->Cell(190, 10, "DATOS EMPLEADO " , 1, 0, 'L', 1);
        $this->SetXY(10, 30);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "DOCUMENTO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, $arExamen->getIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "NOMBRE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(100, 6, $arExamen->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetXY(10, 35);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, $arExamen->getFecha()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "CENTRO COSTOS:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(100, 6, $arExamen->getCentroCostoRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetXY(10, 40);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "TOTAL:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 6, number_format($totalExamen, 2, '.', ',') , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 6, "ENTIDAD EXAMEN:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(100, 6, $arExamen->getEntidadExamenRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 5, "DIRECCION:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(100, 5, $direccion , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 5, "TELEFONO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(30, 5, $telefono , 1, 0, 'L', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('COD', 'TIPO', 'TIPO EXAMEN', 'PRECIO', 'APROBADO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 10, 130, 20, 20);
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
        $arExamenDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
        $arExamenDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => self::$codigoExamen));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arExamenDetalles as $arExamenDetalle) {            
            $pdf->Cell(10, 4, $arExamenDetalle->getCodigoExamenDetallePk(), 1, 0, 'L');
            $pdf->Cell(10, 4, $arExamenDetalle->getExamenTipoRel()->getCodigoExamenTipoPk(), 1, 0, 'L');
            $pdf->Cell(130, 4, $arExamenDetalle->getExamenTipoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(20, 4, number_format($arExamenDetalle->getVrPrecio(), 2, '.', ','), 1, 0, 'R');
            if ($arExamenDetalle->getEstadoAprobado() == 1)
            {    
                $pdf->Cell(20, 4, "SI", 1, 0, 'L');
            }
            else{
                $pdf->Cell(20, 4, "NO", 1, 0, 'L');
            }
                
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
