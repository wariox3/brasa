<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuExamenRepository extends EntityRepository {

    public function listaDQL($codigoCentroCosto = "", $strIdentificacion = "", $boolAprobado = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT e FROM BrasaRecursoHumanoBundle:RhuExamen e WHERE e.codigoExamenPk <> 0";
        if($codigoCentroCosto != "" ) {
            $dql .= " AND e.codigoCentroCostoFk = " . $codigoCentroCosto;
        }
        if($strIdentificacion != "" ) {
            $dql .= " AND e.identificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($boolAprobado == 1 ) {
            $dql .= " AND e.estadoAprobado = 1";
        } elseif($boolAprobado == 0) {
            $dql .= " AND e.estadoAprobado = 0";
        }
        $dql .= " ORDER BY e.codigoExamenPk";
        return $dql;
    }

    public function liquidar($codigoExamen) {
        $em = $this->getEntityManager();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        $arExamenDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => $codigoExamen));
        $douTotal = 0;
        foreach ($arExamenDetalles AS $arExamenDetalle) {
            $douTotal += $arExamenDetalle->getVrPrecio();
        }
        $arExamen->setVrTotal($douTotal);
        $em->persist($arExamen);
        $em->flush();
    }
    
    public function autorizar($codigoExamen) {
        $em = $this->getEntityManager();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);       
        $arExamen->setEstadoAutorizado(1);
        $em->persist($arExamen);
        $em->flush();
    }    

    public function eliminarExamen($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoExamen) {
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
                if($em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->devuelveNumeroDetalleExamen($codigoExamen) <= 0){
                    $em->remove($arSeleccion);
                }
            }
            $em->flush();
        }
    }

    public function aprobarExamen($codigoExamen) {
        $em = $this->getEntityManager();        
        $strRespuesta = '';
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);        
        if($arExamen->getEstadoAprobado() == 0 && $arExamen->getEstadoAutorizado() == 1) {
            $arExamenDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
            $arExamenDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => $codigoExamen, 'estadoAprobado' => 0));
            if(count($arExamenDetalles) <= 0) {
                $arExamen->setEstadoAprobado(1);
                $em->persist($arExamen);
                $em->flush();
            } else {
                $strRespuesta = "Todos los detalles del examen deben estar aprobados";
            }
        } else {
            $strRespuesta = "El examen ya esta aprobado o no esta autorizado";
        }
        return $strRespuesta;
    }

    public function devuelveNumeroDetalleExamen($codigoSeleccionGrupo) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoExamenDetallePk) FROM BrasaRecursoHumanoBundle:RhuExamenDetalle s WHERE s.codigoExamenFk = " . $codigoSeleccionGrupo;
        $query = $em->createQuery($dql);
        $douNumeroDetalleExamen = $query->getSingleScalarResult();
        return $douNumeroDetalleExamen;
    }
    
    public function pendienteCobrar($codigoCentroCosto) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT e FROM BrasaRecursoHumanoBundle:RhuExamen e WHERE e.estadoCobrado = 0 "
                . " AND e.codigoCentroCostoFk = " . $codigoCentroCosto;
        return $dql;
    }    
    
    public function pendienteCobrarConsulta() {        
        $em = $this->getEntityManager();
        $dql   = "SELECT e FROM BrasaRecursoHumanoBundle:RhuExamen e WHERE e.estadoCobrado = 0 ";
        return $dql;
    }
        
}
