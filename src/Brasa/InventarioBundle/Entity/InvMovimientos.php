<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_movimientos")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvMovimientosRepository")
 */
class InvMovimientos
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMovimientoPk;

    /**
     * @ORM\Column(name="codigo_documento_fk", type="integer")
     */
    private $codigoDocumentoFk; 
    
    /**
     * @ORM\Column(name="codigo_documento_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoDocumentoTipoFk;               
    
    /**
     * @ORM\Column(name="numero_movimiento", type="integer", nullable=true)
     */    
    private $numeroMovimiento;
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="fecha1", type="datetime", nullable=true)
     */    
    private $fecha1;    

    /**
     * @ORM\Column(name="fecha2", type="datetime", nullable=true)
     */    
    private $fecha2;    
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_direccion_fk", type="integer", nullable=true)
     */    
    private $codigoDireccionFk;        

    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoFk;    
    
    /**
     * @ORM\Column(name="soporte", type="string", length=50, nullable=true)
     */    
    private $soporte;     
    
    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true)
     */    
    private $vrIva;
    
    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubTotal = 0;
    
    /**
     * @ORM\Column(name="vr_descuento", type="float")
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_bruto", type="float")
     */
    private $vrBruto = 0;        
    
    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;    
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vr_total = 0;

    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;    
    
    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float")
     */
    private $vrRetencionFuente = 0;    

    /**
     * @ORM\Column(name="vr_retencion_CREE", type="float")
     */
    private $vrRetencionCREE = 0;    
    
    /**
     * @ORM\Column(name="vr_retencion_iva_ventas", type="float")
     */
    private $vrRetencionIvaVentas = 0;     
    
    /**
     * @ORM\Column(name="vr_otras_retenciones", type="float")
     */
    private $vrOtrasRetenciones = 0;    

    /**
     * @ORM\Column(name="vr_fletes", type="float")
     */
    private $vrFletes = 0;    
    
    /**
     * @ORM\Column(name="vr_descuento_financiero", type="float")
     */
    private $vrDescuentoFinanciero = 0;       
    
    /**
     * @ORM\Column(name="codigo_usuario_fk", type="string", length=20, nullable=true)
     */    
    private $codigoUsuarioFk; 

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;   
    
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
     * @ORM\ManyToOne(targetEntity="InvDocumentos", inversedBy="movimientosRel")
     * @ORM\JoinColumn(name="codigo_documento_fk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoRel;    
    

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
     * Set codigoDocumentoFk
     *
     * @param integer $codigoDocumentoFk
     * @return InvMovimientos
     */
    public function setCodigoDocumentoFk($codigoDocumentoFk)
    {
        $this->codigoDocumentoFk = $codigoDocumentoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoFk
     *
     * @return integer 
     */
    public function getCodigoDocumentoFk()
    {
        return $this->codigoDocumentoFk;
    }

    /**
     * Set documentoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentoRel
     * @return InvMovimientos
     */
    public function setDocumentoRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentoRel = null)
    {
        $this->documentoRel = $documentoRel;

        return $this;
    }

    /**
     * Get documentoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvDocumentos 
     */
    public function getDocumentoRel()
    {
        return $this->documentoRel;
    }

    /**
     * Set codigoDocumentoTipoFk
     *
     * @param integer $codigoDocumentoTipoFk
     * @return InvMovimientos
     */
    public function setCodigoDocumentoTipoFk($codigoDocumentoTipoFk)
    {
        $this->codigoDocumentoTipoFk = $codigoDocumentoTipoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoTipoFk
     *
     * @return integer 
     */
    public function getCodigoDocumentoTipoFk()
    {
        return $this->codigoDocumentoTipoFk;
    }

    /**
     * Set numeroMovimiento
     *
     * @param integer $numeroMovimiento
     * @return InvMovimientos
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return InvMovimientos
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
     * Set fecha1
     *
     * @param \DateTime $fecha1
     * @return InvMovimientos
     */
    public function setFecha1($fecha1)
    {
        $this->fecha1 = $fecha1;

        return $this;
    }

    /**
     * Get fecha1
     *
     * @return \DateTime 
     */
    public function getFecha1()
    {
        return $this->fecha1;
    }

    /**
     * Set fecha2
     *
     * @param \DateTime $fecha2
     * @return InvMovimientos
     */
    public function setFecha2($fecha2)
    {
        $this->fecha2 = $fecha2;

        return $this;
    }

    /**
     * Get fecha2
     *
     * @return \DateTime 
     */
    public function getFecha2()
    {
        return $this->fecha2;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     * @return InvMovimientos
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
     * Set codigoDireccionFk
     *
     * @param integer $codigoDireccionFk
     * @return InvMovimientos
     */
    public function setCodigoDireccionFk($codigoDireccionFk)
    {
        $this->codigoDireccionFk = $codigoDireccionFk;

        return $this;
    }

    /**
     * Get codigoDireccionFk
     *
     * @return integer 
     */
    public function getCodigoDireccionFk()
    {
        return $this->codigoDireccionFk;
    }

    /**
     * Set codigoFormaPagoFk
     *
     * @param integer $codigoFormaPagoFk
     * @return InvMovimientos
     */
    public function setCodigoFormaPagoFk($codigoFormaPagoFk)
    {
        $this->codigoFormaPagoFk = $codigoFormaPagoFk;

        return $this;
    }

    /**
     * Get codigoFormaPagoFk
     *
     * @return integer 
     */
    public function getCodigoFormaPagoFk()
    {
        return $this->codigoFormaPagoFk;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     * @return InvMovimientos
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
     * Set vrIva
     *
     * @param float $vrIva
     * @return InvMovimientos
     */
    public function setVrIva($vrIva)
    {
        $this->vrIva = $vrIva;

        return $this;
    }

    /**
     * Get vrIva
     *
     * @return float 
     */
    public function getVrIva()
    {
        return $this->vrIva;
    }

    /**
     * Set vrSubTotal
     *
     * @param float $vrSubTotal
     * @return InvMovimientos
     */
    public function setVrSubTotal($vrSubTotal)
    {
        $this->vrSubTotal = $vrSubTotal;

        return $this;
    }

    /**
     * Get vrSubTotal
     *
     * @return float 
     */
    public function getVrSubTotal()
    {
        return $this->vrSubTotal;
    }

    /**
     * Set vrDescuento
     *
     * @param float $vrDescuento
     * @return InvMovimientos
     */
    public function setVrDescuento($vrDescuento)
    {
        $this->vrDescuento = $vrDescuento;

        return $this;
    }

    /**
     * Get vrDescuento
     *
     * @return float 
     */
    public function getVrDescuento()
    {
        return $this->vrDescuento;
    }

    /**
     * Set vrBruto
     *
     * @param float $vrBruto
     * @return InvMovimientos
     */
    public function setVrBruto($vrBruto)
    {
        $this->vrBruto = $vrBruto;

        return $this;
    }

    /**
     * Get vrBruto
     *
     * @return float 
     */
    public function getVrBruto()
    {
        return $this->vrBruto;
    }

    /**
     * Set vrNeto
     *
     * @param float $vrNeto
     * @return InvMovimientos
     */
    public function setVrNeto($vrNeto)
    {
        $this->vrNeto = $vrNeto;

        return $this;
    }

    /**
     * Get vrNeto
     *
     * @return float 
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * Set vr_total
     *
     * @param float $vrTotal
     * @return InvMovimientos
     */
    public function setVrTotal($vrTotal)
    {
        $this->vr_total = $vrTotal;

        return $this;
    }

    /**
     * Get vr_total
     *
     * @return float 
     */
    public function getVrTotal()
    {
        return $this->vr_total;
    }

    /**
     * Set vrCosto
     *
     * @param float $vrCosto
     * @return InvMovimientos
     */
    public function setVrCosto($vrCosto)
    {
        $this->vrCosto = $vrCosto;

        return $this;
    }

    /**
     * Get vrCosto
     *
     * @return float 
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * Set vrRetencionFuente
     *
     * @param float $vrRetencionFuente
     * @return InvMovimientos
     */
    public function setVrRetencionFuente($vrRetencionFuente)
    {
        $this->vrRetencionFuente = $vrRetencionFuente;

        return $this;
    }

    /**
     * Get vrRetencionFuente
     *
     * @return float 
     */
    public function getVrRetencionFuente()
    {
        return $this->vrRetencionFuente;
    }

    /**
     * Set vrRetencionCREE
     *
     * @param float $vrRetencionCREE
     * @return InvMovimientos
     */
    public function setVrRetencionCREE($vrRetencionCREE)
    {
        $this->vrRetencionCREE = $vrRetencionCREE;

        return $this;
    }

    /**
     * Get vrRetencionCREE
     *
     * @return float 
     */
    public function getVrRetencionCREE()
    {
        return $this->vrRetencionCREE;
    }

    /**
     * Set vrRetencionIvaVentas
     *
     * @param float $vrRetencionIvaVentas
     * @return InvMovimientos
     */
    public function setVrRetencionIvaVentas($vrRetencionIvaVentas)
    {
        $this->vrRetencionIvaVentas = $vrRetencionIvaVentas;

        return $this;
    }

    /**
     * Get vrRetencionIvaVentas
     *
     * @return float 
     */
    public function getVrRetencionIvaVentas()
    {
        return $this->vrRetencionIvaVentas;
    }

    /**
     * Set vrOtrasRetenciones
     *
     * @param float $vrOtrasRetenciones
     * @return InvMovimientos
     */
    public function setVrOtrasRetenciones($vrOtrasRetenciones)
    {
        $this->vrOtrasRetenciones = $vrOtrasRetenciones;

        return $this;
    }

    /**
     * Get vrOtrasRetenciones
     *
     * @return float 
     */
    public function getVrOtrasRetenciones()
    {
        return $this->vrOtrasRetenciones;
    }

    /**
     * Set vrFletes
     *
     * @param float $vrFletes
     * @return InvMovimientos
     */
    public function setVrFletes($vrFletes)
    {
        $this->vrFletes = $vrFletes;

        return $this;
    }

    /**
     * Get vrFletes
     *
     * @return float 
     */
    public function getVrFletes()
    {
        return $this->vrFletes;
    }

    /**
     * Set vrDescuentoFinanciero
     *
     * @param float $vrDescuentoFinanciero
     * @return InvMovimientos
     */
    public function setVrDescuentoFinanciero($vrDescuentoFinanciero)
    {
        $this->vrDescuentoFinanciero = $vrDescuentoFinanciero;

        return $this;
    }

    /**
     * Get vrDescuentoFinanciero
     *
     * @return float 
     */
    public function getVrDescuentoFinanciero()
    {
        return $this->vrDescuentoFinanciero;
    }

    /**
     * Set codigoUsuarioFk
     *
     * @param string $codigoUsuarioFk
     * @return InvMovimientos
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;

        return $this;
    }

    /**
     * Get codigoUsuarioFk
     *
     * @return string 
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return InvMovimientos
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     * @return InvMovimientos
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
     * @return InvMovimientos
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
     * @return InvMovimientos
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
     * @return InvMovimientos
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
     * @return InvMovimientos
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
}
