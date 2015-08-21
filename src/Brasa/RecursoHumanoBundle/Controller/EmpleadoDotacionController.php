<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoDotacionType;


class EmpleadoDotacionController extends Controller
{
    public function nuevoAction($codigoEmpleado, $codigoEmpleadoDotacion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arEmpleadoDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion();
        if($codigoEmpleadoDotacion != 0) {
            $arEmpleadoDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoDotacion')->find($codigoEmpleadoDotacion);
        }            
        $arEmpleadoDotacion->setFecha(new \DateTime('now'));
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arEmpleado->getCodigoCentroCostoFk());
        $arEmpleadoDotacion->setCentroCostoRel($arCentroCosto);
        $form = $this->createForm(new RhuEmpleadoDotacionType, $arEmpleadoDotacion);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arEmpleadoDotacion = $form->getData();            
            $arEmpleadoDotacion->setEmpleadoRel($arEmpleado);
            $em->persist($arEmpleadoDotacion);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_empleado_dotacion_nuevo', array('codigoEmpleado' => $codigoEmpleado, 'codigoEmpleadoDotacion' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoDotacion:nuevo.html.twig', array(
            'arEmpleadoDotacion' => $arEmpleadoDotacion,
            'form' => $form->createView()));
    }
  
}
