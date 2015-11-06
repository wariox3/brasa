<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenTipoRepository")
 */
class RhuExamenTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
    
    /**     
     * Aplica para cuando se crea el examen de ingreso automaticamente
     * cree un examen con estos tipos
     * 
     * @ORM\Column(name="general", type="boolean")
     */    
    private $general = 0;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenDetalle", mappedBy="examenTipoRel")
     */
    protected $examenesDetallesExamenTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoExamen", mappedBy="examenTipoRel")
     */
    protected $empleadosExamenesExamenTipoRel;    
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenesDetallesExamenTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosExamenesExamenTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoExamenTipoPk
     *
     * @return integer
     */
    public function getCodigoExamenTipoPk()
    {
        return $this->codigoExamenTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuExamenTipo
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
     * Add examenesDetallesExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel
     *
     * @return RhuExamenTipo
     */
    public function addExamenesDetallesExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel)
    {
        $this->examenesDetallesExamenTipoRel[] = $examenesDetallesExamenTipoRel;

        return $this;
    }

    /**
     * Remove examenesDetallesExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel
     */
    public function removeExamenesDetallesExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel)
    {
        $this->examenesDetallesExamenTipoRel->removeElement($examenesDetallesExamenTipoRel);
    }

    /**
     * Get examenesDetallesExamenTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesDetallesExamenTipoRel()
    {
        return $this->examenesDetallesExamenTipoRel;
    }

    /**
     * Add empleadosExamenesExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoExamen $empleadosExamenesExamenTipoRel
     *
     * @return RhuExamenTipo
     */
    public function addEmpleadosExamenesExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoExamen $empleadosExamenesExamenTipoRel)
    {
        $this->empleadosExamenesExamenTipoRel[] = $empleadosExamenesExamenTipoRel;

        return $this;
    }

    /**
     * Remove empleadosExamenesExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoExamen $empleadosExamenesExamenTipoRel
     */
    public function removeEmpleadosExamenesExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoExamen $empleadosExamenesExamenTipoRel)
    {
        $this->empleadosExamenesExamenTipoRel->removeElement($empleadosExamenesExamenTipoRel);
    }

    /**
     * Get empleadosExamenesExamenTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosExamenesExamenTipoRel()
    {
        return $this->empleadosExamenesExamenTipoRel;
    }

    /**
     * Set general
     *
     * @param boolean $general
     *
     * @return RhuExamenTipo
     */
    public function setGeneral($general)
    {
        $this->general = $general;

        return $this;
    }

    /**
     * Get general
     *
     * @return boolean
     */
    public function getGeneral()
    {
        return $this->general;
    }
}
