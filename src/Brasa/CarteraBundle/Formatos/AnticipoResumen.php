<?php
namespace Brasa\CarteraBundle\Formatos;
class AnticipoResumen extends \FPDF_FPDF {
    public static $em;
    
    public static $fechaDesde;
    public static $fechaHasta;
    
    public function Generar($miThis, $fechaDesde, $fechaHasta) {        
        ob_clean();
        $em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$fechaDesde = $fechaDesde;
        self::$fechaHasta = $fechaHasta;
        $pdf = new AnticipoResumen();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("AnticipoResumen.pdf", 'D');        
        
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
        $this->Cell(150, 7, utf8_decode("RESUMEN RECIBOS ANTICIPOS"), 0, 0, 'C', 1);
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
        
        //$arAnticipo = new \Brasa\CarteraBundle\Entity\CarAnticipo();
        //$arAnticipo = self::$em->getRepository('BrasaCarteraBundle:CarAnticipo')->find(self::$codigoAnticipo);        
        
        //$arAnticipoDetalles = new \Brasa\CarteraBundle\Entity\CarAnticipoDetalle();
        //$arAnticipoDetalles = self::$em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->findBy(array('codigoAnticipoFk' => self::$codigoAnticipo));
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        
        $intY = 40;
        //linea 1
        $this->SetFillColor(272, 272, 272); 
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("FECHA DESDE:") , 1, 0, 'L', 1);
        $this->SetFont('Arial',  '',8);
        $this->Cell(52, 5, self::$fechaDesde, 1, 0, 'L', 1);
       
        //linea 2
        $this->SetXY(10, $intY+5);
        $this->SetFont('Arial','B',8);
        $this->Cell(26, 5, utf8_decode("FECHA HASTA:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(52, 5, self::$fechaHasta, 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);            
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('CUENTA', 'NUMERO', 'TOTAL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7.5);

        //creamos la cabecera de la tabla.
        $w = array(60, 25, 30);
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
        $strSql = "SELECT
            
            gen_cuenta.nombre AS cuenta, 
            COUNT(car_anticipo.codigo_anticipo_pk) AS numeroAnticipos, 
            SUM(car_anticipo.vr_total) AS vrTotal
            FROM car_anticipo  
            
            LEFT JOIN gen_cuenta ON car_anticipo.codigo_cuenta_fk = gen_cuenta.codigo_cuenta_pk 
            WHERE car_anticipo.fecha >= '" . self::$fechaDesde . "' AND car_anticipo.fecha <= '" . self::$fechaHasta . "' 
            GROUP BY car_anticipo.codigo_cuenta_fk";
        $connection = self::$em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $arAnticiposResumen = $statement->fetchAll();   
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7); 
        $total = 0;
        foreach ($arAnticiposResumen as $registro) {
            //$pdf->Cell(30, 4, $registro['tipo'], 1, 0, 'L');
            $pdf->Cell(60, 4, $registro['cuenta'], 1, 0, 'L');
            $pdf->Cell(25, 4, $registro['numeroAnticipos'], 1, 0, 'L');
            $pdf->Cell(30, 4, number_format($registro['vrTotal'], 2, '.', ','), 1, 0, 'R');
            $total += $registro['vrTotal'];
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->Cell(30, 4, '', 0, 0, 'L');
        $pdf->Cell(40, 4, '', 0, 0, 'L');
        $pdf->Cell(15, 4, '', 0, 0, 'L');
        $pdf->Cell(30, 4, number_format($total, 2, '.', ','), 1, 0, 'R'); 
        $pdf->Ln();
        $pdf->Ln();

        $header = array('NUMERO', 'FECHA', 'CUENTA', 'CLIENTE', 'DCTO', 'AJUSTE', 'RTEICA', 'RTEIVA', 'RTEFTE', 'TOTAL');
        $pdf->SetFillColor(236, 236, 236);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.2);
        $pdf->SetFont('', 'B', 6);

        //creamos la cabecera de la tabla.
        $w = array(13, 15, 30, 40, 13, 13, 13, 13, 13, 13);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Ln(4);
        $dql   = "SELECT r FROM BrasaCarteraBundle:CarAnticipo r WHERE r.fecha >='" . self::$fechaDesde . "' AND r.fecha <='" . self::$fechaHasta . "'";                
        $query = self::$em->createQuery($dql);
        $arAnticipos = $query->getResult();
        foreach ($arAnticipos as $arAnticipo) {
            //$pdf->Cell(20, 4, $arAnticipo->getAnticipoTipoRel()->getNombre(), 1, 0, 'L');                        
            $pdf->Cell(13, 4, $arAnticipo->getNumero(), 1, 0, 'L');                        
            $pdf->Cell(15, 4, $arAnticipo->getFecha()->format('Y/m/d'), 1, 0, 'L');                        
            $pdf->Cell(30, 4, $arAnticipo->getCuentaRel()->getNombre(), 1, 0, 'L');                        
            $pdf->Cell(40, 4, $arAnticipo->getClienteRel()->getNombreCorto(), 1, 0, 'L');                        
            $pdf->Cell(13, 4, number_format($arAnticipo->getVrTotalDescuento(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arAnticipo->getVrTotalAjustePeso(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arAnticipo->getVrTotalReteIca(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arAnticipo->getVrTotalReteIva(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arAnticipo->getVrTotalReteFuente(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(13, 4, number_format($arAnticipo->getVrTotalPago(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }        
    }

    public function Footer() {
        
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
