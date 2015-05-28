<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_estado_civil")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEstadoCivilRepository")
 */
class RhuEstadoCivil
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_estado_civil_pk", type="string", length=1)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEstadoCivilPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="estadoCivilRel")
     */
    protected $empleadosEstadoCivilRel;    
  
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="estadoCivilRel")
     */
    protected $seleccionesEstadoCivilRel;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEstadoCivilRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEstadoCivilPk
     *
     * @return string
     */
    public function getCodigoEstadoCivilPk()
    {
        return $this->codigoEstadoCivilPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEstadoCivil
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
     * Add empleadosEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEstadoCivilRel
     *
     * @return RhuEstadoCivil
     */
    public function addEmpleadosEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEstadoCivilRel)
    {
        $this->empleadosEstadoCivilRel[] = $empleadosEstadoCivilRel;

        return $this;
    }

    /**
     * Remove empleadosEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEstadoCivilRel
     */
    public function removeEmpleadosEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEstadoCivilRel)
    {
        $this->empleadosEstadoCivilRel->removeElement($empleadosEstadoCivilRel);
    }

    /**
     * Get empleadosEstadoCivilRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEstadoCivilRel()
    {
        return $this->empleadosEstadoCivilRel;
    }

    /**
     * Add seleccionesEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesEstadoCivilRel
     *
     * @return RhuEstadoCivil
     */
    public function addSeleccionesEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesEstadoCivilRel)
    {
        $this->seleccionesEstadoCivilRel[] = $seleccionesEstadoCivilRel;

        return $this;
    }

    /**
     * Remove seleccionesEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesEstadoCivilRel
     */
    public function removeSeleccionesEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesEstadoCivilRel)
    {
        $this->seleccionesEstadoCivilRel->removeElement($seleccionesEstadoCivilRel);
    }

    /**
     * Get seleccionesEstadoCivilRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesEstadoCivilRel()
    {
        return $this->seleccionesEstadoCivilRel;
    }
}
