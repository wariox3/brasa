<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_registro")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbRegistroRepository")
 */
class CtbRegistro
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
     * @ORM\ManyToOne(targetEntity="CtbCuenta", inversedBy="CtbRegistro")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;       
  
    /**
     * @ORM\ManyToOne(targetEntity="CtbCentroCosto", inversedBy="CtbRegistro")
     * @ORM\JoinColumn(name="codigo_centro_costos_fk", referencedColumnName="codigo_centro_costos_pk")
     */
    private $centroCostosRel;      

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTercero", inversedBy="CtbRegistro")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobanteContable", inversedBy="CtbRegistro")
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
     *
     * @return CtbRegistro
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CtbRegistro
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
     * Set numero
     *
     * @param integer $numero
     *
     * @return CtbRegistro
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
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
     *
     * @return CtbRegistro
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
     * @return CtbRegistro
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
     * @return CtbRegistro
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
     * Set debito
     *
     * @param float $debito
     *
     * @return CtbRegistro
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
     * @return CtbRegistro
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
     * @return CtbRegistro
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
     * Set descripcionContable
     *
     * @param string $descripcionContable
     *
     * @return CtbRegistro
     */
    public function setDescripcionContable($descripcionContable)
    {
        $this->descripcionContable = $descripcionContable;

        return $this;
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
     * @param \Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel
     *
     * @return CtbRegistro
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
     * @return CtbRegistro
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
     * Set terceroRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $terceroRel
     *
     * @return CtbRegistro
     */
    public function setTerceroRel(\Brasa\GeneralBundle\Entity\GenTercero $terceroRel = null)
    {
        $this->terceroRel = $terceroRel;

        return $this;
    }

    /**
     * Get terceroRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenTercero
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * Set comprobanteContableRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbComprobanteContable $comprobanteContableRel
     *
     * @return CtbRegistro
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
