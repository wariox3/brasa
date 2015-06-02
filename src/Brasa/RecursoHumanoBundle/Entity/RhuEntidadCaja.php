<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_entidad_caja")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEntidadCajaRepository")
 */
class RhuEntidadCaja
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_caja_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadCajaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
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
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="entidadCajaRel")
     */
    protected $empleadosEntidadCajaRel;    

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

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return RhuEntidadSalud
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
     * @return RhuEntidadSalud
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
     * @return RhuEntidadSalud
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
     * Get codigoEntidadCajaPk
     *
     * @return integer
     */
    public function getCodigoEntidadCajaPk()
    {
        return $this->codigoEntidadCajaPk;
    }

    /**
     * Add empleadosEntidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCajaRel
     *
     * @return RhuEntidadCaja
     */
    public function addEmpleadosEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCajaRel)
    {
        $this->empleadosEntidadCajaRel[] = $empleadosEntidadCajaRel;

        return $this;
    }

    /**
     * Remove empleadosEntidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCajaRel
     */
    public function removeEmpleadosEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCajaRel)
    {
        $this->empleadosEntidadCajaRel->removeElement($empleadosEntidadCajaRel);
    }

    /**
     * Get empleadosEntidadCajaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEntidadCajaRel()
    {
        return $this->empleadosEntidadCajaRel;
    }
}
