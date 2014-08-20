<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_lotes")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvLotesRepository")
 */
class InvLotes
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */     
    private $codigoItemFk;     
    
    /**
     * @ORM\Id
     * @ORM\Column(name="lote_fk", type="string", length=40)
     */      
    private $loteFk;
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_bodega_fk", type="integer")
     */     
    private $codigoBodegaFk;
    
    /**
     * @ORM\Column(name="existencia", type="integer")
     */            
    private $existencia = 0;

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
     * @ORM\Column(name="fecha_vencimiento", type="date")
     */            
    private $fechaVencimiento;    

    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="InvLotes")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="InvBodegas", inversedBy="InvLotes")
     * @ORM\JoinColumn(name="codigo_bodega_fk", referencedColumnName="codigo_bodega_pk")
     */
    protected $bodegaRel;    
    


    /**
     * Set codigoItemFk
     *
     * @param integer $codigoItemFk
     * @return InvLotes
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
     * Set loteFk
     *
     * @param string $loteFk
     * @return InvLotes
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
     * Set codigoBodegaFk
     *
     * @param integer $codigoBodegaFk
     * @return InvLotes
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
     * Set existencia
     *
     * @param integer $existencia
     * @return InvLotes
     */
    public function setExistencia($existencia)
    {
        $this->existencia = $existencia;

        return $this;
    }

    /**
     * Get existencia
     *
     * @return integer 
     */
    public function getExistencia()
    {
        return $this->existencia;
    }

    /**
     * Set cantidadRemisionada
     *
     * @param integer $cantidadRemisionada
     * @return InvLotes
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
     * @return InvLotes
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
     * @return InvLotes
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
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     * @return InvLotes
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
     * Set itemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemRel
     * @return InvLotes
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
     * Set bodegaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvBodegas $bodegaRel
     * @return InvLotes
     */
    public function setBodegaRel(\Brasa\InventarioBundle\Entity\InvBodegas $bodegaRel = null)
    {
        $this->bodegaRel = $bodegaRel;

        return $this;
    }

    /**
     * Get bodegaRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvBodegas 
     */
    public function getBodegaRel()
    {
        return $this->bodegaRel;
    }
}
