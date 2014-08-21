<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_asientos")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbAsientosRepository")
 */
class CtbAsientos
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
     * @ORM\ManyToOne(targetEntity="CtbAsientosTipos", inversedBy="CtbAsientos")
     * @ORM\JoinColumn(name="codigo_asiento_tipo_fk", referencedColumnName="codigo_asiento_tipo_pk")
     */
    protected $asientoTipoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobantesContables", inversedBy="CtbAsientos")
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
     */
    public function setCodigoAsientoTipoFk($codigoAsientoTipoFk)
    {
        $this->codigoAsientoTipoFk = $codigoAsientoTipoFk;
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
     */
    public function setNumeroAsiento($numeroAsiento)
    {
        $this->numeroAsiento = $numeroAsiento;
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
     * Set fecha_creacion
     *
     * @param date $fechaCreacion
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fecha_creacion = $fechaCreacion;
    }

    /**
     * Get fecha_creacion
     *
     * @return date 
     */
    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
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
     * Set estadoImpreso
     *
     * @param boolean $estadoImpreso
     */
    public function setEstadoImpreso($estadoImpreso)
    {
        $this->estadoImpreso = $estadoImpreso;
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
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;
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
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;
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
     */
    public function setEstadoContabilizado($estadoContabilizado)
    {
        $this->estadoContabilizado = $estadoContabilizado;
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
     */
    public function setTotalDebito($totalDebito)
    {
        $this->totalDebito = $totalDebito;
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
     */
    public function setTotalCredito($totalCredito)
    {
        $this->totalCredito = $totalCredito;
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
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;
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
     * @param Brasa\ContabilidadBundle\Entity\CtbAsientosTipos $asientoTipoRel
     */
    public function setAsientoTipoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientosTipos $asientoTipoRel)
    {
        $this->asientoTipoRel = $asientoTipoRel;
    }

    /**
     * Get asientoTipoRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbAsientosTipos 
     */
    public function getAsientoTipoRel()
    {
        return $this->asientoTipoRel;
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

    /**
     * Set soporte
     *
     * @param string $soporte
     */
    public function setSoporte($soporte)
    {
        $this->soporte = $soporte;
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
}
