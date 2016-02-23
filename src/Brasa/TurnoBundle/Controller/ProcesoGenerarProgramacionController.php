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
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 4)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if($request->request->get('OpGenerar')) {
                $codigoPedido = $request->request->get('OpGenerar');
                $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
                $arPedido =  $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);                    
                
                $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
                $arProgramacion->setClienteRel($arPedido->getClienteRel());
                $arProgramacion->setFecha($arPedido->getFechaProgramacion());
                $arUsuario = $this->getUser();
                $arProgramacion->setUsuario($arUsuario->getUserName());                                
                $em->persist($arProgramacion); 
                $arPedido->setEstadoProgramado(true);
                $em->persist($arPedido);
                
                $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                $arPedidoDetalles =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido)); 
                foreach ($arPedidoDetalles as $arPedidoDetalle) {
                    $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->nuevo($arPedidoDetalle->getCodigoPedidoDetallePk(), $arProgramacion);
                }                
                $em->flush();               
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_programacion_lista')); 
            }    
            if ($form->get('BtnGenerar')->isClicked()) { 
                $dateFecha = $form->get('fecha')->getData();
                $strAnioMes = $dateFecha->format('Y/m');
                $strFechaInicio = $dateFecha->format('Y/m') . '/01';                        
                $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
                $strFechaFinal = $dateFecha->format('Y/m') . '/' . $strUltimoDiaMes;                
                $strDql =  $em->getRepository('BrasaTurnoBundle:TurPedido')->pedidoSinProgramarDql($strFechaInicio, $strFechaFinal);
                $arPedidos = new \Brasa\TurnoBundle\Entity\TurPedido();                
                $query = $em->createQuery($strDql);
                $arPedidos = $query->getResult();
                foreach ($arPedidos as $arPedido) {
                    $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
                    $arProgramacion->setClienteRel($arPedido->getClienteRel());
                    $arProgramacion->setFecha($dateFecha);
                    $em->persist($arProgramacion);                    
                    $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalles =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $arPedido->getCodigoPedidoPk())); 
                    foreach ($arPedidoDetalles as $arPedidoDetalle) {
                        $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->nuevo($arPedidoDetalle->getCodigoPedidoDetallePk(), $arProgramacion);
                    }
                    $arPedidoActualizar = new \Brasa\TurnoBundle\Entity\TurPedido();
                    $arPedidoActualizar =  $em->getRepository('BrasaTurnoBundle:TurPedido')->find($arPedido->getCodigoPedidoPk());    
                    $arPedidoActualizar->setEstadoProgramado(true);
                    $em->persist($arPedidoActualizar);
                }
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_proceso_generar_programacion_lista'));                                 
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arPedidos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarProgramacion:lista.html.twig', array(
            'arPedidos' => $arPedidos, 
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurPedido')->pedidoSinProgramarDql();
    }
    
    private function formularioLista() {
        $dateDia = new \DateTime('now');
        $strDia = $dateDia->format('Y/m/') . "01";
        $dateDia = date_create($strDia);
        $form = $this->createFormBuilder()
            ->add('fecha', 'date', array('data'  => $dateDia, 'format' => 'y MMMM d'))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->getForm();
        return $form;
    }        
    
}