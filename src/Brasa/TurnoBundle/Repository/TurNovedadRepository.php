<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurNovedadRepository extends EntityRepository {

    public function listaDql($codigoRecurso = "") {
        $dql   = "SELECT n FROM BrasaTurnoBundle:TurNovedad n WHERE n.codigoNovedadPk <> 0";
        if($codigoRecurso != "") {
            $dql .= " AND n.codigoRecursoFk = " . $codigoRecurso;
        }
        $dql .= " ORDER BY n.codigoNovedadPk DESC";
        return $dql;
    }

    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigo);
                    $em->remove($arNovedad);
            }
            $em->flush();
        }
    }

    public function aplicar($codigoNovedad) {
        $em = $this->getEntityManager();
        $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigoNovedad);
        if($arNovedad->getEstadoAplicada() == 0) {
            $strAnio = $arNovedad->getFechaDesde()->format('Y');
            $strMes = $arNovedad->getFechaDesde()->format('m');
            $strDiaDesde = $arNovedad->getFechaDesde()->format('j');
            $strDiaHasta = $arNovedad->getFechaHasta()->format('j');
            $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $strAnio, 'mes' => $strMes));
            foreach ($arProgramacionDetalles as $arProgramacionDetalle) {
                
            }
        }
    }
}