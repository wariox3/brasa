<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoEstudioType;


class EmpleadoEstudioController extends Controller
{
    public function nuevoAction($codigoEmpleado, $codigoEmpleadoEstudio = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arEmpleadoEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        if($codigoEmpleadoEstudio != 0) {
            $arEmpleadoEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEmpleadoEstudio);
        }            
        
        $form = $this->createForm(new RhuEmpleadoEstudioType, $arEmpleadoEstudio);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arEmpleadoEstudio = $form->getData();            
            //$arEmpleadoEstudio->setFecha(new \DateTime('now'));
            $arEmpleadoEstudio->setEmpleadoRel($arEmpleado);
            $em->persist($arEmpleadoEstudio);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_empleadoEstudio_nuevo', array('codigoEmpleado' => $codigoEmpleado, 'codigoEmpleadoEstudio' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoEstudio:nuevo.html.twig', array(
            'arEmpleadoEstudio' => $arEmpleadoEstudio,
            'form' => $form->createView()));
    }
  
}
