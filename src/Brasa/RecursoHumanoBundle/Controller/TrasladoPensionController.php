<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class TrasladoPensionController extends Controller
{
    public function nuevoAction($codigoContrato, $codigoTrasladoPension = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arTrasladoPension = new \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if ($codigoTrasladoPension != 0)
        {
            $codigoTrasladoPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuTrasladoPension')->find($codigoTrasladoPension);
        }
        if ($arContrato->getEstadoActivo()== 0){
            $objMensaje->Mensaje("error", "No tiene contrato activo", $this);
        }
        $form = $this->createFormBuilder()
            ->add('entidadPensionNuevaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadPension',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ep')
                    ->orderBy('ep.nombre', 'ASC');},
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
                $arEntidadPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
                $arEntidadPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->find($arContrato->getCodigoEntidadPensionFk());
                $arTrasladoPension->setContratoRel($arContrato);
                $arTrasladoPension->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arTrasladoPension->setFecha($form->get('fechaAplicacion')->getData());
                $arTrasladoPension->setEntidadPensionNuevaRel($form->get('entidadPensionNuevaRel')->getData());
                $arTrasladoPension->setEntidadPensionAnteriorRel($arEntidadPension);
                $arTrasladoPension->setDetalle($form->get('detalle')->getData());
                $arContrato->setEntidadPensionRel($form->get('entidadPensionNuevaRel')->getData());
                $arEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getEmpleadoRel());
                if ($arEmpleadoActualizar->getCodigoCentroCostoFk() <> null){
                    $arEmpleadoActualizar->setEntidadPensionRel($form->get('entidadPensionNuevaRel')->getData());
                    $em->persist($arEmpleadoActualizar);
                }
                $em->persist($arContrato);
                $em->persist($arTrasladoPension);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
            }
            
        }
        return $this->render('BrasaRecursoHumanoBundle:TrasladoPension:nuevo.html.twig', array(
            'form' => $form->createView(),
            'arContrato' => $arContrato,
        ));
    }

}
