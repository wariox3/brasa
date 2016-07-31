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
            ->add('fechaFosyga', 'date', array('data' => new \DateTime('now')))                
            ->add('detalle', 'text', array('required' => true))
            ->add('tipo', 'choice', array('choices' => array('1' => 'TRASLADO', '2' => 'CAMBIO')))                                                
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
                $arTrasladoPension->setTipo($form->get('tipo')->getData());
                $arTrasladoPension->setFechaFosyga($form->get('fechaFosyga')->getData());
                if ($form->get('tipo')->getData() == 1){
                    $arTrasladoPension->setEstadoAfiliado(1);
                }
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
    
    public function editarAction($codigoContrato, $codigoTrasladoPension = 0) {
        /*$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arTrasladoPension = new \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if ($codigoTrasladoPension != 0)
        {
            $arTrasladoPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuTrasladoPension')->find($codigoTrasladoPension);
            $estado = $arTrasladoPension->getEstadoAfiliado();
            if ($estado == 1){
                $nombreEstado = "TRASLADO";
            } else {
                $nombreEstado = "CAMBIO";
            }
        }
        if ($arContrato->getEstadoActivo()== 0){
            $objMensaje->Mensaje("error", "No tiene contrato activo", $this);
        }
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_traslado_pension_editar', array('codigoContrato' => $codigoContrato, 'codigoTrasladoPension' => $codigoTrasladoPension)))
            ->add('fechaCambioAfiliacion', 'date', array('data' => new \DateTime('now')))
            ->add('estado', 'choice', array('choices' => array($estado => $nombreEstado, '1' => 'TRASLADO', '2' => 'CAMBIO')))                                                
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
                $arTrasladoPension->setTipo($form->get('tipo')->getData());
                $arTrasladoPension->setFechaFosyga($form->get('fechaFosyga')->getData());
                if ($form->get('tipo')->getData() == 1){
                    $arTrasladoPension->setEstadoAfiliado(1);
                }
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
            
        }*/
        /*return $this->render('BrasaRecursoHumanoBundle:TrasladoPension:nuevo.html.twig', array(
            'form' => $form->createView(),
            'arContrato' => $arContrato,
        ));*/
    }

}
