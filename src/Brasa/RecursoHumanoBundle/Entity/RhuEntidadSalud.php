<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_entidad_salud")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEntidadSaludRepository")
 */
class RhuEntidadSalud
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_salud_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadSaludPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="entidadSaludRel")
     */
    protected $empleadosEntidadSaludRel;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEntidadSaludPk
     *
     * @return integer
     */
    public function getCodigoEntidadSaludPk()
    {
        return $this->codigoEntidadSaludPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEntidadSalud
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
     * Add empleadosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addEmpleadosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel)
    {
        $this->empleadosEntidadSaludRel[] = $empleadosEntidadSaludRel;

        return $this;
    }

    /**
     * Remove empleadosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel
     */
    public function removeEmpleadosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel)
    {
        $this->empleadosEntidadSaludRel->removeElement($empleadosEntidadSaludRel);
    }

    /**
     * Get empleadosEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEntidadSaludRel()
    {
        return $this->empleadosEntidadSaludRel;
    }
}
