<?php
namespace Brasa\TurnoBundle\Controller\Proceso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurSoportePagoPeriodoType;
use Brasa\TurnoBundle\Form\Type\TurSoportePagoType;
class GenerarSoportePagoController extends Controller
{
    var $strListaDql = "";
    var $strListaDqlDetalle = "";

    /**
     * @Route("/tur/proceso/generar/soporte/pago", name="brs_tur_proceso_generar_soporte_pago")
     */     
    public function generarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioGenerar();
        $form->handleRequest($request); 
        $this->listaPeriodo();
        if ($form->isValid()) {
            if($request->request->get('OpGenerar')) {  
                set_time_limit(0);
                $codigoSoportePagoPeriodo = $request->request->get('OpGenerar');
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);
                $dateFechaDesde = $arSoportePagoPeriodo->getFechaDesde();
                $dateFechaHasta = $arSoportePagoPeriodo->getFechaHasta();
                $intDiaInicial = $dateFechaDesde->format('j');
                $intDiaFinal = $dateFechaHasta->format('j');
                $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($dateFechaDesde->format('Y-m-').'01', $dateFechaHasta->format('Y-m-').'31');
                $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->periodo($dateFechaDesde->format('Y/m/') . "01",$dateFechaHasta->format('Y/m/') . "31", $arSoportePagoPeriodo->getCodigoRecursoGrupoFk());
                foreach ($arProgramacionDetalles as $arProgramacionDetalle) {                    
                    for($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {
                        $strFecha = $dateFechaDesde->format('Y/m/') . $i;
                        $dateFecha = date_create($strFecha);
                        $nuevafecha = strtotime ( '+1 day' , strtotime ( $strFecha ) ) ;
                        $dateFecha2 = date ( 'Y/m/j' , $nuevafecha );
                        $dateFecha2 = date_create($dateFecha2);
                        $boolFestivo = $this->festivo($arFestivos, $dateFecha);
                        $boolFestivo2 = $this->festivo($arFestivos, $dateFecha2);
                        $strTurno = $this->devuelveTurnoDia($arProgramacionDetalle, $i);                        
                        if($strTurno) {
                            $this->insertarSoportePago($arSoportePagoPeriodo, $arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $strTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2);
                        }
                    }                        
                }          
                $arSoportePagoPeriodo->setEstadoGenerado(1);
                $em->persist($arSoportePagoPeriodo);
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->resumen($dateFechaDesde, $dateFechaHasta, $arSoportePagoPeriodo);                
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);                                
                set_time_limit(60);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));
            }
            if($request->request->get('OpDeshacer')) {
                $codigoSoportePagoPeriodo = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM tur_soporte_pago_detalle WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;           
                $em->getConnection()->executeQuery($strSql);
                $strSql = "DELETE FROM tur_soporte_pago WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;           
                $em->getConnection()->executeQuery($strSql); 
                
                $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                $arSoportePagoPeriodo->setEstadoGenerado(0);
                $arSoportePagoPeriodo->setRecursos(0);
                $arSoportePagoPeriodo->setVrPago(0);
                $em->persist($arSoportePagoPeriodo);
                $em->flush();                                                  
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }
            if($request->request->get('OpCerrar')) {
                $codigoSoportePagoPeriodo = $request->request->get('OpCerrar');
                $arSoportePagoPeriodo = NEW \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                $arSoportePagoPeriodo->setEstadoCerrado(1);                
                $em->persist($arSoportePagoPeriodo);
                $em->flush();                                                   
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }            
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
                
            }            
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);                
                $this->listaPeriodo();
            }            
        }        
        $arSoportePagoPeriodos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:generar.html.twig', array(
            'arSoportePagoPeriodos' => $arSoportePagoPeriodos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/proceso/generar/soporte/pago/detalle/{codigoSoportePagoPeriodo}", name="brs_tur_proceso_generar_soporte_pago_detalle")
     */     
    public function detalleAction($codigoSoportePagoPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);
        $this->lista($codigoSoportePagoPeriodo);
        if ($form->isValid()) {
            if ($form->get('BtnExcel')->isClicked()) {
                $this->listaDetalle("", $codigoSoportePagoPeriodo);
                $this->generarExcel();
            }
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurSoportePago')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago_detalle', array('codigoSoportePagoPeriodo' => $codigoSoportePagoPeriodo)));                                
            }             
            if ($form->get('BtnLiquidarCompensacion')->isClicked()) {      
                $festivos = $arSoportePagoPeriodo->getDiaFestivoReal() + $arSoportePagoPeriodo->getDiaDomingoReal();
                $horasTope = $arSoportePagoPeriodo->getDiasPeriodo() * 8;
                $horasFestivas = $festivos * 8;                
                $horasLaborales = $horasTope - $horasFestivas;
                //$horasDescanso = $horasLaborales / $festivos;
                $arSoportePagos = new \Brasa\TurnoBundle\Entity\TurSoportePago();  
                $arSoportePagos = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->findBy(array('codigoSoportePagoPeriodoFk' => $codigoSoportePagoPeriodo));
                foreach ($arSoportePagos as $arSoportePago) {
                    if($arSoportePago->getCodigoRecursoFk() == 6) {
                        echo "hola";
                    }
                    //$horasNovedad = $arSoportePago->getHorasNovedad(); 
                    //$horasLaboralesRecurso = $horasLaborales - $horasNovedad;                                     
                    
                    $horasDia = $arSoportePago->getHorasDiurnasReales();
                    $horasExtraDia = $arSoportePago->getHorasExtrasOrdinariasDiurnasReales();
                    $horasNoche = $arSoportePago->getHorasNocturnasReales();
                    $horasExtraNoche = $arSoportePago->getHorasExtrasOrdinariasNocturnasReales();                  
                    $totalHoras = $horasDia + $horasNoche;
                    if($totalHoras < $horasLaborales) {
                        $horasDia += $horasExtraDia;
                        $totalHoras += $horasExtraDia;
                        $horasExtraDia = 0;
                    }
                    if($totalHoras < $horasLaborales) {
                        $horasNoche += $horasExtraNoche;
                        $totalHoras += $horasExtraNoche;
                        $horasExtraNoche = 0;
                        if($totalHoras > $horasLaborales) {
                            $diferencia = $totalHoras - $horasLaborales;
                            $totalHoras -= $diferencia;
                            $diferenciaHoras = $diferencia / 2;    
                            $horasDia -= $diferenciaHoras;
                            $horasNoche -= $diferenciaHoras;
                            $horasExtraDia += $diferenciaHoras;
                            $horasExtraNoche += $diferenciaHoras;

                        }                        
                    } else {
                        if($totalHoras > $horasLaborales) {
                            $diferencia = $totalHoras - $horasLaborales;
                            $totalHoras -= $diferencia;                            
                            $horasDia -= $diferencia;                            
                            $horasExtraDia += $diferencia;
                            

                        }                                                
                    }

                    //Descanso    
                    /*$horasFestivasDiurnas = $arSoportePago->getHorasFestivasDiurnas();
                    $horasFestivasNocturnas = $arSoportePago->getHorasFestivasNocturnas();
                    $horasExtrasFestivasDiurnas = $arSoportePago->getHorasExtrasFestivasDiurnas();
                    $horasExtrasFestivasNocturnas = $arSoportePago->getHorasExtrasFestivasNocturnas();
                    $horasNovedad = $arSoportePago->getHorasNovedad();
                    $horasDescansoRecurso = ($horasDia + $horasNoche + $horasExtraDia + $horasExtraNoche + $horasFestivasDiurnas + $horasFestivasNocturnas  + $horasExtrasFestivasDiurnas + $horasExtrasFestivasNocturnas) - $horasNovedad;
                    if($horasDescansoRecurso > 0) {
                        $prueba = $horasDescansoRecurso / $horasDescanso;
                        $prueba = round($prueba);
                        if($prueba > $festivos) {
                            $horasDescansoRecurso = $festivos * 8;
                        } else {
                            $horasDescansoRecurso = $prueba * 8;
                        }
                    } else {
                        $horasDescansoRecurso = 0;
                    }
                     * 
                     */
                    //Fin descanso 
                    
                    $arSoportePagoAct = new \Brasa\TurnoBundle\Entity\TurSoportePago();
                    $arSoportePagoAct = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($arSoportePago->getCodigoSoportePagoPk());                    
                    $arSoportePagoAct->setHorasDiurnas($horasDia);
                    $arSoportePagoAct->setHorasNocturnas($horasNoche);
                    $arSoportePagoAct->setHorasExtrasOrdinariasDiurnas($horasExtraDia);
                    $arSoportePagoAct->setHorasExtrasOrdinariasNocturnas($horasExtraNoche);
                    $arSoportePagoAct->setHorasDescanso($horasFestivas);
                    //$arSoportePagoAct->setHorasDescanso($horasDescansoRecurso);
                    $horas = $horasFestivas + $horasDia + $horasNoche + $arSoportePagoAct->getHorasNovedad();
                    $arSoportePagoAct->setHoras($horas);
                    $em->persist($arSoportePagoAct);
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->liquidar($codigoSoportePagoPeriodo);                                
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago_detalle', array('codigoSoportePagoPeriodo' => $codigoSoportePagoPeriodo)));                
                
            }             
        }
        $arSoportesPago = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);        
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:detalle.html.twig', array(            
            'arSoportesPagos' => $arSoportesPago,
            'arSoportePagoPeriodo' => $arSoportePagoPeriodo,
            'form' => $form->createView()));
    }    

    /**
     * @Route("/tur/proceso/generar/soporte/pago/detalle/ver/{codigoSoportePago}", name="brs_tur_proceso_generar_soporte_pago_detalle_ver")
     */    
    public function verAction($codigoSoportePago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioVer();
        $form->handleRequest($request);
        $this->listaDetalle($codigoSoportePago, "");
        if ($form->isValid()) {
        }        
        $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportePago =  $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($codigoSoportePago);                                
        $strAnio = $arSoportePago->getFechaDesde()->format('Y');
        $strMes = $arSoportePago->getFechaDesde()->format('m');
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $strAnio, 'mes' => $strMes, 'codigoRecursoFk' => $arSoportePago->getCodigoRecursoFk()));                        
        $arSoportesPagoDetalle = $paginator->paginate($em->createQuery($this->strListaDqlDetalle), $request->query->get('page', 1), 200);        
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:ver.html.twig', array(                        
            'arProgramacionDetalle' => $arProgramacionDetalle,
            'arSoportesPagosDetalles' => $arSoportesPagoDetalle,
            'arSoportePago' => $arSoportePago,
            'form' => $form->createView()));
    }     
    
    /**
     * @Route("/tur/proceso/generar/soporte/pago/nuevo/{codigoSoportePagoPeriodo}", name="brs_tur_proceso_generar_soporte_pago_nuevo")
     */    
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
            $arSoportePagoPeriodo->setDiasPeriodo(15);
        }
        $form = $this->createForm(new TurSoportePagoPeriodoType, $arSoportePagoPeriodo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arSoportePagoPeriodo = $form->getData();  
            $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arSoportePagoPeriodo->getFechaDesde()->format('Y-m-d'), $arSoportePagoPeriodo->getFechaHasta()->format('Y-m-d'));
            $arrDias = $this->festivosDomingos($arSoportePagoPeriodo->getFechaDesde(), $arSoportePagoPeriodo->getFechaHasta(), $arFestivos);
            $arSoportePagoPeriodo->setDiaDomingoReal($arrDias['domingos']);
            $arSoportePagoPeriodo->setDiaFestivoReal($arrDias['festivos']);
            $em->persist($arSoportePagoPeriodo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                                                                              
        }
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:nuevo.html.twig', array(
            'arSoportePagoPeriodo' => $arSoportePagoPeriodo,
            'form' => $form->createView()));
    }    

    /**
     * @Route("/tur/proceso/generar/soporte/pago/editar/{codigoSoportePago}", name="brs_tur_proceso_generar_soporte_pago_editar")
     */     
    public function editarAction($codigoSoportePago) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        if($codigoSoportePago != 0) {
            $arSoportePago = $em->getRepository('BrasaTurnoBundle:TurSoportePago')->find($codigoSoportePago);
        }
        $form = $this->createForm(new TurSoportePagoType, $arSoportePago);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arSoportePago = $form->getData();            
            $em->persist($arSoportePago);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaTurnoBundle:Procesos/GenerarSoportePago:editar.html.twig', array(
            'arSoportePago' => $arSoportePago,
            'form' => $form->createView()));
    }    

    private function listaPeriodo() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->listaDql(                
                $session->get('filtroSoportePagoEstadoCerrado')
                );        
    }    
    
    private function lista($codigoSoportePagoPeriodo) {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurSoportePago')->listaDql(
                $codigoSoportePagoPeriodo,
                $session->get('filtroSoportePagoEstadoCerrado')
                );        
    }

    private function listaDetalle($codigoSoportePago, $codigoSoportePagoPeriodo) {
        $em = $this->getDoctrine()->getManager();        
        $this->strListaDqlDetalle =  $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->listaDql($codigoSoportePagoPeriodo, $codigoSoportePago);
    }    

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();        
        $session->set('filtroSoportePagoEstadoCerrado', $form->get('estadoCerrado')->getData());          
    }    
    
    private function formularioGenerar() {
        $session = $this->getRequest()->getSession();
        $arrayPropiedadesRecursoGrupo = array(
                'class' => 'BrasaTurnoBundle:TurRecursoGrupo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rg')
                    ->orderBy('rg.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoRecursoGrupo')) {
            $arrayPropiedadesRecursoGrupo['data'] = $em->getReference("BrasaTurnoBundle:TurRecursoGrupo", $session->get('filtroCodigoRecursoGrupo'));
        }        
        if($session->get('filtroSoportePagoEstadoCerrado') == null) {
            $session->set('filtroSoportePagoEstadoCerrado', 0); 
        }
        $form = $this->createFormBuilder()
            ->add('recursoGrupoRel', 'entity', $arrayPropiedadesRecursoGrupo)
            ->add('estadoCerrado', 'choice', array('choices'   => array('0' => 'SIN CERRAR', '1' => 'CERRADO', '2' => 'TODOS'), 'data' => $session->get('filtroSoportePagoEstadoCerrado')))                                
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar')) 
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                
            ->getForm();
        return $form;
    }

    private function formularioDetalle() {
        $form = $this->createFormBuilder()
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))                        
            ->add('BtnLiquidarCompensacion', 'submit', array('label'  => 'Liquidar por compensacion'))                        
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar')) 
            ->getForm();
        return $form;
    }
    
    private function formularioVer() {
        $form = $this->createFormBuilder()                                   
            ->getForm();
        return $form;
    }
    
    private function insertarSoportePago ($arSoportePagoPeriodo, $arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $codigoTurno, $dateFecha, $dateFecha2, $boolFestivo, $boolFestivo2) {
        $em = $this->getDoctrine()->getManager();       
        //$arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
        $strTurnoFijoNomina = $arSoportePagoPeriodo->getRecursoGrupoRel()->getCodigoTurnoFijoNominaFk();
        $strTurnoFijoDescanso = $arSoportePagoPeriodo->getRecursoGrupoRel()->getCodigoTurnoFijoDescansoFk();
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
        if($arTurno->getDescanso() == 0 && $arTurno->getNovedad() == 0) {                
            if($strTurnoFijoNomina) {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurnoFijoNomina);
            }                
        }     
        if($arTurno->getDescanso() == 1) {
            if($strTurnoFijoDescanso) {
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurnoFijoDescanso);
            }
        }
        
        $intDias = 0;                       
        $intMinutoInicio = (($arTurno->getHoraDesde()->format('i') * 100)/60)/100;
        $intHoraInicio = $arTurno->getHoraDesde()->format('G');        
        $intHoraInicio += $intMinutoInicio;
        $intMinutoFinal = (($arTurno->getHoraHasta()->format('i') * 100)/60)/100;
        $intHoraFinal = $arTurno->getHoraHasta()->format('G');
        $intHoraFinal += $intMinutoFinal;
        $diaSemana = $dateFecha->format('N');
        $diaSemana2 = $dateFecha2->format('N');
        if($arTurno->getNovedad() == 0) {
            $intDias += 1;
        }                    
        if($diaSemana == 7) {
            $boolFestivo = 1;
        }
        if($diaSemana2 == 7) {
            $boolFestivo2 = 1;
        }        
        $arrHoras1 = null;
        if(($intHoraInicio + $intMinutoInicio) <= $intHoraFinal){  
            $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, 0, $arTurno->getNovedad(), $arTurno->getDescanso());
        } else {
            $arrHoras = $this->turnoHoras($intHoraInicio, $intMinutoInicio, 24, $boolFestivo, 0, $arTurno->getNovedad(), $arTurno->getDescanso());
            $arrHoras1 = $this->turnoHoras(0, 0, $intHoraFinal, $boolFestivo2, $arrHoras['horas'], $arTurno->getNovedad(), $arTurno->getDescanso());                 
        }
        $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
        $arSoportePagoDetalle->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
        $arSoportePagoDetalle->setRecursoRel($arProgramacionDetalle->getRecursoRel());
        $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
        $arSoportePagoDetalle->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());            
        $arSoportePagoDetalle->setFecha($dateFecha);
        $arSoportePagoDetalle->setTurnoRel($arTurno);
        $arSoportePagoDetalle->setDescanso($arTurno->getDescanso());
        $arSoportePagoDetalle->setNovedad($arTurno->getNovedad());
        $arSoportePagoDetalle->setIncapacidad($arTurno->getIncapacidad());
        $arSoportePagoDetalle->setLicencia($arTurno->getLicencia());
        $arSoportePagoDetalle->setVacacion($arTurno->getVacacion());
        $arSoportePagoDetalle->setDias($intDias);
        $arSoportePagoDetalle->setHoras($arTurno->getHorasNomina());        
        $arSoportePagoDetalle->setHorasDiurnas($arrHoras['horasDiurnas']);
        $arSoportePagoDetalle->setHorasNocturnas($arrHoras['horasNocturnas']);
        $arSoportePagoDetalle->setHorasExtrasOrdinariasDiurnas($arrHoras['horasExtrasDiurnas']);
        $arSoportePagoDetalle->setHorasExtrasOrdinariasNocturnas($arrHoras['horasExtrasNocturnas']);
        $arSoportePagoDetalle->setHorasFestivasDiurnas($arrHoras['horasFestivasDiurnas']);
        $arSoportePagoDetalle->setHorasFestivasNocturnas($arrHoras['horasFestivasNocturnas']);        
        $arSoportePagoDetalle->setHorasExtrasFestivasDiurnas($arrHoras['horasExtrasFestivasDiurnas']);
        $arSoportePagoDetalle->setHorasExtrasFestivasNocturnas($arrHoras['horasExtrasFestivasNocturnas']);
        $arSoportePagoDetalle->setHorasDescanso($arrHoras['horasDescanso']);
        $arSoportePagoDetalle->setHorasNovedad($arrHoras['horasNovedad']);
        if($strTurnoFijoNomina) {
            $arSoportePagoDetalle->setHorasDiurnas($arrHoras['horasDiurnas'] + $arrHoras['horasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasFestivasDiurnas(0);
        }
        $em->persist($arSoportePagoDetalle);

        if($arrHoras1) {
            $arSoportePagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
            $arSoportePagoDetalle->setSoportePagoPeriodoRel($arSoportePagoPeriodo);
            $arSoportePagoDetalle->setRecursoRel($arProgramacionDetalle->getRecursoRel());
            $arSoportePagoDetalle->setProgramacionDetalleRel($arProgramacionDetalle);
            $arSoportePagoDetalle->setPedidoDetalleRel($arProgramacionDetalle->getPedidoDetalleRel());
            $arSoportePagoDetalle->setFecha($dateFecha2);
            $arSoportePagoDetalle->setTurnoRel($arTurno);
            $arSoportePagoDetalle->setDescanso($arTurno->getDescanso());
            $arSoportePagoDetalle->setNovedad(0);
            $arSoportePagoDetalle->setDias(0);
            $arSoportePagoDetalle->setHoras($arTurno->getHorasNomina());        
            $arSoportePagoDetalle->setHorasDiurnas($arrHoras1['horasDiurnas']);
            $arSoportePagoDetalle->setHorasNocturnas($arrHoras1['horasNocturnas']);
            $arSoportePagoDetalle->setHorasExtrasOrdinariasDiurnas($arrHoras1['horasExtrasDiurnas']);
            $arSoportePagoDetalle->setHorasExtrasOrdinariasNocturnas($arrHoras1['horasExtrasNocturnas']);
            $arSoportePagoDetalle->setHorasFestivasDiurnas($arrHoras1['horasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasFestivasNocturnas($arrHoras1['horasFestivasNocturnas']);        
            $arSoportePagoDetalle->setHorasExtrasFestivasDiurnas($arrHoras1['horasExtrasFestivasDiurnas']);
            $arSoportePagoDetalle->setHorasExtrasFestivasNocturnas($arrHoras1['horasExtrasFestivasNocturnas']);
            $arSoportePagoDetalle->setHorasDescanso($arrHoras1['horasDescanso']);
            $arSoportePagoDetalle->setHorasNovedad($arrHoras1['horasNovedad']);
            $em->persist($arSoportePagoDetalle);            
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
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'CODIGO RECURSO')
                    ->setCellValue('C1', 'IDENTIFICACION')
                    ->setCellValue('D1', 'RECURSO')
                    ->setCellValue('E1', 'DESDE')
                    ->setCellValue('F1', 'HASTA')
                    ->setCellValue('G1', 'DÍAS')
                    ->setCellValue('H1', 'DES')
                    ->setCellValue('I1', 'NOV')
                    ->setCellValue('J1', 'INC')
                    ->setCellValue('K1', 'LIC')
                    ->setCellValue('L1', 'H')
                    ->setCellValue('M1', 'HDS')
                    ->setCellValue('N1', 'HD')
                    ->setCellValue('O1', 'HN')
                    ->setCellValue('P1', 'HFD')
                    ->setCellValue('Q1', 'HFN')                
                    ->setCellValue('R1', 'HEOD')
                    ->setCellValue('S1', 'HEON')
                    ->setCellValue('T1', 'HEFD')
                    ->setCellValue('U1', 'HEFN')
                    ->setCellValue('W1', 'HDSR')
                    ->setCellValue('X1', 'HDR')
                    ->setCellValue('Y1', 'HNR')
                    ->setCellValue('Z1', 'HFDR')
                    ->setCellValue('AA1', 'HFNR')                
                    ->setCellValue('AB1', 'HEODR')
                    ->setCellValue('AC1', 'HEONR')
                    ->setCellValue('AD1', 'HEFDR')
                    ->setCellValue('AE1', 'HEFNR')
                    ->setCellValue('AF1', 'PAGO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportesPago = $query->getResult();
        foreach ($arSoportesPago as $arSoportePago) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSoportePago->getCodigoSoportePagoPk())
                    ->setCellValue('B' . $i, $arSoportePago->getCodigoRecursoFk())
                    ->setCellValue('C' . $i, $arSoportePago->getRecursoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arSoportePago->getRecursoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arSoportePago->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arSoportePago->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arSoportePago->getDias())
                    ->setCellValue('H' . $i, $arSoportePago->getDescanso())
                    ->setCellValue('I' . $i, $arSoportePago->getNovedad())
                    ->setCellValue('J' . $i, $arSoportePago->getIncapacidad())
                    ->setCellValue('K' . $i, $arSoportePago->getLicencia())
                    ->setCellValue('L' . $i, $arSoportePago->getHoras())
                    ->setCellValue('M' . $i, $arSoportePago->getHorasDescanso())
                    ->setCellValue('N' . $i, $arSoportePago->getHorasDiurnas())
                    ->setCellValue('O' . $i, $arSoportePago->getHorasNocturnas())
                    ->setCellValue('P' . $i, $arSoportePago->getHorasFestivasDiurnas())
                    ->setCellValue('Q' . $i, $arSoportePago->getHorasFestivasNocturnas())                    
                    ->setCellValue('R' . $i, $arSoportePago->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('S' . $i, $arSoportePago->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('T' . $i, $arSoportePago->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('U' . $i, $arSoportePago->getHorasExtrasFestivasNocturnas())
                    ->setCellValue('W' . $i, $arSoportePago->getHorasDescansoReales())
                    ->setCellValue('X' . $i, $arSoportePago->getHorasDiurnasReales())
                    ->setCellValue('Y' . $i, $arSoportePago->getHorasNocturnasReales())
                    ->setCellValue('Z' . $i, $arSoportePago->getHorasFestivasDiurnasReales())
                    ->setCellValue('AA' . $i, $arSoportePago->getHorasFestivasNocturnasReales())                    
                    ->setCellValue('AB' . $i, $arSoportePago->getHorasExtrasOrdinariasDiurnasReales())
                    ->setCellValue('AC' . $i, $arSoportePago->getHorasExtrasOrdinariasNocturnasReales())
                    ->setCellValue('AD' . $i, $arSoportePago->getHorasExtrasFestivasDiurnasReales())
                    ->setCellValue('AE' . $i, $arSoportePago->getHorasExtrasFestivasNocturnasReales())
                    ->setCellValue('AF' . $i, $arSoportePago->getVrPago());

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
                    ->setCellValue('G1', 'HDS')    
                    ->setCellValue('H1', 'HD')
                    ->setCellValue('I1', 'HN')
                    ->setCellValue('J1', 'HFD')
                    ->setCellValue('K1', 'HFN')                
                    ->setCellValue('L1', 'HEOD')
                    ->setCellValue('M1', 'HEON')
                    ->setCellValue('N1', 'HEFD')
                    ->setCellValue('O1', 'HEFN');
        
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
                    ->setCellValue('G' . $i, $arSoportePagoDetalle->getHorasDescanso())
                    ->setCellValue('H' . $i, $arSoportePagoDetalle->getHorasDiurnas())
                    ->setCellValue('I' . $i, $arSoportePagoDetalle->getHorasNocturnas())
                    ->setCellValue('J' . $i, $arSoportePagoDetalle->getHorasFestivasDiurnas())
                    ->setCellValue('K' . $i, $arSoportePagoDetalle->getHorasFestivasNocturnas())                    
                    ->setCellValue('L' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('M' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('N' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('O' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasNocturnas());

            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('Detalle');                
        $objPHPExcel->setActiveSheetIndex(0);
        
        //Hoja con las programaciones de los recursos                
        $objPHPExcel->createSheet(3)->setTitle('RecursoProgramacion')
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'COD.REC')
                    ->setCellValue('C1', 'IDENT')
                    ->setCellValue('D1', 'RECURSO')
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'PUESTO')
                    ->setCellValue('G1', 'D1')
                    ->setCellValue('H1', 'D2')
                    ->setCellValue('I1', 'D3')
                    ->setCellValue('J1', 'D4')
                    ->setCellValue('K1', 'D5')
                    ->setCellValue('L1', 'D6')    
                    ->setCellValue('M1', 'D7')
                    ->setCellValue('N1', 'D8')
                    ->setCellValue('O1', 'D9')
                    ->setCellValue('P1', 'D10')                
                    ->setCellValue('Q1', 'D11')
                    ->setCellValue('R1', 'D12')
                    ->setCellValue('S1', 'D13')
                    ->setCellValue('T1', 'D14')
                    ->setCellValue('U1', 'D15')
                    ->setCellValue('V1', 'D16')
                    ->setCellValue('W1', 'D17')
                    ->setCellValue('X1', 'D18')
                    ->setCellValue('Y1', 'D19')
                    ->setCellValue('Z1', 'D20')
                    ->setCellValue('AA1', 'D21')
                    ->setCellValue('AB1', 'D22')
                    ->setCellValue('AC1', 'D23')
                    ->setCellValue('AD1', 'D24')
                    ->setCellValue('AE1', 'D25')
                    ->setCellValue('AF1', 'D26')
                    ->setCellValue('AG1', 'D27')
                    ->setCellValue('AH1', 'D28')
                    ->setCellValue('AI1', 'D29')
                    ->setCellValue('AJ1', 'D30')
                    ->setCellValue('AK1', 'D31');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arSoportesPago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportesPago = $query->getResult();
        foreach ($arSoportesPago as $arSoportePago) { 
            $strAnio = $arSoportePago->getFechaDesde()->format('Y');
            $strMes = $arSoportePago->getFechaDesde()->format('m');            
            $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalles =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $strAnio, 'mes' => $strMes, 'codigoRecursoFk' => $arSoportePago->getCodigoRecursoFk()));                                    
            foreach($arProgramacionDetalles as $arProgramacionDetalle) {
                $objPHPExcel->setActiveSheetIndex(2)
                        ->setCellValue('A' . $i, $arSoportePago->getCodigoSoportePagoPk())
                        ->setCellValue('B' . $i, $arSoportePago->getCodigoRecursoFk())
                        ->setCellValue('C' . $i, $arSoportePago->getRecursoRel()->getNumeroIdentificacion())
                        ->setCellValue('D' . $i, $arSoportePago->getRecursoRel()->getNombreCorto())
                        ->setCellValue('E' . $i, $arProgramacionDetalle->getProgramacionRel()->getClienteRel()->getNombreCorto())                        
                        ->setCellValue('G' . $i, $arProgramacionDetalle->getDia1())
                        ->setCellValue('H' . $i, $arProgramacionDetalle->getDia2())
                        ->setCellValue('I' . $i, $arProgramacionDetalle->getDia3())
                        ->setCellValue('J' . $i, $arProgramacionDetalle->getDia4())
                        ->setCellValue('K' . $i, $arProgramacionDetalle->getDia5())
                        ->setCellValue('L' . $i, $arProgramacionDetalle->getDia6())
                        ->setCellValue('M' . $i, $arProgramacionDetalle->getDia7())
                        ->setCellValue('N' . $i, $arProgramacionDetalle->getDia8())
                        ->setCellValue('O' . $i, $arProgramacionDetalle->getDia9())
                        ->setCellValue('P' . $i, $arProgramacionDetalle->getDia10())
                        ->setCellValue('Q' . $i, $arProgramacionDetalle->getDia11())
                        ->setCellValue('R' . $i, $arProgramacionDetalle->getDia12())
                        ->setCellValue('S' . $i, $arProgramacionDetalle->getDia13())
                        ->setCellValue('T' . $i, $arProgramacionDetalle->getDia14())
                        ->setCellValue('U' . $i, $arProgramacionDetalle->getDia15())
                        ->setCellValue('V' . $i, $arProgramacionDetalle->getDia16())
                        ->setCellValue('W' . $i, $arProgramacionDetalle->getDia17())
                        ->setCellValue('X' . $i, $arProgramacionDetalle->getDia18())
                        ->setCellValue('Y' . $i, $arProgramacionDetalle->getDia19())
                        ->setCellValue('Z' . $i, $arProgramacionDetalle->getDia20())
                        ->setCellValue('AA' . $i, $arProgramacionDetalle->getDia21())
                        ->setCellValue('AB' . $i, $arProgramacionDetalle->getDia22())
                        ->setCellValue('AC' . $i, $arProgramacionDetalle->getDia23())
                        ->setCellValue('AD' . $i, $arProgramacionDetalle->getDia24())
                        ->setCellValue('AE' . $i, $arProgramacionDetalle->getDia25())
                        ->setCellValue('AF' . $i, $arProgramacionDetalle->getDia26())
                        ->setCellValue('AG' . $i, $arProgramacionDetalle->getDia27())
                        ->setCellValue('AH' . $i, $arProgramacionDetalle->getDia28())
                        ->setCellValue('AI' . $i, $arProgramacionDetalle->getDia29())
                        ->setCellValue('AJ' . $i, $arProgramacionDetalle->getDia30())
                        ->setCellValue('AK' . $i, $arProgramacionDetalle->getDia31());
                if($arProgramacionDetalle->getPuestoRel()) {
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('F' . $i, $arProgramacionDetalle->getPuestoRel()->getNombre());
                }
                $i++;                
            }
        }
        for($col = 'A'; $col !== 'AK'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);            
        }        
        $objPHPExcel->getActiveSheet()->setTitle('RecursoProgramacion');     
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        
        
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
    
    private function turnoHoras($intHoraInicio, $intMinutoInicio, $intHoraFinal, $boolFestivo, $intHoras, $boolNovedad = 0, $boolDescanso = 0) {        
        if($boolNovedad == 0) {
            $intHorasNocturnas = $this->calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);        
            $intHorasExtrasNocturnas = 0;
            $intTotalHoras = $intHorasNocturnas + $intHoras;
            if($intTotalHoras > 8) {
                $intHorasJornada = 8 - $intHoras;
                if($intHorasJornada >= 1) {
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
            if($boolDescanso == 1) {                
                $arrHoras = array(
                    'horasDescanso' => $intTotalHoras,
                    'horasNovedad' => 0,
                    'horasDiurnas' => 0, 
                    'horasNocturnas' => 0, 
                    'horasExtrasDiurnas' => 0, 
                    'horasExtrasNocturnas' => 0,
                    'horasFestivasDiurnas' => 0, 
                    'horasFestivasNocturnas' => 0, 
                    'horasExtrasFestivasDiurnas' => 0, 
                    'horasExtrasFestivasNocturnas' => 0,
                    'horas' => $intTotalHoras);                
            } else {
                $arrHoras = array(
                    'horasDescanso' => 0,
                    'horasNovedad' => 0,
                    'horasDiurnas' => $intHorasDiurnas, 
                    'horasNocturnas' => $intHorasNocturnas, 
                    'horasExtrasDiurnas' => $intHorasExtrasDiurnas, 
                    'horasExtrasNocturnas' => $intHorasExtrasNocturnas,
                    'horasFestivasDiurnas' => $intHorasFestivasDiurnas, 
                    'horasFestivasNocturnas' => $intHorasFestivasNocturnas, 
                    'horasExtrasFestivasDiurnas' => $intHorasExtrasFestivasDiurnas, 
                    'horasExtrasFestivasNocturnas' => $intHorasExtrasFestivasNocturnas,
                    'horas' => $intTotalHoras);                
            }
            
        } else {
            $arrHoras = array(
                'horasDescanso' => 0,
                'horasNovedad' => 8,
                'horasDiurnas' => 0, 
                'horasNocturnas' => 0, 
                'horasExtrasDiurnas' => 0, 
                'horasExtrasNocturnas' => 0,
                'horasFestivasDiurnas' => 0, 
                'horasFestivasNocturnas' => 0, 
                'horasExtrasFestivasDiurnas' => 0, 
                'horasExtrasFestivasNocturnas' => 0,
                'horas' => 0);            
        }  
        
        return $arrHoras;
    }
    
    private function devuelveTurnoDia($arProgramacionDetalle, $intDia) {        
        $strTurno = NULL;
        if($intDia == 1) {
            $strTurno = $arProgramacionDetalle->getDia1();
        }
        if($intDia == 2) {
            $strTurno = $arProgramacionDetalle->getDia2();
        }
        if($intDia == 3) {
            $strTurno = $arProgramacionDetalle->getDia3();
        }
        if($intDia == 4) {
            $strTurno = $arProgramacionDetalle->getDia4();
        }
        if($intDia == 5) {
            $strTurno = $arProgramacionDetalle->getDia5();
        }
        if($intDia == 6) {
            $strTurno = $arProgramacionDetalle->getDia6();
        }
        if($intDia == 7) {
            $strTurno = $arProgramacionDetalle->getDia7();
        }
        if($intDia == 8) {
            $strTurno = $arProgramacionDetalle->getDia8();
        }
        if($intDia == 9) {
            $strTurno = $arProgramacionDetalle->getDia9();
        }
        if($intDia == 10) {
            $strTurno = $arProgramacionDetalle->getDia10();
        }
        if($intDia == 11) {
            $strTurno = $arProgramacionDetalle->getDia11();
        }
        if($intDia == 12) {
            $strTurno = $arProgramacionDetalle->getDia12();
        }
        if($intDia == 13) {
            $strTurno = $arProgramacionDetalle->getDia13();
        }
        if($intDia == 14) {
            $strTurno = $arProgramacionDetalle->getDia14();
        }
        if($intDia == 15) {
            $strTurno = $arProgramacionDetalle->getDia15();
        }
        if($intDia == 16) {
            $strTurno = $arProgramacionDetalle->getDia16();
        }
        if($intDia == 17) {
            $strTurno = $arProgramacionDetalle->getDia17();
        }
        if($intDia == 18) {
            $strTurno = $arProgramacionDetalle->getDia18();
        }
        if($intDia == 19) {
            $strTurno = $arProgramacionDetalle->getDia19();
        }
        if($intDia == 20) {
            $strTurno = $arProgramacionDetalle->getDia20();
        }
        if($intDia == 21) {
            $strTurno = $arProgramacionDetalle->getDia21();
        }
        if($intDia == 22) {
            $strTurno = $arProgramacionDetalle->getDia22();
        }
        if($intDia == 23) {
            $strTurno = $arProgramacionDetalle->getDia23();
        }
        if($intDia == 24) {
            $strTurno = $arProgramacionDetalle->getDia24();
        }
        if($intDia == 25) {
            $strTurno = $arProgramacionDetalle->getDia25();
        }
        if($intDia == 26) {
            $strTurno = $arProgramacionDetalle->getDia26();
        }
        if($intDia == 27) {
            $strTurno = $arProgramacionDetalle->getDia27();
        }
        if($intDia == 28) {
            $strTurno = $arProgramacionDetalle->getDia28();
        }
        if($intDia == 29) {
            $strTurno = $arProgramacionDetalle->getDia29();
        }
        if($intDia == 30) {
            $strTurno = $arProgramacionDetalle->getDia30();
        }        
        if($intDia == 31) {
            $strTurno = $arProgramacionDetalle->getDia31();
        }
        return $strTurno;
    }
    
    private function festivosDomingos($desde, $hasta, $arFestivos) {
        $arrDias = array('festivos' => 0, 'domingos' => 0);
        $fechaDesde = date_create($desde->format('Y-m-d'));
        $domingos = 0;
        $festivos = 0;        
        while($fechaDesde <= $hasta) {
            $fechaDesde->modify('+1 day');  
            if($fechaDesde->format('N') == 7) {
               $domingos++; 
            }
            if($this->festivo($arFestivos, $fechaDesde) == true) {
               $festivos++; 
            }
        }
        $arrDias['domingos'] = $domingos;
        $arrDias['festivos'] = $festivos;
        return $arrDias;
    }
}