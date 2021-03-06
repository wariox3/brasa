<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CuentasCobrarRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CarNotaCreditoDetalleRepository extends EntityRepository {
    
    public function detalleConsultaDql($numero = "", $codigoCliente = "", $codigoCuentaCobrarTipo = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT ncd FROM BrasaCarteraBundle:CarNotaCreditoDetalle ncd JOIN ncd.notaCreditoRel nc  WHERE ncd.codigoNotaCreditoDetallePk <> 0 ";
        if($numero != "") {
            $dql .= " AND ncd.numeroFactura = " . $numero;  
        }
        if($codigoCliente != "") {
            $dql .= " AND nc.codigoClienteFk = " . $codigoCliente;  
        }
        if($codigoCuentaCobrarTipo != "") {
            $dql .= " AND ncd.codigoCuentaCobrarTipoFk = " . $codigoCuentaCobrarTipo;  
        }
        if ($strFechaDesde != ""){
            $dql .= " AND nc.fecha >='" . date_format($strFechaDesde, ('Y-m-d')). "'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND nc.fecha <='" . date_format($strFechaHasta, ('Y-m-d')) . "'";
        }        
        return $dql;
    } 
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arNotaCreditoDetalle = $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->find($codigo);                
                $em->remove($arNotaCreditoDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(ncd.codigoNotaCreditoDetallePk) as numeroRegistros FROM BrasaCarteraBundle:CarNotaCreditoDetalle ncd "
                . "WHERE ncd.codigoNotaCreditoFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrNotaCreditoDetalles = $query->getSingleResult(); 
        if($arrNotaCreditoDetalles) {
            $intNumeroRegistros = $arrNotaCreditoDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }  

    public function liquidar($codigoNotaCredito) {        
        $em = $this->getEntityManager();        
        $arNotaCredito = new \Brasa\CarteraBundle\Entity\CarNotaCredito();        
        $arNotaCredito = $em->getRepository('BrasaCarteraBundle:CarNotaCredito')->find($codigoNotaCredito); 
        $intCantidad = 0;
        $floValor = 0;
        $arNotaCredito = $em->getRepository('BrasaCarteraBundle:CarNotaCredito')->find($codigoNotaCredito);         
        $arNotaCreditosDetalle = new \Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle();        
        $arNotaCreditosDetalle = $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->findBy(array('codigoNotaCreditoFk' => $codigoNotaCredito));         
        foreach ($arNotaCreditosDetalle as $arNotaCreditoDetalle) {         
            $floValor += $arNotaCreditoDetalle->getValor();
        }                 
        $arNotaCredito->setValor($floValor);
        $em->persist($arNotaCredito);
        $em->flush();
        return true;
    }
    
    public function validarCuenta($codigoCuenta, $codigoNotaCredito) {        
        $em = $this->getEntityManager();
        $boolValidar = TRUE;        
        $dql   = "SELECT COUNT(ncd.codigoNotaCreditoDetallePk) as numeroRegistros FROM BrasaCarteraBundle:CarNotaCreditoDetalle ncd "
                . "WHERE ncd.codigoCuentaCobrarFk = " . $codigoCuenta . " AND ncd.codigoNotaCreditoFk = " . $codigoNotaCredito;
        $query = $em->createQuery($dql);
        $arrNotaCreditoDetalles = $query->getSingleResult(); 
        if($arrNotaCreditoDetalles) {
            $intNumeroRegistros = $arrNotaCreditoDetalles['numeroRegistros'];
            if($intNumeroRegistros > 0) {
                $boolValidar = FALSE;
            }
        }
        return $boolValidar;
    }
           
}