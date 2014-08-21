<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_asientos_detalles")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbAsientosDetallesRepository")
 */
class CtbAsientosDetalles
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
     * @ORM\Column(name="codigo_centro_costos_fk", type="integer", nullable=true)
     */     
    private $codigoCentroCostosFk;     
    
    /**
     * @ORM\Column(name="descripcion_contable", type="string", length=80, nullable=true)
     */    
    private $descripcionContable;     
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;           

        /**
     * @ORM\ManyToOne(targetEntity="CtbAsientos", inversedBy="CtbAsientosDetalles")
     * @ORM\JoinColumn(name="codigo_asiento_fk", referencedColumnName="codigo_asiento_pk")
     */
    protected $asientoRel; 

    /**
     * @ORM\ManyToOne(targetEntity="CtbCuentasContables", inversedBy="CtbAsientosDetalles")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    private $cuentaRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCentrosCostos", inversedBy="CtbAsientosDetalles")
     * @ORM\JoinColumn(name="codigo_centro_costos_fk", referencedColumnName="codigo_centro_costos_pk")
     */
    private $centroCostosRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTerceros", inversedBy="CtbAsientosDetalles")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    



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
     */
    public function setCodigoAsientoFk($codigoAsientoFk)
    {
        $this->codigoAsientoFk = $codigoAsientoFk;
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
     * Set codigo_cuenta_fk
     *
     * @param string $codigoCuentaFk
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigo_cuenta_fk = $codigoCuentaFk;
    }

    /**
     * Get codigo_cuenta_fk
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;
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
     * @param Brasa\ContabilidadBundle\Entity\CtbAsientos $asientoRel
     */
    public function setAsientoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientos $asientoRel)
    {
        $this->asientoRel = $asientoRel;
    }

    /**
     * Get asientoRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbAsientos 
     */
    public function getAsientoRel()
    {
        return $this->asientoRel;
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
}
