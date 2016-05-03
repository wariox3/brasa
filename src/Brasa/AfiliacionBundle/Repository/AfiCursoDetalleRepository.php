<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiCursoDetalleRepository extends EntityRepository {  
    
    public function ListaDql($codigoCurso = '') {
        $em = $this->getEntityManager();
        $dql   = "SELECT cd FROM BrasaAfiliacionBundle:AfiCursoDetalle cd WHERE cd.codigoCursoDetallePk <> 0 ";
        if($codigoCurso != '') {
           $dql .= " AND cd.codigoCursoFk = " . $codigoCurso; 
        }
        $dql .= " ORDER BY cd.codigoCursoDetallePk";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    } 
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(cd.codigoCursoDetallePk) as numeroRegistros FROM BrasaAfiliacionBundle:AfiCursoDetalle cd "
                . "WHERE cd.codigoCursoFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrCursosDetalles = $query->getSingleResult(); 
        if($arrCursosDetalles) {
            $intNumeroRegistros = $arrCursosDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }              
        
}