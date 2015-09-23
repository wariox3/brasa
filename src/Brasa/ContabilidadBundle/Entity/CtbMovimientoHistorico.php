<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_movimiento_historico")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbMovimientoHistoricoRepository")
 */
class CtbMovimientoHistorico
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_historico_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoMovimientoHistoricoPk;       
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     
    
    /**
     * @ORM\Column(name="numero_movimiento", type="integer", nullable=true)
     */    
    private $numeroMovimiento;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20)
     */     
    private $codigoCuentaFk;     

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    
    
    /**
     * @ORM\Column(name="codigo_centro_costos_fk", type="integer", nullable=true)
     */     
    private $codigoCentroCostosFk;           
    
    /**
     * @ORM\Column(name="codigo_comprobante_contable_fk", type="integer", nullable=true)
     */     
    private $codigoComprobanteContableFk;     
    
    /**
     * @ORM\Column(name="debito", type="float")
     */    
    private $debito = 0;
    
    /**
     * @ORM\Column(name="credito", type="float")
     */    
    private $credito = 0;      
    
    /**
     * @ORM\Column(name="base", type="float")
     */    
    private $base = 0;                
    
    /**
     * @ORM\Column(name="detalle", type="string", length=80, nullable=true)
     */    
    private $detalle;             
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuenta", inversedBy="CtbMovimientoHistorico")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;       
  
    /**
     * @ORM\ManyToOne(targetEntity="CtbCentroCosto", inversedBy="CtbMovimientoHistorico")
     * @ORM\JoinColumn(name="codigo_centro_costos_fk", referencedColumnName="codigo_centro_costos_pk")
     */
    protected $centroCostosRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobanteContable", inversedBy="CtbMovimientoHistorico")
     * @ORM\JoinColumn(name="codigo_comprobante_contable_fk", referencedColumnName="codigo_comprobante_contable_pk")
     */
    protected $comprobanteContableRel;     



    /**
     * Get codigoMovimientoHistoricoPk
     *
     * @return integer
     */
    public function getCodigoMovimientoHistoricoPk()
    {
        return $this->codigoMovimientoHistoricoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CtbMovimientoHistorico
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set numeroMovimiento
     *
     * @param integer $numeroMovimiento
     *
     * @return CtbMovimientoHistorico
     */
    public function setNumeroMovimiento($numeroMovimiento)
    {
        $this->numeroMovimiento = $numeroMovimiento;

        return $this;
    }

    /**
     * Get numeroMovimiento
     *
     * @return integer
     */
    public function getNumeroMovimiento()
    {
        return $this->numeroMovimiento;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return CtbMovimientoHistorico
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return string
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return CtbMovimientoHistorico
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;

        return $this;
    }

    /**
     * Get codigoTerceroFk
     *
     * @return integer
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * Set codigoCentroCostosFk
     *
     * @param integer $codigoCentroCostosFk
     *
     * @return CtbMovimientoHistorico
     */
    public function setCodigoCentroCostosFk($codigoCentroCostosFk)
    {
        $this->codigoCentroCostosFk = $codigoCentroCostosFk;

        return $this;
    }

    /**
     * Get codigoCentroCostosFk
     *
     * @return integer
     */
    public function getCodigoCentroCostosFk()
    {
        return $this->codigoCentroCostosFk;
    }

    /**
     * Set codigoComprobanteContableFk
     *
     * @param integer $codigoComprobanteContableFk
     *
     * @return CtbMovimientoHistorico
     */
    public function setCodigoComprobanteContableFk($codigoComprobanteContableFk)
    {
        $this->codigoComprobanteContableFk = $codigoComprobanteContableFk;

        return $this;
    }

    /**
     * Get codigoComprobanteContableFk
     *
     * @return integer
     */
    public function getCodigoComprobanteContableFk()
    {
        return $this->codigoComprobanteContableFk;
    }

    /**
     * Set debito
     *
     * @param float $debito
     *
     * @return CtbMovimientoHistorico
     */
    public function setDebito($debito)
    {
        $this->debito = $debito;

        return $this;
    }

    /**
     * Get debito
     *
     * @return float
     */
    public function getDebito()
    {
        return $this->debito;
    }

    /**
     * Set credito
     *
     * @param float $credito
     *
     * @return CtbMovimientoHistorico
     */
    public function setCredito($credito)
    {
        $this->credito = $credito;

        return $this;
    }

    /**
     * Get credito
     *
     * @return float
     */
    public function getCredito()
    {
        return $this->credito;
    }

    /**
     * Set base
     *
     * @param float $base
     *
     * @return CtbMovimientoHistorico
     */
    public function setBase($base)
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Get base
     *
     * @return float
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return CtbMovimientoHistorico
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel
     *
     * @return CtbMovimientoHistorico
     */
    public function setCuentaRel(\Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel = null)
    {
        $this->cuentaRel = $cuentaRel;

        return $this;
    }

    /**
     * Get cuentaRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCuenta
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * Set centroCostosRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostosRel
     *
     * @return CtbMovimientoHistorico
     */
    public function setCentroCostosRel(\Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostosRel = null)
    {
        $this->centroCostosRel = $centroCostosRel;

        return $this;
    }

    /**
     * Get centroCostosRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCentroCosto
     */
    public function getCentroCostosRel()
    {
        return $this->centroCostosRel;
    }

    /**
     * Set comprobanteContableRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbComprobanteContable $comprobanteContableRel
     *
     * @return CtbMovimientoHistorico
     */
    public function setComprobanteContableRel(\Brasa\ContabilidadBundle\Entity\CtbComprobanteContable $comprobanteContableRel = null)
    {
        $this->comprobanteContableRel = $comprobanteContableRel;

        return $this;
    }

    /**
     * Get comprobanteContableRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbComprobanteContable
     */
    public function getComprobanteContableRel()
    {
        return $this->comprobanteContableRel;
    }
}
