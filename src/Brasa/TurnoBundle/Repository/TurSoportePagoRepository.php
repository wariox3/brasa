<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSoportePagoRepository extends EntityRepository {
    
    public function listaDql() {
        $dql   = "SELECT sp FROM BrasaTurnoBundle:TurSoportePago sp WHERE sp.estadoCerrado = 0";
        return $dql;
    }
    
    public function resumen($dateFechaDesde, $dateFechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT spd.codigoRecursoFk, "
                . "SUM(spd.horasDiurnas) as horasDiurnas, "
                . "SUM(spd.horasExtrasOrdinariasDiurnas) as horasExtrasOrdinariasDiurnas, "
                . "SUM(spd.horasExtrasOrdinariasNocturnas) as horasExtrasOrdinariasNocturnas, "
                . "SUM(spd.horasExtrasFestivasDiurnas) as horasExtrasFestivasDiurnas, "
                . "SUM(spd.horasExtrasFestivasNocturnas) as horasExtrasFestivasNocturnas "
                . "FROM BrasaTurnoBundle:TurSoportePagoDetalle spd "
                . "WHERE spd.estadoCerrado = 0 "
                . "GROUP BY spd.codigoRecursoFk" ;
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();        
        for($i = 0; $i < count($arrayResultado); $i++){
            $codigoRecurso = $arrayResultado[$i]['codigoRecursoFk'];
            $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
            $arSoportePago = new \Brasa\TurnoBundle\Entity\TurSoportePago();
            $arSoportePago->setRecursoRel($arRecurso);
            $arSoportePago->setFechaDesde($dateFechaDesde);
            $arSoportePago->setFechaHasta($dateFechaHasta);
            $arSoportePago->setHorasDiurnas($arrayResultado[$i]['horasDiurnas']);
            $arSoportePago->setHorasExtrasOrdinariasDiurnas($arrayResultado[$i]['horasExtrasOrdinariasDiurnas']);
            $arSoportePago->setHorasExtrasOrdinariasNocturnas($arrayResultado[$i]['horasExtrasOrdinariasNocturnas']);
            $arSoportePago->setHorasExtrasFestivasDiurnas($arrayResultado[$i]['horasExtrasFestivasDiurnas']);
            $arSoportePago->setHorasExtrasFestivasNocturnas($arrayResultado[$i]['horasExtrasFestivasNocturnas']);
            $em->persist($arSoportePago);            
        }
        $em->flush();
        return $arrayResultado;        
    }

}