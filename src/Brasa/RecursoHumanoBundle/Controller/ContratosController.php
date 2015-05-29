<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuContratoType;

class ContratosController extends Controller
{
    public function nuevoAction($codigoContrato, $codigoEmpleado) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        if($codigoContrato != 0) {
            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        } else {
            $arContrato->setFechaDesde(new \DateTime('now'));
            $arContrato->setFechaHasta(new \DateTime('now'));
            $arContrato->setIndefinido(1);
            $arContrato->setEstadoActivo(1);
            $arContrato->setVrSalario(644350); //Parametrizar con configuracion salario minimo
        }
        $form = $this->createForm(new RhuContratoType(), $arContrato);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arContrato = $form->getData();
            $arContrato->setFecha(date_create(date('Y-m-d H:i:s')));
            $arContrato->setEmpleadoRel($arEmpleado);      
            $em->persist($arContrato);
            $douSalarioMinimo = 644350;
            if($arContrato->getVrSalario() <= $douSalarioMinimo * 2) {
                $arEmpleado->setAuxilioTransporte(1);
            }
            $arEmpleado->setCentroCostoRel($arContrato->getCentroCostoRel());
            $arEmpleado->setTipoTiempoRel($arContrato->getTipoTiempoRel());
            $arEmpleado->setVrSalario($arContrato->getVrSalario());
            $arEmpleado->setFechaContrato($arContrato->getFechaDesde());
            $arEmpleado->setFechaFinalizaContrato($arContrato->getFechaHasta());
            $em->persist($arEmpleado);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }

        return $this->render('BrasaRecursoHumanoBundle:Contratos:nuevo.html.twig', array(
            'arContrato' => $arContrato,
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }

    public function terminarAction($codigoContrato) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $formContrato = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_contratos_terminar', array('codigoContrato' => $codigoContrato)))
            ->add('fechaTerminacion', 'date', array('label'  => 'Terminacion', 'data' => new \DateTime('now')))                            
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $formContrato->handleRequest($request);        
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);        
        //$arContrato->setFechaHasta(new \DateTime('now'));        
        if ($formContrato->isValid()) {
            $fechaHasta = $formContrato->get('fechaTerminacion')->getData()->format('Y-m-d');                        
            $arContrato->setFechaHasta(date_create($fechaHasta));            
            $arContrato->setIndefinido(0);
            $em->persist($arContrato);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_empleados_detalles', array('codigoEmpleado' => $arContrato->getCodigoEmpleadoFk())));
        }

        return $this->render('BrasaRecursoHumanoBundle:Contratos:terminar.html.twig', array(
            'arContrato' => $arContrato,
            'formContrato' => $formContrato->createView()
        ));
    }    
}
