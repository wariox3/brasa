<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * zikmont\InventarioBundle\Entity\UnidadesMedida
 *
 * @ORM\Table(name="inv_unidades_medida")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvUnidadesMedidaRepository")
 */
class InvUnidadesMedida
{
    /**
     * @var integer $codigo_unidad_medida_pk
     * @ORM\Id
     * @ORM\Column(name="codigo_unidad_medida_pk", type="string", length=25)
     */
    private $codigoUnidadMedidaPk;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="InvItem", mappedBy="unidadMedidaRel")
     */
    protected $itemsRel;

    public function __construct()
    {
        $this->itemsRel = new ArrayCollection();
    }



    /**
     * Set codigoUnidadMedidaPk
     *
     * @param string $codigoUnidadMedidaPk
     * @return InvUnidadesMedida
     */
    public function setCodigoUnidadMedidaPk($codigoUnidadMedidaPk)
    {
        $this->codigoUnidadMedidaPk = $codigoUnidadMedidaPk;

        return $this;
    }

    /**
     * Get codigoUnidadMedidaPk
     *
     * @return string 
     */
    public function getCodigoUnidadMedidaPk()
    {
        return $this->codigoUnidadMedidaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return InvUnidadesMedida
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
     * Add itemsRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemsRel
     * @return InvUnidadesMedida
     */
    public function addItemsRel(\Brasa\InventarioBundle\Entity\InvItem $itemsRel)
    {
        $this->itemsRel[] = $itemsRel;

        return $this;
    }

    /**
     * Remove itemsRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemsRel
     */
    public function removeItemsRel(\Brasa\InventarioBundle\Entity\InvItem $itemsRel)
    {
        $this->itemsRel->removeElement($itemsRel);
    }

    /**
     * Get itemsRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItemsRel()
    {
        return $this->itemsRel;
    }
}
