<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_bodega")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurBodegaRepository")
 */
class TurBodega
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_bodega_pk", type="string", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoBodegaPk;                
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="TurMovimientoDetalle", mappedBy="bodegaRel")
     */
    protected $movimientosDetallesBodegaRel;
                                       
    

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosDetallesBodegaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoBodegaPk
     *
     * @return string
     */
    public function getCodigoBodegaPk()
    {
        return $this->codigoBodegaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurBodega
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add movimientosDetallesBodegaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurMovimientoDetalle $movimientosDetallesBodegaRel
     *
     * @return TurBodega
     */
    public function addMovimientosDetallesBodegaRel(\Brasa\TurnoBundle\Entity\TurMovimientoDetalle $movimientosDetallesBodegaRel)
    {
        $this->movimientosDetallesBodegaRel[] = $movimientosDetallesBodegaRel;

        return $this;
    }

    /**
     * Remove movimientosDetallesBodegaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurMovimientoDetalle $movimientosDetallesBodegaRel
     */
    public function removeMovimientosDetallesBodegaRel(\Brasa\TurnoBundle\Entity\TurMovimientoDetalle $movimientosDetallesBodegaRel)
    {
        $this->movimientosDetallesBodegaRel->removeElement($movimientosDetallesBodegaRel);
    }

    /**
     * Get movimientosDetallesBodegaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosDetallesBodegaRel()
    {
        return $this->movimientosDetallesBodegaRel;
    }
}
