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
        $intTotalHorasDiurnas = 0;
        $intTotalHorasExtrasOrinariasDiurnas = 0;
        $intTotalHorasExtrasOrinariasNocturnas = 0;
        $intTotalHorasExtrasFestivasDiurnas = 0;
        $intTotalHorasExtrasFestivasNocturnas = 0;

        $intHoraInicio = $arTurno->getHoraDesde()->format('G');
        $intHoraFinal = $arTurno->getHoraHasta()->format('G');
        $diaSemana = $dateFecha->format('N');
        if($arTurno->getNovedad() == 0) {
            if($intHoraInicio < $intHoraFinal){
                $intHorasExtras = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);
                $intHorasDiurnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 6, 22);
                $intHorasExtras2 = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 22, 24);
                $intTotalHorasDiurnas = $intHorasDiurnas;
                $intTotalHorasExtrasOrinariasNocturnas = $intHorasExtras + $intHorasExtras2;
            } else {

                $intHorasExtras = $this->calcularTiempo($intHoraInicio, 24, 0, 6);
                $intHorasDiurnas = $this->calcularTiempo($intHoraInicio, 24, 6, 22);
                $intHorasExtras2 = $this->calcularTiempo($intHoraInicio, 24, 22, 24);

                $intHorasExtrasNueva = $this->calcularTiempo(0, $intHoraFinal, 0, 6);
                $intHorasDiurnasNueva = $this->calcularTiempo(0, $intHoraFinal, 6, 22);
                $intHorasExtras2Nueva = $this->calcularTiempo(0, $intHoraFinal, 22, 24);

                if($diaSemana >= 1 && $diaSemana <= 5) {
                    $intTotalHorasDiurnas = $intHorasDiurnas + $intHorasDiurnasNueva;
                    $intTotalHorasExtrasOrinariasDiurnas = 0;
                    $intTotalHorasExtrasOrinariasNocturnas = $intHorasExtras + $intHorasExtrasNueva + $intHorasExtras2 + $intHorasExtras2Nueva;
                    $intTotalHorasExtrasFestivasDiurnas = 0;
                    $intTotalHorasExtrasFestivasNocturnas = 0;
                    if($boolFestivo2 == 1) {
                        $intTotalHorasDiurnas = $intHorasDiurnas;
                        $intTotalHorasExtrasOrinariasDiurnas = 0;
                        $intTotalHorasExtrasOrinariasNocturnas = $intHorasExtras + $intHorasExtras2;
                        $intTotalHorasExtrasFestivasDiurnas = $intHorasDiurnasNueva;
                        $intTotalHorasExtrasFestivasNocturnas = $intHorasExtrasNueva + $intHorasExtras2Nueva;
                    }
                }
                if($diaSemana == 6) {
                    $intTotalHorasDiurnas = $intHorasDiurnas + $intHorasDiurnasNueva;
                    $intTotalHorasExtrasOrinariasDiurnas = 0;
                    $intTotalHorasExtrasOrinariasNocturnas = $intHorasExtras + $intHorasExtrasNueva;
                    $intTotalHorasExtrasFestivasDiurnas = 0;
                    $intTotalHorasExtrasFestivasNocturnas = $intHorasExtrasNueva + $intHorasExtras2Nueva;
                }

                if($diaSemana == 7) {
                    $intTotalHorasDiurnas = $intHorasDiurnasNueva;
                    $intTotalHorasExtrasOrinariasDiurnas = 0;
                    $intTotalHorasExtrasOrinariasNocturnas = $intHorasExtrasNueva + $intHorasExtras2Nueva;
                    $intTotalHorasExtrasFestivasDiurnas = $intHorasDiurnas;
                    $intTotalHorasExtrasFestivasNocturnas = $intHorasExtras + $intHorasExtras2;
                    if($boolFestivo2 == 1) {
                        $intTotalHorasDiurnas = 0;
                        $intTotalHorasExtrasOrinariasDiurnas = 0;
                        $intTotalHorasExtrasOrinariasNocturnas = 0;
                        $intTotalHorasExtrasFestivasDiurnas = $intHorasDiurnas + $intHorasDiurnasNueva;
                        $intTotalHorasExtrasFestivasNocturnas = $intHorasExtras + $intHorasExtras2 + $intHorasExtrasNueva + $intHorasExtras2Nueva;
                    }
                }
                if($boolFestivo == 1) {
                    if($diaSemana >= 1 && $diaSemana <= 5) {
                        $intTotalHorasDiurnas = $intHorasDiurnasNueva;
                        $intTotalHorasExtrasOrinariasDiurnas = 0;
                        $intTotalHorasExtrasOrinariasNocturnas = $intHorasExtrasNueva + $intHorasExtras2Nueva;
                        $intTotalHorasExtrasFestivasDiurnas = $intHorasDiurnas;
                        $intTotalHorasExtrasFestivasNocturnas = $intHorasExtras + $intHorasExtras2;
                        if($boolFestivo2 == 1) {
                            $intTotalHorasDiurnas = 0;
                            $intTotalHorasExtrasOrinariasDiurnas = 0;
                            $intTotalHorasExtrasOrinariasNocturnas = 0;
                            $intTotalHorasExtrasFestivasDiurnas = $intHorasDiurnas + $intHorasDiurnasNueva;
                            $intTotalHorasExtrasFestivasNocturnas = $intHorasExtras + $intHorasExtras2 + $intHorasExtrasNueva + $intHorasExtras2Nueva;                            
                        }
                    }
                    if($diaSemana == 6) {
                        $intTotalHorasDiurnas = 0;
                        $intTotalHorasExtrasOrinariasDiurnas = 0;
                        $intTotalHorasExtrasOrinariasNocturnas = 0;
                        $intTotalHorasExtrasFestivasDiurnas = $intHorasDiurnas + $intHorasDiurnasNueva;
                        $intTotalHorasExtrasFestivasNocturnas = $intHorasExtrasNueva + $intHorasExtras2Nueva + $intHorasExtras + $intHorasExtrasNueva;
                    }

                    if($diaSemana == 7) {
                        $intTotalHorasDiurnas = $intHorasDiurnasNueva;
                        $intTotalHorasExtrasOrinariasDiurnas = 0;
                        $intTotalHorasExtrasOrinariasNocturnas = $intHorasExtrasNueva + $intHorasExtras2Nueva;
                        $intTotalHorasExtrasFestivasDiurnas = $intHorasDiurnas;
                        $intTotalHorasExtrasFestivasNocturnas = $intHorasExtras + $intHorasExtras2;
                        if($boolFestivo2 == 1) {
                            $intTotalHorasDiurnas = 0;
                            $intTotalHorasExtrasOrinariasDiurnas = 0;
                            $intTotalHorasExtrasOrinariasNocturnas = 0;
                            $intTotalHorasExtrasFestivasDiurnas = $intHorasDiurnas + $intHorasDiurnasNueva;
                            $intTotalHorasExtrasFestivasNocturnas = $intHorasExtras + $intHorasExtras2 + $intHorasExtrasNueva + $intHorasExtras2Nueva;                            
                        }
                    }
                }
            }
        }


        $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
        $arSoportePagoDetalle->setRecursoRel($arProgramacionDetalle->getRecursoRel());
        $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
        $arSoportePagoDetalle->setFecha($dateFecha);
        $arSoportePagoDetalle->setTurnoRel($arTurno);
        $arSoportePagoDetalle->setHoras($arTurno->getHoras());
        $arSoportePagoDetalle->setHorasDiurnas($intTotalHorasDiurnas);
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
                    ->setCellValue('E1', 'HD')
                    ->setCellValue('F1', 'HEOD')
                    ->setCellValue('G1', 'HEON')
                    ->setCellValue('H1', 'HEFD')
                    ->setCellValue('I1', 'HEFN');

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
                    ->setCellValue('E' . $i, $arSoportePago->getHorasDiurnas())
                    ->setCellValue('F' . $i, $arSoportePago->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('G' . $i, $arSoportePago->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('H' . $i, $arSoportePago->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('I' . $i, $arSoportePago->getHorasExtrasFestivasNocturnas());

            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('SoportePago');
        
        $objPHPExcel->createSheet(2)->setTitle('Detalle')       
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'RECURSO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'HD')
                    ->setCellValue('E1', 'HEOD')
                    ->setCellValue('F1', 'HEON')
                    ->setCellValue('G1', 'HEFD')
                    ->setCellValue('H1', 'HEFN');
        
        $i = 2;
        $query = $em->createQuery($this->strListaDqlDetalle);
        $arSoportesPagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
        $arSoportesPagoDetalle = $query->getResult();

        foreach ($arSoportesPagoDetalle as $arSoportePagoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('A' . $i, $arSoportePagoDetalle->getCodigoSoportePagoDetallePk())
                    ->setCellValue('B' . $i, $arSoportePagoDetalle->getRecursoRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arSoportePagoDetalle->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arSoportePagoDetalle->getHorasDiurnas())
                    ->setCellValue('E' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('F' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('G' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('H' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasNocturnas());

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