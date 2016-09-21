<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiContratoRepository extends EntityRepository {    
    
    public function listaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiContrato c WHERE c.codigoContratoPk <> 0";
        $dql .= " ORDER BY c.codigoContratoPk";
        return $dql;
    }
    
    public function listaConsultaDql($strEmpleado = '', $codigoCliente = '', $strIdentificacion = '',$strDesde = "", $strHasta = "") {
        //$em = $this->getEntityManager();
        $dql   = "SELECT c,e FROM BrasaAfiliacionBundle:AfiContrato c JOIN c.empleadoRel e WHERE c.codigoContratoPk <> 0";
        if($strEmpleado != '') {
            $dql .= " AND e.nombreCorto LIKE '%" . $strEmpleado . "%'";
        }
        if($codigoCliente != '') {
            $dql .= " AND e.codigoClienteFk = " . $codigoCliente;
        } 
        if($strIdentificacion != '') {
            $dql .= " AND e.numeroIdentificacion = " . $strIdentificacion;
        } 
        if($strDesde != "") {
            $dql .= " AND c.fechaDesde >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND c.fechaDesde <='" . $strHasta . "'";
        }
        
        //$dql .= " ORDER BY pd.codigoPeriodoDetallePk";
        return $dql;
    }

    public function listaConsultaGeneralDql($strEmpleado = '', $codigoCliente = '', $strIdentificacion = '',$strDesde = "", $strHasta = "") {
        $em = $this->getEntityManager();
        /*$dql   = "SELECT c,e FROM BrasaAfiliacionBundle:AfiContrato c JOIN c.empleadoRel e WHERE c.codigoContratoPk <> 0";
        if($strEmpleado != '') {
            $dql .= " AND e.nombreCorto LIKE '%" . $strEmpleado . "%'";
        }
        if($codigoCliente != '') {
            $dql .= " AND e.codigoClienteFk = " . $codigoCliente;
        } 
        if($strIdentificacion != '') {
            $dql .= " AND e.numeroIdentificacion = " . $strIdentificacion;
        } 
        if($strDesde != "") {
            $dql .= " AND c.fechaDesde >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND c.fechaDesde <='" . $strHasta . "'";
        }
        
        //$dql .= " ORDER BY pd.codigoPeriodoDetallePk";
        return $dql;*/
        $strSql = "SELECT
                afi_contrato.codigo_contrato_pk as codigoContratoPk,
                afi_cliente.nombre_corto as cliente,
                afi_empleado.numero_identificacion as identificacion,
                afi_empleado.nombre_corto as empleado,
                afi_contrato.indefinido as indefinido,
                afi_contrato.fecha_desde as desde,
                afi_contrato.fecha_hasta as hasta
                FROM
                afi_empleado
                INNER JOIN afi_contrato ON afi_contrato.codigo_empleado_fk = afi_empleado.codigo_empleado_pk
                LEFT JOIN afi_cliente ON afi_empleado.codigo_cliente_fk = afi_cliente.codigo_cliente_pk AND afi_contrato.codigo_cliente_fk = afi_cliente.codigo_cliente_pk
                WHERE  afi_contrato.codigo_contrato_pk <> 0";
        if($strEmpleado != '') {
            $strSql .= " AND afi_empleado.numero_corto LIKE '%" . $strEmpleado . "%'";
        }
        if($codigoCliente != '') {
            $strSql .= " AND afi_contrato.codigo_cliente_fk = " . $codigoCliente;
        } 
        if($strIdentificacion != '') {
            $strSql .= " AND afi_empleado.numero_identificacion = " . $strIdentificacion;
        }
        if($strDesde != "") {
            $strSql .= " AND afi_contrato.fecha_desde >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $strSql .= " AND afi_contrato.fecha_hasta <='" . $strHasta . "'";
        }        
                //afi_contrato.codigo_cliente_fk = '401' AND
                //afi_empleado.numero_identificacion = '98553229'";
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $strSql = $statement->fetchAll();
        return $strSql;
    }
    
    public function listaConsultaPagoPendienteDql($strEmpleado = '', $codigoCliente = '', $strIdentificacion = '',$strDesde = "", $strHasta = "") {
        //$em = $this->getEntityManager();
        $dql   = "SELECT c,e FROM BrasaAfiliacionBundle:AfiContrato c JOIN c.empleadoRel e WHERE c.codigoContratoPk <> 0 AND c.estadoGeneradoCtaCobrar = 0";
        if($strEmpleado != '') {
            $dql .= " AND e.nombreCorto LIKE '%" . $strEmpleado . "%'";
        }
        if($codigoCliente != '') {
            $dql .= " AND e.codigoClienteFk = " . $codigoCliente;
        } 
        if($strIdentificacion != '') {
            $dql .= " AND e.numeroIdentificacion = " . $strIdentificacion;
        } 
        if($strDesde != "") {
            $dql .= " AND c.fechaDesde >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND c.fechaDesde <='" . $strHasta . "'";
        }
        
        //$dql .= " ORDER BY pd.codigoPeriodoDetallePk";
        return $dql;
    }
    
    public function pendienteAfiliacionDql($codigoCliente = '') {
        //$em = $this->getEntityManager();
        $dql   = "SELECT c,e FROM BrasaAfiliacionBundle:AfiContrato c JOIN c.empleadoRel e WHERE c.codigoContratoPk <> 0 AND c.estadoGeneradoCtaCobrar = 0";
        /*if($strEmpleado != '') {
            $dql .= " AND e.nombreCorto LIKE '%" . $strEmpleado . "%'";
        }*/
        if($codigoCliente != '') {
            $dql .= " AND c.codigoClienteFk = " . $codigoCliente;
        } 
        /*if($strIdentificacion != '') {
            $dql .= " AND e.numeroIdentificacion = " . $strIdentificacion;
        } 
        if($strDesde != "") {
            $dql .= " AND c.fechaDesde >='" . date_format($strDesde, ('Y-m-d')) . "'";
        }
        if($strHasta != "") {
            $dql .= " AND c.fechaDesde <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }*/
        
        //$dql .= " ORDER BY pd.codigoPeriodoDetallePk";
        return $dql;
    }
    
    public function listaDetalleDql($codigoEmpleado) {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiContrato c WHERE c.codigoEmpleadoFk = " . $codigoEmpleado;
        return $dql;
    }                
    
    public function eliminar($arrSeleccionados,$codigoEmpleado) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($codigo);
                $em->remove($ar);
                $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigoEmpleado);
                $arEmpleado->setCodigoContratoActivo(null);
                $em->persist($arEmpleado);
            }
            $em->flush();
        }
    }  
    
    public function contratosPeriodo($fechaDesde = "", $fechaHasta = "", $codigoCliente = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiContrato c "
                ." WHERE (c.fechaHasta >= '" . $fechaDesde . "' OR c.indefinido = 1) "
                . "AND c.fechaDesde <= '" . $fechaHasta . "' AND c.codigoClienteFk=" . $codigoCliente;
        $query = $em->createQuery($dql);        
        $arContratos = $query->getResult();        
        return $arContratos;
    }    
}