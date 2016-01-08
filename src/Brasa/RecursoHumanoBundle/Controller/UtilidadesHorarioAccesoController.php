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
                        $arHorarioAcceso->setFecha(new \DateTime('now'));
                        $em->persist($arHorarioAcceso);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_utilidades_control_acceso_empleado'));
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
        $form = $this->createFormBuilder()
            ->add('attachment', 'file')
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);

        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $form['attachment']->getData()->move("/var/www/temporal", "carga.txt");
                $fp = fopen("/var/www/temporal/carga.txt", "r");
                $empleadoSinContrato = "";
                $empleadoNoExiste = "";
                while(!feof($fp)) {
                    $linea = fgets($fp);
                    if($linea){
                        $arrayDetalle = explode(";", $linea);
                        if($arrayDetalle[0] != "") {
                            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrayDetalle[0]));
                            if(count($arEmpleado) > 0) {
                                $arEmpleadoValidar = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrayDetalle[0], 'codigoCentroCostoFk' => null));
                                if (count($arEmpleadoValidar) > 0){
                                    $empleadoSinContrato = "El numero de identificación " .$arrayDetalle[0]. " No tiene contrato";
                                }else{
                                    //Registro acceso empleado
                                    $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
                                    $arTipoAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuTipoAcceso();
                                    $arTipoAcceso = $em->getRepository('BrasaRecursoHumanoBundle:RhuTipoAcceso')->find($arrayDetalle[1]);
                                    $arHorarioAcceso->setEmpleadoRel($arEmpleado);
                                    $arHorarioAcceso->setTipoAccesoRel($arTipoAcceso);
                                    $dateFecha = $arrayDetalle[2];
                                    $dateFecha = new \DateTime($dateFecha);
                                    $arHorarioAcceso->setFecha($dateFecha);
                                    $em->persist($arHorarioAcceso);
                                }
                            }else{
                                $empleadoNoExiste = "El numero de identificación " .$arrayDetalle[0]. " No existe";
                            }
                        }
                    }
                }
                fclose($fp);
                if ($empleadoNoExiste <> ""){
                    $objMensaje->Mensaje("error", "" .$empleadoNoExiste. "", $this);
                }else{
                    if($empleadoSinContrato <> ""){
                        $objMensaje->Mensaje("error", "" .$empleadoSinContrato. "", $this);                        
                    }else{
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                        
                    }
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/HorarioAcceso:cargarRegistro.html.twig', array(
            'form' => $form->createView()
            ));
    }    
        
}
