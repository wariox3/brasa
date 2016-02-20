<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ContratoProrrogaController extends Controller
{
    public function nuevoAction($codigoContrato, $codigoContratoProrroga = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContratoProrroga = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoProrroga();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if ($codigoContratoProrroga != 0)
        {
            $arContratoProrroga = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoProrroga')->find($codigoContratoProrroga);
            $fechaDesdeProrroga = $arContratoProrroga->getFechaInicialNueva();
            $fechaHastaProrroga = $arContratoProrroga->getFechaFinalNueva();
            $detalle = $arContratoProrroga->getDetalle();
        } else {
            $fechaDesdeProrroga = new \DateTime('now');
            $fechaHastaProrroga = new \DateTime('now');
            $detalle = $arContratoProrroga->getDetalle();
        }    
        $form = $this->createFormBuilder()
            ->add('fechaInicioNueva', 'date', array('data' => $fechaDesdeProrroga))
            ->add('fechaFinalNueva', 'date', array('data' => $fechaHastaProrroga))    
            ->add('detalle', 'text', array('data' => $detalle,'required' => true))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $boolValidarContratoFijo = TRUE;
            $fechaDesde = $form->get('fechaInicioNueva')->getData();
            $fechaHasta = $form->get('fechaFinalNueva')->getData();
            $nuevafecha = strtotime ( '+1 year' , strtotime ( $fechaDesde->format('Y-m-d'))) ;
            $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
            if ($fechaHasta->format('Y-m-d') >= $nuevafecha){
                 $boolValidarContratoFijo = FALSE;
            }
            if ($fechaHasta <= $fechaDesde ){
               $objMensaje->Mensaje("error", "La fecha hasta no puede ser menor o igual a la fecha desde", $this);
            } else {
               if ($boolValidarContratoFijo == FALSE){
                    $objMensaje->Mensaje("error", "La prorroga no puede ser mayor o igual a un año", $this);;
               } else {
                    if ($codigoContratoProrroga = 0){
                        $arContratoProrroga->setContratoRel($arContrato);
                        $arContratoProrroga->setCodigoEmpleadoFk($arContrato->getEmpleadoRel()->getCodigoEmpleadoPk());
                        $arContratoProrroga->setFecha(new \DateTime('now'));
                        if ($arContrato->getFechaProrrogaInicio() == null){
                            $arContratoProrroga->setFechaInicialAnterior($arContrato->getFechaDesde());
                            $arContratoProrroga->setFechaFinalAnterior($arContrato->getFechaHasta());
                        }
                        else {
                            $arContratoProrroga->setFechaInicialAnterior($arContrato->getFechaProrrogaInicio());
                            $arContratoProrroga->setFechaFinalAnterior($arContrato->getFechaProrrogaFinal());
                        }
                        $arContratoProrroga->setFechaInicialNueva($form->get('fechaInicioNueva')->getData());
                        $arContratoProrroga->setFechaFinalNueva($form->get('fechaFinalNueva')->getData());
                        $dateFechaInicialProrroga = $form->get('fechaInicioNueva')->getData();
                        $dateFechaFinalProrroga = $form->get('fechaFinalNueva')->getData();
                        //inicio calculo meses           
                        $interval = $dateFechaInicialProrroga->diff($dateFechaFinalProrroga);
                        $interval = round($interval->format('%a%') / 30);
                        //fin calculo meses
                        $arContratoProrroga->setMeses($interval);
                        $arContratoProrroga->setDetalle($form->get('detalle')->getData());
                        $arContrato->setFechaHasta($form->get('fechaFinalNueva')->getData());
                        $arContrato->setFechaProrrogaInicio($form->get('fechaInicioNueva')->getData());
                        $arContrato->setFechaProrrogaFinal($form->get('fechaFinalNueva')->getData());
                        $em->persist($arContratoProrroga);
                        $em->persist($arContrato);
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
                    } else {    
                        $arContratoProrroga->setContratoRel($arContrato);
                        $arContratoProrroga->setCodigoEmpleadoFk($arContrato->getEmpleadoRel()->getCodigoEmpleadoPk());
                        $arContratoProrroga->setFecha(new \DateTime('now'));
                        $dateFechaInicialProrroga = $form->get('fechaInicioNueva')->getData();
                        $dateFechaFinalProrroga = $form->get('fechaFinalNueva')->getData();
                        //inicio calculo meses           
                        $interval = $dateFechaInicialProrroga->diff($dateFechaFinalProrroga);
                        $interval = round($interval->format('%a%') / 30);
                        //fin calculo meses
                        $arContratoProrroga->setMeses($interval);
                        $arContratoProrroga->setDetalle($form->get('detalle')->getData());
                        $arContratoProrroga->setFechaInicialNueva($form->get('fechaInicioNueva')->getData());
                        $arContratoProrroga->setFechaFinalNueva($form->get('fechaFinalNueva')->getData());
                        $arContrato->setFechaHasta($form->get('fechaFinalNueva')->getData());
                        $arContrato->setFechaProrrogaInicio($form->get('fechaInicioNueva')->getData());
                        $arContrato->setFechaProrrogaFinal($form->get('fechaFinalNueva')->getData());
                        $em->persist($arContratoProrroga);
                        $em->persist($arContrato);
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
                      }        
                }   
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:ContratoProrroga:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
