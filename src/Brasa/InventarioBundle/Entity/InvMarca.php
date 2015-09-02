<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="inv_marca")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvMarcaRepository")
 */
class InvMarca
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_marca_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMarcaPk;

    /**
     * @ORM\Column(name="nombre_marca", type="string", length=255)
     * @Assert\NotNull()(message="Debe escribir un nombre de marca")
     */
    private $nombreMarca;

    /**
     * @ORM\OneToMany(targetEntity="InvItem", mappedBy="marcaRel")
     */
    protected $itemsRel;

    public function __construct()
    {
        $this->itemsRel = new ArrayCollection();
    }
 


    /**
     * Get codigoMarcaPk
     *
     * @return integer 
     */
    public function getCodigoMarcaPk()
    {
        return $this->codigoMarcaPk;
    }

    /**
     * Set nombreMarca
     *
     * @param string $nombreMarca
     * @return InvMarca
     */
    public function setNombreMarca($nombreMarca)
    {
        $this->nombreMarca = $nombreMarca;

        return $this;
    }

    /**
     * Get nombreMarca
     *
     * @return string 
     */
    public function getNombreMarca()
    {
        return $this->nombreMarca;
    }

    /**
     * Add itemsRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemsRel
     * @return InvMarca
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
