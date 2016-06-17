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
     * @Route("/tur/utilidad/simular/programacion/{codigoServicio}/{codigoServicioDetalle}", name="brs_tur_utilidad_simular_programacion")
     */    
    public function listaAction($codigoServicio, $codigoServicioDetalle) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        $fechaProgramacion = $form->get('fecha')->getData();
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {      
                $strSql = "DELETE FROM tur_simulacion_detalle WHERE 1 ";           
                $em->getConnection()->executeQuery($strSql);                
                $arServicio = new \Brasa\TurnoBundle\Entity\TurServicio();
                $arServicio = $em->getRepository('BrasaTurnoBundle:TurServicio')->find($codigoServicio); 
                $arServicioDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                if($codigoServicioDetalle == 0) {                    
                    $arServicioDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigoServicio));                                    
                } else {
                    $arServicioDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $codigoServicio, 'codigoServicioDetallePk' => $codigoServicioDetalle));                                    
                }                
                foreach ($arServicioDetalles as $arServicioDetalle) {            
                    $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->nuevo($arServicioDetalle->getCodigoServicioDetallePk(), $fechaProgramacion);
                }     
                $fechaProgramacion = $form->get('fecha')->getData();
                $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
                $arConfiguracion->setFechaUltimaSimulacion($fechaProgramacion);
                $em->persist($arConfiguracion);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_utilidad_simular_programacion', array('codigoServicio' => $codigoServicio, 'codigoServicioDetalle' => $codigoServicioDetalle))); 
            } 
        }                 
        $strAnioMes = $fechaProgramacion->format('Y/m');
        $arrDiaSemana = array();
        for($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $boolFestivo = 0;
            if($diaSemana == 'd') {
                $boolFestivo = 1;
            }
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana, 'festivo' => $boolFestivo);
        }        
        //$dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionInconsistencia')->listaDql();
        //$arProgramacionInconsistencias = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        $arSimulacionDetalle = $em->getRepository('BrasaTurnoBundle:TurSimulacionDetalle')->findAll();
        return $this->render('BrasaTurnoBundle:Utilidades/Simular:programacion.html.twig', array(            
            'arSimulacionDetalle' => $arSimulacionDetalle,
            'arrDiaSemana' => $arrDiaSemana,
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {                
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);         
        $form = $this->createFormBuilder()                        
            ->add('fecha', 'date', array('data' => $arConfiguracion->getFechaUltimaSimulacion(), 'format' => 'yyyyMMdd'))                            
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))       
            ->getForm();        
        return $form;
    }           

    private function devuelveDiaSemanaEspaniol ($dateFecha) {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "l";
                break;
            case 2:
                $strDia = "m";
                break;
            case 3:
                $strDia = "i";
                break;
            case 4:
                $strDia = "j";
                break;
            case 5:
                $strDia = "v";
                break;
            case 6:
                $strDia = "s";
                break;
            case 7:
                $strDia = "d";
                break;
        }

        return $strDia;
    }    
    
}
