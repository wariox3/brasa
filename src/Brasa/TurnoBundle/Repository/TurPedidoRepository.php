<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoRepository extends EntityRepository {
    
    public function listaDQL() {
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoPk <> 0";
        return $dql;
    }
    
    public function liquidar($codigoProgramacion) {        
        $em = $this->getEntityManager();        
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();        
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion); 
        $douTotalHoras = 0;
        $arProgramacionesDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
        $arProgramacionesDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoProgramacionFk' => $codigoProgramacion));         
        foreach ($arProgramacionesDetalle as $arProgramacionDetalle) {
            $douHorasDetalle = 0;
            if($arProgramacionDetalle->getDia1() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia1()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia2() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia2()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia3() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia3()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia4() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia4()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia5() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia5()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia6() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia6()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia7() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia7()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia8() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia8()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }            
            if($arProgramacionDetalle->getDia9() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia9()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia10() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia10()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia11() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia11()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia12() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia12()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia13() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia13()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia14() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia14()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia15() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia15()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia16() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia16()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia17() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia17()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia18() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia18()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia19() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia19()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia20() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia20()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia21() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia21()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia22() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia22()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia23() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia23()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia24() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia24()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia25() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia25()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia26() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia26()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia27() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia27()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia28() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia28()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia29() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia29()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia30() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia30()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }
            if($arProgramacionDetalle->getDia31() != '') {
                $arProgramacionesDetalleActualizar = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();        
                $arProgramacionesDetalleActualizar = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($arProgramacionDetalle->getCodigoProgramacionDetallePk());                         
                $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arProgramacionDetalle->getDia31()); 
                $arProgramacionesDetalleActualizar->setHoras($arTurno->getHoras());
                $em->persist($arProgramacionesDetalleActualizar);
                $douHorasDetalle += $arTurno->getHoras();
            }            
            $douTotalHoras += $douHorasDetalle;
        }
        $arProgramacion->setHoras($douTotalHoras);
        $em->persist($arProgramacion);
        $em->flush();
        return true;
    }        
}