<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_productos")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteProductosRepository")
 */
class TteProductos
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_producto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProductoPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;     

    /**
     * @ORM\OneToMany(targetEntity="TteListasPreciosDetalles", mappedBy="productoRel")
     */
    protected $listasPreciosDetallesRel;    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listasPreciosDetallesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoProductoPk
     *
     * @return integer 
     */
    public function getCodigoProductoPk()
    {
        return $this->codigoProductoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TteProductos
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
     * Add listasPreciosDetallesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $listasPreciosDetallesRel
     * @return TteProductos
     */
    public function addListasPreciosDetallesRel(\Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $listasPreciosDetallesRel)
    {
        $this->listasPreciosDetallesRel[] = $listasPreciosDetallesRel;

        return $this;
    }

    /**
     * Remove listasPreciosDetallesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $listasPreciosDetallesRel
     */
    public function removeListasPreciosDetallesRel(\Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $listasPreciosDetallesRel)
    {
        $this->listasPreciosDetallesRel->removeElement($listasPreciosDetallesRel);
    }

    /**
     * Get listasPreciosDetallesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getListasPreciosDetallesRel()
    {
        return $this->listasPreciosDetallesRel;
    }
}
