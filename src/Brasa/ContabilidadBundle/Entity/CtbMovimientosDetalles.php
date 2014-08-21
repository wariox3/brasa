<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_movimientos_detalles")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbMovimientosDetallesRepository")
 */
class CtbMovimientosDetalles
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoMovimientoDetallePk;              

    /**
     * @ORM\Column(name="codigo_movimiento_fk", type="integer")
     */     
    private $codigoMovimientoFk;    
    
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
     * @ORM\Column(name="codigo_cuenta_pagar_fk", type="integer", nullable=true)
     */     
    private $codigoCuentaPagarFk;                          
    
    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk", type="integer", nullable=true)
     */     
    private $codigoCuentaCobrarFk;     
    
    /**
     * @ORM\Column(name="valor", type="float")
     */    
    private $valor = 0;       
    
    /**
     * @ORM\Column(name="codigo_registro_tipo_fk", type="smallint")
     */    
    private $codigoRegistroTipoFk;    
    
    /**
     * @ORM\Column(name="base", type="float")
     */    
    private $base = 0;                

    /**
     * @ORM\Column(name="descripcion_contable", type="string", length=80, nullable=true)
     */    
    private $descripcionContable;     
    
    /**
     * @ORM\Column(name="codigo_impuesto_iva_fk", type="integer", nullable=true)
     */     
    private $codigoImpuestoIvaFk;    

    /**
     * @ORM\Column(name="codigo_impuesto_retencion_fk", type="integer", nullable=true)
     */     
    private $codigoImpuestoRetencionFk;        

    /**
     * @ORM\Column(name="codigo_impuesto_cree_fk", type="integer", nullable=true)
     * Contribucion empresarial para la equidad
     */     
    private $codigoImpuestoCreeFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbMovimientos", inversedBy="CtbMovimientosDetalles")
     * @ORM\JoinColumn(name="codigo_movimiento_fk", referencedColumnName="codigo_movimiento_pk")
     */
    protected $movimientoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuentasContables", inversedBy="CtbMovimientosDetalles")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;       
  
    /**
     * @ORM\ManyToOne(targetEntity="CtbCentrosCostos", inversedBy="CtbMovimientosDetalles")
     * @ORM\JoinColumn(name="codigo_centro_costos_fk", referencedColumnName="codigo_centro_costos_pk")
     */
    private $centroCostosRel;      

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTerceros", inversedBy="CtbMovimientosDetalles")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    

    /**
     * @ORM\ManyToOne(targetEntity="CtbImpuestos", inversedBy="CtbMovimientosDetalles")
     * @ORM\JoinColumn(name="codigo_impuesto_iva_fk", referencedColumnName="codigo_impuesto_pk")
     */
    private $impuestoIvaRel;    

    /**
     * @ORM\ManyToOne(targetEntity="CtbImpuestos", inversedBy="CtbMovimientosDetalles")
     * @ORM\JoinColumn(name="codigo_impuesto_retencion_fk", referencedColumnName="codigo_impuesto_pk")
     */
    private $impuestoRetencionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbImpuestos", inversedBy="CtbMovimientosDetalles")
     * @ORM\JoinColumn(name="codigo_impuesto_cree_fk", referencedColumnName="codigo_impuesto_pk")
     */
    private $impuestoCreeRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\TesoreriaBundle\Entity\TesCuentasPagar", inversedBy="CtbMovimientosDetalles")
     * @ORM\JoinColumn(name="codigo_cuenta_pagar_fk", referencedColumnName="codigo_cuenta_pagar_pk")
     */
    protected $cuentaPagarRel;    

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\CarteraBundle\Entity\CarCuentasCobrar", inversedBy="CtbMovimientosDetalles")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarRel; 

    /**
     * @ORM\ManyToOne(targetEntity="CtbRegistrosTipos", inversedBy="CtbMovimientosDetalles")
     * @ORM\JoinColumn(name="codigo_registro_tipo_fk", referencedColumnName="codigo_registro_tipo_pk")
     */
    private $registroTipoRel;    
    


    /**
     * Get codigoMovimientoDetallePk
     *
     * @return integer 
     */
    public function getCodigoMovimientoDetallePk()
    {
        return $this->codigoMovimientoDetallePk;
    }

    /**
     * Set codigoMovimientoFk
     *
     * @param integer $codigoMovimientoFk
     */
    public function setCodigoMovimientoFk($codigoMovimientoFk)
    {
        $this->codigoMovimientoFk = $codigoMovimientoFk;
    }

    /**
     * Get codigoMovimientoFk
     *
     * @return integer 
     */
    public function getCodigoMovimientoFk()
    {
        return $this->codigoMovimientoFk;
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
     * Set codigoCuentaPagarFk
     *
     * @param integer $codigoCuentaPagarFk
     */
    public function setCodigoCuentaPagarFk($codigoCuentaPagarFk)
    {
        $this->codigoCuentaPagarFk = $codigoCuentaPagarFk;
    }

    /**
     * Get codigoCuentaPagarFk
     *
     * @return integer 
     */
    public function getCodigoCuentaPagarFk()
    {
        return $this->codigoCuentaPagarFk;
    }

    /**
     * Set codigoCuentaCobrarFk
     *
     * @param integer $codigoCuentaCobrarFk
     */
    public function setCodigoCuentaCobrarFk($codigoCuentaCobrarFk)
    {
        $this->codigoCuentaCobrarFk = $codigoCuentaCobrarFk;
    }

    /**
     * Get codigoCuentaCobrarFk
     *
     * @return integer 
     */
    public function getCodigoCuentaCobrarFk()
    {
        return $this->codigoCuentaCobrarFk;
    }

    /**
     * Set valor
     *
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Get valor
     *
     * @return float 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set codigoRegistroTipoFk
     *
     * @param smallint $codigoRegistroTipoFk
     */
    public function setCodigoRegistroTipoFk($codigoRegistroTipoFk)
    {
        $this->codigoRegistroTipoFk = $codigoRegistroTipoFk;
    }

    /**
     * Get codigoRegistroTipoFk
     *
     * @return smallint 
     */
    public function getCodigoRegistroTipoFk()
    {
        return $this->codigoRegistroTipoFk;
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
     * Set descripcionContable
     *
     * @param string $descripcionContable
     */
    public function setDescripcionContable($descripcionContable)
    {
        $this->descripcionContable = $descripcionContable;
    }

    /**
     * Get descripcionContable
     *
     * @return string 
     */
    public function getDescripcionContable()
    {
        return $this->descripcionContable;
    }

    /**
     * Set codigoImpuestoIvaFk
     *
     * @param integer $codigoImpuestoIvaFk
     */
    public function setCodigoImpuestoIvaFk($codigoImpuestoIvaFk)
    {
        $this->codigoImpuestoIvaFk = $codigoImpuestoIvaFk;
    }

    /**
     * Get codigoImpuestoIvaFk
     *
     * @return integer 
     */
    public function getCodigoImpuestoIvaFk()
    {
        return $this->codigoImpuestoIvaFk;
    }

    /**
     * Set codigoImpuestoRetencionFk
     *
     * @param integer $codigoImpuestoRetencionFk
     */
    public function setCodigoImpuestoRetencionFk($codigoImpuestoRetencionFk)
    {
        $this->codigoImpuestoRetencionFk = $codigoImpuestoRetencionFk;
    }

    /**
     * Get codigoImpuestoRetencionFk
     *
     * @return integer 
     */
    public function getCodigoImpuestoRetencionFk()
    {
        return $this->codigoImpuestoRetencionFk;
    }

    /**
     * Set codigoImpuestoCreeFk
     *
     * @param integer $codigoImpuestoCreeFk
     */
    public function setCodigoImpuestoCreeFk($codigoImpuestoCreeFk)
    {
        $this->codigoImpuestoCreeFk = $codigoImpuestoCreeFk;
    }

    /**
     * Get codigoImpuestoCreeFk
     *
     * @return integer 
     */
    public function getCodigoImpuestoCreeFk()
    {
        return $this->codigoImpuestoCreeFk;
    }

    /**
     * Set movimientoRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbMovimientos $movimientoRel
     */
    public function setMovimientoRel(\Brasa\ContabilidadBundle\Entity\CtbMovimientos $movimientoRel)
    {
        $this->movimientoRel = $movimientoRel;
    }

    /**
     * Get movimientoRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbMovimientos 
     */
    public function getMovimientoRel()
    {
        return $this->movimientoRel;
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
     * Set impuestoIvaRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbImpuestos $impuestoIvaRel
     */
    public function setImpuestoIvaRel(\Brasa\ContabilidadBundle\Entity\CtbImpuestos $impuestoIvaRel)
    {
        $this->impuestoIvaRel = $impuestoIvaRel;
    }

    /**
     * Get impuestoIvaRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbImpuestos 
     */
    public function getImpuestoIvaRel()
    {
        return $this->impuestoIvaRel;
    }

    /**
     * Set impuestoRetencionRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbImpuestos $impuestoRetencionRel
     */
    public function setImpuestoRetencionRel(\Brasa\ContabilidadBundle\Entity\CtbImpuestos $impuestoRetencionRel)
    {
        $this->impuestoRetencionRel = $impuestoRetencionRel;
    }

    /**
     * Get impuestoRetencionRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbImpuestos 
     */
    public function getImpuestoRetencionRel()
    {
        return $this->impuestoRetencionRel;
    }

    /**
     * Set impuestoCreeRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbImpuestos $impuestoCreeRel
     */
    public function setImpuestoCreeRel(\Brasa\ContabilidadBundle\Entity\CtbImpuestos $impuestoCreeRel)
    {
        $this->impuestoCreeRel = $impuestoCreeRel;
    }

    /**
     * Get impuestoCreeRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbImpuestos 
     */
    public function getImpuestoCreeRel()
    {
        return $this->impuestoCreeRel;
    }

    /**
     * Set cuentaPagarRel
     *
     * @param Brasa\TesoreriaBundle\Entity\TesCuentasPagar $cuentaPagarRel
     */
    public function setCuentaPagarRel(\Brasa\TesoreriaBundle\Entity\TesCuentasPagar $cuentaPagarRel)
    {
        $this->cuentaPagarRel = $cuentaPagarRel;
    }

    /**
     * Get cuentaPagarRel
     *
     * @return Brasa\TesoreriaBundle\Entity\TesCuentasPagar 
     */
    public function getCuentaPagarRel()
    {
        return $this->cuentaPagarRel;
    }

    /**
     * Set cuentaCobrarRel
     *
     * @param Brasa\CarteraBundle\Entity\CarCuentasCobrar $cuentaCobrarRel
     */
    public function setCuentaCobrarRel(\Brasa\CarteraBundle\Entity\CarCuentasCobrar $cuentaCobrarRel)
    {
        $this->cuentaCobrarRel = $cuentaCobrarRel;
    }

    /**
     * Get cuentaCobrarRel
     *
     * @return Brasa\CarteraBundle\Entity\CarCuentasCobrar 
     */
    public function getCuentaCobrarRel()
    {
        return $this->cuentaCobrarRel;
    }

    /**
     * Set registroTipoRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbRegistrosTipos $registroTipoRel
     */
    public function setRegistroTipoRel(\Brasa\ContabilidadBundle\Entity\CtbRegistrosTipos $registroTipoRel)
    {
        $this->registroTipoRel = $registroTipoRel;
    }

    /**
     * Get registroTipoRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbRegistrosTipos 
     */
    public function getRegistroTipoRel()
    {
        return $this->registroTipoRel;
    }
}
