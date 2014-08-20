<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_listas_precios")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvListasPreciosRepository")
 */
class InvListasPrecios
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_lista_precios_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoListaPreciosPk; 

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;    

    /**
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */    
    private $fechaCreacion; 
    
    /**
     * @ORM\Column(name="vigente_hasta", type="date", nullable=true)
     */    
    private $vigenteHasta;    
    
    /**
     * @ORM\Column(name="estado_inactiva", type="boolean")
     */    
    private $estadoInactiva = 0;  
           


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
     * Set nombre
     *
     * @param string $nombre
     * @return InvListasPrecios
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return InvListasPrecios
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set vigenteHasta
     *
     * @param \DateTime $vigenteHasta
     * @return InvListasPrecios
     */
    public function setVigenteHasta($vigenteHasta)
    {
        $this->vigenteHasta = $vigenteHasta;

        return $this;
    }

    /**
     * Get vigenteHasta
     *
     * @return \DateTime 
     */
    public function getVigenteHasta()
    {
        return $this->vigenteHasta;
    }

    /**
     * Set estadoInactiva
     *
     * @param boolean $estadoInactiva
     * @return InvListasPrecios
     */
    public function setEstadoInactiva($estadoInactiva)
    {
        $this->estadoInactiva = $estadoInactiva;

        return $this;
    }

    /**
     * Get estadoInactiva
     *
     * @return boolean 
     */
    public function getEstadoInactiva()
    {
        return $this->estadoInactiva;
    }
}
