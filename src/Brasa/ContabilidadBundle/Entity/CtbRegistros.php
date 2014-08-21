<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_registros")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbRegistrosRepository")
 */
class CtbRegistros
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_registro_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoRegistroPk;                      
    
    /**
     * @ORM\Column(name="codigo_comprobante_contable_fk", type="integer", nullable=true)
     */     
    private $codigoComprobanteContableFk;     
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero = 0; 
    
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
     * @ORM\Column(name="descripcion_contable", type="string", length=80, nullable=true)
     */    
    private $descripcionContable;     
    
              
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuentasContables", inversedBy="CtbRegistros")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;       
  
    /**
     * @ORM\ManyToOne(targetEntity="CtbCentrosCostos", inversedBy="CtbRegistros")
     * @ORM\JoinColumn(name="codigo_centro_costos_fk", referencedColumnName="codigo_centro_costos_pk")
     */
    private $centroCostosRel;      

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTerceros", inversedBy="CtbRegistros")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobantesContables", inversedBy="CtbRegistros")
     * @ORM\JoinColumn(name="codigo_comprobante_contable_fk", referencedColumnName="codigo_comprobante_contable_pk")
     */
    protected $comprobanteContableRel;     


    /**
     * Get codigoRegistroPk
     *
     * @return integer 
     */
    public function getCodigoRegistroPk()
    {
        return $this->codigoRegistroPk;
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
     * Set numero
     *
     * @param integer $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
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
