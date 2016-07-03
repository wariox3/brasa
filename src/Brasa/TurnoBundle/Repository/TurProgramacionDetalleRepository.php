<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurProgramacionDetalleRepository extends EntityRepository {

    public function listaDql($codigoProgramacion = "") {
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd WHERE pd.codigoProgramacionDetallePk <> 0 ";
        
        if($codigoProgramacion != '') {
            $dql .= "AND pd.codigoProgramacionFk = " . $codigoProgramacion . " ";  
        }        
        $dql .= " ORDER BY pd.codigoPuestoFk";
        return $dql;
    } 
    
    public function consultaDetalleDql($codigoCliente, $codigoRecurso, $codigoCentroCosto, $strFechaDesde = "", $strFechaHasta = "", $boolEstadoAutorizado = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd JOIN pd.programacionRel p JOIN pd.recursoRel r "
                . "WHERE pd.codigoProgramacionDetallePk <> 0 ";
        if($codigoCliente != '') {
            $dql = $dql . "AND p.codigoClienteFk = " . $codigoCliente;
        }
        if($codigoRecurso != '') {
            $dql = $dql . "AND pd.codigoRecursoFk = " . $codigoRecurso;
        }
        if($codigoCentroCosto != '') {
            $dql = $dql . "AND r.codigoCentroCostoFk = " . $codigoCentroCosto;
        }
        if($strFechaDesde != "") {
            $dql .= " AND p.fecha >= '" . $strFechaDesde . "'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND p.fecha <= '" . $strFechaHasta . "'";
        }
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND p.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND p.estadoAutorizado = 0";
        }
        $dql .= " ORDER BY p.codigoClienteFk";
        return $dql;
    }

    public function pedido($codigoPedidoDetalle) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.horas) as horas "
                . "FROM BrasaTurnoBundle:TurProgramacionDetalle pd "
                . "WHERE pd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle;
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado[0];
    }

    public function eliminarDetallesSeleccionados($arrSeleccionados) {
        $strResultado = "";
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {
                $intNumeroRegistros = 0;
                $dql   = "SELECT COUNT(spd.codigoProgramacionDetalleFk) as numeroRegistros FROM BrasaTurnoBundle:TurSoportePagoDetalle spd "
                        . "WHERE spd.codigoProgramacionDetalleFk = " . $codigo;
                $query = $em->createQuery($dql);
                $arrSoportePagoDetalles = $query->getSingleResult();
                if($arrSoportePagoDetalles) {
                    $intNumeroRegistros = $arrSoportePagoDetalles['numeroRegistros'];
                }
                if($intNumeroRegistros <= 0) {
                    $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigo);
                    $em->remove($arProgramacionDetalle);
                } else {
                    $strResultado .= "El detalle " . $codigo . " no se puede eliminar porque tiene soportes de pago asociados ";
                }
            }
            $em->flush();
        }
        return $strResultado;
    }

    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigo));
        return count($arDetalles);
    }

    public function periodo($strFechaDesde, $strFechaHasta, $codigoRecursoGrupo) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd JOIN pd.programacionRel p JOIN pd.recursoRel r "
                . "WHERE p.fecha >= '" . $strFechaDesde . "' AND p.fecha <='" . $strFechaHasta . "' AND r.codigoRecursoGrupoFk = " . $codigoRecursoGrupo;
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;
    }

    public function validarRecurso($codigoProgramacion) {
        $em = $this->getEntityManager();
        $boolResultado = TRUE;
        $dql   = "SELECT pd.codigoProgramacionDetallePk FROM BrasaTurnoBundle:TurProgramacionDetalle pd "
                . "WHERE pd.codigoProgramacionFk = " . $codigoProgramacion . " AND pd.codigoRecursoFk IS NULL";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        if(count($arResultado) > 0) {
            $boolResultado = FALSE;
        }
        return $boolResultado;
    }
    
    public function validarPuesto($codigoProgramacion) {
        $em = $this->getEntityManager();
        $boolResultado = TRUE;
        $dql   = "SELECT pd.codigoProgramacionDetallePk FROM BrasaTurnoBundle:TurProgramacionDetalle pd "
                . "WHERE pd.codigoProgramacionFk = " . $codigoProgramacion . " AND pd.codigoPuestoFk IS NULL";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        if(count($arResultado) > 0) {
            $boolResultado = FALSE;
        }
        return $boolResultado;
    }    

    public function nuevo($codigoPedidoDetalle, $arProgramacion) {
        $em = $this->getEntityManager();
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);
        $intDiaInicial = $arPedidoDetalle->getDiaDesde();
        $intDiaFinal = $arPedidoDetalle->getDiaHasta();
        $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($arProgramacion->getFecha()->format('Y-m-') . $intDiaInicial, $arProgramacion->getFecha()->format('Y-m-') . $intDiaFinal);
        $strMesAnio = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y/m');
        if($arPedidoDetalle->getPlantillaRel()) {
            if($arPedidoDetalle->getPlantillaRel()) {
                $arPlantilla = $arPedidoDetalle->getPlantillaRel();
            }
            $arPlantillaDetalles = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
            $arPlantillaDetalles = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array('codigoPlantillaFk' => $arPlantilla->getCodigoPlantillaPk()));
            foreach ($arPlantillaDetalles as $arPlantillaDetalle) {
                $strFechaDesde = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y/m') . "/" . $arPedidoDetalle->getDiaDesde();
                $strAnio = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y');
                $intPosicion = $this->devuelvePosicionInicialMatrizPlantilla($strAnio, $arPlantilla->getDiasSecuencia(), $strFechaDesde, $arPedidoDetalle->getFechaIniciaPlantilla());
                $arrTurnos = $this->devuelveTurnosMes($arPlantillaDetalle);                    
                $arPedidoDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                $arPedidoDetalleRecursos = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleRecurso')->findBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle, 'posicion' => $arPlantillaDetalle->getPosicion()));
                foreach ($arPedidoDetalleRecursos as $arPedidoDetalleRecurso) {                        
                    $intPosicionPlantilla = $intPosicion;
                    $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                    $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                    $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                    $arProgramacionDetalle->setProyectoRel($arPedidoDetalle->getProyectoRel());
                    $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                    $arProgramacionDetalle->setAnio($arProgramacion->getFecha()->format('Y'));
                    $arProgramacionDetalle->setMes($arProgramacion->getFecha()->format('m'));                        
                    $arProgramacionDetalle->setRecursoRel($arPedidoDetalleRecurso->getRecursoRel());
                    $arProgramacionDetalle->setAjusteProgramacion($arPedidoDetalle->getAjusteProgramacion());
                    for($i = 1; $i < 32; $i++) {                        
                        $strTurno = $arrTurnos[$intPosicionPlantilla];
                        $strFechaDia = $arProgramacion->getFecha()->format('Y-m-') . $i;
                        $dateFechaDia = date_create($strFechaDia);
                        $diaSemana = $dateFechaDia->format('N');

                        $boolFestivo = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->festivo($arFestivos, $dateFechaDia);
                        if($diaSemana == 1 && isset($arrTurnos['lunes'])) {
                            $strTurno = $arrTurnos['lunes'];
                        }
                        if($diaSemana == 2 && isset($arrTurnos['martes'])) {
                            $strTurno = $arrTurnos['martes'];
                        }
                        if($diaSemana == 3 && isset($arrTurnos['miercoles'])) {
                            $strTurno = $arrTurnos['miercoles'];
                        }
                        if($diaSemana == 4 && isset($arrTurnos['jueves'])) {
                            $strTurno = $arrTurnos['jueves'];
                        }
                        if($diaSemana == 5 && isset($arrTurnos['viernes'])) {
                            $strTurno = $arrTurnos['viernes'];
                        }
                        if($diaSemana == 6 && isset($arrTurnos['sabado'])) {
                            $strTurno = $arrTurnos['sabado'];
                        }
                        if($diaSemana == 7 && isset($arrTurnos['domingo'])) {
                            $strTurno = $arrTurnos['domingo'];
                        }
                        if($diaSemana == 7 && isset($arrTurnos['domingoFestivo'])) {
                            $strFechaDiaSiguiente = $arProgramacion->getFecha()->format('Y-m-') . ($i+1);
                            $dateFechaDiaSiguiente = date_create($strFechaDiaSiguiente);                            
                            $boolFestivoSiguiente = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->festivo($arFestivos, $dateFechaDiaSiguiente);                                
                            if($boolFestivoSiguiente == 1) {
                                $strTurno = $arrTurnos['domingoFestivo'];                                
                            }                                
                        }                            
                        if($boolFestivo == 1 && isset($arrTurnos['festivo'])) {
                            $strTurno = $arrTurnos['festivo'];
                        }

                        if($arPlantilla->getHomologarCodigoTurno() == 1) {
                            $strTurno = $this->devuelveCodigoTurno($arrTurnos[$intPosicionPlantilla]);
                        }
                        $boolAplica = $this->aplicaPlantilla($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle, $strTurno, $boolFestivo);                        
                        if($boolAplica == TRUE) {
                            if($i == 1) {
                                $arProgramacionDetalle->setDia1($strTurno);
                            }
                            if($i == 2) {
                                $arProgramacionDetalle->setDia2($strTurno);
                            }
                            if($i == 3) {
                                $arProgramacionDetalle->setDia3($strTurno);
                            }
                            if($i == 4) {
                                $arProgramacionDetalle->setDia4($strTurno);
                            }
                            if($i == 5) {
                                $arProgramacionDetalle->setDia5($strTurno);
                            }
                            if($i == 6) {
                                $arProgramacionDetalle->setDia6($strTurno);
                            }
                            if($i == 7) {
                                $arProgramacionDetalle->setDia7($strTurno);
                            }
                            if($i == 8) {
                                $arProgramacionDetalle->setDia8($strTurno);
                            }
                            if($i == 9) {
                                $arProgramacionDetalle->setDia9($strTurno);
                            }
                            if($i == 10) {
                                $arProgramacionDetalle->setDia10($strTurno);
                            }
                            if($i == 11) {
                                $arProgramacionDetalle->setDia11($strTurno);
                            }
                            if($i == 12) {
                                $arProgramacionDetalle->setDia12($strTurno);
                            }
                            if($i == 13) {
                                $arProgramacionDetalle->setDia13($strTurno);
                            }
                            if($i == 14) {
                                $arProgramacionDetalle->setDia14($strTurno);
                            }
                            if($i == 15) {
                                $arProgramacionDetalle->setDia15($strTurno);
                            }
                            if($i == 16) {
                                $arProgramacionDetalle->setDia16($strTurno);
                            }
                            if($i == 17) {
                                $arProgramacionDetalle->setDia17($strTurno);
                            }
                            if($i == 18) {
                                $arProgramacionDetalle->setDia18($strTurno);
                            }
                            if($i == 19) {
                                $arProgramacionDetalle->setDia19($strTurno);
                            }
                            if($i == 20) {
                                $arProgramacionDetalle->setDia20($strTurno);
                            }
                            if($i == 21) {
                                $arProgramacionDetalle->setDia21($strTurno);
                            }
                            if($i == 22) {
                                $arProgramacionDetalle->setDia22($strTurno);
                            }
                            if($i == 23) {
                                $arProgramacionDetalle->setDia23($strTurno);
                            }
                            if($i == 24) {
                                $arProgramacionDetalle->setDia24($strTurno);
                            }
                            if($i == 25) {
                                $arProgramacionDetalle->setDia25($strTurno);
                            }
                            if($i == 26) {
                                $arProgramacionDetalle->setDia26($strTurno);
                            }
                            if($i == 27) {
                                $arProgramacionDetalle->setDia27($strTurno);
                            }
                            if($i == 28) {
                                $arProgramacionDetalle->setDia28($strTurno);
                            }
                            if($i == 29) {
                                $arProgramacionDetalle->setDia29($strTurno);
                            }
                            if($i == 30) {
                                $arProgramacionDetalle->setDia30($strTurno);
                            }
                            if($i == 31) {
                                $arProgramacionDetalle->setDia31($strTurno);
                            }
                        }
                        $intPosicionPlantilla++;
                        if($intPosicionPlantilla == ($arPlantilla->getDiasSecuencia() + 1)) {
                            $intPosicionPlantilla = 1;
                        }
                    }                        
                    $em->persist($arProgramacionDetalle);                        
                }
            }
        } else {
            if($arPedidoDetalle->getCodigoServicioDetalleFk()) {
                $arPlantillaDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetallePlantilla();
                $arPlantillaDetalles = $em->getRepository('BrasaTurnoBundle:TurServicioDetallePlantilla')->findBy(array('codigoServicioDetalleFk' => $arPedidoDetalle->getCodigoServicioDetalleFk()));
                foreach ($arPlantillaDetalles as $arPlantillaDetalle) {
                    $strFechaDesde = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y/m') . "/" . $arPedidoDetalle->getDiaDesde();
                    $strAnio = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y');
                    $intPosicion = $this->devuelvePosicionInicialMatrizPlantilla($strAnio, $arPedidoDetalle->getServicioDetalleRel()->getDiasSecuencia(), $strFechaDesde, $arPedidoDetalle->getFechaIniciaPlantilla());
                    $arrTurnos = $this->devuelveTurnosMes($arPlantillaDetalle);                        
                    $arPedidoDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                    $arPedidoDetalleRecursos = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleRecurso')->findBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle, 'posicion' => $arPlantillaDetalle->getPosicion()));
                    foreach ($arPedidoDetalleRecursos as $arPedidoDetalleRecurso) {
                        $intPosicionPlantilla = $intPosicion;
                        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                        $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                        $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                        $arProgramacionDetalle->setProyectoRel($arPedidoDetalle->getProyectoRel());
                        $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arProgramacionDetalle->setAjusteProgramacion($arPedidoDetalle->getAjusteProgramacion());                                                        
                        $arProgramacionDetalle->setAnio($arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y'));
                        $arProgramacionDetalle->setMes($arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('m'));                        
                        $arProgramacionDetalle->setRecursoRel($arPedidoDetalleRecurso->getRecursoRel());                            
                        for($i = 1; $i < 32; $i++) {                            
                            $strTurno = $arrTurnos[$intPosicionPlantilla];
                            $strFechaDia = $arProgramacion->getFecha()->format('Y-m-') . $i;
                            $dateFechaDia = date_create($strFechaDia);
                            $diaSemana = $dateFechaDia->format('N');

                            $boolFestivo = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->festivo($arFestivos, $dateFechaDia);
                            if($diaSemana == 1 && isset($arrTurnos['lunes'])) {
                                $strTurno = $arrTurnos['lunes'];
                            }
                            if($diaSemana == 2 && isset($arrTurnos['martes'])) {
                                $strTurno = $arrTurnos['martes'];
                            }
                            if($diaSemana == 3 && isset($arrTurnos['miercoles'])) {
                                $strTurno = $arrTurnos['miercoles'];
                            }
                            if($diaSemana == 4 && isset($arrTurnos['jueves'])) {
                                $strTurno = $arrTurnos['jueves'];
                            }
                            if($diaSemana == 5 && isset($arrTurnos['viernes'])) {
                                $strTurno = $arrTurnos['viernes'];
                            }
                            if($diaSemana == 6 && isset($arrTurnos['sabado'])) {
                                $strTurno = $arrTurnos['sabado'];
                            }
                            if($diaSemana == 7 && isset($arrTurnos['domingo'])) {
                                $strTurno = $arrTurnos['domingo'];
                            }
                            if($boolFestivo == 1 && isset($arrTurnos['festivo'])) {
                                $strTurno = $arrTurnos['festivo'];
                            }                 
                            if($diaSemana == 7 && isset($arrTurnos['domingoFestivo'])) {
                                $strFechaDiaSiguiente = $arProgramacion->getFecha()->format('Y-m-') . ($i+1);
                                $dateFechaDiaSiguiente = date_create($strFechaDiaSiguiente);                            
                                $boolFestivoSiguiente = $em->getRepository('BrasaTurnoBundle:TurCotizacion')->festivo($arFestivos, $dateFechaDiaSiguiente);                                
                                if($boolFestivoSiguiente == 1) {
                                    $strTurno = $arrTurnos['domingoFestivo'];                                
                                }                                
                            }                                
                            $boolAplica = $this->aplicaPlantilla($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle, $strTurno, $boolFestivo);
                            if($boolAplica == TRUE) {
                                if($i == 1) {
                                    $arProgramacionDetalle->setDia1($strTurno);
                                }
                                if($i == 2) {
                                    $arProgramacionDetalle->setDia2($strTurno);
                                }
                                if($i == 3) {
                                    $arProgramacionDetalle->setDia3($strTurno);
                                }
                                if($i == 4) {
                                    $arProgramacionDetalle->setDia4($strTurno);
                                }
                                if($i == 5) {
                                    $arProgramacionDetalle->setDia5($strTurno);
                                }
                                if($i == 6) {
                                    $arProgramacionDetalle->setDia6($strTurno);
                                }
                                if($i == 7) {
                                    $arProgramacionDetalle->setDia7($strTurno);
                                }
                                if($i == 8) {
                                    $arProgramacionDetalle->setDia8($strTurno);
                                }
                                if($i == 9) {
                                    $arProgramacionDetalle->setDia9($strTurno);
                                }
                                if($i == 10) {
                                    $arProgramacionDetalle->setDia10($strTurno);
                                }
                                if($i == 11) {
                                    $arProgramacionDetalle->setDia11($strTurno);
                                }
                                if($i == 12) {
                                    $arProgramacionDetalle->setDia12($strTurno);
                                }
                                if($i == 13) {
                                    $arProgramacionDetalle->setDia13($strTurno);
                                }
                                if($i == 14) {
                                    $arProgramacionDetalle->setDia14($strTurno);
                                }
                                if($i == 15) {
                                    $arProgramacionDetalle->setDia15($strTurno);
                                }
                                if($i == 16) {
                                    $arProgramacionDetalle->setDia16($strTurno);
                                }
                                if($i == 17) {
                                    $arProgramacionDetalle->setDia17($strTurno);
                                }
                                if($i == 18) {
                                    $arProgramacionDetalle->setDia18($strTurno);
                                }
                                if($i == 19) {
                                    $arProgramacionDetalle->setDia19($strTurno);
                                }
                                if($i == 20) {
                                    $arProgramacionDetalle->setDia20($strTurno);
                                }
                                if($i == 21) {
                                    $arProgramacionDetalle->setDia21($strTurno);
                                }
                                if($i == 22) {
                                    $arProgramacionDetalle->setDia22($strTurno);
                                }
                                if($i == 23) {
                                    $arProgramacionDetalle->setDia23($strTurno);
                                }
                                if($i == 24) {
                                    $arProgramacionDetalle->setDia24($strTurno);
                                }
                                if($i == 25) {
                                    $arProgramacionDetalle->setDia25($strTurno);
                                }
                                if($i == 26) {
                                    $arProgramacionDetalle->setDia26($strTurno);
                                }
                                if($i == 27) {
                                    $arProgramacionDetalle->setDia27($strTurno);
                                }
                                if($i == 28) {
                                    $arProgramacionDetalle->setDia28($strTurno);
                                }
                                if($i == 29) {
                                    $arProgramacionDetalle->setDia29($strTurno);
                                }
                                if($i == 30) {
                                    $arProgramacionDetalle->setDia30($strTurno);
                                }
                                if($i == 31) {
                                    $arProgramacionDetalle->setDia31($strTurno);
                                }
                            }
                            $intPosicionPlantilla++;
                            if($intPosicionPlantilla == ($arPedidoDetalle->getServicioDetalleRel()->getDiasSecuencia() + 1)) {
                                $intPosicionPlantilla = 1;
                            }
                        }
                        $em->persist($arProgramacionDetalle);                            
                    }


                }
            } else {
                if($arPedidoDetalle->getCantidadRecurso() != 0) {
                    $intCantidad = $arPedidoDetalle->getCantidadRecurso();
                    for($k = 1; $k <= $intCantidad; $k++) {
                        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                        $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                        $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                        $arProgramacionDetalle->setProyectoRel($arPedidoDetalle->getProyectoRel());
                        $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arProgramacionDetalle->setAjusteProgramacion($arPedidoDetalle->getAjusteProgramacion());
                        $arProgramacionDetalle->setAnio($arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y'));
                        $arProgramacionDetalle->setMes($arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('m'));
                        $em->persist($arProgramacionDetalle);
                    }
                }
            }
        }
        
        $arPedidoDetalle->setEstadoProgramado(1);
        $em->persist($arPedidoDetalle);
        $em->flush();
    }

    private function aplicaPlantilla ($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle, $strTurno, $festivo) {
        $em = $this->getEntityManager();
        $boolResultado = FALSE;
        if($strTurno != '') {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno);
            if($arTurno->getDescanso() == 1 && ($i >= $intDiaInicial && $i <= $intDiaFinal)) {
                $boolResultado = TRUE;
            } else {
                if($i >= $intDiaInicial && $i <= $intDiaFinal) {
                    $strFecha = $strMesAnio . '/' . $i;
                    $dateNuevaFecha = date_create($strFecha);
                    $diaSemana = $dateNuevaFecha->format('N');
                    if($diaSemana == 1) {
                        if($arPedidoDetalle->getLunes() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 2) {
                        if($arPedidoDetalle->getMartes() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 3) {
                        if($arPedidoDetalle->getMiercoles() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 4) {
                        if($arPedidoDetalle->getJueves() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 5) {
                        if($arPedidoDetalle->getViernes() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 6) {
                        if($arPedidoDetalle->getSabado() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($diaSemana == 7) {
                        if($arPedidoDetalle->getDomingo() == 1) {
                            $boolResultado = TRUE;
                        }
                    }
                    if($festivo == 1) {
                        if($arPedidoDetalle->getFestivo() == 1) {
                            $boolResultado = TRUE;
                        }                        
                    }
                }                            
            }
        }

        return $boolResultado;
    }

    private function devuelvePosicionInicialMatrizPlantilla($strAnio, $intPosiciones, $strFechaHasta, $dateFechaDesde) {
        if($intPosiciones == 0) {
            $intPosiciones = 1;
        }
        $intPos = 1;

        $dateFechaHasta = date_create($strFechaHasta);
        //$strFecha = $strAnio."/01/1";
        $strFecha = $dateFechaDesde->format('Y/m/j');
        if($dateFechaDesde < $dateFechaHasta) {
            while($strFecha != $strFechaHasta) {
                //$dateFecha = date_create($strAnio."/01/01");
                $nuevafecha = strtotime ( '+1 day' , strtotime ( $strFecha ) ) ;
                $strFecha = date ( 'Y/m/j' , $nuevafecha );

                $intPos++;
                if($intPos == ($intPosiciones+1)) {
                    $intPos = 1;
                }
            }
        }
        return $intPos;
    }

    private function devuelveTurnosMes($arPlantillaDetalle) {
        $arrTurnos = array(
            '1' => $arPlantillaDetalle->getDia1(),
            '2' => $arPlantillaDetalle->getDia2(),
            '3' => $arPlantillaDetalle->getDia3(),
            '4' => $arPlantillaDetalle->getDia4(),
            '5' => $arPlantillaDetalle->getDia5(),
            '6' => $arPlantillaDetalle->getDia6(),
            '7' => $arPlantillaDetalle->getDia7(),
            '8' => $arPlantillaDetalle->getDia8(),
            '9' => $arPlantillaDetalle->getDia9(),
            '10' => $arPlantillaDetalle->getDia10(),
            '11' => $arPlantillaDetalle->getDia11(),
            '12' => $arPlantillaDetalle->getDia12(),
            '13' => $arPlantillaDetalle->getDia13(),
            '14' => $arPlantillaDetalle->getDia14(),
            '15' => $arPlantillaDetalle->getDia15(),
            '16' => $arPlantillaDetalle->getDia16(),
            '17' => $arPlantillaDetalle->getDia17(),
            '18' => $arPlantillaDetalle->getDia18(),
            '19' => $arPlantillaDetalle->getDia19(),
            '20' => $arPlantillaDetalle->getDia20(),
            '21' => $arPlantillaDetalle->getDia21(),
            '22' => $arPlantillaDetalle->getDia22(),
            '23' => $arPlantillaDetalle->getDia23(),
            '24' => $arPlantillaDetalle->getDia24(),
            '25' => $arPlantillaDetalle->getDia25(),
            '26' => $arPlantillaDetalle->getDia26(),
            '27' => $arPlantillaDetalle->getDia27(),
            '28' => $arPlantillaDetalle->getDia28(),
            '29' => $arPlantillaDetalle->getDia29(),
            '30' => $arPlantillaDetalle->getDia30(),
            '31' => $arPlantillaDetalle->getDia31(),
            'lunes' => $arPlantillaDetalle->getLunes(),
            'martes' => $arPlantillaDetalle->getMartes(),
            'miercoles' => $arPlantillaDetalle->getMiercoles(),
            'jueves' => $arPlantillaDetalle->getJueves(),
            'viernes' => $arPlantillaDetalle->getViernes(),
            'sabado' => $arPlantillaDetalle->getSabado(),
            'domingo' => $arPlantillaDetalle->getDomingo(),
            'domingoFestivo' => $arPlantillaDetalle->getDomingoFestivo(),
            'festivo' => $arPlantillaDetalle->getFestivo(),);
        return $arrTurnos;
    }

    private function devuelveCodigoTurno ($strCodigo) {
        $strCodigoReal = "";
        if($strCodigo == 'A') {
            $strCodigoReal = '1';
        }
        if($strCodigo == 'B') {
            $strCodigoReal = '2';
        }
        if($strCodigo == 'D') {
            $strCodigoReal = 'D';
        }
        return $strCodigoReal;
    }

    public function detallesPedido ($codigoPedidoDetalle, $strAnio, $strMes) {
        $em = $this->getEntityManager();
        $strSql = "SELECT
                    COUNT(codigo_programacion_detalle_pk) as numeroRegistros,
                    SUM(horas) as horas,                    
                    SUM(horas * vr_hora_recurso) as vrRecurso
                    FROM tur_programacion_detalle
                    WHERE codigo_pedido_detalle_fk = $codigoPedidoDetalle AND anio = $strAnio AND mes = $strMes";        
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results[0];        
    }
    
    public function detallesRecurso ($codigoRecurso, $strAnio, $strMes) {
        $em = $this->getEntityManager();
        $strSql = "SELECT
                    COUNT(codigo_programacion_detalle_pk) as numeroRegistros,
                    SUM(horas) as horas
                    FROM tur_programacion_detalle
                    WHERE codigo_recurso_fk = $codigoRecurso AND anio = $strAnio AND mes = $strMes";        
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results[0];        
    }
    
    public function marcarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigo);                
                if($arProgramacionDetalle->getMarca() == 1) {
                    $arProgramacionDetalle->setMarca(0);
                } else {
                    $arProgramacionDetalle->setMarca(1);
                }                
            }                                         
            $em->flush();       
        }
        
    }            
    
    public function ajustarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigo);                
                if($arProgramacionDetalle->getAjusteProgramacion() == 1) {
                    $arProgramacionDetalle->setAjusteProgramacion(0);
                } else {
                    $arProgramacionDetalle->setAjusteProgramacion(1);
                }                
            }                                         
            $em->flush();       
        }
        
    }     
}