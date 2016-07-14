<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_zona")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuZonaRepository")
 */
class RhuZona
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_zona_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoZonaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;          
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="zonaRel")
     */
    protected $empleadosZonaRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosZonaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoZonaPk
     *
     * @return integer
     */
    public function getCodigoZonaPk()
    {
        return $this->codigoZonaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuZona
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
     * Add empleadosZonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosZonaRel
     *
     * @return RhuZona
     */
    public function addEmpleadosZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosZonaRel)
    {
        $this->empleadosZonaRel[] = $empleadosZonaRel;

        return $this;
    }

    /**
     * Remove empleadosZonaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosZonaRel
     */
    public function removeEmpleadosZonaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosZonaRel)
    {
        $this->empleadosZonaRel->removeElement($empleadosZonaRel);
    }

    /**
     * Get empleadosZonaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosZonaRel()
    {
        return $this->empleadosZonaRel;
    }
}
