<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_item")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvItemRepository")
 */
class InvItem
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_item_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoItemPk;    
    
    /**
     * @ORM\Column(name="codigo_marca_fk", type="integer", nullable=true)
     */    
    private $codigoMarcaFk;
    
    /**
     * @ORM\Column(name="descripcion", type="string", length=150, nullable=true)
     */    
    private $descripcion;
    
    /**
     * @ORM\Column(name="vr_costo_predeterminado", type="float", nullable=true)
     */
    private $vrCostoPredeterminado = 0;

    /**
     * @ORM\Column(name="vr_costo_promedio", type="float")
     */
    private $vrCostoPromedio = 0;    
    
    /**
     * @ORM\Column(name="vr_precio_predeterminado", type="float", nullable=true)
     */
    private $vrPrecioPredeterminado = 0;

    /**
     * @ORM\Column(name="codigo_ean", type="string", length=80, nullable=true)
     */    
    private $codigoEAN;    
    
    /**
     * @ORM\Column(name="codigo_barras", type="string", length=80, nullable=true)
     */    
    private $codigoBarras;     
    
    /**
     * @ORM\Column(name="cuenta_ventas", type="string", length=15, nullable=true)
     */    
    private $cuentaVentas; 
    
    /**
     * @ORM\Column(name="cuenta_dovolucion_ventas", type="string", length=15, nullable=true)
     */    
    private $cuentaDevolucionVentas;     
    
    /**
     * @ORM\Column(name="cuenta_compras", type="string", length=15, nullable=true)
     */    
    private $cuentaCompras;        
    
    /**
     * @ORM\Column(name="cuenta_devolucion_compras", type="string", length=15, nullable=true)
     */    
    private $cuentaDevolucionCompras;     
    
    /**
     * @ORM\Column(name="cuenta_costo", type="string", length=15, nullable=true)
     */    
    private $cuentaCosto;      
        
    /**
     * @ORM\Column(name="cuenta_inventario", type="string", length=15, nullable=true)
     */    
    private $cuentaInventario;  

    /**
     * @ORM\Column(name="porcentaje_iva", type="integer")
     */    
    private $porcentajeIva = 0;    

    /**
     * @ORM\Column(name="cantidad_existencia", type="integer")
     */    
    private $cantidadExistencia = 0;        
    
    /**
     * @ORM\Column(name="cantidad_remisionada", type="integer")
     */    
    private $cantidadRemisionada = 0;        

    /**
     * @ORM\Column(name="cantidad_reservada", type="integer")
     */    
    private $cantidadReservada = 0;    
    
    /**
     * @ORM\Column(name="cantidad_disponible", type="integer")
     */    
    private $cantidadDisponible = 0;        

    /**
     * @ORM\Column(name="cantidad_orden_compra", type="integer")
     */    
    private $cantidadOrdenCompra = 0;    
    
    /**
     * @ORM\Column(name="permitir_inventario_negativo", type="boolean")
     */    
    private $permitirInventarioNegativo = 0; 
       
    /**
     * @ORM\Column(name="codigo_unidad_medida_fk", type="string", length=25, nullable=true)
     */    
    private $codigoUnidadMedidaFk;           
    
    /**
     * @ORM\Column(name="item_servicio", type="boolean")
     */    
    private $itemServicio = 0;              

    /**
     * @ORM\Column(name="materiaPrima", type="boolean")
     */    
    private $materiaPrima = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="InvUnidadesMedida", inversedBy="itemsRel")
     * @ORM\JoinColumn(name="codigo_unidad_medida_fk", referencedColumnName="codigo_unidad_medida_pk")
     */
    protected $unidadMedidaRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="InvMarcas", inversedBy="itemsRel")
     * @ORM\JoinColumn(name="codigo_marca_fk", referencedColumnName="codigo_marca_pk")
     */
    protected $marcaRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="InvMovimientosDetalles", mappedBy="itemRel")
     */
    protected $movimientosDetallesRel;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosDetallesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoItemPk
     *
     * @return integer 
     */
    public function getCodigoItemPk()
    {
        return $this->codigoItemPk;
    }

    /**
     * Set codigoMarcaFk
     *
     * @param integer $codigoMarcaFk
     * @return InvItem
     */
    public function setCodigoMarcaFk($codigoMarcaFk)
    {
        $this->codigoMarcaFk = $codigoMarcaFk;

        return $this;
    }

    /**
     * Get codigoMarcaFk
     *
     * @return integer 
     */
    public function getCodigoMarcaFk()
    {
        return $this->codigoMarcaFk;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return InvItem
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
     * Set vrCostoPredeterminado
     *
     * @param float $vrCostoPredeterminado
     * @return InvItem
     */
    public function setVrCostoPredeterminado($vrCostoPredeterminado)
    {
        $this->vrCostoPredeterminado = $vrCostoPredeterminado;

        return $this;
    }

    /**
     * Get vrCostoPredeterminado
     *
     * @return float 
     */
    public function getVrCostoPredeterminado()
    {
        return $this->vrCostoPredeterminado;
    }

    /**
     * Set vrCostoPromedio
     *
     * @param float $vrCostoPromedio
     * @return InvItem
     */
    public function setVrCostoPromedio($vrCostoPromedio)
    {
        $this->vrCostoPromedio = $vrCostoPromedio;

        return $this;
    }

    /**
     * Get vrCostoPromedio
     *
     * @return float 
     */
    public function getVrCostoPromedio()
    {
        return $this->vrCostoPromedio;
    }

    /**
     * Set vrPrecioPredeterminado
     *
     * @param float $vrPrecioPredeterminado
     * @return InvItem
     */
    public function setVrPrecioPredeterminado($vrPrecioPredeterminado)
    {
        $this->vrPrecioPredeterminado = $vrPrecioPredeterminado;

        return $this;
    }

    /**
     * Get vrPrecioPredeterminado
     *
     * @return float 
     */
    public function getVrPrecioPredeterminado()
    {
        return $this->vrPrecioPredeterminado;
    }

    /**
     * Set codigoEAN
     *
     * @param string $codigoEAN
     * @return InvItem
     */
    public function setCodigoEAN($codigoEAN)
    {
        $this->codigoEAN = $codigoEAN;

        return $this;
    }

    /**
     * Get codigoEAN
     *
     * @return string 
     */
    public function getCodigoEAN()
    {
        return $this->codigoEAN;
    }

    /**
     * Set codigoBarras
     *
     * @param string $codigoBarras
     * @return InvItem
     */
    public function setCodigoBarras($codigoBarras)
    {
        $this->codigoBarras = $codigoBarras;

        return $this;
    }

    /**
     * Get codigoBarras
     *
     * @return string 
     */
    public function getCodigoBarras()
    {
        return $this->codigoBarras;
    }

    /**
     * Set cuentaVentas
     *
     * @param string $cuentaVentas
     * @return InvItem
     */
    public function setCuentaVentas($cuentaVentas)
    {
        $this->cuentaVentas = $cuentaVentas;

        return $this;
    }

    /**
     * Get cuentaVentas
     *
     * @return string 
     */
    public function getCuentaVentas()
    {
        return $this->cuentaVentas;
    }

    /**
     * Set cuentaDevolucionVentas
     *
     * @param string $cuentaDevolucionVentas
     * @return InvItem
     */
    public function setCuentaDevolucionVentas($cuentaDevolucionVentas)
    {
        $this->cuentaDevolucionVentas = $cuentaDevolucionVentas;

        return $this;
    }

    /**
     * Get cuentaDevolucionVentas
     *
     * @return string 
     */
    public function getCuentaDevolucionVentas()
    {
        return $this->cuentaDevolucionVentas;
    }

    /**
     * Set cuentaCompras
     *
     * @param string $cuentaCompras
     * @return InvItem
     */
    public function setCuentaCompras($cuentaCompras)
    {
        $this->cuentaCompras = $cuentaCompras;

        return $this;
    }

    /**
     * Get cuentaCompras
     *
     * @return string 
     */
    public function getCuentaCompras()
    {
        return $this->cuentaCompras;
    }

    /**
     * Set cuentaDevolucionCompras
     *
     * @param string $cuentaDevolucionCompras
     * @return InvItem
     */
    public function setCuentaDevolucionCompras($cuentaDevolucionCompras)
    {
        $this->cuentaDevolucionCompras = $cuentaDevolucionCompras;

        return $this;
    }

    /**
     * Get cuentaDevolucionCompras
     *
     * @return string 
     */
    public function getCuentaDevolucionCompras()
    {
        return $this->cuentaDevolucionCompras;
    }

    /**
     * Set cuentaCosto
     *
     * @param string $cuentaCosto
     * @return InvItem
     */
    public function setCuentaCosto($cuentaCosto)
    {
        $this->cuentaCosto = $cuentaCosto;

        return $this;
    }

    /**
     * Get cuentaCosto
     *
     * @return string 
     */
    public function getCuentaCosto()
    {
        return $this->cuentaCosto;
    }

    /**
     * Set cuentaInventario
     *
     * @param string $cuentaInventario
     * @return InvItem
     */
    public function setCuentaInventario($cuentaInventario)
    {
        $this->cuentaInventario = $cuentaInventario;

        return $this;
    }

    /**
     * Get cuentaInventario
     *
     * @return string 
     */
    public function getCuentaInventario()
    {
        return $this->cuentaInventario;
    }

    /**
     * Set porcentajeIva
     *
     * @param integer $porcentajeIva
     * @return InvItem
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
     * Set cantidadExistencia
     *
     * @param integer $cantidadExistencia
     * @return InvItem
     */
    public function setCantidadExistencia($cantidadExistencia)
    {
        $this->cantidadExistencia = $cantidadExistencia;

        return $this;
    }

    /**
     * Get cantidadExistencia
     *
     * @return integer 
     */
    public function getCantidadExistencia()
    {
        return $this->cantidadExistencia;
    }

    /**
     * Set cantidadRemisionada
     *
     * @param integer $cantidadRemisionada
     * @return InvItem
     */
    public function setCantidadRemisionada($cantidadRemisionada)
    {
        $this->cantidadRemisionada = $cantidadRemisionada;

        return $this;
    }

    /**
     * Get cantidadRemisionada
     *
     * @return integer 
     */
    public function getCantidadRemisionada()
    {
        return $this->cantidadRemisionada;
    }

    /**
     * Set cantidadReservada
     *
     * @param integer $cantidadReservada
     * @return InvItem
     */
    public function setCantidadReservada($cantidadReservada)
    {
        $this->cantidadReservada = $cantidadReservada;

        return $this;
    }

    /**
     * Get cantidadReservada
     *
     * @return integer 
     */
    public function getCantidadReservada()
    {
        return $this->cantidadReservada;
    }

    /**
     * Set cantidadDisponible
     *
     * @param integer $cantidadDisponible
     * @return InvItem
     */
    public function setCantidadDisponible($cantidadDisponible)
    {
        $this->cantidadDisponible = $cantidadDisponible;

        return $this;
    }

    /**
     * Get cantidadDisponible
     *
     * @return integer 
     */
    public function getCantidadDisponible()
    {
        return $this->cantidadDisponible;
    }

    /**
     * Set cantidadOrdenCompra
     *
     * @param integer $cantidadOrdenCompra
     * @return InvItem
     */
    public function setCantidadOrdenCompra($cantidadOrdenCompra)
    {
        $this->cantidadOrdenCompra = $cantidadOrdenCompra;

        return $this;
    }

    /**
     * Get cantidadOrdenCompra
     *
     * @return integer 
     */
    public function getCantidadOrdenCompra()
    {
        return $this->cantidadOrdenCompra;
    }

    /**
     * Set permitirInventarioNegativo
     *
     * @param boolean $permitirInventarioNegativo
     * @return InvItem
     */
    public function setPermitirInventarioNegativo($permitirInventarioNegativo)
    {
        $this->permitirInventarioNegativo = $permitirInventarioNegativo;

        return $this;
    }

    /**
     * Get permitirInventarioNegativo
     *
     * @return boolean 
     */
    public function getPermitirInventarioNegativo()
    {
        return $this->permitirInventarioNegativo;
    }

    /**
     * Set codigoUnidadMedidaFk
     *
     * @param string $codigoUnidadMedidaFk
     * @return InvItem
     */
    public function setCodigoUnidadMedidaFk($codigoUnidadMedidaFk)
    {
        $this->codigoUnidadMedidaFk = $codigoUnidadMedidaFk;

        return $this;
    }

    /**
     * Get codigoUnidadMedidaFk
     *
     * @return string 
     */
    public function getCodigoUnidadMedidaFk()
    {
        return $this->codigoUnidadMedidaFk;
    }

    /**
     * Set itemServicio
     *
     * @param boolean $itemServicio
     * @return InvItem
     */
    public function setItemServicio($itemServicio)
    {
        $this->itemServicio = $itemServicio;

        return $this;
    }

    /**
     * Get itemServicio
     *
     * @return boolean 
     */
    public function getItemServicio()
    {
        return $this->itemServicio;
    }

    /**
     * Set materiaPrima
     *
     * @param boolean $materiaPrima
     * @return InvItem
     */
    public function setMateriaPrima($materiaPrima)
    {
        $this->materiaPrima = $materiaPrima;

        return $this;
    }

    /**
     * Get materiaPrima
     *
     * @return boolean 
     */
    public function getMateriaPrima()
    {
        return $this->materiaPrima;
    }

    /**
     * Set unidadMedidaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvUnidadesMedida $unidadMedidaRel
     * @return InvItem
     */
    public function setUnidadMedidaRel(\Brasa\InventarioBundle\Entity\InvUnidadesMedida $unidadMedidaRel = null)
    {
        $this->unidadMedidaRel = $unidadMedidaRel;

        return $this;
    }

    /**
     * Get unidadMedidaRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvUnidadesMedida 
     */
    public function getUnidadMedidaRel()
    {
        return $this->unidadMedidaRel;
    }

    /**
     * Set marcaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMarcas $marcaRel
     * @return InvItem
     */
    public function setMarcaRel(\Brasa\InventarioBundle\Entity\InvMarcas $marcaRel = null)
    {
        $this->marcaRel = $marcaRel;

        return $this;
    }

    /**
     * Get marcaRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvMarcas 
     */
    public function getMarcaRel()
    {
        return $this->marcaRel;
    }

    /**
     * Add movimientosDetallesRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientosDetalles $movimientosDetallesRel
     * @return InvItem
     */
    public function addMovimientosDetallesRel(\Brasa\InventarioBundle\Entity\InvMovimientosDetalles $movimientosDetallesRel)
    {
        $this->movimientosDetallesRel[] = $movimientosDetallesRel;

        return $this;
    }

    /**
     * Remove movimientosDetallesRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientosDetalles $movimientosDetallesRel
     */
    public function removeMovimientosDetallesRel(\Brasa\InventarioBundle\Entity\InvMovimientosDetalles $movimientosDetallesRel)
    {
        $this->movimientosDetallesRel->removeElement($movimientosDetallesRel);
    }

    /**
     * Get movimientosDetallesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovimientosDetallesRel()
    {
        return $this->movimientosDetallesRel;
    }
}
