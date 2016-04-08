<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class HorarioAccesoController extends Controller
{
    
    /**
     * @Route("/rhu/utilidad/horario/acceso/empleado", name="brs_rhu_utilidad_horario_acceso_empleado")
     */ 
    public function listaAction() {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $strDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->listaDql(1,0);
        $arHorarioPeriodo = $paginator->paginate($em->createQuery($strDql), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/HorarioAcceso:lista.html.twig', array(
            'arHorarioPeriodos' => $arHorarioPeriodo
                ));
    }            
    
    /**
     * @Route("/rhu/utilidad/horario/acceso/empleado/detalle/{codigoHorarioPeriodo}", name="brs_rhu_utilidad_horario_acceso_empleado_detalle")
     */     
    public function detalleAction($codigoHorarioPeriodo) {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arHorarioPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo();
        $arHorarioPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->find($codigoHorarioPeriodo);        
        $dateFechaDiaAnterior = date_create($arHorarioPeriodo->getFechaPeriodo()->format('Y/m/d'));
        $dateFechaDiaAnterior = date_add($dateFechaDiaAnterior, date_interval_create_from_date_string('-1 days'));        
        $arHorarioPeriodoDiaAnterior = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo();
        $arHorarioPeriodoDiaAnterior = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->findOneBy(array('fechaPeriodo' => $dateFechaDiaAnterior));                        
        $codigoHorarioPeriodoDiaAnterior = 0;
        if($arHorarioPeriodoDiaAnterior) {
            $codigoHorarioPeriodoDiaAnterior = $arHorarioPeriodoDiaAnterior->getCodigoHorarioPeriodoPk();
        }
        $form = $this->createFormBuilder()    
                    ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
                    ->getForm();               
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arrControles = $request->request->All();            
            if($arrControles['txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
                if($arEmpleado) {
                    $arHorarioAccesoDiaAnt = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->verificarSalidaPendiente($codigoHorarioPeriodoDiaAnterior, $arEmpleado->getCodigoEmpleadoPk());
                    if($arHorarioAccesoDiaAnt) {
                        $dateFechaSalida = new \DateTime('now');
                        //Pendiente este segmento es para turnos de un dia para otro
                    } else {
                        $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
                        $arHorarioAcceso = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->findOneBy(array('codigoHorarioPeriodoFk' => $codigoHorarioPeriodo, 'codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));                     
                        if($arHorarioAcceso) {
                            $arHorarioAccesoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
                            $arHorarioAccesoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->find($arHorarioAcceso->getCodigoHorarioAccesoPk());                                                                             
                            if ($arHorarioAcceso->getEstadoEntrada() == 0 ){
                                $dateFechaEntradaTurno = $arHorarioPeriodo->getFechaPeriodo()->format('Y/m/d') . ' ' . $arHorarioAcceso->getHoraEntradaTurno()->format('H:i:s');
                                $dateFechaEntradaTurno = date_create($dateFechaEntradaTurno);
                                $dateFechaEntrada = new \DateTime('now');
                                if($dateFechaEntrada->format('Y/m/d') == $dateFechaEntradaTurno->format('Y/m/d')) {
                                    //calculo entrada tarde
                                    if ($dateFechaEntrada > $dateFechaEntradaTurno){   
                                        $dateDiferenciaFecha = $dateFechaEntrada->diff($dateFechaEntradaTurno);                                    
                                        $intTiempoLlegadaTarde = ($dateDiferenciaFecha->format('%h') * 60) + $dateDiferenciaFecha->format('%i');                                                                                                                                                                                                                       
                                        $arHorarioAccesoActualizar->setEntradaTarde(1);
                                        $arHorarioAccesoActualizar->setDuracionLlegadaTarde($intTiempoLlegadaTarde);
                                    }
                                    $arHorarioAccesoActualizar->setFechaEntrada($dateFechaEntrada);
                                    $arHorarioAccesoActualizar->setEstadoEntrada(1);
                                    $em->persist($arHorarioAccesoActualizar);
                                    $em->flush();                                
                                } else {
                                    $objMensaje->Mensaje("error", "Solo puede realizar ingresos de la fecha actual", $this);                                
                                }
                                return $this->redirect($this->generateUrl('brs_rhu_utilidad_horario_acceso_empleado_detalle', array('codigoHorarioPeriodo' => $codigoHorarioPeriodo)));
                            } else {
                                if ($arHorarioAcceso->getEstadoSalida() == 0 ){
                                    $dateFechaSalida = new \DateTime('now');
                                    $dateFechaSalidaTurno = $arHorarioAcceso->getFechaSalida()->format('Y/m/d') . ' ' . $arHorarioAcceso->getHoraSalidaTurno()->format('H:i:s');
                                    $dateFechaSalidaTurno = date_create($dateFechaSalidaTurno);                                    
                                    if($dateFechaSalida < $dateFechaSalidaTurno) {
                                        $dateDiferenciaFecha = $dateFechaSalidaTurno->diff($dateFechaSalida);                                    
                                        $intTiempoSalidaAnticipada = ($dateDiferenciaFecha->format('%h') * 60) + $dateDiferenciaFecha->format('%i');                                                                                                                                                                                                                       
                                        $arHorarioAccesoActualizar->setSalidaAntes(1);
                                        $arHorarioAccesoActualizar->setDuracionSalidaAntes($intTiempoSalidaAnticipada);                                        
                                    }
                                    $arHorarioAccesoActualizar->setFechaSalida($dateFechaSalida);
                                    $arHorarioAccesoActualizar->setEstadoSalida(1);
                                    $em->persist($arHorarioAccesoActualizar);
                                    $em->flush();
                                    return $this->redirect($this->generateUrl('brs_rhu_utilidad_horario_acceso_empleado_detalle', array('codigoHorarioPeriodo' => $codigoHorarioPeriodo)));
                                } else {
                                    if ($arHorarioAcceso->getEstadoEntrada() == 1 && $arHorarioAcceso->getEstadoSalida() == 1 ){
                                        $objMensaje->Mensaje("error", "El empleado registra entrada y salida el día de hoy", $this);
                                    }
                                }
                            }
                        } else {
                            $objMensaje->Mensaje("error", "El número de identificacion no se encuentra registrada en el horario de este periodo", $this);
                        }                        
                    }                    
                }
            }else {
                    $objMensaje->Mensaje("error", "Digite por favor el numero de identificación", $this);
            }                 
        }
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->listaDql2($codigoHorarioPeriodo);        
        $arHorarioAccesos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 40);   
        $arHorarioAccesosDiaAnterior = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        if($codigoHorarioPeriodoDiaAnterior != 0) {
            $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->listaDql3($codigoHorarioPeriodoDiaAnterior);        
            $arHorarioAccesosDiaAnterior = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 40);                       
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/HorarioAcceso:detalle.html.twig', array(        
        'arHorarioAccesos' => $arHorarioAccesos,
        'arHorarioAccesosAnt' => $arHorarioAccesosDiaAnterior,
        'form' => $form->createView()));
    }
    
    public function cargarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $rutaTemporal = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $rutaTemporal = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $form = $this->createFormBuilder()
            ->add('attachment', 'file')
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        $arrErrores = array();
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $form['attachment']->getData()->move($rutaTemporal->getRutaTemporal(), "carga.txt");
                $fp = fopen($rutaTemporal->getRutaTemporal()."carga.txt", "r");
                $arrRegistros = array();
                while(!feof($fp)) {
                    $linea = fgets($fp);
                    if($linea){
                        $arrayDetalle = explode(";", $linea);
                        if($arrayDetalle[0] != "") {
                            $arrRegistros[] = array('identificacion' => $arrayDetalle[0], 
                                'fechaIngreso' => $arrayDetalle[1],
                                'fechaSalida' => $arrayDetalle[2]);
                        }
                    }
                }                
                fclose($fp);                
                /*foreach ($arrRegistros as $arrRegistro) { 
                    $i = 0;
                    foreach ($arrRegistros as $arrRegistroValidar) {                         
                        if($arrRegistro['identificacion'] == $arrRegistroValidar['identificacion']) {
                            $i++;
                            if($i > 1) {
                                $arrErrores[] = array('error' => "La identificacion " . $arrRegistro['identificacion'] . " esta ducplicada en el archivo");                                
                            }                            
                        }
                    }
                }*/
                foreach ($arrRegistros as $arrRegistro) {                    
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrRegistro['identificacion']));
                    if(count($arEmpleado) > 0) {
                        if($arEmpleado->getCodigoContratoActivoFk() != ''){
                            $dateFechaIngreso = $arrRegistro['fechaIngreso'];
                            $dateFechaIngreso = new \DateTime($dateFechaIngreso);
                            $dateFechaSalida = $arrRegistro['fechaSalida'];
                            $dateFechaSalida = new \DateTime($dateFechaSalida);                            
                            if($em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->validarIngreso($dateFechaIngreso->format('Y/m/d'), $arEmpleado->getCodigoEmpleadoPk()) == FALSE) {
                                $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
                                $arHorarioAcceso->setEmpleadoRel($arEmpleado);
                                $arHorarioAcceso->setFechaEntrada($dateFechaIngreso);
                                $arHorarioAcceso->setFechaSalida($dateFechaSalida);
                                $arHorarioAcceso->setEstadoSalida(1);
                                $arHorarioAcceso->setDuracionRegistro('0');
                                $em->persist($arHorarioAcceso);                                                                    
                            } else {
                                $arrErrores[] = array('error' => "El empleado " . $arrRegistro['identificacion'] . " ya registra ingreso");                                    
                            }
                        }else {
                            $arrErrores[] = array('error' => "El empleado " . $arrRegistro['identificacion'] . " " . $arEmpleado->getNombreCorto() . " no tiene contrato");                                
                        }                                                                                               
                    }else{
                        $arrErrores[] = array('error' => "El numero de identificación " . $arrRegistro['identificacion'] . " no existe");                                
                    }                                     
                }
                                
                if(count($arrErrores) <= 0) {
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                            
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/HorarioAcceso:cargarRegistro.html.twig', array(
            'arrErrores' => $arrErrores,
            'form' => $form->createView()
            ));
    }     
          
}
