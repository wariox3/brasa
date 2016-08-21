<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CarAnticipoDetalleRepository extends EntityRepository {
    
    /*public function detalleConsultaDql($numero = "", $codigoCliente = "", $codigoCuentaCobrarTipo = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT rd FROM BrasaCarteraBundle:CarReciboDetalle rd JOIN rd.reciboRel r  WHERE rd.codigoReciboDetallePk <> 0 ";
        if($numero != "") {
            $dql .= " AND rd.numeroFactura = " . $numero;  
        }
        if($codigoCliente != "") {
            $dql .= " AND r.codigoClienteFk = " . $codigoCliente;  
        }
        if($codigoCuentaCobrarTipo != "") {
            $dql .= " AND rd.codigoCuentaCobrarTipoFk = " . $codigoCuentaCobrarTipo;  
        }
        if ($strFechaDesde != ""){
            $dql .= " AND r.fecha >='" . $strFechaDesde. "'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND r.fecha <='" . $strFechaHasta . "'";
        }        
        return $dql;
    } 
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arReciboDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->find($codigo);                
                $em->remove($arReciboDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }*/        
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(ad.codigoAnticipoDetallePk) as numeroRegistros FROM BrasaCarteraBundle:CarAnticipoDetalle ad "
                . "WHERE ad.codigoAnticipoFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrAnticipoDetalles = $query->getSingleResult(); 
        if($arrAnticipoDetalles) {
            $intNumeroRegistros = $arrAnticipoDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }  

    public function liquidar($codigoAnticipo) {        
        $em = $this->getEntityManager();        
        $arAnticipo = new \Brasa\CarteraBundle\Entity\CarAnticipo();        
        $arAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigoAnticipo); 
        $intCantidad = 0;
        $floValor = 0;
        $floValorPago = 0;
        $floDescuento = 0;
        $floAjustePeso = 0;
        $floReteIca = 0;
        $floReteIva = 0;
        $floReteFuente = 0;
        $arAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigoAnticipo);         
        $arAnticiposDetalle = new \Brasa\CarteraBundle\Entity\CarAnticipoDetalle();        
        $arAnticiposDetalle = $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->findBy(array('codigoAnticipoFk' => $codigoAnticipo));         
        foreach ($arAnticiposDetalle as $arAnticiposDetalle) {         
            $floDescuento += $arAnticiposDetalle->getVrDescuento();
            $floAjustePeso += $arAnticiposDetalle->getVrAjustePeso();
            $floReteIca += $arAnticiposDetalle->getVrReteIca();
            $floReteIva += $arAnticiposDetalle->getVrReteIva();
            $floReteFuente += $arAnticiposDetalle->getVrReteFuente();
            $floValor += $arAnticiposDetalle->getValor();
            $floValorPago += $arAnticiposDetalle->getVrPagoDetalle();
        }                 
        $arAnticipo->setVrTotal($floValor);
        $arAnticipo->setVrTotalPago($floValorPago);
        $arAnticipo->setVrTotalDescuento($floDescuento);
        $arAnticipo->setVrTotalAjustePeso($floAjustePeso);
        $arAnticipo->setVrTotalReteIca($floReteIca);
        $arAnticipo->setVrTotalReteIva($floReteIva);
        $arAnticipo->setVrTotalReteFuente($floReteFuente);
        $em->persist($arAnticipo);
        $em->flush();
        return true;
    }
    
    public function validarCuenta($codigoCuenta, $codigoAnticipo) {        
        $em = $this->getEntityManager();
        $boolValidar = TRUE;        
        $dql   = "SELECT COUNT(ad.codigoAnticipoDetallePk) as numeroRegistros FROM BrasaCarteraBundle:CarAnticipoDetalle ad "
                . "WHERE ad.codigoCuentaCobrarFk = " . $codigoCuenta . " AND ad.codigoAnticipoFk = " . $codigoAnticipo;
        $query = $em->createQuery($dql);
        $arrAnticipoDetalles = $query->getSingleResult(); 
        if($arrAnticipoDetalles) {
            $intNumeroRegistros = $arrAnticipoDetalles['numeroRegistros'];
            if($intNumeroRegistros > 0) {
                $boolValidar = FALSE;
            }
        }
        return $boolValidar;
    }

}