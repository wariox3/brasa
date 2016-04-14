<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurFacturaConceptoRepository extends EntityRepository {
    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT fc FROM BrasaTurnoBundle:TurFacturaConcepto fc WHERE fc.codigoFacturaConceptoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND fc.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND fc.codigoFacturaConceptoPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY fc.nombre";
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurFacturaConcepto')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }
    
}