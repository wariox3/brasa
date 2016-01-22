<?php
namespace Brasa\TurnoBundle\Formatos;
class FormatoProgramacion extends \FPDF_FPDF {
    public static $em;
    
    public static $codigoProgramacion;
    
    public function Generar($miThis, $codigoProgramacion) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoProgramacion = $codigoProgramacion;
        $pdf = new FormatoProgramacion('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Programacion$codigoProgramacion.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("PROGRAMACION DE TURNOS"), 0, 0, 'C', 1);
        $this->SetXY(53, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);        
        
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = self::$em->getRepository('BrasaTurnoBundle:TurProgramacion')->find(self::$codigoProgramacion);        
        
        $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalles = self::$em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => self::$codigoProgramacion));
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        
        $intY = 40;
        $this->SetFillColor(272, 272, 272); 
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "NUMERO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arProgramacion->getCodigoProgramacionPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arProgramacion->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);       

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "NIT:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arProgramacion->getClienteRel()->getTerceroRel()->getNit(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "PERIODO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arProgramacion->getFecha()->format('Y F'), 1, 0, 'L', 1);               
        
        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "CLIENTE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arProgramacion->getClienteRel()->getTerceroRel()->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'CONTACTO:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arProgramacion->getClienteRel()->getTerceroRel()->getContactoCliente(), 1, 0, 'L', 1);
        
        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "DIRECCION:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arProgramacion->getClienteRel()->getTerceroRel()->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'CARGO:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 16);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "CIUDAD:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, "", 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'DEPARTAMENTO:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);        

        $this->SetXY(10, $intY + 20);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "TELEFONO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arProgramacion->getClienteRel()->getTerceroRel()->getTelefono(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'CELULAR:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arProgramacion->getClienteRel()->getTerceroRel()->getCelular(), 1, 0, 'L', 1);                
        
        $this->SetXY(10, $intY + 24);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "EMAIL:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arProgramacion->getClienteRel()->getTerceroRel()->getEmail(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, '' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);                
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('COD', 'RECURSO', 'PUESTO','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 30, 30, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5);
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
        $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalles = self::$em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => self::$codigoProgramacion));
        $arrTurnos = array();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
                
        foreach ($arProgramacionDetalles as $arProgramacionDetalle) {                                       
            $pdf->Cell(10, 4, $arProgramacionDetalle->getCodigoProgramacionDetallePk(), 1, 0, 'L');
            $pdf->Cell(30, 4, substr($arProgramacionDetalle->getRecursoRel()->getNombreCorto(), 0,17), 1, 0, 'L');
            $pdf->Cell(30, 4, substr($arProgramacionDetalle->getPuestoRel()->getNombre(), 0, 17), 1, 0, 'L');
            
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia1(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia2(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia3(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia4(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia5(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia6(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia7(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia8(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia9(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia10(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia11(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia12(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia13(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia14(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia15(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia16(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia17(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia18(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia19(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia20(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia21(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia22(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia23(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia24(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia25(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia26(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia27(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia28(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia29(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia30(), 1, 0, 'L');
            $pdf->Cell(5, 4, $arProgramacionDetalle->getDia31(), 1, 0, 'L');
            $pdf->Ln();            
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->Ln();
        $pdf->Cell(15, 4, "CODIGO", 1, 0, 'L');
        $pdf->Cell(30, 4, "NOMBRE", 1, 0, 'L');
        $pdf->Cell(15, 4, "H. DESDE", 1, 0, 'L');
        $pdf->Cell(15, 4, "H. HASTA", 1, 0, 'L');
        
        $pdf->Ln();
        $arrTurnos = $this->turnos($arProgramacionDetalles);        
        foreach ($arrTurnos as $arrTurno) {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = self::$em->getRepository('BrasaTurnoBundle:TurTurno')->find($arrTurno['turno']);            
            $pdf->Cell(15, 4, $arrTurno['turno'], 1, 0, 'L');
            $pdf->Cell(30, 4, $arTurno->getNombre(), 1, 0, 'L');
            $pdf->Cell(15, 4, $arTurno->getHoraDesde()->format('H:s'), 1, 0, 'L');
            $pdf->Cell(15, 4, $arTurno->getHoraHasta()->format('H:s'), 1, 0, 'L');
            $pdf->Ln();
        }
    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
    
    private function turnos($arProgramacionDetalles) {
        $arrTurnos = array();
        foreach ($arProgramacionDetalles as $arProgramacionDetalle){
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia1()); 
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia2()); 
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia3()); 
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia4()); 
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia5());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia6());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia7());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia8());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia9());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia10());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia11());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia12());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia13());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia14());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia15());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia16());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia17());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia18());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia19());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia20());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia21());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia22());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia23());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia24());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia25());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia26());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia27());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia28());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia29());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia30());
            $arrTurnos[] = array('turno' => $arProgramacionDetalle->getDia31());
            $i= 0;
        }
        $arrTurnosResumen = array();
        foreach ($arrTurnos as $arrTurno) {            
            $boolExiste = FALSE;
            foreach ($arrTurnosResumen as $arrTurnoResumen) {
                if($arrTurno['turno'] == $arrTurnoResumen['turno']) {
                    $boolExiste = TRUE;
                }
            }                   
            if($boolExiste == FALSE) {
                if($arrTurno['turno'] != "") {
                    $arrTurnosResumen[] = array('turno' => $arrTurno['turno']);
                }                
            }            
        }
        return $arrTurnosResumen;
    }
}

?>
