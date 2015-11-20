<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_sector")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSectorRepository")
 */
class TurSector
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sector_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSectorPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="porcentaje", type="float")
     */    
    private $porcentaje = 0;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     

    /**
     * @ORM\OneToMany(targetEntity="TurCotizacion", mappedBy="sectorRel")
     */
    protected $cotizacionesSectorRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cotizacionesSectorRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSectorPk
     *
     * @return integer
     */
    public function getCodigoSectorPk()
    {
        return $this->codigoSectorPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurSector
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
     * Set porcentaje
     *
     * @param float $porcentaje
     *
     * @return TurSector
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return float
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurSector
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Add cotizacionesSectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesSectorRel
     *
     * @return TurSector
     */
    public function addCotizacionesSectorRel(\Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesSectorRel)
    {
        $this->cotizacionesSectorRel[] = $cotizacionesSectorRel;

        return $this;
    }

    /**
     * Remove cotizacionesSectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesSectorRel
     */
    public function removeCotizacionesSectorRel(\Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesSectorRel)
    {
        $this->cotizacionesSectorRel->removeElement($cotizacionesSectorRel);
    }

    /**
     * Get cotizacionesSectorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesSectorRel()
    {
        return $this->cotizacionesSectorRel;
    }
}
