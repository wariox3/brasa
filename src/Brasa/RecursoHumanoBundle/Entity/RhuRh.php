<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_rh")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuRhRepository")
 */
class RhuRh
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_rh_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRhPk;
    
    /**
     * @ORM\Column(name="tipo", type="string", length=80, nullable=true)
     */    
    private $tipo;      

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="rhRel")
     */
    protected $empleadosRhRel;    
  
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="estadoCivilRel")
     */
    protected $seleccionesEstadoCivilRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="rhRel")
     */
    protected $seleccionesRhRel;



  
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosRhRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesEstadoCivilRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesRhRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoRhPk
     *
     * @return integer
     */
    public function getCodigoRhPk()
    {
        return $this->codigoRhPk;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return RhuRh
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Add empleadosRhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosRhRel
     *
     * @return RhuRh
     */
    public function addEmpleadosRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosRhRel)
    {
        $this->empleadosRhRel[] = $empleadosRhRel;

        return $this;
    }

    /**
     * Remove empleadosRhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosRhRel
     */
    public function removeEmpleadosRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosRhRel)
    {
        $this->empleadosRhRel->removeElement($empleadosRhRel);
    }

    /**
     * Get empleadosRhRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosRhRel()
    {
        return $this->empleadosRhRel;
    }

    /**
     * Add seleccionesEstadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesEstadoCivilRel
     *
     * @return RhuRh
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

    /**
     * Add seleccionesRhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesRhRel
     *
     * @return RhuRh
     */
    public function addSeleccionesRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesRhRel)
    {
        $this->seleccionesRhRel[] = $seleccionesRhRel;

        return $this;
    }

    /**
     * Remove seleccionesRhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesRhRel
     */
    public function removeSeleccionesRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesRhRel)
    {
        $this->seleccionesRhRel->removeElement($seleccionesRhRel);
    }

    /**
     * Get seleccionesRhRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesRhRel()
    {
        return $this->seleccionesRhRel;
    }
}
