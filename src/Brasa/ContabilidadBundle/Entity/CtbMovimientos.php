<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_movimientos")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbMovimientosRepository")
 */
class CtbMovimientos
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoMovimientoPk;        
    
    /**
     * @ORM\Column(name="codigo_movimiento_tipo_fk", type="integer")
     */     
    private $codigoMovimientoTipoFk;    
    
    /**
     * @ORM\Column(name="codigo_movimiento_concepto_fk", type="integer")
     */     
    private $codigoMovimientoConceptoFk;      
    
    /**
     * @ORM\Column(name="numero_movimiento", type="integer", nullable=true)
     */    
    private $numeroMovimiento = 0;            
    
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
     * @ORM\Column(name="codigo_banco_fk", type="integer", nullable=true)
     */     
    private $codigoBancoFk;     
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    
    
    /**
     * @ORM\Column(name="total_debito", type="float")
     */    
    private $totalDebito = 0;
    
    /**
     * @ORM\Column(name="total_credito", type="float")
     */    
    private $totalCredito = 0;      
    
    /**
     * @ORM\Column(name="total_base", type="float")
     */    
    private $totalBase = 0;                

    /**
     * @ORM\Column(name="total_iva", type="float")
     */    
    private $totalIva = 0;    

    /**
     * @ORM\Column(name="total_retencion", type="float")
     */    
    private $totalRetencion = 0;    

    /**
     * @ORM\Column(name="total_retencion_cree", type="float")
     */    
    private $totalRetencionCree = 0;        

    /**
     * @ORM\Column(name="total_bruto", type="float")
     */    
    private $totalBruto = 0;            

    /**
     * @ORM\Column(name="total_neto", type="float")
     */    
    private $totalNeto = 0;    
    
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
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbMovimientosTipos", inversedBy="CtbMovimientos")
     * @ORM\JoinColumn(name="codigo_movimiento_tipo_fk", referencedColumnName="codigo_movimiento_tipo_pk")
     */
    protected $movimientoTipoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="CtbMovimientosConceptos", inversedBy="CtbMovimientos")
     * @ORM\JoinColumn(name="codigo_movimiento_concepto_fk", referencedColumnName="codigo_movimiento_concepto_pk")
     */
    protected $movimientoConceptoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobantesContables", inversedBy="CtbMovimientos")
     * @ORM\JoinColumn(name="codigo_comprobante_contable_fk", referencedColumnName="codigo_comprobante_contable_pk")
     */
    protected $comprobanteContableRel; 

    /**
     * @ORM\ManyToOne(targetEntity="CtbBancos", inversedBy="CtbMovimientos")
     * @ORM\JoinColumn(name="codigo_banco_fk", referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTerceros", inversedBy="CtbMovimientosDetalles")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    


    /**
     * Get codigoMovimientoPk
     *
     * @return integer 
     */
    public function getCodigoMovimientoPk()
    {
        return $this->codigoMovimientoPk;
    }

    /**
     * Set codigoMovimientoTipoFk
     *
     * @param integer $codigoMovimientoTipoFk
     */
    public function setCodigoMovimientoTipoFk($codigoMovimientoTipoFk)
    {
        $this->codigoMovimientoTipoFk = $codigoMovimientoTipoFk;
    }

    /**
     * Get codigoMovimientoTipoFk
     *
     * @return integer 
     */
    public function getCodigoMovimientoTipoFk()
    {
        return $this->codigoMovimientoTipoFk;
    }

    /**
     * Set codigoMovimientoConceptoFk
     *
     * @param integer $codigoMovimientoConceptoFk
     */
    public function setCodigoMovimientoConceptoFk($codigoMovimientoConceptoFk)
    {
        $this->codigoMovimientoConceptoFk = $codigoMovimientoConceptoFk;
    }

    /**
     * Get codigoMovimientoConceptoFk
     *
     * @return integer 
     */
    public function getCodigoMovimientoConceptoFk()
    {
        return $this->codigoMovimientoConceptoFk;
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
     * Set codigoBancoFk
     *
     * @param integer $codigoBancoFk
     */
    public function setCodigoBancoFk($codigoBancoFk)
    {
        $this->codigoBancoFk = $codigoBancoFk;
    }

    /**
     * Get codigoBancoFk
     *
     * @return integer 
     */
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
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
     * Set totalBase
     *
     * @param float $totalBase
     */
    public function setTotalBase($totalBase)
    {
        $this->totalBase = $totalBase;
    }

    /**
     * Get totalBase
     *
     * @return float 
     */
    public function getTotalBase()
    {
        return $this->totalBase;
    }

    /**
     * Set totalIva
     *
     * @param float $totalIva
     */
    public function setTotalIva($totalIva)
    {
        $this->totalIva = $totalIva;
    }

    /**
     * Get totalIva
     *
     * @return float 
     */
    public function getTotalIva()
    {
        return $this->totalIva;
    }

    /**
     * Set totalRetencion
     *
     * @param float $totalRetencion
     */
    public function setTotalRetencion($totalRetencion)
    {
        $this->totalRetencion = $totalRetencion;
    }

    /**
     * Get totalRetencion
     *
     * @return float 
     */
    public function getTotalRetencion()
    {
        return $this->totalRetencion;
    }

    /**
     * Set totalRetencionCree
     *
     * @param float $totalRetencionCree
     */
    public function setTotalRetencionCree($totalRetencionCree)
    {
        $this->totalRetencionCree = $totalRetencionCree;
    }

    /**
     * Get totalRetencionCree
     *
     * @return float 
     */
    public function getTotalRetencionCree()
    {
        return $this->totalRetencionCree;
    }

    /**
     * Set totalBruto
     *
     * @param float $totalBruto
     */
    public function setTotalBruto($totalBruto)
    {
        $this->totalBruto = $totalBruto;
    }

    /**
     * Get totalBruto
     *
     * @return float 
     */
    public function getTotalBruto()
    {
        return $this->totalBruto;
    }

    /**
     * Set totalNeto
     *
     * @param float $totalNeto
     */
    public function setTotalNeto($totalNeto)
    {
        $this->totalNeto = $totalNeto;
    }

    /**
     * Get totalNeto
     *
     * @return float 
     */
    public function getTotalNeto()
    {
        return $this->totalNeto;
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
     * Set movimientoTipoRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbMovimientosTipos $movimientoTipoRel
     */
    public function setMovimientoTipoRel(\Brasa\ContabilidadBundle\Entity\CtbMovimientosTipos $movimientoTipoRel)
    {
        $this->movimientoTipoRel = $movimientoTipoRel;
    }

    /**
     * Get movimientoTipoRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbMovimientosTipos 
     */
    public function getMovimientoTipoRel()
    {
        return $this->movimientoTipoRel;
    }

    /**
     * Set movimientoConceptoRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbMovimientosConceptos $movimientoConceptoRel
     */
    public function setMovimientoConceptoRel(\Brasa\ContabilidadBundle\Entity\CtbMovimientosConceptos $movimientoConceptoRel)
    {
        $this->movimientoConceptoRel = $movimientoConceptoRel;
    }

    /**
     * Get movimientoConceptoRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbMovimientosConceptos 
     */
    public function getMovimientoConceptoRel()
    {
        return $this->movimientoConceptoRel;
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
     * Set bancoRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbBancos $bancoRel
     */
    public function setBancoRel(\Brasa\ContabilidadBundle\Entity\CtbBancos $bancoRel)
    {
        $this->bancoRel = $bancoRel;
    }

    /**
     * Get bancoRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbBancos 
     */
    public function getBancoRel()
    {
        return $this->bancoRel;
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
