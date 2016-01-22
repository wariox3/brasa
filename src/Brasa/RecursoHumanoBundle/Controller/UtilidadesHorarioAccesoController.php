<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuHorarioAccesoType;

class UtilidadesHorarioAccesoController extends Controller
{

    public function registroAction() {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        $arHorarioAccesos = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        $fechaHoy = new \DateTime('now');
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->RegistroHoy($fechaHoy->format('Y/m/d'));
        $arHorarioAccesos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 40);
        $form = $this->createForm(new RhuHorarioAccesoType, $arHorarioAcceso);         
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arrControles = $request->request->All();
            $arHorarioAcceso = $form->getData();
            if($arrControles['txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arHorarioAcceso->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {
                        $arEmpleadoEntrada = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->RegistroEntrada($fechaHoy->format('Y/m/d'),$arEmpleado->getCodigoEmpleadoPk());
                        if (count($arEmpleadoEntrada) != 0){
                            $objMensaje->Mensaje("error", "El empleado se encuentra registrado", $this);
                        }else {
                            $arHorarioAcceso->setFechaEntrada(new \DateTime('now'));
                            $em->persist($arHorarioAcceso);
                            $em->flush();
                            return $this->redirect($this->generateUrl('brs_rhu_utilidades_control_acceso_empleado'));
                        }
                    }else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                        }                       
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }                    
            }else {
                    $objMensaje->Mensaje("error", "Digite por favor el numero de identificación", $this);
                }                 
            }
            return $this->render('BrasaRecursoHumanoBundle:Utilidades/HorarioAcceso:registro.html.twig', array(
            'arHorarioAcceso' => $arHorarioAcceso,
            'arHorarioAccesos' => $arHorarioAccesos,
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
    
    public function salidaAction($codigoHorarioAcceso) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_salida_control_acceso_empleados', array('codigoHorarioAcceso' => $codigoHorarioAcceso)))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        $arHorarioAcceso = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->find($codigoHorarioAcceso);
        if ($form->isValid()) {
            
            $arHorarioAcceso->setFechaSalida(new \DateTime('now'));
            $arHorarioAcceso->setEstadoSalida(1);
            $arHorarioAcceso->setComentarios($form->get('comentarios')->getData());
            $dateEntrada = $arHorarioAcceso->getFechaEntrada();
            $dateSalida = $arHorarioAcceso->getFechaSalida();
            $dateDiferencia = date_diff($dateSalida, $dateEntrada);
            $horas = $dateDiferencia->format('%H');
            $minutos = $dateDiferencia->format('%i');
            $segundos = $dateDiferencia->format('%s');
            $diferencia = $horas.":".$minutos.":".$segundos;
            $arHorarioAcceso->setDuracionRegistro($diferencia);
            $em->persist($arHorarioAcceso);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_utilidades_control_acceso_empleado'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/HorarioAcceso:salida.html.twig', array(
            '$arHorarioAcceso' => $arHorarioAcceso,
            'form' => $form->createView()
        ));
    }
        
}
