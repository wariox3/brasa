<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ProcesoGenerarProgramacionController extends Controller
{
    var $strListaDql = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if ($form->get('BtnGenerar')->isClicked()) { 
                $arPedidos = new \Brasa\TurnoBundle\Entity\TurPedido();
                $query = $em->createQuery($this->strListaDql);
                $arPedidos = $query->getResult();
                foreach ($arPedidos as $arPedido) {
                    $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalles =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('arPedido' => $arPedido->getCodigoPedidoPk())); 
                    foreach ($arPedidoDetalles as $arPedidoDetalle) {
                        
                    }
                }
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_programacion_lista'));                                 
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arPedidos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarProgramacion:lista.html.twig', array(
            'arPedidos' => $arPedidos, 
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurPedido')->pedidoPermanenteDql();
    }
    
    private function formularioLista() {                
        $form = $this->createFormBuilder()
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
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