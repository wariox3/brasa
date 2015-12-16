<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ProcesoGenerarSoportePagoController extends Controller
{
    var $strListaDql = "";
    var $strListaDqlDetalle = "";

    public function generarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioGenerar();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {
                $strSql = "DELETE FROM tur_soporte_pago WHERE estado_cerrado = 0";           
                $em->getConnection()->executeQuery($strSql);                 
                $strSql = "DELETE FROM tur_soporte_pago_detalle WHERE estado_cerrado = 0";           
                $em->getConnection()->executeQuery($strSql);  
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }
            if ($form->get('BtnCerrar')->isClicked()) {
                $strSql = "UPDATE tur_soporte_pago SET estado_cerrado = 1 WHERE estado_cerrado = 0";           
                $em->getConnection()->executeQuery($strSql);                 
                $strSql = "UPDATE tur_soporte_pago_detalle SET estado_cerrado = 1 WHERE estado_cerrado = 0";           
                $em->getConnection()->executeQuery($strSql);  
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }
            if ($form->get('BtnExcel')->isClicked()) {
                //$this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }                        
            if ($form->get('BtnGenerar')->isClicked()) {
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                $intDiaInicial = $dateFechaDesde->format('j');
                $intDiaFinal = $dateFechaHasta->format('j');
                $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->periodo($dateFechaDesde->format('Y/m/') . "01",$dateFechaHasta->format('Y/m/') . "31");
                foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
                    $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($dateFechaDesde->format('Y-m-d'), $dateFechaHasta->format('Y-m-d'));
                    //if($arProgramacionDetalle->getCodigoRecursoFk() != 2) {
                        for($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {
                        $strFecha = $dateFechaDesde->format('Y/m/') . $i;
                        $dateFecha = date_create($strFecha);
                        $nuevafecha = strtotime ( '+1 day' , strtotime ( $strFecha ) ) ;
                        $dateFecha2 = date ( 'Y/m/j' , $nuevafecha );
                        $dateFecha2 = date_create($dateFecha2);
                        $boolFestivo = $this->festivo($arFestivos, $dateFecha);
                        $boolFestivo2 = $this->festivo($arFestivos, $dateFecha2);
                        $arTurno = NULL;
                        if($i == 1) {
                            $arTurno = $arProgramacionDetalle->getDia1();
                        }
                        if($i == 2) {
                            $arTurno = $arProgramacionDetalle->getDia2();
                        }
                        if($i == 3) {
                            $arTurno = $arProgramacionDetalle->getDia3();
                        }
                        if($i == 4) {
                            $arTurno = $arProgramacionDetalle->getDia4();
                        }
                        if($i == 5) {
                            $arTurno = $arProgramacionDetalle->getDia5();
                        }
                        if($i == 6) {
                            $arTurno = $arProgramacionDetalle->getDia6();
                        }
                        if($i == 7) {
                            $arTurno = $arProgramacionDetalle->getDia7();
                        }
                        if($i == 8) {
                            $arTurno = $arProgramacionDetalle->getDia8();
                        }
                        if($i == 9) {
                            $arTurno = $arProgramacionDetalle->getDia9();
                        }
                        if($i == 10) {
                            $arTurno = $arProgramacionDetalle->getDia10();
                        }
                        if($i == 11) {
                            $arTurno = $arProgramacionDetalle->getDia11();
                        }
                        if($i == 12) {
                            $arTurno = $arProgramacionDetalle->getDia12();
                        }
                        if($i == 13) {
                            $arTurno = $arProgramacionDetalle->getDia13();
                        }
                        if($i == 14) {
                            $arTurno = $arProgramacionDetalle->getDia14();
                        }
                        if($i == 15) {
                            $arTurno = $arProgramacionDetalle->getDia15();
                        }
                        if($arTurno) {
                            $this->insertarSoportePago($arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $arTurno, $dateFecha, $boolFestivo, $boolFestivo2);
                        }
                    }                        
                    //}

                }                
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->resumen($dateFechaDesde, $dateFechaHasta);
            }
        }
        $arSoportesPago = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        $arSoportesPagoDetalle = $paginator->paginate($em->createQuery($this->strListaDqlDetalle), $request->query->get('page', 1), 20);        
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:generar.html.twig', array(
            'arSoportesPagos' => $arSoportesPago,
            'arSoportesPagosDetalles' => $arSoportesPagoDetalle,
            'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurSoportePago')->listaDql();
        $this->strListaDqlDetalle =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->listaDql();
    }

    private function formularioGenerar() {
        $form = $this->createFormBuilder()
            ->add('fechaDesde', 'date', array('data' => new \DateTime('now')))
            ->add('fechaHasta', 'date', array('data' => new \DateTime('now')))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->add('BtnCerrar', 'submit', array('label'  => 'Cerrar'))                
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))                        
            ->getForm();
        return $form;
    }

    private function insertarSoportePago ($arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $codigoTurno, $dateFecha, $boolFestivo, $boolFestivo2) {
        $em = $this->getDoctrine()->getManager();
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
        $intDias = 0;
        $intDescanso = 0;
        $intTotalHorasDiurnas = 0;
        $intTotalHorasNocturnas = 0;
        $intTotalHorasFestivasDiurnas = 0;
        $intTotalHorasFestivasNocturnas = 0;
        $intTotalHorasExtrasOrinariasDiurnas = 0;
        $intTotalHorasExtrasOrinariasNocturnas = 0;
        $intTotalHorasExtrasFestivasDiurnas = 0;
        $intTotalHorasExtrasFestivasNocturnas = 0;

        $intHoraInicio = $arTurno->getHoraDesde()->format('G');
        $intHoraFinal = $arTurno->getHoraHasta()->format('G');
        $diaSemana = $dateFecha->format('N');
        if($arTurno->getDescanso() == 1){
            $intDescanso = 1;
        }
        if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
            $intDias += 1;
            if($diaSemana == 7) {
                $boolFestivo = 1;
            }
            if($intHoraInicio < $intHoraFinal){  
                if($boolFestivo == 0) {
                    $intHorasExtras = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);
                    $intHorasDiurnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 6, 22);
                    $intHorasExtras2 = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 22, 24);
                    $intTotalHorasDiurnas = $intHorasDiurnas;
                    $intTotalHorasExtrasOrinariasNocturnas = $intHorasExtras + $intHorasExtras2;
                    if($intHorasDiurnas > 8) {
                        $intTotalHorasDiurnas = 8;
                        $intTotalHorasExtrasOrinariasDiurnas = $intHorasDiurnas - 8;
                    }                    
                } else {                    
                    $intHorasFestivasDiurnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 6, 22);                    
                    $intTotalHorasFestivasDiurnas = $intHorasFestivasDiurnas;                    
                    if($intHorasFestivasDiurnas > 8) {
                        $intTotalHorasFestivasDiurnas = 8;
                        $intTotalHorasExtrasFestivasDiurnas = $intHorasFestivasDiurnas - 8;
                    }                    
                }
            } else {
                if($boolFestivo == 0) {
                    $intHorasDiurnas = $this->calcularTiempo($intHoraInicio, 24, 6, 22);
                    $intHorasNocturnas = $this->calcularTiempo($intHoraInicio, 24, 22, 24);                
                    $intTotalHorasDiurnas = $intHorasDiurnas;
                    $intTotalHorasNocturnas += $intHorasNocturnas;

                    if($boolFestivo2 == 1 || $diaSemana == 6) {
                        $intHorasFestivasNocturnas = $this->calcularTiempo(0, $intHoraFinal, 0, 2);   
                        $intHorasExtrasFestivasNocturnas = $this->calcularTiempo(0, $intHoraFinal, 2, 6);   
                        $intTotalHorasFestivasNocturnas += $intHorasFestivasNocturnas;
                        $intTotalHorasExtrasFestivasNocturnas += $intHorasExtrasFestivasNocturnas;
                    } else {
                        $intHorasNocturnas = $this->calcularTiempo(0, $intHoraFinal, 0, 6);      
                        $intTotalHorasNocturnas += $intHorasNocturnas;
                    }                       
                } else {
                    $intHorasFestivasDiurnas = $this->calcularTiempo($intHoraInicio, 24, 18, 22);
                    $intHorasFestivasNocturnas = $this->calcularTiempo($intHoraInicio, 24, 22, 24);                                    
                    $intTotalHorasFestivasDiurnas += $intHorasFestivasDiurnas;
                    $intTotalHorasFestivasNocturnas += $intHorasFestivasNocturnas;

                    if($boolFestivo2 == 1 || $diaSemana == 6) {
                        $intHorasFestivasNocturnas = $this->calcularTiempo(0, $intHoraFinal, 0, 2);   
                        $intHorasExtrasFestivasNocturnas = $this->calcularTiempo(0, $intHoraFinal, 2, 6);   
                        $intTotalHorasFestivasNocturnas += $intHorasFestivasNocturnas;
                        $intTotalHorasExtrasFestivasNocturnas += $intHorasExtrasFestivasNocturnas;
                    } else {
                        $intHorasNocturnas = $this->calcularTiempo(0, $intHoraFinal, 0, 2);
                        $intHorasExtrasNocturnas = $this->calcularTiempo(0, $intHoraFinal, 2, 6);
                        $intTotalHorasNocturnas += $intHorasNocturnas;
                        $intTotalHorasExtrasOrinariasNocturnas += $intHorasExtrasNocturnas;
                    }                    
                }             
            }
            
        }


        $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
        $arSoportePagoDetalle->setRecursoRel($arProgramacionDetalle->getRecursoRel());
        $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
        $arSoportePagoDetalle->setFecha($dateFecha);
        $arSoportePagoDetalle->setTurnoRel($arTurno);
        $arSoportePagoDetalle->setDescanso($intDescanso);
        $arSoportePagoDetalle->setDias($intDias);
        $arSoportePagoDetalle->setHoras($arTurno->getHoras());        
        $arSoportePagoDetalle->setHorasDiurnas($intTotalHorasDiurnas);
        $arSoportePagoDetalle->setHorasNocturnas($intTotalHorasNocturnas);
        $arSoportePagoDetalle->setHorasFestivasDiurnas($intTotalHorasFestivasDiurnas);
        $arSoportePagoDetalle->setHorasFestivasNocturnas($intTotalHorasFestivasNocturnas);        
        $arSoportePagoDetalle->setHorasExtrasOrdinariasDiurnas($intTotalHorasExtrasOrinariasDiurnas);
        $arSoportePagoDetalle->setHorasExtrasOrdinariasNocturnas($intTotalHorasExtrasOrinariasNocturnas);
        $arSoportePagoDetalle->setHorasExtrasFestivasDiurnas($intTotalHorasExtrasFestivasDiurnas);
        $arSoportePagoDetalle->setHorasExtrasFestivasNocturnas($intTotalHorasExtrasFestivasNocturnas);
        $em->persist($arSoportePagoDetalle);
    }

    public function festivo($arFestivos, $dateFecha) {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }

    private function calcularTiempo($intInicial, $intFinal, $intParametroInicio, $intParametroFinal) {
        $intHoras = 0;
        $intHoraIniciaTemporal = 0;
        $intHoraTerminaTemporal = 0;
        if($intInicial < $intParametroInicio) {
            $intHoraIniciaTemporal = $intParametroInicio;
        } else {
            $intHoraIniciaTemporal = $intInicial;
        }
        if($intFinal > $intParametroFinal) {
            if($intInicial > $intParametroFinal) {
                $intHoraTerminaTemporal = $intInicial;
            } else {
                $intHoraTerminaTemporal = $intParametroFinal;
            }
        } else {
            if($intFinal > $intParametroInicio) {
                $intHoraTerminaTemporal = $intFinal;
            } else {
                $intHoraTerminaTemporal = $intParametroInicio;
            }
        }
        $intHoras = $intHoraTerminaTemporal - $intHoraIniciaTemporal;
        return $intHoras;
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();        
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'RECURSO')
                    ->setCellValue('C1', 'DESDE')
                    ->setCellValue('D1', 'HASTA')
                    ->setCellValue('E1', 'DIAS')
                    ->setCellValue('F1', 'DESCANSO')
                    ->setCellValue('G1', 'HD')
                    ->setCellValue('H1', 'HN')
                    ->setCellValue('I1', 'HFD')
                    ->setCellValue('J1', 'HFN')                
                    ->setCellValue('K1', 'HEOD')
                    ->setCellValue('L1', 'HEON')
                    ->setCellValue('M1', 'HEFD')
                    ->setCellValue('N1', 'HEFN');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportesPago = $query->getResult();

        foreach ($arSoportesPago as $arSoportePago) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSoportePago->getCodigoSoportePagoPk())
                    ->setCellValue('B' . $i, $arSoportePago->getRecursoRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arSoportePago->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arSoportePago->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arSoportePago->getDias())
                    ->setCellValue('F' . $i, $arSoportePago->getDescanso())
                    ->setCellValue('G' . $i, $arSoportePago->getHorasDiurnas())
                    ->setCellValue('H' . $i, $arSoportePago->getHorasNocturnas())
                    ->setCellValue('I' . $i, $arSoportePago->getHorasFestivasDiurnas())
                    ->setCellValue('J' . $i, $arSoportePago->getHorasFestivasNocturnas())                    
                    ->setCellValue('K' . $i, $arSoportePago->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('L' . $i, $arSoportePago->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('M' . $i, $arSoportePago->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('N' . $i, $arSoportePago->getHorasExtrasFestivasNocturnas());

            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('SoportePago');
        
        $objPHPExcel->createSheet(2)->setTitle('Detalle')       
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'RECURSO')
                    ->setCellValue('C1', 'TURNO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'DIAS')
                    ->setCellValue('F1', 'DESCANSO')
                    ->setCellValue('G1', 'HD')
                    ->setCellValue('H1', 'HN')
                    ->setCellValue('I1', 'HFD')
                    ->setCellValue('J1', 'HFN')                
                    ->setCellValue('K1', 'HEOD')
                    ->setCellValue('L1', 'HEON')
                    ->setCellValue('M1', 'HEFD')
                    ->setCellValue('N1', 'HEFN');
        
        $i = 2;
        $query = $em->createQuery($this->strListaDqlDetalle);
        $arSoportesPagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
        $arSoportesPagoDetalle = $query->getResult();

        foreach ($arSoportesPagoDetalle as $arSoportePagoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('A' . $i, $arSoportePagoDetalle->getCodigoSoportePagoDetallePk())
                    ->setCellValue('B' . $i, $arSoportePagoDetalle->getRecursoRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arSoportePagoDetalle->getCodigoTurnoFk())
                    ->setCellValue('D' . $i, $arSoportePagoDetalle->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arSoportePagoDetalle->getDias())
                    ->setCellValue('F' . $i, $arSoportePagoDetalle->getDescanso())
                    ->setCellValue('G' . $i, $arSoportePagoDetalle->getHorasDiurnas())
                    ->setCellValue('H' . $i, $arSoportePagoDetalle->getHorasNocturnas())
                    ->setCellValue('I' . $i, $arSoportePagoDetalle->getHorasFestivasDiurnas())
                    ->setCellValue('J' . $i, $arSoportePagoDetalle->getHorasFestivasNocturnas())                    
                    ->setCellValue('K' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('L' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('M' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('N' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasNocturnas());

            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('Detalle');        
        
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SoportesPagoTurnos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }        
}