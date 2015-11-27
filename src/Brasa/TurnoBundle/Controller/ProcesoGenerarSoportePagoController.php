<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ProcesoGenerarSoportePagoController extends Controller
{
    var $strListaDql = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_programacion_lista'));                                 
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arSoportesPago = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarConceptoPago:lista.html.twig', array(
            'arProgramaciones' => $arSoportesPago, 
            'form' => $form->createView()));
    }
    
    public function generarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();        
        $form = $this->formularioGenerar();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if ($form->get('BtnGenerar')->isClicked()) {
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                $intDiaInicial = $dateFechaDesde->format('j');
                $intDiaFinal = $dateFechaHasta->format('j');
                $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->periodo($dateFechaDesde->format('Y/m/') . "01",$dateFechaHasta->format('Y/m/') . "31");
                foreach ($arProgramacionDetalles as $arProgramacionDetalle) {              
                    for($i = $intDiaInicial; $i <= $intDiaFinal; $i++) {
                        if($i == 1) {
                            if($arProgramacionDetalle->getDia1() != '') {
                                $this->insertarSoportePago($arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $arProgramacionDetalle->getDia1());
                            }
                        }                      
                        if($i == 2) {
                            if($arProgramacionDetalle->getDia2() != '') {
                                $this->insertarSoportePago($arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $arProgramacionDetalle->getDia2());
                            }
                        }                        
                    }
                }
                $em->flush();
            }
        }
              
        return $this->render('BrasaTurnoBundle:Procesos/GenerarConceptoPago:generar.html.twig', array(
            'form' => $form->createView()));
    }
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurSoportePago')->listaDql();
    }
    
    private function formularioGenerar() {                
        $form = $this->createFormBuilder()
            ->add('fechaDesde', 'date', array('data' => new \DateTime('now')))
            ->add('fechaHasta', 'date', array('data' => new \DateTime('now')))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->getForm();
        return $form;
    }    
    
    private function insertarSoportePago ($arProgramacionDetalle, $dateFechaDesde, $dateFechaHasta, $codigoTurno) {
        $em = $this->getDoctrine()->getManager();
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);   
        $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
        $arSoportePago->setRecursoRel($arProgramacionDetalle->getRecursoRel());
        $arSoportePago->setFechaDesde($dateFechaDesde);
        $arSoportePago->setFechaHasta($dateFechaHasta);
        $arSoportePago->setTurnoRel($arTurno);
        $em->persist($arSoportePago);          
    }
    
}