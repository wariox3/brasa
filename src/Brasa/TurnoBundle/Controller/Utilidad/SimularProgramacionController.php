<?php

namespace Brasa\TurnoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;


class SimularProgramacionController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/utilidad/simular/programacion/{codigoServicio}", name="brs_tur_utilidad_simular_programacion")
     */    
    public function listaAction($codigoServicio) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {  
                $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
                $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio); 
                $arServicioDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                $arServicioDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigoServicio));                
                foreach ($arServicioDetalles as $arServicioDetalle) {            
                    $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->nuevo($arServicioDetalle->getCodigoServicioDetallePk());
                }                
                return $this->redirect($this->generateUrl('brs_tur_utilidad_simular_programacion', array('codigoServicio' => $codigoServicio))); 
            } 
        }                 
        //$dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionInconsistencia')->listaDql();
        //$arProgramacionInconsistencias = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        $arSimulacionDetalle = $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->findAll();
        return $this->render('BrasaTurnoBundle:Utilidades/Simular:programacion.html.twig', array(            
            'arSimulacionDetalle' => $arSimulacionDetalle,
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {                

        $form = $this->createFormBuilder()                        
            ->add('fecha', 'date', array('data' => new \DateTime('now'), 'format' => 'yyyyMMdd'))                            
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))       
            ->getForm();        
        return $form;
    }           

}
