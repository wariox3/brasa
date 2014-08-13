<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Table(name="inv_movimientos_detalles")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvMovimientosDetallesRepository")
 */
class InvMovimientosDetalles
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_detalle_movimiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoDetalleMovimientoPk;
    
    /**
     * @ORM\Column(name="codigo_movimiento_fk", type="integer", nullable=true)
     */     
    private $codigoMovimientoFk;    
    
    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */     
    private $codigoItemFk;    

    /**
     * @ORM\Column(name="codigo_bodega_fk", type="integer", nullable=true)
     */     
    private $codigoBodegaFk;

    /**
     * @ORM\Column(name="codigo_bodega_destino_fk", type="integer", nullable=true)
     */     
    private $codigoBodegaDestinoFk;    
    
    /**
     * @ORM\Column(name="lote_fk", type="string", length=40, nullable=true)
     */      
    private $loteFk;
    
    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */      
    private $fechaVencimiento;
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */        
    private $cantidad = 0;

    /**
     * @ORM\Column(name="cantidad_operada", type="integer")
     */        
    private $cantidadOperada = 0;    

    /**
     * @ORM\Column(name="cantidad_afectada", type="integer")
     */        
    private $cantidadAfectada = 0;      
    
    /**
     * @ORM\Column(name="costo", type="float")
     */    
    private $costo = 0;

    /**
     * @ORM\Column(name="total_costo", type="float")
     */    
    private $totalCosto = 0;    
    
    /**
     * @ORM\Column(name="costo_promedio", type="float")
     */    
    private $costoPromedio = 0;    
    
    /**
     * @ORM\Column(name="precio", type="float")
     */    
    private $precio = 0;

    /**
     * @ORM\Column(name="subtotal", type="float")
     */    
    private $subTotal = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="integer")
     */    
    private $porcentajeIva = 0;
    
    /**
     * @ORM\Column(name="valor_total_iva", type="float")
     */    
    private $valorTotalIva = 0;

    /**
     * @ORM\Column(name="porcentaje_descuento", type="float")
     */    
    private $porcentajeDescuento = 0;

    /**
     * @ORM\Column(name="valor_total_descuento", type="float")
     */    
    private $valorTotalDescuento = 0;

    /**
     * @ORM\Column(name="total_bruto", type="float")
     */    
    private $totalBruto = 0;    
    
    /**
     * @ORM\Column(name="total", type="float")
     */    
    private $total = 0;

    /**
     * @ORM\Column(name="operacion_inventario", type="bigint")
     */    
    private $operacionInventario = 0;

    /**
     * @ORM\Column(name="operacion_comercial", type="bigint")
     */    
    private $operacionComercial = 0;

    /**
     * @ORM\Column(name="afectar_remision", type="bigint")
     */    
    private $afectarRemision = 0;    
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;    

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;    
    
    /**
     * @ORM\Column(name="codigo_detalle_movimiento_enlace", type="integer", nullable=true)     
     */        
    private $codigoDetalleMovimientoEnlace;    
    
    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="movimientosDetallesRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="InvMovimientos", inversedBy="movimientosDetallesRel")
     * @ORM\JoinColumn(name="codigo_movimiento_fk", referencedColumnName="codigo_movimiento_pk")
     */
    protected $movimientoRel;  
    

    /**
     * Get codigoDetalleMovimientoPk
     *
     * @return integer 
     */
    public function getCodigoDetalleMovimientoPk()
    {
        return $this->codigoDetalleMovimientoPk;
    }

    /**
     * Set codigoMovimientoFk
     *
     * @param integer $codigoMovimientoFk
     * @return InvMovimientosDetalles
     */
    public function setCodigoMovimientoFk($codigoMovimientoFk)
    {
        $this->codigoMovimientoFk = $codigoMovimientoFk;

        return $this;
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
     * Set codigoItemFk
     *
     * @param integer $codigoItemFk
     * @return InvMovimientosDetalles
     */
    public function setCodigoItemFk($codigoItemFk)
    {
        $this->codigoItemFk = $codigoItemFk;

        return $this;
    }

    /**
     * Get codigoItemFk
     *
     * @return integer 
     */
    public function getCodigoItemFk()
    {
        return $this->codigoItemFk;
    }

    /**
     * Set codigoBodegaFk
     *
     * @param integer $codigoBodegaFk
     * @return InvMovimientosDetalles
     */
    public function setCodigoBodegaFk($codigoBodegaFk)
    {
        $this->codigoBodegaFk = $codigoBodegaFk;

        return $this;
    }

    /**
     * Get codigoBodegaFk
     *
     * @return integer 
     */
    public function getCodigoBodegaFk()
    {
        return $this->codigoBodegaFk;
    }

    /**
     * Set codigoBodegaDestinoFk
     *
     * @param integer $codigoBodegaDestinoFk
     * @return InvMovimientosDetalles
     */
    public function setCodigoBodegaDestinoFk($codigoBodegaDestinoFk)
    {
        $this->codigoBodegaDestinoFk = $codigoBodegaDestinoFk;

        return $this;
    }

    /**
     * Get codigoBodegaDestinoFk
     *
     * @return integer 
     */
    public function getCodigoBodegaDestinoFk()
    {
        return $this->codigoBodegaDestinoFk;
    }

    /**
     * Set loteFk
     *
     * @param string $loteFk
     * @return InvMovimientosDetalles
     */
    public function setLoteFk($loteFk)
    {
        $this->loteFk = $loteFk;

        return $this;
    }

    /**
     * Get loteFk
     *
     * @return string 
     */
    public function getLoteFk()
    {
        return $this->loteFk;
    }

    /**
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     * @return InvMovimientosDetalles
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime 
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return InvMovimientosDetalles
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set cantidadOperada
     *
     * @param integer $cantidadOperada
     * @return InvMovimientosDetalles
     */
    public function setCantidadOperada($cantidadOperada)
    {
        $this->cantidadOperada = $cantidadOperada;

        return $this;
    }

    /**
     * Get cantidadOperada
     *
     * @return integer 
     */
    public function getCantidadOperada()
    {
        return $this->cantidadOperada;
    }

    /**
     * Set cantidadAfectada
     *
     * @param integer $cantidadAfectada
     * @return InvMovimientosDetalles
     */
    public function setCantidadAfectada($cantidadAfectada)
    {
        $this->cantidadAfectada = $cantidadAfectada;

        return $this;
    }

    /**
     * Get cantidadAfectada
     *
     * @return integer 
     */
    public function getCantidadAfectada()
    {
        return $this->cantidadAfectada;
    }

    /**
     * Set costo
     *
     * @param float $costo
     * @return InvMovimientosDetalles
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float 
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set totalCosto
     *
     * @param float $totalCosto
     * @return InvMovimientosDetalles
     */
    public function setTotalCosto($totalCosto)
    {
        $this->totalCosto = $totalCosto;

        return $this;
    }

    /**
     * Get totalCosto
     *
     * @return float 
     */
    public function getTotalCosto()
    {
        return $this->totalCosto;
    }

    /**
     * Set costoPromedio
     *
     * @param float $costoPromedio
     * @return InvMovimientosDetalles
     */
    public function setCostoPromedio($costoPromedio)
    {
        $this->costoPromedio = $costoPromedio;

        return $this;
    }

    /**
     * Get costoPromedio
     *
     * @return float 
     */
    public function getCostoPromedio()
    {
        return $this->costoPromedio;
    }

    /**
     * Set precio
     *
     * @param float $precio
     * @return InvMovimientosDetalles
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set subTotal
     *
     * @param float $subTotal
     * @return InvMovimientosDetalles
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;

        return $this;
    }

    /**
     * Get subTotal
     *
     * @return float 
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * Set porcentajeIva
     *
     * @param integer $porcentajeIva
     * @return InvMovimientosDetalles
     */
    public function setPorcentajeIva($porcentajeIva)
    {
        $this->porcentajeIva = $porcentajeIva;

        return $this;
    }

    /**
     * Get porcentajeIva
     *
     * @return integer 
     */
    public function getPorcentajeIva()
    {
        return $this->porcentajeIva;
    }

    /**
     * Set valorTotalIva
     *
     * @param float $valorTotalIva
     * @return InvMovimientosDetalles
     */
    public function setValorTotalIva($valorTotalIva)
    {
        $this->valorTotalIva = $valorTotalIva;

        return $this;
    }

    /**
     * Get valorTotalIva
     *
     * @return float 
     */
    public function getValorTotalIva()
    {
        return $this->valorTotalIva;
    }

    /**
     * Set porcentajeDescuento
     *
     * @param float $porcentajeDescuento
     * @return InvMovimientosDetalles
     */
    public function setPorcentajeDescuento($porcentajeDescuento)
    {
        $this->porcentajeDescuento = $porcentajeDescuento;

        return $this;
    }

    /**
     * Get porcentajeDescuento
     *
     * @return float 
     */
    public function getPorcentajeDescuento()
    {
        return $this->porcentajeDescuento;
    }

    /**
     * Set valorTotalDescuento
     *
     * @param float $valorTotalDescuento
     * @return InvMovimientosDetalles
     */
    public function setValorTotalDescuento($valorTotalDescuento)
    {
        $this->valorTotalDescuento = $valorTotalDescuento;

        return $this;
    }

    /**
     * Get valorTotalDescuento
     *
     * @return float 
     */
    public function getValorTotalDescuento()
    {
        return $this->valorTotalDescuento;
    }

    /**
     * Set totalBruto
     *
     * @param float $totalBruto
     * @return InvMovimientosDetalles
     */
    public function setTotalBruto($totalBruto)
    {
        $this->totalBruto = $totalBruto;

        return $this;
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
     * Set total
     *
     * @param float $total
     * @return InvMovimientosDetalles
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set operacionInventario
     *
     * @param integer $operacionInventario
     * @return InvMovimientosDetalles
     */
    public function setOperacionInventario($operacionInventario)
    {
        $this->operacionInventario = $operacionInventario;

        return $this;
    }

    /**
     * Get operacionInventario
     *
     * @return integer 
     */
    public function getOperacionInventario()
    {
        return $this->operacionInventario;
    }

    /**
     * Set operacionComercial
     *
     * @param integer $operacionComercial
     * @return InvMovimientosDetalles
     */
    public function setOperacionComercial($operacionComercial)
    {
        $this->operacionComercial = $operacionComercial;

        return $this;
    }

    /**
     * Get operacionComercial
     *
     * @return integer 
     */
    public function getOperacionComercial()
    {
        return $this->operacionComercial;
    }

    /**
     * Set afectarRemision
     *
     * @param integer $afectarRemision
     * @return InvMovimientosDetalles
     */
    public function setAfectarRemision($afectarRemision)
    {
        $this->afectarRemision = $afectarRemision;

        return $this;
    }

    /**
     * Get afectarRemision
     *
     * @return integer 
     */
    public function getAfectarRemision()
    {
        return $this->afectarRemision;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     * @return InvMovimientosDetalles
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
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     * @return InvMovimientosDetalles
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     * @return InvMovimientosDetalles
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
     * Set codigoDetalleMovimientoEnlace
     *
     * @param integer $codigoDetalleMovimientoEnlace
     * @return InvMovimientosDetalles
     */
    public function setCodigoDetalleMovimientoEnlace($codigoDetalleMovimientoEnlace)
    {
        $this->codigoDetalleMovimientoEnlace = $codigoDetalleMovimientoEnlace;

        return $this;
    }

    /**
     * Get codigoDetalleMovimientoEnlace
     *
     * @return integer 
     */
    public function getCodigoDetalleMovimientoEnlace()
    {
        return $this->codigoDetalleMovimientoEnlace;
    }

    /**
     * Set itemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemRel
     * @return InvMovimientosDetalles
     */
    public function setItemRel(\Brasa\InventarioBundle\Entity\InvItem $itemRel = null)
    {
        $this->itemRel = $itemRel;

        return $this;
    }

    /**
     * Get itemRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvItem 
     */
    public function getItemRel()
    {
        return $this->itemRel;
    }

    /**
     * Set movimientoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientoRel
     * @return InvMovimientosDetalles
     */
    public function setMovimientoRel(\Brasa\InventarioBundle\Entity\InvMovimientos $movimientoRel = null)
    {
        $this->movimientoRel = $movimientoRel;

        return $this;
    }

    /**
     * Get movimientoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvMovimientos 
     */
    public function getMovimientoRel()
    {
        return $this->movimientoRel;
    }
}
