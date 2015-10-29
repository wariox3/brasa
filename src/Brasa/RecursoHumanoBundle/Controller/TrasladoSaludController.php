<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class TrasladoSaludController extends Controller
{
    public function nuevoAction($codigoContrato, $codigoTrasladoSalud = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arTrasladoSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if ($codigoTrasladoSalud != 0)
        {
            $arTrasladoSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuTrasladoSalud')->find($codigoTrasladoSalud);
        }
        if ($arContrato->getEstadoActivo()== 0){
            $objMensaje->Mensaje("error", "No tiene contrato activo", $this);
        }
        $form = $this->createFormBuilder()
            ->add('entidadSaludNuevaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('es')
                    ->orderBy('es.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('fechaAplicacion', 'date', array('data' => new \DateTime('now')))
            ->add('detalle', 'text', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            if ($arContrato->getEstadoActivo()== 0){
                $objMensaje->Mensaje("error", "No tiene contrato activo", $this);
            }else{
                $arEntidadSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
                $arEntidadSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->find($arContrato->getCodigoEntidadSaludFk());
                $arTrasladoSalud->setContratoRel($arContrato);
                $arTrasladoSalud->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arTrasladoSalud->setFecha($form->get('fechaAplicacion')->getData());
                $arTrasladoSalud->setEntidadSaludNuevaRel($form->get('entidadSaludNuevaRel')->getData());
                $arTrasladoSalud->setEntidadSaludAnteriorRel($arEntidadSalud);
                $arTrasladoSalud->setDetalle($form->get('detalle')->getData());
                $arContrato->setEntidadSaludRel($form->get('entidadSaludNuevaRel')->getData());
                $arEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getEmpleadoRel());
                if ($arEmpleadoActualizar->getCodigoCentroCostoFk() <> null){
                    $arEmpleadoActualizar->setEntidadSaludRel($form->get('entidadSaludNuevaRel')->getData());
                    $em->persist($arEmpleadoActualizar);
                }
                $em->persist($arContrato);
                $em->persist($arTrasladoSalud);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:TrasladoSalud:nuevo.html.twig', array(
            'form' => $form->createView(),
            'arContrato' => $arContrato,
        ));
    }

}
