<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurProgramacionDetalleRepository extends EntityRepository {
    
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd WHERE pd.codigoProgramacionDetallePk <> 0";
        $dql .= " ORDER BY pd.codigoProgramacionDetallePk";
        return $dql;
    }                
    
    public function eliminarDetallesSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigo);                
                $em->remove($arProgramacionDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }  
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigo));
        return count($arDetalles);
    }  
    
    public function periodo($strFechaDesde, $strFechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd JOIN pd.programacionRel p "
                . "WHERE p.fecha >= '" . $strFechaDesde . "' AND p.fecha <='" . $strFechaHasta . "'";
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    
    
    public function nuevo($codigoPedidoDetalle, $arProgramacion) {
        $em = $this->getEntityManager();
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();        
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);                
        
        $intDiaInicial = $arPedidoDetalle->getFechaDesde()->format('j');
        $intDiaFinal = $arPedidoDetalle->getFechaHasta()->format('j');
        $strMesAnio = $arPedidoDetalle->getPedidoRel()->getFecha()->format('Y/m');
        for($j = 1; $j <= $arPedidoDetalle->getCantidad(); $j++) {
            if($arPedidoDetalle->getPlantillaRel()) { 
                if($arPedidoDetalle->getPlantillaRel()) {
                    $arPlantilla = $arPedidoDetalle->getPlantillaRel();
                }
                $intPosicionRecurso = 1;
                $arPlantillaDetalles = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
                $arPlantillaDetalles = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array('codigoPlantillaFk' => 1));                
                foreach ($arPlantillaDetalles as $arPlantillaDetalle) {
                    $intPosicion = $this->devuelvePosicionInicialMatrizPlantilla(2016, $arPlantilla->getDias(), $arPedidoDetalle->getFechaDesde()->format('Y/m/d'));                                                                    
                    $arrTurnos = $this->devuelveTurnosMes($arPlantillaDetalle);
                    $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                    $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                    $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                    $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                    $arPedidoDetalleRecurso = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso();
                    $arPedidoDetalleRecurso = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleRecurso')->findOneBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle, 'posicion' => $intPosicionRecurso));                
                    if(count($arPedidoDetalleRecurso) > 0) {
                        $arProgramacionDetalle->setRecursoRel($arPedidoDetalleRecurso->getRecursoRel());
                    }
                    for($i = 1; $i < 32; $i++) {
                        $boolAplica = $this->aplicaPlantilla($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle);

                        if($i == 1 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia1($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 2 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia2($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 3 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia3($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 4 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia4($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 5 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia5($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 6 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia6($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 7 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia7($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 8 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia8($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 9 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia9($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 10 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia10($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 11 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia11($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 12 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia12($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 13 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia13($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 14 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia14($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 15 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia15($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 16 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia16($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 17 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia17($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 18 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia18($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 19 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia19($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 20 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia20($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 21 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia21($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 22 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia22($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 23 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia23($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 24 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia24($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 25 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia25($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 26 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia26($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 27 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia27($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 28 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia28($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 29 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia29($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 30 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia30($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        if($i == 31 && $boolAplica == TRUE) {
                            $arProgramacionDetalle->setDia31($this->devuelveCodigoTurno($arrTurnos[$intPosicion]));
                        }
                        $intPosicion++;
                        if($intPosicion == ($arPlantilla->getDias() + 1)) {
                            $intPosicion = 1;
                        }
                    }
                    $em->persist($arProgramacionDetalle);
                    $intPosicionRecurso++;
                }
            } else {
                if($arPedidoDetalle->getCantidadRecurso() != 0) {
                    $intCantidad = $arPedidoDetalle->getCantidadRecurso();
                    for($k = 1; $k <= $intCantidad; $k++) {
                        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                        $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                        $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                        $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $em->persist($arProgramacionDetalle);
                    }
                }
            }
        }        
    }
    
    private function aplicaPlantilla ($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle) {
        $boolResultado = FALSE;
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
        }
        return $boolResultado;
    }    

    private function devuelvePosicionInicialMatrizPlantilla($strAnio, $intPosiciones, $strFechaHasta) {
        $intPos = 1;        
        $strFecha = $strAnio."/01/01";
        while($strFecha != $strFechaHasta) {
            //$dateFecha = date_create($strAnio."/01/01");
            $nuevafecha = strtotime ( '+1 day' , strtotime ( $strFecha ) ) ;
            $strFecha = date ( 'Y/m/d' , $nuevafecha );

            $intPos++;
            if($intPos == ($intPosiciones+1)) {
                $intPos = 1;
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
            '31' => $arPlantillaDetalle->getDia31(),);        
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
}