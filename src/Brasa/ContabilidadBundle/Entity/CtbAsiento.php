<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_asiento")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbAsientoRepository")
 */
class CtbAsiento
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_asiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoAsientoPk;

    /**
     * @ORM\Column(name="codigo_asiento_tipo_fk", type="integer", nullable=true)
     */     
    private $codigoAsientoTipoFk;    
    
    /**
     * @ORM\Column(name="numero_asiento", type="integer", nullable=true)
     */     
    private $numeroAsiento;    

    /**
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */    
    private $soporte;      
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     

    /**
     * @ORM\Column(name="fecha_creacion", type="date", nullable=true)
     */    
    private $fecha_creacion; 
    
    /**
     * @ORM\Column(name="codigo_comprobante_contable_fk", type="integer", nullable=true)
     */     
    private $codigoComprobanteContableFk;          
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;    

    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = 0;    
    
    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;    
    
    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;    
    
    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */    
    private $estadoContabilizado = 0;     
    
    /**
     * @ORM\Column(name="total_debe", type="float")
     */
    private $totalDebito = 0;    
    
    /**
     * @ORM\Column(name="total_haber", type="float")
     */
    private $totalCredito = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;             
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbAsientoTipo", inversedBy="CtbAsiento")
     * @ORM\JoinColumn(name="codigo_asiento_tipo_fk", referencedColumnName="codigo_asiento_tipo_pk")
     */
    protected $asientoTipoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobanteContable", inversedBy="CtbAsiento")
     * @ORM\JoinColumn(name="codigo_comprobante_contable_fk", referencedColumnName="codigo_comprobante_contable_pk")
     */
    protected $comprobanteContableRel;    
   


    /**
     * Get codigoAsientoPk
     *
     * @return integer
     */
    public function getCodigoAsientoPk()
    {
        return $this->codigoAsientoPk;
    }

    /**
     * Set codigoAsientoTipoFk
     *
     * @param integer $codigoAsientoTipoFk
     *
     * @return CtbAsiento
     */
    public function setCodigoAsientoTipoFk($codigoAsientoTipoFk)
    {
        $this->codigoAsientoTipoFk = $codigoAsientoTipoFk;

        return $this;
    }

    /**
     * Get codigoAsientoTipoFk
     *
     * @return integer
     */
    public function getCodigoAsientoTipoFk()
    {
        return $this->codigoAsientoTipoFk;
    }

    /**
     * Set numeroAsiento
     *
     * @param integer $numeroAsiento
     *
     * @return CtbAsiento
     */
    public function setNumeroAsiento($numeroAsiento)
    {
        $this->numeroAsiento = $numeroAsiento;

        return $this;
    }

    /**
     * Get numeroAsiento
     *
     * @return integer
     */
    public function getNumeroAsiento()
    {
        return $this->numeroAsiento;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return CtbAsiento
     */
    public function setSoporte($soporte)
    {
        $this->soporte = $soporte;

        return $this;
    }

    /**
     * Get soporte
     *
     * @return string
     */
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CtbAsiento
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return CtbAsiento
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fecha_creacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    /**
     * Set codigoComprobanteContableFk
     *
     * @param integer $codigoComprobanteContableFk
     *
     * @return CtbAsiento
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return CtbAsiento
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
     * Set estadoImpreso
     *
     * @param boolean $estadoImpreso
     *
     * @return CtbAsiento
     */
    public function setEstadoImpreso($estadoImpreso)
    {
        $this->estadoImpreso = $estadoImpreso;

        return $this;
    }

    /**
     * Get estadoImpreso
     *
     * @return boolean
     */
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return CtbAsiento
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return CtbAsiento
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Set estadoContabilizado
     *
     * @param boolean $estadoContabilizado
     *
     * @return CtbAsiento
     */
    public function setEstadoContabilizado($estadoContabilizado)
    {
        $this->estadoContabilizado = $estadoContabilizado;

        return $this;
    }

    /**
     * Get estadoContabilizado
     *
     * @return boolean
     */
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * Set totalDebito
     *
     * @param float $totalDebito
     *
     * @return CtbAsiento
     */
    public function setTotalDebito($totalDebito)
    {
        $this->totalDebito = $totalDebito;

        return $this;
    }

    /**
     * Get totalDebito
     *
     * @return float
     */
    public function getTotalDebito()
    {
        return $this->totalDebito;
    }

    /**
     * Set totalCredito
     *
     * @param float $totalCredito
     *
     * @return CtbAsiento
     */
    public function setTotalCredito($totalCredito)
    {
        $this->totalCredito = $totalCredito;

        return $this;
    }

    /**
     * Get totalCredito
     *
     * @return float
     */
    public function getTotalCredito()
    {
        return $this->totalCredito;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return CtbAsiento
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set asientoTipoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoTipo $asientoTipoRel
     *
     * @return CtbAsiento
     */
    public function setAsientoTipoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoTipo $asientoTipoRel = null)
    {
        $this->asientoTipoRel = $asientoTipoRel;

        return $this;
    }

    /**
     * Get asientoTipoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbAsientoTipo
     */
    public function getAsientoTipoRel()
    {
        return $this->asientoTipoRel;
    }

    /**
     * Set comprobanteContableRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbComprobanteContable $comprobanteContableRel
     *
     * @return CtbAsiento
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
