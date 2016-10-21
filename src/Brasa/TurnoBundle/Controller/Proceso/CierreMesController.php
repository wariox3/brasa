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
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $codigoCierreMes = $request->request->get('OpGenerar');
                $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
                $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);
                $strSql = "DELETE FROM tur_cierre_mes_servicio WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;           
                $em->getConnection()->executeQuery($strSql); 
                $strSql = "DELETE FROM tur_recurso_puesto WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();           
                $em->getConnection()->executeQuery($strSql);                                
                $strSql = "DELETE FROM rhu_empleado_centro_costo WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();           
                $em->getConnection()->executeQuery($strSql);                 
                
                $strUltimoDiaMes = date("d",(mktime(0,0,0,$arCierreMes->getMes()+1,1,$arCierreMes->getAnio())-1));
                $strFechaDesde = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/01";
                $strFechaHasta = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/" . $strUltimoDiaMes;
                
                //Recursos que tuvieron programacion en el periodo de cierre                
                $arrRecursos = $em->getRepository('BrasaTurnoBundle:TurRecurso')->programacionFecha($arCierreMes->getAnio(), $arCierreMes->getMes(), "2220");
                foreach ($arrRecursos as $arrRecurso) {
                    //OJooooooo quitar
                    if($arrRecurso['codigo_recurso_fk'] == 2220) {
                        $arrPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->pagoDevengadoFecha($strFechaDesde, $strFechaHasta, $arrRecurso['codigo_empleado_fk']);                    
                        if($arrPagos) {
                            //$arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                            $devengado = $arrPagos[0]['vrDevengado'];
                            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrRecurso['codigo_recurso_fk']);
                            $arCostoRecurso = new \Brasa\TurnoBundle\Entity\TurCostoRecurso();
                            $arCostoRecurso->setCierreMesRel($arCierreMes);
                            $arCostoRecurso->setRecursoRel($arRecurso);
                            $arCostoRecurso->setAnio($arCierreMes->getAnio());
                            $arCostoRecurso->setMes($arCierreMes->getMes());
                            $arCostoRecurso->setVrNomina($devengado); 
                            $floTotal = $devengado;
                            $arCostoRecurso->setVrCostoTotal($floTotal);
                            $horas = 0;
                            $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                            $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoRecursoFk' => $arRecurso->getCodigoRecursoPk(), 'anio' => $arCierreMes->getAnio(), 'mes' => $arCierreMes->getMes()));                                            
                            foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
                                $horas += $arProgramacionDetalle->getHoras(); 
                            }                        
                            $arCostoRecurso->setHoras($horas);
                            $floVrHora = 0;
                            if($horas > 0) {
                                $floVrHora = $floTotal / $horas;
                            }                 
                            $arCostoRecurso->setVrHora($floVrHora);
                            $em->persist($arCostoRecurso);
                            //Actualizar programaciones detalle                        
                            $query = $em->createQuery('update BrasaTurnoBundle:TurProgramacionDetalle pd set pd.vrHoraRecurso = ' . $floVrHora . ' where pd.codigoRecursoFk = ' . $arRecurso->getCodigoRecursoPk() . ' and pd.anio = ' . $arCierreMes->getAnio() . ' and pd.mes =' . $arCierreMes->getMes());
                            $query->execute();                        
                        }                         
                    }                                        
                }
                $em->flush();
                
                //Asignar los centros de costos donde mas trabajo el recurso
                /*
                foreach ($arrRecursos as $arrRecurso) {
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arrRecurso['codigo_empleado_fk']);
                    if($arEmpleado) {
                        if($arEmpleado->getEmpleadoTipoRel()->getTipo() == 2) {
                            $arrProgramaciones = $em->getRepository('BrasaTurnoBundle:TurRecurso')->programacionFechaRecurso($arCierreMes->getAnio(), $arCierreMes->getMes(), $arrRecurso['codigo_recurso_fk']);
                            if($arrProgramaciones) {
                                $codigoPuesto = $arrProgramaciones[0]['codigo_puesto_fk'];
                                if($codigoPuesto) {                                    
                                    $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto);
                                    if($arPuesto) {
                                        $arEmpleado->setPuestoRel($arPuesto);
                                        $arEmpleado->setCentroCostoContabilidadRel($arPuesto->getCentroCostoContabilidadRel());
                                        $em->persist($arEmpleado); 
                                        
                                        $arRecursoPuesto = new \Brasa\TurnoBundle\Entity\TurRecursoPuesto();
                                        $arRecursoPuesto->setAnio($arCierreMes->getAnio());
                                        $arRecursoPuesto->setMes($arCierreMes->getMes());
                                        $arRecursoPuesto->setCodigoPuestoFk($codigoPuesto);
                                        $arRecursoPuesto->setCodigoRecursoFk($arrRecurso['codigo_recurso_fk']);
                                        $arRecursoPuesto->setCodigoEmpleadoFk($arrRecurso['codigo_empleado_fk']);
                                        $arRecursoPuesto->setCodigoCentroCostoFk($arPuesto->getCodigoCentroCostoContabilidadFk());
                                        $em->persist($arRecursoPuesto);
                                    }
                                }
                            }
                        }
                    }
                } 
                $em->flush();                                
                */
                /*
                //Asignar centro de costo a empleados
                $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();                                
                $dql   = "SELECT c.codigoEmpleadoFk FROM BrasaRecursoHumanoBundle:RhuContrato c "
                        ." WHERE (c.fechaHasta >= '" . $strFechaDesde . "' OR c.indefinido = 1) "
                        . "AND c.fechaDesde <= '" . $strFechaHasta . "' GROUP BY c.codigoEmpleadoFk";
                $query = $em->createQuery($dql);        
                $arContratos = $query->getResult();                        
                foreach ($arContratos as $arContrato) {
                    $arEmpleadoCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoCentroCosto();
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato['codigoEmpleadoFk']);                    
                    $arEmpleadoCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoCentroCosto();
                    $arEmpleadoCentroCosto->setAnio($arCierreMes->getAnio());
                    $arEmpleadoCentroCosto->setMes($arCierreMes->getMes());
                    $arEmpleadoCentroCosto->setCodigoEmpleadoFk($arContrato['codigoEmpleadoFk']);
                    $arRecursoPuesto = new \Brasa\TurnoBundle\Entity\TurRecursoPuesto();
                    $arRecursoPuesto = $em->getRepository('BrasaTurnoBundle:TurRecursoPuesto')->findOneBy(array('anio' => $arCierreMes->getAnio(), 'mes' => $arCierreMes->getMes(), 'codigoEmpleadoFk' => $arContrato['codigoEmpleadoFk']));
                    if($arRecursoPuesto) {
                        $arEmpleadoCentroCosto->setCodigoCentroCostoFk($arRecursoPuesto->getCodigoCentroCostoFk());
                        $arEmpleadoCentroCosto->setCodigoPuestoFk($arRecursoPuesto->getCodigoPuestoFk());                        
                    } else {
                        $arEmpleadoCentroCosto->setCodigoCentroCostoFk($arEmpleado->getCodigoCentroCostoContabilidadFk());
                        $arEmpleadoCentroCosto->setCodigoPuestoFk(0);                        
                    }
                    $em->persist($arEmpleadoCentroCosto);
                }
                $em->flush();
                */
                /*
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
                }
                $em->flush(); 
                 * 
                 */
                $arCierreMes->setEstadoGenerado(1);
                $em->persist($arCierreMes);
                $em->flush();   
                  
                              
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
                $strSql = "DELETE FROM tur_recurso_puesto WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();           
                $em->getConnection()->executeQuery($strSql); 
                $strSql = "DELETE FROM rhu_empleado_centro_costo WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();           
                $em->getConnection()->executeQuery($strSql);                
                
                $arCierreMes->setEstadoGenerado(0);
                $em->persist($arCierreMes);
                $em->flush();                                                  
                return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));                
            }

            
            
            /*if($request->request->get('OpCerrar')) {
                $codigoSoportePagoPeriodo = $request->request->get('OpCerrar');
                $arSoportePagoPeriodo = NEW \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo();
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigoSoportePagoPeriodo);                
                $arSoportePagoPeriodo->setEstadoCerrado(1);                
                $em->persist($arSoportePagoPeriodo);
                $em->flush();                                                   
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