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
            $arContrato->setVrSalario(644350); //Parametrizar con configuracion salario minimo
        }
        $form = $this->createForm(new RhuContratoType(), $arContrato);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arContrato = $form->getData();
            $arContrato->setEmpleadoRel($arEmpleado);
            $em->persist($arContrato);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_empleados_detalles', array('codigoEmpleado' => $codigoEmpleado)));
        }

        return $this->render('BrasaRecursoHumanoBundle:Contratos:nuevo.html.twig', array(
            'arContrato' => $arContrato,
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }
}
