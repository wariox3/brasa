<?php

namespace Brasa\ContabilidadBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AsientosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CtbAsientoRepository extends EntityRepository
{
    /**
     * Liquida los totales de un asiento
     * @param integer $codigoAsiento codigo del asiento que se va a procesar.
     * */
    public function listaDQL($intCodigoAsiento = "", $intCodigoComprobante = "") {
        $dql   = "SELECT a FROM BrasaContabilidadBundle:CtbAsiento a WHERE a.codigoAsientoPk <> 0";
        if($intCodigoAsiento != "" && $intCodigoAsiento != 0) {
            $dql .= " AND a.codigoAsientoPk = " . $intCodigoAsiento;
        }
        if($intCodigoComprobante != "" && $intCodigoComprobante != 0) {
            $dql .= " AND a.codigoComprobanteFk = " . $intCodigoComprobante;
        }
        $dql .= " ORDER BY a.fecha DESC";
        return $dql;
    }
    
    public function validarAsientosDQL($intCodigoAsiento = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT ad, a FROM BrasaContabilidadBundle:CtbAsientoDetalle ad JOIN ad.asientoRel a "
                . "WHERE ad.codigoAsientoFk = " . $intCodigoAsiento;
        $query = $em->createQuery($dql);
        $arAsientoRegistros = $query->getResult();
        return $arAsientoRegistros;
    }
    
    public function Liquidar($codigoAsiento) {
        $em = $this->getEntityManager();
        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
        $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
        $arAsientoDetalles = new \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle();
        $arAsientoDetalles = $em->getRepository('BrasaContabilidadBundle:CtbAsientoDetalle')->findBy(array('codigoAsientoFk' => $codigoAsiento));
        $douTotalDebito = 0;
        $douTotalCredito = 0;
        foreach($arAsientoDetalles as $arAsientoDetalles) {
            $douTotalDebito = $douTotalDebito + $arAsientoDetalles->getDebito();
            $douTotalCredito = $douTotalCredito + $arAsientoDetalles->getCredito();
        }
        $arAsiento->setTotalDebito($douTotalDebito);
        $arAsiento->setTotalCredito($douTotalCredito);
        $arAsiento->setDiferencia($douTotalDebito - $douTotalCredito);
        $em->persist($arAsiento);
        $em->flush();

    }   
    
    public function Autorizar($codigoAsiento) {
        $em = $this->getEntityManager();
        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
        $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
        if($arAsiento->getEstadoAutorizado() == 0) {            
            if($arAsiento->getTotalDebito() == $arAsiento->getTotalCredito()){
                $arAsiento->setEstadoAutorizado(1);
                $em->persist($arAsiento);
                $em->flush();
                return "";
            }else{
                return "El asiento esta descuadrado";
            }    
        }
        else
            return "El asiento no esta autorizado";
    }
    
    public function DesAutorizar($codigoAsiento) {
        $em = $this->getEntityManager();
        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
        $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
        if($arAsiento->getEstadoAutorizado() == 1) {            
            $arAsiento->setEstadoAutorizado(0);
            $em->persist($arAsiento);
            $em->flush();
            return "";
        }
        
    }
    
    public function Aprobar($codigoAsiento) {
        $em = $this->getEntityManager();
        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsiento();
        $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsiento')->find($codigoAsiento);
        
        if($arAsiento->getTotalDebito() == $arAsiento->getTotalCredito()){
            $arAsiento->setEstadoAprobado(1);
            $em->persist($arAsiento);
            $em->flush();
            return "";
        }else{
            return "El asiento esta descuadrado";
        }    
        
        
    }
    
    /*public function Imprimir($codigoAsiento) {
        $em = $this->getEntityManager();
        $arAsiento = new \Brasa\ContabilidadBundle\Entity\CtbAsientos();
        $arAsiento = $em->getRepository('BrasaContabilidadBundle:CtbAsientos')->find($codigoAsiento);
        if ($arAsiento->getEstadoAutorizado() == 1) {            
            if($arAsiento->getNumeroAsiento() == 0 && $arAsiento->getEstadoImpreso() == 0) {
                $arAsiento->setNumeroAsiento($em->getRepository('BrasaContabilidadBundle:CtbAsientos')->DevConsecutivo($arAsiento->getCodigoAsientoTipoFk()));
                $arAsiento->setFecha(date_create(date('Y-m-d H:i:s')));                    
            }            
            $arAsiento->setEstadoImpreso(1);
            $em->persist($arAsiento);
            $em->flush();
        }
    }
    
    public function DevConsecutivo ($intCodigoAsientoTipo) {
        $em = $this->getEntityManager();
        $arAsientoTipo = new \Brasa\ContabilidadBundle\Entity\CtbAsientosTipos();
        $arAsientoTipo = $em->getRepository('BrasaContabilidadBundle:CtbAsientosTipos')->find($intCodigoAsientoTipo);
        $intNroDocumento = $arAsientoTipo->getConsecutivo();
        $arAsientoTipo->setConsecutivo($intNroDocumento + 1);
        $em->persist($arAsientoTipo);
        $em->flush();
        return $intNroDocumento;
    }    
    
    public function DevAsientos($intNumero = "", $intComprobante = "", $dateFechaDesde = "", $dateFechaHasta = "", $boolContabilizado = "") {
        $objRepositorio = $this->getEntityManager()->getRepository('BrasaContabilidadBundle:CtbAsientos');    
        $objQuery = $objRepositorio->createQueryBuilder('asientos');           
        
        if($intNumero != "")
            $objQuery->andWhere ("asientos.numeroAsiento = " . $intNumero);        
        if($intComprobante != "")
            $objQuery->andWhere ("asientos.codigoComprobanteContableFk = " . $intComprobante);
        
        $objQuery = $objQuery->getQuery();        
        return $objQuery->getResult();
    }     */
}