<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_asiento_detalle")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbAsientoDetalleRepository")
 */
class CtbAsientoDetalle
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_asiento_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoAsientoDetallePk;
    
    /**
     * @ORM\Column(name="codigo_asiento_fk", type="integer", nullable=false)
     */     
    private $codigoAsientoFk; 

    /**
     * @ORM\Column(name="debe", type="float")
     */
    private $debito = 0;    

    /**
     * @ORM\Column(name="haber", type="float")
     */
    private $credito = 0;    

    /**
     * @ORM\Column(name="base", type="float")
     */
    private $base = 0;    
    
    /**    
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=40)
     */ 
    private $codigo_cuenta_fk;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     * @Assert\NotBlank
     */    
    private $codigoTerceroFk;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */     
    private $codigoCentroCostoFk;     
    
    /**
     * @ORM\Column(name="descripcion", type="string", length=150, nullable=true)
     */    
    private $descripcion;     
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;           

        /**
     * @ORM\ManyToOne(targetEntity="CtbAsiento", inversedBy="asientosDetallesAsientoRel")
     * @ORM\JoinColumn(name="codigo_asiento_fk", referencedColumnName="codigo_asiento_pk")
     */
    protected $asientoRel; 

    /**
     * @ORM\ManyToOne(targetEntity="CtbCuenta", inversedBy="asientosDetallesCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    private $cuentaRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbTercero", inversedBy="asientosDetallesTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCentroCosto", inversedBy="asientosDetallesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    private $centroCostoRel; 
    
    


    /**
     * Get codigoAsientoDetallePk
     *
     * @return integer
     */
    public function getCodigoAsientoDetallePk()
    {
        return $this->codigoAsientoDetallePk;
    }

    /**
     * Set codigoAsientoFk
     *
     * @param integer $codigoAsientoFk
     *
     * @return CtbAsientoDetalle
     */
    public function setCodigoAsientoFk($codigoAsientoFk)
    {
        $this->codigoAsientoFk = $codigoAsientoFk;

        return $this;
    }

    /**
     * Get codigoAsientoFk
     *
     * @return integer
     */
    public function getCodigoAsientoFk()
    {
        return $this->codigoAsientoFk;
    }

    /**
     * Set debito
     *
     * @param float $debito
     *
     * @return CtbAsientoDetalle
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
     * @return CtbAsientoDetalle
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
     * @return CtbAsientoDetalle
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
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return CtbAsientoDetalle
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigo_cuenta_fk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return string
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigo_cuenta_fk;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return CtbAsientoDetalle
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return CtbAsientoDetalle
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return CtbAsientoDetalle
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return CtbAsientoDetalle
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set asientoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsiento $asientoRel
     *
     * @return CtbAsientoDetalle
     */
    public function setAsientoRel(\Brasa\ContabilidadBundle\Entity\CtbAsiento $asientoRel = null)
    {
        $this->asientoRel = $asientoRel;

        return $this;
    }

    /**
     * Get asientoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbAsiento
     */
    public function getAsientoRel()
    {
        return $this->asientoRel;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCuenta $cuentaRel
     *
     * @return CtbAsientoDetalle
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
     * Set terceroRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $terceroRel
     *
     * @return CtbAsientoDetalle
     */
    public function setTerceroRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $terceroRel = null)
    {
        $this->terceroRel = $terceroRel;

        return $this;
    }

    /**
     * Get terceroRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbTercero
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoRel
     *
     * @return CtbAsientoDetalle
     */
    public function setCentroCostoRel(\Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }
}
