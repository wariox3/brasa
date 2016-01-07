<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuHorarioAccesoType;

class UtilidadesHorarioAccesoController extends Controller
{

    public function registroAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        $arHorarioAccesos = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        $fechaHoy = new \DateTime('now');
        $arHorarioAccesos = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->RegistroHoy($fechaHoy);
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
                        return $this->redirect($this->generateUrl('brs_rhu_utilidades_controlacceso_registro'));
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
        
}
