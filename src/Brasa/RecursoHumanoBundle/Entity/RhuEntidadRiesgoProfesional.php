<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_entidad_riesgo_profesional")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEntidadRiesgoProfesionalRepository")
 */
class RhuEntidadRiesgoProfesional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_riesgo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadRiesgoPk;
    
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
     * @ORM\Column(name="codigo_interface", type="string", length=10, nullable=true)
     */    
    private $codigoInterface;

    /**
     * @ORM\OneToMany(targetEntity="RhuConfiguracion", mappedBy="entidadRiesgoProfesionalRel")
     */
    protected $configuracionEntidadRiesgoProfesionalRel;
   
   
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->configuracionEntidadRiesgoProfesionalRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEntidadRiesgoPk
     *
     * @return integer
     */
    public function getCodigoEntidadRiesgoPk()
    {
        return $this->codigoEntidadRiesgoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEntidadRiesgoProfesional
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
     * @return RhuEntidadRiesgoProfesional
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
     * @return RhuEntidadRiesgoProfesional
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
     * @return RhuEntidadRiesgoProfesional
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
     * Add configuracionEntidadRiesgoProfesionalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion $configuracionEntidadRiesgoProfesionalRel
     *
     * @return RhuEntidadRiesgoProfesional
     */
    public function addConfiguracionEntidadRiesgoProfesionalRel(\Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion $configuracionEntidadRiesgoProfesionalRel)
    {
        $this->configuracionEntidadRiesgoProfesionalRel[] = $configuracionEntidadRiesgoProfesionalRel;

        return $this;
    }

    /**
     * Remove configuracionEntidadRiesgoProfesionalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion $configuracionEntidadRiesgoProfesionalRel
     */
    public function removeConfiguracionEntidadRiesgoProfesionalRel(\Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion $configuracionEntidadRiesgoProfesionalRel)
    {
        $this->configuracionEntidadRiesgoProfesionalRel->removeElement($configuracionEntidadRiesgoProfesionalRel);
    }

    /**
     * Get configuracionEntidadRiesgoProfesionalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConfiguracionEntidadRiesgoProfesionalRel()
    {
        return $this->configuracionEntidadRiesgoProfesionalRel;
    }

    /**
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return RhuEntidadRiesgoProfesional
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
}
