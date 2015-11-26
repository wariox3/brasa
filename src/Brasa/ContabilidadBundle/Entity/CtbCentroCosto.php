<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_centro_costo")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbCentroCostoRepository")
 */
class CtbCentroCosto
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_costo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoCentroCostoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;      
    
    /**
     * @ORM\OneToMany(targetEntity="CtbAsientoDetalle", mappedBy="centroCostoRel")
     */
    protected $asientosDetallesCentroCostoRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->asientosDetallesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCentroCostoPk
     *
     * @return integer
     */
    public function getCodigoCentroCostoPk()
    {
        return $this->codigoCentroCostoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbCentroCosto
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
     * Add asientosDetallesCentroCostoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel
     *
     * @return CtbCentroCosto
     */
    public function addAsientosDetallesCentroCostoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel)
    {
        $this->asientosDetallesCentroCostoRel[] = $asientosDetallesCentroCostoRel;

        return $this;
    }

    /**
     * Remove asientosDetallesCentroCostoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel
     */
    public function removeAsientosDetallesCentroCostoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel)
    {
        $this->asientosDetallesCentroCostoRel->removeElement($asientosDetallesCentroCostoRel);
    }

    /**
     * Get asientosDetallesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsientosDetallesCentroCostoRel()
    {
        return $this->asientosDetallesCentroCostoRel;
    }
}
