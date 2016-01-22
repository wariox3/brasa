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
                $arServicios = new \Brasa\TurnoBundle\Entity\TurServicio();
                $query = $em->createQuery($this->strListaDql);
                $arServicios = $query->getResult();
                foreach ($arServicios as $arServicio) {
                    $arPedidoTipo = $em->getRepository('BrasaTurnoBundle:TurPedidoTipo')->find(2);
                    $arPedidoNuevo = new \Brasa\TurnoBundle\Entity\TurPedido();                    
                    $arPedidoNuevo->setPedidoTipoRel($arPedidoTipo);
                    $arPedidoNuevo->setClienteRel($arServicio->getClienteRel());
                    $arPedidoNuevo->setSectorRel($arServicio->getSectorRel());
                    $arPedidoNuevo->setFecha($dateFecha);
                    $arPedidoNuevo->setFechaProgramacion($dateFecha);
                    $arPedidoNuevo->setEstadoAutorizado(1);
                    
                    $em->persist($arPedidoNuevo);                    
                                        
                    $arServicioDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                    $arServicioDetalles =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('codigoServicioFk' => $arServicio->getCodigoServicioPk())); 
                    foreach ($arServicioDetalles as $arServicioDetalle) {
                        $arPedidoDetalleNuevo = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalleNuevo->setPedidoRel($arPedidoNuevo);
                        if($arServicioDetalle->getCodigoPeriodoFk() == 1) {                            
                            $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
                            $arPedidoDetalleNuevo->setDiaDesde(1);
                            $arPedidoDetalleNuevo->setDiaHasta($strUltimoDiaMes);                            
                        } else {
                            $arPedidoDetalleNuevo->setDiaDesde($arServicioDetalle->getDiaDesde());
                            $arPedidoDetalleNuevo->setDiaHasta($arServicioDetalle->getDiaHasta());
                        }
                        $arPedidoDetalleNuevo->setCantidad($arServicioDetalle->getCantidad());
                        $arPedidoDetalleNuevo->setConceptoServicioRel($arServicioDetalle->getConceptoServicioRel());
                        $arPedidoDetalleNuevo->setModalidadServicioRel($arServicioDetalle->getModalidadServicioRel());
                        $arPedidoDetalleNuevo->setPeriodoRel($arServicioDetalle->getPeriodoRel());
                        $arPedidoDetalleNuevo->setPuestoRel($arServicioDetalle->getPuestoRel());
                        $arPedidoDetalleNuevo->setPlantillaRel($arServicioDetalle->getPlantillaRel());
                        $arPedidoDetalleNuevo->setServicioDetalleRel($arServicioDetalle);
                        $arPedidoDetalleNuevo->setLunes($arServicioDetalle->getLunes());
                        $arPedidoDetalleNuevo->setMartes($arServicioDetalle->getMartes());
                        $arPedidoDetalleNuevo->setMiercoles($arServicioDetalle->getMiercoles());
                        $arPedidoDetalleNuevo->setJueves($arServicioDetalle->getJueves());
                        $arPedidoDetalleNuevo->setViernes($arServicioDetalle->getViernes());
                        $arPedidoDetalleNuevo->setSabado($arServicioDetalle->getSabado());
                        $arPedidoDetalleNuevo->setDomingo($arServicioDetalle->getDomingo());
                        $arPedidoDetalleNuevo->setFestivo($arServicioDetalle->getFestivo());    
                        $arPedidoDetalleNuevo->setVrPrecioAjustado($arServicioDetalle->getVrPrecioAjustado());
                        $em->persist($arPedidoDetalleNuevo);  
                        $arServicioDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
                        $arServicioDetalleRecursos =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->findBy(array('codigoServicioDetalleFk' => $arServicioDetalle->getCodigoServicioDetallePk())); 
                        foreach ($arServicioDetalleRecursos as $arServicioDetalleRecurso) {
                            $arPedidoDetalleRecursoNuevo = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                            $arPedidoDetalleRecursoNuevo->setPedidoDetalleRel($arPedidoDetalleNuevo);
                            $arPedidoDetalleRecursoNuevo->setRecursoRel($arServicioDetalleRecurso->getRecursoRel());
                            $arPedidoDetalleRecursoNuevo->setPosicion($arServicioDetalleRecurso->getPosicion());
                            $em->persist($arPedidoDetalleRecursoNuevo);
                        }
                    }                   
                    $em->flush();
                    $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($arPedidoNuevo->getCodigoPedidoPk());
                }
                //$em->flush();
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_pedido_lista'));                                 
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arServicios = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarPedido:lista.html.twig', array(
            'arServicios' => $arServicios, 
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurServicio')->listaDql();
    }
    
    private function formularioLista() {                
        $form = $this->createFormBuilder()
            ->add('fecha', 'date', array('data'  => new \DateTime('now'), 'format' => 'y MMMM d'))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->getForm();
        return $form;
    }        
    
}