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
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="nit", type="string", length=10, nullable=true)
     */    
    private $nit;    
    
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */    
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono; 
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=20, nullable=true)
     */    
    private $codigoInterface;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="entidadPensionRel")
     */
    protected $empleadosEntidadPensionRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadPensionRel")
     */
    protected $contratosEntidadPensionRel;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEntidadPensionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosEntidadPensionRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nit
     *
     * @param string $nit
     *
     * @return RhuEntidadPension
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuEntidadPension
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuEntidadPension
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return RhuEntidadPension
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
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

    /**
     * Add contratosEntidadPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadPensionRel
     *
     * @return RhuEntidadPension
     */
    public function addContratosEntidadPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadPensionRel)
    {
        $this->contratosEntidadPensionRel[] = $contratosEntidadPensionRel;

        return $this;
    }

    /**
     * Remove contratosEntidadPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadPensionRel
     */
    public function removeContratosEntidadPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadPensionRel)
    {
        $this->contratosEntidadPensionRel->removeElement($contratosEntidadPensionRel);
    }

    /**
     * Get contratosEntidadPensionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosEntidadPensionRel()
    {
        return $this->contratosEntidadPensionRel;
    }
}
