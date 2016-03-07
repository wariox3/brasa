<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ProcesoCierreMesController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioGenerar();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            if($request->request->get('OpGenerar')) {            
                $codigoCierreMes = $request->request->get('OpGenerar');
                $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
                $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);
                $strSql = "DELETE FROM tur_cierre_mes_servicio WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;           
                $em->getConnection()->executeQuery($strSql);                
                $strUltimoDiaMes = date("d",(mktime(0,0,0,$arCierreMes->getMes()+1,1,$arCierreMes->getAnio())-1));
                $strFechaDesde = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/01";
                $strFechaHasta = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/" . $strUltimoDiaMes;
                //Creo los servicios (Detalles de pedido)
                $arPedidosDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();                
                $arPedidosDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->fecha($strFechaDesde, $strFechaHasta);                                
                foreach ($arPedidosDetalles as $arPedidoDetalle) {
                    $arCierreMesServicio = new \Brasa\TurnoBundle\Entity\TurCierreMesServicio();
                    $arCierreMesServicio->setCierreMesRel($arCierreMes);
                    $arCierreMesServicio->setAnio($arCierreMes->getAnio());
                    $arCierreMesServicio->setMes($arCierreMes->getMes());
                    $arCierreMesServicio->setPuestoRel($arPedidoDetalle->getPuestoRel());
                    $arCierreMesServicio->setConceptoServicioRel($arPedidoDetalle->getConceptoServicioRel());
                    $arCierreMesServicio->setModalidadServicioRel($arPedidoDetalle->getModalidadServicioRel());
                    $arCierreMesServicio->setPeriodoRel($arPedidoDetalle->getPeriodoRel());
                    $arCierreMesServicio->setDiaDesde($arPedidoDetalle->getDiaDesde());
                    $arCierreMesServicio->setDiaHasta($arPedidoDetalle->getDiaHasta());
                    $arCierreMesServicio->setDias($arPedidoDetalle->getDias());
                    $arCierreMesServicio->setHoras($arPedidoDetalle->getHoras());
                    $arCierreMesServicio->setHorasDiurnas($arPedidoDetalle->getHorasDiurnas());
                    $arCierreMesServicio->setHorasNocturnas($arPedidoDetalle->getHorasNocturnas());
                    $arCierreMesServicio->setCantidad($arPedidoDetalle->getCantidad());
                    $arCierreMesServicio->setVrTotal($arPedidoDetalle->getVrTotalDetalle());                    
                    //$arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoPedidoDetalleFk' => $arPedidoDetalle->getCodigoPedidoDetallePk()));                                
                    /*if($arProgramacionDetalle['horas'] != null) {
                        $arCierreMesServicio->setHorasProgramadas($arProgramacionDetalle['horas']);
                    }*/                    
                    $em->persist($arCierreMesServicio);                    
                }
                $em->flush(); 
                //Creo los soportes de cada servicio (Detalles de programacion)
                $arCierreMesServicio = new \Brasa\TurnoBundle\Entity\TurCierreMesServicio();
                $arCierreMesServicio = $em->getRepository('BrasaTurnoBundle:TurCierreMesServicio')->findBy(array('codigoCierreMesFk' => $arCierreMes->getCodigoCierreMesPk()));                                
                return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));
            }
            /*if($request->request->get('OpDeshacer')) {
                $codigoSoportePagoPeriodo = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM tur_soporte_pago_detalle WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;           
                $em->getConnection()->executeQuery($strSql);
                $strSql = "DELETE FROM tur_soporte_pago WHERE codigo_soporte_pago_periodo_fk = " . $codigoSoportePagoPeriodo;           
                $em->getConnection()->executeQuery($strSql); 
                
                $arSoportePagoPeriodo = new \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                $arSoportePagoPeriodo->setEstadoGenerado(0);
                $arSoportePagoPeriodo->setRecursos(0);
                $em->persist($arSoportePagoPeriodo);
                $em->flush();                                                  
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }
            if($request->request->get('OpCerrar')) {
                $codigoSoportePagoPeriodo = $request->request->get('OpCerrar');
                $arSoportePagoPeriodo = NEW \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                $arSoportePagoPeriodo->setEstadoCerrado(1);                
                $em->persist($arSoportePagoPeriodo);
                $em->flush();                                                   
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
            }            
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_soporte_pago'));                
                
            }*/            
            
        }
        $dql = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->listaDql();
        $arCierreMes = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Procesos/CierreMes:lista.html.twig', array(
            'arCierreMes' => $arCierreMes,
            'form' => $form->createView()));
    }
    
    private function formularioGenerar() {
        $form = $this->createFormBuilder()             
            ->getForm();
        return $form;
    }    
}