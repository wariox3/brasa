<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_listas_precios")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteListasPreciosRepository")
 */
class TteListasPrecios
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_lista_precios_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoListaPreciosPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;     
    
    /**
     * @ORM\Column(name="fecha_vencimiento", type="datetime", nullable=true)
     */    
    private $fechaVencimiento;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteListasPreciosDetalles", mappedBy="listaPreciosRel")
     */
    protected $listasPreciosDetallesRel;
    
    /**
     * @ORM\OneToMany(targetEntity="TteListasPreciosDetalles", mappedBy="listaPreciosRel")
     */
    protected $negociacionesRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listasPreciosDetallesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoListaPreciosPk
     *
     * @return integer 
     */
    public function getCodigoListaPreciosPk()
    {
        return $this->codigoListaPreciosPk;
    }

    /**
     * Add listasPreciosDetallesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $listasPreciosDetallesRel
     * @return TteListasPrecios
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

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TteListasPrecios
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
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     * @return TteListasPrecios
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
     * Add negociacionesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $negociacionesRel
     * @return TteListasPrecios
     */
    public function addNegociacionesRel(\Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $negociacionesRel)
    {
        $this->negociacionesRel[] = $negociacionesRel;

        return $this;
    }

    /**
     * Remove negociacionesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $negociacionesRel
     */
    public function removeNegociacionesRel(\Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $negociacionesRel)
    {
        $this->negociacionesRel->removeElement($negociacionesRel);
    }

    /**
     * Get negociacionesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNegociacionesRel()
    {
        return $this->negociacionesRel;
    }
}
