<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class ProcesoGenerarPedidoController extends Controller
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
                $dateFecha = $form->get('fecha')->getData();
                $arPedidos = new \Brasa\TurnoBundle\Entity\TurPedido();
                $query = $em->createQuery($this->strListaDql);
                $arPedidos = $query->getResult();
                foreach ($arPedidos as $arPedido) {
                    $arPedidoTipo = $em->getRepository('BrasaTurnoBundle:TurPedidoTipo')->find(1);
                    $arPedidoNuevo = new \Brasa\TurnoBundle\Entity\TurPedido();
                    $arPedidoNuevo = clone $arPedido;                    
                    $arPedidoNuevo->setPedidoTipoRel($arPedidoTipo);
                    $arPedidoNuevo->setFecha($dateFecha);
                    $em->persist($arPedidoNuevo);                    
                                        
                    $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalles =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $arPedido->getCodigoPedidoPk())); 
                    foreach ($arPedidoDetalles as $arPedidoDetalle) {
                        $arPedidoDetalleNuevo = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalleNuevo = clone $arPedidoDetalle;
                        $arPedidoDetalleNuevo->setPedidoRel($arPedidoNuevo);
                        if($arPedidoDetalle->getCodigoPeriodoFk() == 1) {
                            $strFechaInicio = $dateFecha->format('Y/m') . '/01';                        
                            $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
                            $strFechaFinal = $dateFecha->format('Y/m') . '/' . $strUltimoDiaMes;
                        } else {
                            $strAnioMes = $dateFecha->format('Y/m');
                            $strFechaInicio = $strAnioMes . "/" . $arPedidoDetalle->getFechaDesde()->format('d');                                                    
                            $strFechaFinal = $strAnioMes . "/" . $arPedidoDetalle->getFechaHasta()->format('d');                                                    
                        }
                        $dateFechaDesde = date_create($strFechaInicio);
                        $dateFechaHasta = date_create($strFechaFinal);                        
                        $arPedidoDetalleNuevo->setFechaDesde($dateFechaDesde);
                        $arPedidoDetalleNuevo->setFechaHasta($dateFechaHasta);
                        $em->persist($arPedidoDetalleNuevo);                               
                    }
                }
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_pedido_lista'));                                 
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arPedidos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarPedido:lista.html.twig', array(
            'arPedidos' => $arPedidos, 
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurServicio')->listaDql();
    }
    
    private function formularioLista() {                
        $form = $this->createFormBuilder()
            ->add('fecha', 'date', array('data'  => new \DateTime('now')))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->getForm();
        return $form;
    }        
    
}