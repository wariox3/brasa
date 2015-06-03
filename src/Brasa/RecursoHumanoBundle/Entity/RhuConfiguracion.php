<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConfiguracionRepository")
 */
class RhuConfiguracion
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoConfiguracionPk;
    
    /**
     * @ORM\Column(name="porcentaje_retencion_fuente_servicios", type="float")
     */    
    private $porcentajeRetencionFuenteServicios;          
    
    /**
     * @ORM\Column(name="porcentaje_retencion_cree", type="float")
     */    
    private $porcentajeRetencionCREE;     
    
    /**
     * @ORM\Column(name="base_retencion_fuente_servicios", type="float")
     */    
    private $baseRetencionFuenteServicios;

    /**
     * @ORM\Column(name="porcentaje_iva_ventas", type="float")
     */    
    private $porcentajeIvaVentas;    

    /**
     * @ORM\Column(name="porcentaje_retencion_iva", type="float")
     */    
    private $porcentajeRetencionIva;    
    
    /**
     * Get codigoConfiguracionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set porcentajeRetencionFuenteServicios
     *
     * @param float $porcentajeRetencionFuenteServicios
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajeRetencionFuenteServicios($porcentajeRetencionFuenteServicios)
    {
        $this->porcentajeRetencionFuenteServicios = $porcentajeRetencionFuenteServicios;

        return $this;
    }

    /**
     * Get porcentajeRetencionFuenteServicios
     *
     * @return float
     */
    public function getPorcentajeRetencionFuenteServicios()
    {
        return $this->porcentajeRetencionFuenteServicios;
    }

    /**
     * Set baseRetencionFuenteServicios
     *
     * @param float $baseRetencionFuenteServicios
     *
     * @return RhuConfiguracion
     */
    public function setBaseRetencionFuenteServicios($baseRetencionFuenteServicios)
    {
        $this->baseRetencionFuenteServicios = $baseRetencionFuenteServicios;

        return $this;
    }

    /**
     * Get baseRetencionFuenteServicios
     *
     * @return float
     */
    public function getBaseRetencionFuenteServicios()
    {
        return $this->baseRetencionFuenteServicios;
    }

    /**
     * Set porcentajeRetencionCREE
     *
     * @param float $porcentajeRetencionCREE
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajeRetencionCREE($porcentajeRetencionCREE)
    {
        $this->porcentajeRetencionCREE = $porcentajeRetencionCREE;

        return $this;
    }

    /**
     * Get porcentajeRetencionCREE
     *
     * @return float
     */
    public function getPorcentajeRetencionCREE()
    {
        return $this->porcentajeRetencionCREE;
    }

    /**
     * Set porcentajeIvaVentas
     *
     * @param float $porcentajeIvaVentas
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajeIvaVentas($porcentajeIvaVentas)
    {
        $this->porcentajeIvaVentas = $porcentajeIvaVentas;

        return $this;
    }

    /**
     * Get porcentajeIvaVentas
     *
     * @return float
     */
    public function getPorcentajeIvaVentas()
    {
        return $this->porcentajeIvaVentas;
    }

    /**
     * Set porcentajeRetencionIva
     *
     * @param float $porcentajeRetencionIva
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajeRetencionIva($porcentajeRetencionIva)
    {
        $this->porcentajeRetencionIva = $porcentajeRetencionIva;

        return $this;
    }

    /**
     * Get porcentajeRetencionIva
     *
     * @return float
     */
    public function getPorcentajeRetencionIva()
    {
        return $this->porcentajeRetencionIva;
    }
}
