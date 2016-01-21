<?php
namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class ProcesoGenerarSoportePagoHorarioController extends Controller
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
                $strSql = "DELETE FROM rhu_soporte_pago_horario WHERE estado_cerrado = 0";           
                $em->getConnection()->executeQuery($strSql);                   
                return $this->redirect($this->generateUrl('brs_rhu_proceso_soporte_pago_horario'));                
            }            
            if ($form->get('BtnExcel')->isClicked()) {
                //$this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }    
            if ($form->get('BtnGenerar')->isClicked()) {
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                $arEmpleadosPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->resumenEmpleado($dateFechaDesde->format('Y/m/d'), $dateFechaHasta->format('Y/m/d'));                
                
                foreach ($arEmpleadosPeriodo as $arEmpleadoPeriodo ) {
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arEmpleadoPeriodo['codigoEmpleadoFk']);                                        
                    $arHorario = $arEmpleado->getHorarioRel();                                        
                    $arHorarioAccesos = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->empleado($dateFechaDesde->format('Y/m/d'), $dateFechaHasta->format('Y/m/d'), $arEmpleadoPeriodo['codigoEmpleadoFk']);                    
                    $intHorasDiurnas = 0;   
                    $arrHorasTotal = array(
                        'horasDiurnas' => 0,
                        'horasExtrasOrdinariasDiurnas' => 0,
                        'horasNocturnas' => 0,
                        'horasExtrasOrdinariasNocturnas' => 0);
                    foreach ($arHorarioAccesos as $arHorarioAcceso) {
                        $diaSemana = $arHorarioAcceso->getFechaEntrada()->format('N');
                        $codigoTurno = $this->devuelveCodigoTurno($diaSemana, $arHorario);
                        $arTurno = new \Brasa\RecursoHumanoBundle\Entity\RhuTurno();
                        $arTurno = $em->getRepository('BrasaRecursoHumanoBundle:RhuTurno')->find($codigoTurno);
                        $intHoraInicial = 0;
                        $intHoraFinal = 0;                        
                        if($arTurno->getHoraDesde()->format('h') >= $arHorarioAcceso->getFechaEntrada()->format('h')) {
                            $intHoraInicial = $arTurno->getHoraDesde()->format('G');                            
                        }
                        if($arTurno->getHoraHasta()->format('h') >= $arHorarioAcceso->getFechaSalida()->format('h')){
                            $intHoraFinal = $arTurno->getHoraHasta()->format('G');
                        } else {
                            $intHoraFinal = $arHorarioAcceso->getFechaSalida()->format('G');
                        }
                        $arrHoras = $this->devuelveHoras($intHoraInicial, $intHoraFinal); 
                        $arrHorasTotal['horasDiurnas'] += $arrHoras['horasDiurnas'];
                        $arrHorasTotal['horasNocturnas'] += $arrHoras['horasNocturnas'];
                        $arrHorasTotal['horasExtrasOrdinariasDiurnas'] += $arrHoras['horasExtrasOrdinariasDiurnas'];
                        $arrHorasTotal['horasExtrasOrdinariasNocturnas'] += $arrHoras['horasExtrasOrdinariasNocturnas'];
                    }
                    
                    $arSoportePagoHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorario();                                                            
                    $arSoportePagoHorario->setEmpleadoRel($arEmpleado);
                    $arSoportePagoHorario->setFechaDesde($dateFechaDesde);
                    $arSoportePagoHorario->setFechaHasta($dateFechaHasta);
                    $arSoportePagoHorario->setHorasDiurnas($arrHorasTotal['horasDiurnas']);
                    $arSoportePagoHorario->setHorasNocturnas($arrHorasTotal['horasNocturnas']);
                    $arSoportePagoHorario->setHorasExtrasOrdinariasDiurnas($arrHorasTotal['horasExtrasOrdinariasDiurnas']);
                    $arSoportePagoHorario->setHorasExtrasOrdinariasNocturnas($arrHorasTotal['horasExtrasOrdinariasNocturnas']);
                    $em->persist($arSoportePagoHorario);                    
                }                
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_proceso_soporte_pago_horario'));
            }
        }
        $arSoportesPagoHorario = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        //$arSoportesPagoHorarioDetalle = $paginator->paginate($em->createQuery($this->strListaDqlDetalle), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarSoportePagoHorario:generar.html.twig', array(
            'arSoportesPagosHorarios' => $arSoportesPagoHorario,
            //'arSoportesPagosDetalles' => $arSoportesPagoDetalle,
            'form' => $form->createView()));
    }
    
    private function devuelveCodigoTurno($diaSemana, $arHorario) {
        $codigoTurno = "";
        if($diaSemana == 1) {
            $codigoTurno = $arHorario->getLunes();
        }
        if($diaSemana == 2) {
            $codigoTurno = $arHorario->getMartes();
        }
        if($diaSemana == 3) {
            $codigoTurno = $arHorario->getMiercoles();
        }
        if($diaSemana == 4) {
            $codigoTurno = $arHorario->getJueves();
        }
        if($diaSemana == 5) {
            $codigoTurno = $arHorario->getViernes();
        }
        if($diaSemana == 6) {
            $codigoTurno = $arHorario->getSabado();
        }
        if($diaSemana == 7) {
            $codigoTurno = $arHorario->getDomingo();
        }
        return $codigoTurno;
    }
    
    private function devuelveHoras ($horaInicio, $horaFin) {        
        $intHorasDiurnas = 0;
        $intHorasNocturnas = 0;
        $intHorasExtrasOrdinariasDiurnas = 0;
        $intHorasExtrasOrdinariasNocturnas = 0;        
        $intTotalHoras = 0;
        $horaInicio++;
        if($horaInicio < $horaFin) {
            for($hora = $horaInicio; $hora <= $horaFin; $hora++) {
                if($hora > 6 && $hora <= 22) {
                    if($intTotalHoras <= 8) {
                        $intHorasDiurnas++;
                    } else {
                        $intHorasExtrasOrdinariasDiurnas++;
                    }                    
                }
                if($hora > 22 && $hora <= 23) {
                    if($intTotalHoras <= 8) {
                        $intHorasNocturnas++;
                    } else {
                        $intHorasExtrasOrdinariasNocturnas++;
                    }                    
                }                
                $intTotalHoras++;
            }
        }
        $arrHoras = array(
            'horasDiurnas' => $intHorasDiurnas,
            'horasExtrasOrdinariasDiurnas' => $intHorasExtrasOrdinariasDiurnas,
            'horasNocturnas' => $intHorasNocturnas,
            'horasExtrasOrdinariasNocturnas' => $intHorasExtrasOrdinariasNocturnas);
        return $arrHoras;
    }
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->listaDql();
        //$this->strListaDqlDetalle =  $em->getRepository('BrasaRecursoHumanoBundle:TurSoportePagoDetalle')->listaDql();
    }

    private function formularioGenerar() {
        $form = $this->createFormBuilder()
            ->add('fechaDesde', 'date', array('data' => new \DateTime('now'), 'format' => 'yyyyMMMMdd'))
            ->add('fechaHasta', 'date', array('data' => new \DateTime('now'), 'format' => 'yyyyMMMMdd'))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->add('BtnCerrar', 'submit', array('label'  => 'Cerrar'))                
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))                        
            ->getForm();
        return $form;
    }
    
}