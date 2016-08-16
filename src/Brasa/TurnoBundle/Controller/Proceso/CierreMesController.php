<?php
namespace Brasa\TurnoBundle\Controller\Proceso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class CierreMesController extends Controller
{
    /**
     * @Route("/tur/proceso/cierre/mes", name="brs_tur_proceso_cierre_mes")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 8)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
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
                //Recursos que tuvieron programacion en el periodo de cierre                
                $arrRecursos = $em->getRepository('BrasaTurnoBundle:TurRecurso')->programacionFecha($arCierreMes->getAnio(), $arCierreMes->getMes());
                foreach ($arrRecursos as $arrRecurso) {
                    $arrPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->pagosFecha($strFechaDesde, $strFechaHasta, $arrRecurso['codigo_empleado_fk']);                    
                    if($arrPagos) {
                        //$arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrRecurso['codigo_recurso_fk']);
                        $arCostoRecurso = new \Brasa\TurnoBundle\Entity\TurCostoRecurso();
                        $arCostoRecurso->setCierreMesRel($arCierreMes);
                        $arCostoRecurso->setRecursoRel($arRecurso);
                        $arCostoRecurso->setAnio($arCierreMes->getAnio());
                        $arCostoRecurso->setMes($arCierreMes->getMes());
                        $arCostoRecurso->setVrNomina($arrPagos[0]['vrNeto']); 
                        $arCostoRecurso->setVrPrestaciones($arrPagos[0]['vrPrestaciones']);
                        $arCostoRecurso->setVrAportesSociales($arrPagos[0]['vrAportes']);
                        $floTotal = $arrPagos[0]['vrNeto'] + $arrPagos[0]['vrPrestaciones'] + $arrPagos[0]['vrAportes'];
                        $arCostoRecurso->setVrCostoTotal($floTotal);
                        $arrProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->detallesRecurso($arRecurso->getCodigoRecursoPk(), $arCierreMes->getAnio(), $arCierreMes->getMes());                                            
                        $intHoras = $arrProgramacionDetalles['horas'];
                        $arCostoRecurso->setHoras($arrProgramacionDetalles['horas']);
                        $floVrHora = 0;
                        if($intHoras > 0) {
                            $floVrHora = $floTotal / $intHoras;
                        }                 
                        $arCostoRecurso->setVrHora($floVrHora);
                        $em->persist($arCostoRecurso);
                        //Actualizar programaciones detalle                        
                        $query = $em->createQuery('update BrasaTurnoBundle:TurProgramacionDetalle pd set pd.vrHoraRecurso = ' . $floVrHora . ' where pd.codigoRecursoFk = ' . $arRecurso->getCodigoRecursoPk() . ' and pd.anio = ' . $arCierreMes->getAnio() . ' and pd.mes =' . $arCierreMes->getMes());
                        $query->execute();                        
                    }                    
                }
                $em->flush();
                
                //Creo los servicios (Detalles de pedido)
                $arPedidosDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();                
                $arPedidosDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->fecha($strFechaDesde, $strFechaHasta);                                
                foreach ($arPedidosDetalles as $arPedidoDetalle) {
                    $arCierreMesServicio = new \Brasa\TurnoBundle\Entity\TurCierreMesServicio();
                    $arCierreMesServicio->setCierreMesRel($arCierreMes);
                    $arCierreMesServicio->setAnio($arCierreMes->getAnio());
                    $arCierreMesServicio->setMes($arCierreMes->getMes());
                    $arCierreMesServicio->setPedidoDetalleRel($arPedidoDetalle);
                    $arCierreMesServicio->setClienteRel($arPedidoDetalle->getPedidoRel()->getClienteRel());
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
                    $arrProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->detallesPedido($arPedidoDetalle->getCodigoPedidoDetallePk(), $arCierreMes->getAnio(), $arCierreMes->getMes());                    
                    if($arrProgramacionDetalles['horas'] != NULL) {
                        $arCierreMesServicio->setHorasProgramadas($arrProgramacionDetalles['horas']);
                        $arCierreMesServicio->setVrCostoRecurso($arrProgramacionDetalles['vrRecurso']);                        
                    }
                    $em->persist($arCierreMesServicio);  
                    /*$arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoPedidoDetalleFk' => $arPedidoDetalle->getCodigoPedidoDetallePk()));                                                    
                    $arCierreMesServicioDetalle = new \Brasa\TurnoBundle\Entity\TurCierreMesServicioDetalle();
                    $arCierreMesServicioDetalle->setCierreMesRel($arCierreMes);
                    $arCierreMesServicioDetalle->setCierreMesServicioRel($arCierreMesServicio);                    
                     * 
                     */
                }
                $em->flush(); 
                $arCierreMes->setEstadoGenerado(1);
                $em->persist($arCierreMes);
                $em->flush();
                //Creo los soportes de cada servicio (Detalles de programacion)
                /*$arCierreMesServicios = new \Brasa\TurnoBundle\Entity\TurCierreMesServicio();
                $arCierreMesServicios = $em->getRepository('BrasaTurnoBundle:TurCierreMesServicio')->findBy(array('codigoCierreMesFk' => $arCierreMes->getCodigoCierreMesPk()));                                
                foreach ($arCierreMesServicios as $arCierreMesServicio) {
                    $prueba = $arCierreMesServicio->getPedidoDetalleRel()->getCodigoPedidoDetallePk();
                    
                    
                }*/
                return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));
            }
            if($request->request->get('OpDeshacer')) {                
                $codigoCierreMes = $request->request->get('OpDeshacer');
                $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
                $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);
                
                $strSql = "DELETE FROM tur_costo_recurso WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;           
                $em->getConnection()->executeQuery($strSql);
                $strSql = "DELETE FROM tur_cierre_mes_servicio_detalle WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;           
                $em->getConnection()->executeQuery($strSql); 
                $strSql = "DELETE FROM tur_cierre_mes_servicio WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;           
                $em->getConnection()->executeQuery($strSql); 
                $arCierreMes->setEstadoGenerado(0);
                $em->persist($arCierreMes);
                $em->flush();                                                  
                return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));                
            }
            /*
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