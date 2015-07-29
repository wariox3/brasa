<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_estudio_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoEstudioTipoRepository")
 */
class RhuEmpleadoEstudioTipo
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_estudio_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $empleadoEstudioTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;          
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoEstudio", mappedBy="empleadoEstudioTipoRel")
     */
    protected $empleadosEstudiosEmpleadoEstudioTipoRel;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEstudiosEmpleadoEstudioTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get empleadoEstudioTipoPk
     *
     * @return integer
     */
    public function getEmpleadoEstudioTipoPk()
    {
        return $this->empleadoEstudioTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEmpleadoEstudioTipo
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
     * Add empleadosEstudiosEmpleadoEstudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoEstudioTipoRel
     *
     * @return RhuEmpleadoEstudioTipo
     */
    public function addEmpleadosEstudiosEmpleadoEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoEstudioTipoRel)
    {
        $this->empleadosEstudiosEmpleadoEstudioTipoRel[] = $empleadosEstudiosEmpleadoEstudioTipoRel;

        return $this;
    }

    /**
     * Remove empleadosEstudiosEmpleadoEstudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoEstudioTipoRel
     */
    public function removeEmpleadosEstudiosEmpleadoEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $empleadosEstudiosEmpleadoEstudioTipoRel)
    {
        $this->empleadosEstudiosEmpleadoEstudioTipoRel->removeElement($empleadosEstudiosEmpleadoEstudioTipoRel);
    }

    /**
     * Get empleadosEstudiosEmpleadoEstudioTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEstudiosEmpleadoEstudioTipoRel()
    {
        return $this->empleadosEstudiosEmpleadoEstudioTipoRel;
    }
}
