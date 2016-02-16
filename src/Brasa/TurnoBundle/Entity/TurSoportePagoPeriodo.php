<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_soporte_pago_periodo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSoportePagoPeriodoRepository")
 */
class TurSoportePagoPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pago_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePagoPeriodoPk;            
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;        

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;            
    
    /**
     * @ORM\Column(name="recursos", type="integer")
     */    
    private $recursos = 0;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;    
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = false; 

    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = false;     

    /**
     * @ORM\ManyToOne(targetEntity="TurCentroCosto", inversedBy="soportesPagosPeriodosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     
    
   /**
     * @ORM\OneToMany(targetEntity="TurSoportePago", mappedBy="soportePagoPeriodoRel")
     */
    protected $soportesPagosSoportePagoPeriodoRel;    
    
   /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoDetalle", mappedBy="soportePagoPeriodoRel")
     */
    protected $soportesPagosDetallesSoportePagoPeriodoRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->soportesPagosSoportePagoPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosDetallesSoportePagoPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSoportePagoPeriodoPk
     *
     * @return integer
     */
    public function getCodigoSoportePagoPeriodoPk()
    {
        return $this->codigoSoportePagoPeriodoPk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return TurSoportePagoPeriodo
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return TurSoportePagoPeriodo
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set recursos
     *
     * @param integer $recursos
     *
     * @return TurSoportePagoPeriodo
     */
    public function setRecursos($recursos)
    {
        $this->recursos = $recursos;

        return $this;
    }

    /**
     * Get recursos
     *
     * @return integer
     */
    public function getRecursos()
    {
        return $this->recursos;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return TurSoportePagoPeriodo
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return TurSoportePagoPeriodo
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return TurSoportePagoPeriodo
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCentroCosto $centroCostoRel
     *
     * @return TurSoportePagoPeriodo
     */
    public function setCentroCostoRel(\Brasa\TurnoBundle\Entity\TurCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * Add soportesPagosSoportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosSoportePagoPeriodoRel
     *
     * @return TurSoportePagoPeriodo
     */
    public function addSoportesPagosSoportePagoPeriodoRel(\Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosSoportePagoPeriodoRel)
    {
        $this->soportesPagosSoportePagoPeriodoRel[] = $soportesPagosSoportePagoPeriodoRel;

        return $this;
    }

    /**
     * Remove soportesPagosSoportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosSoportePagoPeriodoRel
     */
    public function removeSoportesPagosSoportePagoPeriodoRel(\Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosSoportePagoPeriodoRel)
    {
        $this->soportesPagosSoportePagoPeriodoRel->removeElement($soportesPagosSoportePagoPeriodoRel);
    }

    /**
     * Get soportesPagosSoportePagoPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosSoportePagoPeriodoRel()
    {
        return $this->soportesPagosSoportePagoPeriodoRel;
    }

    /**
     * Add soportesPagosDetallesSoportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoPeriodoRel
     *
     * @return TurSoportePagoPeriodo
     */
    public function addSoportesPagosDetallesSoportePagoPeriodoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoPeriodoRel)
    {
        $this->soportesPagosDetallesSoportePagoPeriodoRel[] = $soportesPagosDetallesSoportePagoPeriodoRel;

        return $this;
    }

    /**
     * Remove soportesPagosDetallesSoportePagoPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoPeriodoRel
     */
    public function removeSoportesPagosDetallesSoportePagoPeriodoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesSoportePagoPeriodoRel)
    {
        $this->soportesPagosDetallesSoportePagoPeriodoRel->removeElement($soportesPagosDetallesSoportePagoPeriodoRel);
    }

    /**
     * Get soportesPagosDetallesSoportePagoPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosDetallesSoportePagoPeriodoRel()
    {
        return $this->soportesPagosDetallesSoportePagoPeriodoRel;
    }
}
