<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_entidad_pension")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEntidadPensionRepository")
 */
class RhuEntidadPension
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_pension_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadPensionPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="entidadPensionRel")
     */
    protected $empleadosEntidadPensionRel;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEntidadPensionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEntidadPensionPk
     *
     * @return integer
     */
    public function getCodigoEntidadPensionPk()
    {
        return $this->codigoEntidadPensionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEntidadPension
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
     * Add empleadosEntidadPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadPensionRel
     *
     * @return RhuEntidadPension
     */
    public function addEmpleadosEntidadPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadPensionRel)
    {
        $this->empleadosEntidadPensionRel[] = $empleadosEntidadPensionRel;

        return $this;
    }

    /**
     * Remove empleadosEntidadPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadPensionRel
     */
    public function removeEmpleadosEntidadPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadPensionRel)
    {
        $this->empleadosEntidadPensionRel->removeElement($empleadosEntidadPensionRel);
    }

    /**
     * Get empleadosEntidadPensionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEntidadPensionRel()
    {
        return $this->empleadosEntidadPensionRel;
    }
}
