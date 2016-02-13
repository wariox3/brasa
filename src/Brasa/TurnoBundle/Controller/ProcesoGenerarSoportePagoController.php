<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurSoportePagoPeriodoType;
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
        if ($form->isValid()) {
            if($request->request->get('OpGenerar')) {            
                $codigoSoportePagoPeriodo = $request->request->get('OpGenerar');
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);
                $dateFechaDesde = $arSoportePagoPeriodo->getFechaDesde();
                $dateFechaHasta = $arSoportePagoPeriodo->getFechaHasta();
                $intDiaInicial = $dateFechaDesde->format('j');
                $intDiaFinal = $dateFechaHasta->format('j');
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($dateFechaDesde->format('Y-m-').'01', $dateFechaHasta->format('Y-m-').'31');
                $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->periodo($dateFechaDesde->format('Y/m/') . "01",$dateFechaHasta->format('Y/m/') . "31");
                foreach ($arProgramacionDetalles as $arProgramacionDetalle) {                    
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
                            $this->insertarSoportePago($arSoportePagoPeriodo, $arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $arTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2);
                        }
                    }                        
                    //}

                }          
                $arSoportePagoPeriodo->setEstadoGenerado(1);
                $em->persist($arSoportePagoPeriodo);
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->resumen($dateFechaDesde, $dateFechaHasta, $arSoportePagoPeriodo);                
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));
            }
            if($request->request->get('OpDeshacer')) {
                $codigoSoportePagoPeriodo = $request->request->get('OpDeshacer');
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                $arSoportePagoPeriodo->setEstadoGenerado(0);
                $em->persist($arSoportePagoPeriodo);
                $em->flush();  
                $strSql = "DELETE FROM tur_soporte_pago WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;           
                $em->getConnection()->executeQuery($strSql);                 
                $strSql = "DELETE FROM tur_soporte_pago_detalle WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;           
                $em->getConnection()->executeQuery($strSql);                
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
                
            }            
            
        }
        $dql = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->listaDql();
        $arSoportePagoPeriodos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:generar.html.twig', array(
            'arSoportePagoPeriodos' => $arSoportePagoPeriodos,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoSoportePagoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        $this->lista($codigoSoportePagoPeriodo);
        if ($form->isValid()) {
        }
        $arSoportesPago = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        $arSoportesPagoDetalle = $paginator->paginate($em->createQuery($this->strListaDqlDetalle), $request->query->get('page', 1), 20);        
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:detalle.html.twig', array(            
            'arSoportesPagos' => $arSoportesPago,
            'arSoportesPagosDetalles' => $arSoportesPagoDetalle,
            'form' => $form->createView()));
    }    
    
    public function nuevoAction($codigoSoportePagoPeriodo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        if($codigoSoportePagoPeriodo != 0) {
            $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);
        }else{
            $arSoportePagoPeriodo->setFechaDesde(new \DateTime('now'));            
            $arSoportePagoPeriodo->setFechaHasta(new \DateTime('now'));            
        }
        $form = $this->createForm(new TurSoportePagoPeriodoType, $arSoportePagoPeriodo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arSoportePagoPeriodo = $form->getData();            
            $em->persist($arSoportePagoPeriodo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                                                                              
        }
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:nuevo.html.twig', array(
            'arSoportePagoPeriodo' => $arSoportePagoPeriodo,
            'form' => $form->createView()));
    }    
    
    private function lista($codigoSoportePagoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurSoportePago')->listaDql($codigoSoportePagoPeriodo);
        $this->strListaDqlDetalle =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->listaDql($codigoSoportePagoPeriodo);
    }

    private function formularioGenerar() {
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar')) 
            ->getForm();
        return $form;
    }

    private function formularioDetalle() {
        $form = $this->createFormBuilder()
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))                        
            ->getForm();
        return $form;
    }
    
    private function insertarSoportePago ($arSoportePagoPeriodo, $arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $codigoTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2) {
        $em = $this->getDoctrine()->getManager();        
        
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
        $intDias = 0;
        $intDescanso = 0;

        $intHoraInicio = $arTurno->getHoraDesde()->format('G');
        $intHoraFinal = $arTurno->getHoraHasta()->format('G');
        $diaSemana = $dateFecha->format('N');
        $diaSemana2 = $dateFecha2->format('N');
        if($arTurno->getDescanso() == 1){
            $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
            $arSoportePagoDetalle->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
            $arSoportePagoDetalle->setRecursoRel($arProgramacionDetalle->getRecursoRel());
            $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
            $arSoportePagoDetalle->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());
            $arSoportePagoDetalle->setFecha($dateFecha);
            $arSoportePagoDetalle->setTurnoRel($arTurno);
            $arSoportePagoDetalle->setDescanso(1);
            $em->persist($arSoportePagoDetalle);            
        }
        if($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
            
            $intDias += 1;
            if($diaSemana == 7) {
                $boolFestivo = 1;
            }
            if($diaSemana2 == 7) {
                $boolFestivo2 = 1;
            }        
            $arrHoras1 = null;
            if($intHoraInicio < $intHoraFinal){  
                $arrHoras = $this->turnoHoras($intHoraInicio, $intHoraFinal, $boolFestivo, 0);
            } else {
                $arrHoras = $this->turnoHoras($intHoraInicio, 24, $boolFestivo, 0);
                $arrHoras1 = $this->turnoHoras(0, $intHoraFinal, $boolFestivo2, $arrHoras['horas']);                 
            }
            $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
            $arSoportePagoDetalle->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
            $arSoportePagoDetalle->setRecursoRel($arProgramacionDetalle->getRecursoRel());
            $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
            $arSoportePagoDetalle->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());            
            $arSoportePagoDetalle->setFecha($dateFecha);
            $arSoportePagoDetalle->setTurnoRel($arTurno);
            $arSoportePagoDetalle->setDescanso($intDescanso);
            $arSoportePagoDetalle->setDias($intDias);
            $arSoportePagoDetalle->setHoras($arTurno->getHoras());        
            $arSoportePagoDetalle->setHorasDiurnas($arrHoras['horasDiurnas']);
            $arSoportePagoDetalle->setHorasNocturnas($arrHoras['horasNocturnas']);
            $arSoportePagoDetalle->setHorasExtrasOrdinariasDiurnas($arrHoras['horasExtrasDiurnas']);
            $arSoportePagoDetalle->setHorasExtrasOrdinariasNocturnas($arrHoras['horasExtrasNocturnas']);
            $arSoportePagoDetalle->setHorasFestivasDiurnas($arrHoras['horasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasFestivasNocturnas($arrHoras['horasFestivasNocturnas']);        
            $arSoportePagoDetalle->setHorasExtrasFestivasDiurnas($arrHoras['horasExtrasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasExtrasFestivasNocturnas($arrHoras['horasExtrasFestivasNocturnas']);
            $em->persist($arSoportePagoDetalle);

            if($arrHoras1) {
                $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
                $arSoportePagoDetalle->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
                $arSoportePagoDetalle->setRecursoRel($arProgramacionDetalle->getRecursoRel());
                $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
                $arSoportePagoDetalle->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());
                $arSoportePagoDetalle->setFecha($dateFecha2);
                $arSoportePagoDetalle->setTurnoRel($arTurno);
                $arSoportePagoDetalle->setDescanso($intDescanso);
                $arSoportePagoDetalle->setDias(0);
                $arSoportePagoDetalle->setHoras($arTurno->getHoras());        
                $arSoportePagoDetalle->setHorasDiurnas($arrHoras1['horasDiurnas']);
                $arSoportePagoDetalle->setHorasNocturnas($arrHoras1['horasNocturnas']);
                $arSoportePagoDetalle->setHorasExtrasOrdinariasDiurnas($arrHoras1['horasExtrasDiurnas']);
                $arSoportePagoDetalle->setHorasExtrasOrdinariasNocturnas($arrHoras1['horasExtrasNocturnas']);
                $arSoportePagoDetalle->setHorasFestivasDiurnas($arrHoras1['horasFestivasDiurnas']);
                $arSoportePagoDetalle->setHorasFestivasNocturnas($arrHoras1['horasFestivasNocturnas']);        
                $arSoportePagoDetalle->setHorasExtrasFestivasDiurnas($arrHoras1['horasExtrasFestivasDiurnas']);
                $arSoportePagoDetalle->setHorasExtrasFestivasNocturnas($arrHoras1['horasExtrasFestivasNocturnas']);
                $em->persist($arSoportePagoDetalle);            
            }            
            
        }        
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'RECURSO')
                    ->setCellValue('C1', 'DESDE')
                    ->setCellValue('D1', 'HASTA')
                    ->setCellValue('E1', 'DÍAS')
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
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
    
    private function turnoHoras($intHoraInicio, $intHoraFinal, $boolFestivo, $intHoras) {
        $intHorasNocturnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);        
        $intHorasExtrasNocturnas = 0;
        $intTotalHoras = $intHorasNocturnas + $intHoras;
        if($intTotalHoras > 8) {
            $intHorasJornada = 8 - $intHoras;
            if($intHorasJornada > 1) {
                $intHorasNocturnasReales = $intHorasNocturnas - $intHorasJornada;
                $intHorasNocturnas = $intHorasNocturnas - $intHorasNocturnasReales;
                $intHorasExtrasNocturnas = $intHorasNocturnasReales;
            } else {
                $intHorasExtrasNocturnas = $intHorasNocturnas;
                $intHorasNocturnas = 0;
            }
        }
        
        $intHorasDiurnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 6, 22);
        $intHorasExtrasDiurnas = 0;
        $intTotalHoras = $intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas + $intHorasDiurnas;
        if($intTotalHoras > 8) {
            $intHorasJornada = 8 - ($intHoras + $intHorasNocturnas + $intHorasExtrasNocturnas);                    
            if($intHorasJornada > 1) {
                $intHorasDiurnasReales = $intHorasDiurnas - $intHorasJornada;
                $intHorasDiurnas = $intHorasDiurnas - $intHorasDiurnasReales;
                $intHorasExtrasDiurnas = $intHorasDiurnasReales;
            } else {
                $intHorasExtrasDiurnas = $intHorasDiurnas;
                $intHorasDiurnas = 0;
            }            
            //$intHorasDiurnasReales = $intHorasDiurnas - $intHorasJornada;
            //$intHorasDiurnas = $intHorasDiurnas - $intHorasDiurnasReales;
            //$intHorasExtrasDiurnas = $intHorasDiurnasReales;
        }

        $intHorasNocturnasNoche = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 22, 24); 
        $intHorasExtrasNocturnasNoche = 0;
        $intTotalHoras = $intHorasDiurnas + $intHorasExtrasDiurnas + $intHorasNocturnas + $intHorasNocturnasNoche;                                        
        if($intTotalHoras > 8) {                    
            $intHorasJornada = 8 - ($intHorasNocturnas + $intHorasDiurnas + $intHorasExtrasDiurnas);                    
            if($intHorasJornada > 1) {
                $intHorasNocturnasNocheReales = $intHorasNocturnasNoche - $intHorasJornada;
                $intHorasNocturnasNoche = $intHorasNocturnasNoche - $intHorasNocturnasNocheReales;
                $intHorasExtrasNocturnasNoche = $intHorasNocturnasNocheReales;                        
            } else {
                $intHorasExtrasNocturnasNoche = $intHorasNocturnasNoche;
                $intHorasNocturnasNoche = 0;
            }
        }
        $intHorasNocturnas += $intHorasNocturnasNoche;        
        $intHorasExtrasNocturnas += $intHorasExtrasNocturnasNoche;
        
        $intHorasFestivasDiurnas = 0;
        $intHorasFestivasNocturnas = 0;
        $intHorasExtrasFestivasDiurnas = 0;
        $intHorasExtrasFestivasNocturnas = 0;
        if($boolFestivo == 1) {
            $intHorasFestivasDiurnas = $intHorasDiurnas;
            $intHorasDiurnas = 0;
            $intHorasFestivasNocturnas = $intHorasNocturnas;
            $intHorasNocturnas = 0;
            $intHorasExtrasFestivasDiurnas = $intHorasExtrasDiurnas;
            $intHorasExtrasDiurnas = 0;
            $intHorasExtrasFestivasNocturnas = $intHorasExtrasNocturnas;
            $intHorasExtrasNocturnas = 0;
        }                
        $intTotalHoras = $intHorasDiurnas+$intHorasNocturnas+$intHorasExtrasDiurnas+$intHorasExtrasNocturnas+$intHorasFestivasDiurnas+$intHorasFestivasNocturnas+$intHorasExtrasFestivasDiurnas+$intHorasExtrasFestivasNocturnas;
        $arrHoras = array(
            'horasDiurnas' => $intHorasDiurnas, 
            'horasNocturnas' => $intHorasNocturnas, 
            'horasExtrasDiurnas' => $intHorasExtrasDiurnas, 
            'horasExtrasNocturnas' => $intHorasExtrasNocturnas,
            'horasFestivasDiurnas' => $intHorasFestivasDiurnas, 
            'horasFestivasNocturnas' => $intHorasFestivasNocturnas, 
            'horasExtrasFestivasDiurnas' => $intHorasExtrasFestivasDiurnas, 
            'horasExtrasFestivasNocturnas' => $intHorasExtrasFestivasNocturnas,
            'horas' => $intTotalHoras);                       
        return $arrHoras;
    }
    
}