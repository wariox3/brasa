<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class CambioSalarioController extends Controller
{
    public function nuevoAction($codigoContrato, $codigoCambioSalario = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCambioSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if ($codigoCambioSalario != 0)
        {
            $arCambioSalario = $em->getRepository('BrasaRecursoHumanoBundle:RhuCambioSalario')->find($codigoCambioSalario);
        }    
        $form = $this->createFormBuilder()
            ->add('salarioNuevo', 'number', array('required' => true))
            ->add('fechaAplicacion', 'date', array('data' => new \DateTime('now')))
            ->add('detalle', 'text', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            if ($arContrato->getFechaUltimoPago() > $form->get('fechaAplicacion')->getData()){
                $objMensaje->Mensaje("error", "El cambio de salario se debe realizar despues del pago del ".$arContrato->getFechaUltimoPago()->format('Y/m/d')."", $this);
            } else {
                $arCambioSalario->setContratoRel($arContrato);
                $arCambioSalario->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arCambioSalario->setFecha($form->get('fechaAplicacion')->getData());
                $arCambioSalario->setVrSalarioNuevo($form->get('salarioNuevo')->getData());
                $arCambioSalario->setVrSalarioAnterior($arContrato->getVrSalario());
                $arCambioSalario->setDetalle($form->get('detalle')->getData());
                $arEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getEmpleadoRel());
                $arEmpleadoActualizar->setVrSalario($form->get('salarioNuevo')->getData());
                $arContrato->setVrSalario($form->get('salarioNuevo')->getData());
                $arContrato->setVrSalarioPago($form->get('salarioNuevo')->getData());
                $em->persist($arEmpleadoActualizar);
                $em->persist($arCambioSalario);  
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
            }
            
        }
        return $this->render('BrasaRecursoHumanoBundle:CambioSalario:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
