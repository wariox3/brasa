<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_movimientos_historico")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbMovimientosHistoricoRepository")
 */
class CtbMovimientosHistorico
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
     * @ORM\ManyToOne(targetEntity="CtbCuentasContables", inversedBy="CtbMovimientosHistorico")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;       
  
    /**
     * @ORM\ManyToOne(targetEntity="CtbCentrosCostos", inversedBy="CtbMovimientosHistorico")
     * @ORM\JoinColumn(name="codigo_centro_costos_fk", referencedColumnName="codigo_centro_costos_pk")
     */
    protected $centroCostosRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobantesContables", inversedBy="CtbMovimientosHistorico")
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
     * @param date $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Get fecha
     *
     * @return date 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set numeroMovimiento
     *
     * @param integer $numeroMovimiento
     */
    public function setNumeroMovimiento($numeroMovimiento)
    {
        $this->numeroMovimiento = $numeroMovimiento;
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
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
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
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
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
     */
    public function setCodigoCentroCostosFk($codigoCentroCostosFk)
    {
        $this->codigoCentroCostosFk = $codigoCentroCostosFk;
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
     */
    public function setCodigoComprobanteContableFk($codigoComprobanteContableFk)
    {
        $this->codigoComprobanteContableFk = $codigoComprobanteContableFk;
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
     */
    public function setDebito($debito)
    {
        $this->debito = $debito;
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
     */
    public function setCredito($credito)
    {
        $this->credito = $credito;
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
     */
    public function setBase($base)
    {
        $this->base = $base;
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
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
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
     * Set terceroRel
     *
     * @param Brasa\GeneralBundle\Entity\GenTerceros $terceroRel
     */
    public function setTerceroRel(\Brasa\GeneralBundle\Entity\GenTerceros $terceroRel)
    {
        $this->terceroRel = $terceroRel;
    }

    /**
     * Get terceroRel
     *
     * @return Brasa\GeneralBundle\Entity\GenTerceros 
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * Set cuentaRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbCuentasContables $cuentaRel
     */
    public function setCuentaRel(\Brasa\ContabilidadBundle\Entity\CtbCuentasContables $cuentaRel)
    {
        $this->cuentaRel = $cuentaRel;
    }

    /**
     * Get cuentaRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbCuentasContables 
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * Set centroCostosRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbCentrosCostos $centroCostosRel
     */
    public function setCentroCostosRel(\Brasa\ContabilidadBundle\Entity\CtbCentrosCostos $centroCostosRel)
    {
        $this->centroCostosRel = $centroCostosRel;
    }

    /**
     * Get centroCostosRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbCentrosCostos 
     */
    public function getCentroCostosRel()
    {
        return $this->centroCostosRel;
    }

    /**
     * Set comprobanteContableRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbComprobantesContables $comprobanteContableRel
     */
    public function setComprobanteContableRel(\Brasa\ContabilidadBundle\Entity\CtbComprobantesContables $comprobanteContableRel)
    {
        $this->comprobanteContableRel = $comprobanteContableRel;
    }

    /**
     * Get comprobanteContableRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbComprobantesContables 
     */
    public function getComprobanteContableRel()
    {
        return $this->comprobanteContableRel;
    }
}
