<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoExamenType;


class EmpleadoExamenController extends Controller
{
    public function nuevoAction($codigoEmpleado, $codigoEmpleadoExamen = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arEmpleadoExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoExamen();
        if($codigoEmpleadoExamen != 0) {
            $arEmpleadoExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoExamen')->find($codigoEmpleadoExamen);
        } else {
            $arEmpleadoExamen->setFechaVencimiento(new \DateTime('now'));
        }            
        
        $form = $this->createForm(new RhuEmpleadoExamenType, $arEmpleadoExamen);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arEmpleadoExamen = $form->getData();            
            //$arEmpleadoExamen->setFecha(new \DateTime('now'));
            $arEmpleadoExamen->setEmpleadoRel($arEmpleado);
            $em->persist($arEmpleadoExamen);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_empleado_examen_nuevo', array('codigoEmpleado' => $codigoEmpleado, 'codigoEmpleadoExamen' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoExamen:nuevo.html.twig', array(
            'arEmpleadoExamen' => $arEmpleadoExamen,
            'form' => $form->createView()));
    }
  
}
