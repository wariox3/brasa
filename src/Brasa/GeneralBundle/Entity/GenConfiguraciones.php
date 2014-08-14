<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_configuraciones")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenConfiguracionesRepository")
 */
class GenConfiguraciones
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="smallint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoConfiguracionPk;    
    
    /**
     * @ORM\Column(name="base_retencion_fuente", type="float")
     */
    private $baseRetencionFuente = 0; 

    /**
     * @ORM\Column(name="base_retencion_cree", type="float")
     */
    private $baseRetencionCREE = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_retencion_fuente", type="float")
     */
    private $porcentajeRetencionFuente = 0;     

    /**
     * @ORM\Column(name="porcentaje_retencion_cree", type="float")
     */
    private $porcentajeRetencionCREE = 0;     
    
    /**
     * @ORM\Column(name="base_retencion_iva_ventas", type="float")
     */
    private $baseRetencionIvaVentas = 0;     

    /**
     * @ORM\Column(name="porcentaje_retencion_iva_ventas", type="float")
     */
    private $porcentajeRetencionIvaVentas = 0;     
    
    /**
     * @ORM\Column(name="fecha_ultimo_cierre", type="date", nullable=true)
     */    
    private $fechaUltimoCierre;     

    /**
     * @ORM\Column(name="nit_ventas_mostrador", type="integer")
     */
    private $nitVentasMostrador = 0;         


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
     * Set baseRetencionFuente
     *
     * @param float $baseRetencionFuente
     * @return GenConfiguraciones
     */
    public function setBaseRetencionFuente($baseRetencionFuente)
    {
        $this->baseRetencionFuente = $baseRetencionFuente;

        return $this;
    }

    /**
     * Get baseRetencionFuente
     *
     * @return float 
     */
    public function getBaseRetencionFuente()
    {
        return $this->baseRetencionFuente;
    }

    /**
     * Set baseRetencionCREE
     *
     * @param float $baseRetencionCREE
     * @return GenConfiguraciones
     */
    public function setBaseRetencionCREE($baseRetencionCREE)
    {
        $this->baseRetencionCREE = $baseRetencionCREE;

        return $this;
    }

    /**
     * Get baseRetencionCREE
     *
     * @return float 
     */
    public function getBaseRetencionCREE()
    {
        return $this->baseRetencionCREE;
    }

    /**
     * Set porcentajeRetencionFuente
     *
     * @param float $porcentajeRetencionFuente
     * @return GenConfiguraciones
     */
    public function setPorcentajeRetencionFuente($porcentajeRetencionFuente)
    {
        $this->porcentajeRetencionFuente = $porcentajeRetencionFuente;

        return $this;
    }

    /**
     * Get porcentajeRetencionFuente
     *
     * @return float 
     */
    public function getPorcentajeRetencionFuente()
    {
        return $this->porcentajeRetencionFuente;
    }

    /**
     * Set porcentajeRetencionCREE
     *
     * @param float $porcentajeRetencionCREE
     * @return GenConfiguraciones
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
     * Set baseRetencionIvaVentas
     *
     * @param float $baseRetencionIvaVentas
     * @return GenConfiguraciones
     */
    public function setBaseRetencionIvaVentas($baseRetencionIvaVentas)
    {
        $this->baseRetencionIvaVentas = $baseRetencionIvaVentas;

        return $this;
    }

    /**
     * Get baseRetencionIvaVentas
     *
     * @return float 
     */
    public function getBaseRetencionIvaVentas()
    {
        return $this->baseRetencionIvaVentas;
    }

    /**
     * Set porcentajeRetencionIvaVentas
     *
     * @param float $porcentajeRetencionIvaVentas
     * @return GenConfiguraciones
     */
    public function setPorcentajeRetencionIvaVentas($porcentajeRetencionIvaVentas)
    {
        $this->porcentajeRetencionIvaVentas = $porcentajeRetencionIvaVentas;

        return $this;
    }

    /**
     * Get porcentajeRetencionIvaVentas
     *
     * @return float 
     */
    public function getPorcentajeRetencionIvaVentas()
    {
        return $this->porcentajeRetencionIvaVentas;
    }

    /**
     * Set fechaUltimoCierre
     *
     * @param \DateTime $fechaUltimoCierre
     * @return GenConfiguraciones
     */
    public function setFechaUltimoCierre($fechaUltimoCierre)
    {
        $this->fechaUltimoCierre = $fechaUltimoCierre;

        return $this;
    }

    /**
     * Get fechaUltimoCierre
     *
     * @return \DateTime 
     */
    public function getFechaUltimoCierre()
    {
        return $this->fechaUltimoCierre;
    }

    /**
     * Set nitVentasMostrador
     *
     * @param integer $nitVentasMostrador
     * @return GenConfiguraciones
     */
    public function setNitVentasMostrador($nitVentasMostrador)
    {
        $this->nitVentasMostrador = $nitVentasMostrador;

        return $this;
    }

    /**
     * Get nitVentasMostrador
     *
     * @return integer 
     */
    public function getNitVentasMostrador()
    {
        return $this->nitVentasMostrador;
    }
}
