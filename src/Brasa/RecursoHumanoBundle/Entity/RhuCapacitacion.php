<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_capacitacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCapacitacionRepository")
 */
class RhuCapacitacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionPk;                    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;             
        
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;           

    /**
     * @ORM\OneToMany(targetEntity="RhuCapacitacionDetalle", mappedBy="capacitacionRel", cascade={"persist", "remove"})
     */
    protected $capacitacionesDetallesCapacitacionRel;    


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->capacitacionesDetallesCapacitacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCapacitacionPk
     *
     * @return integer
     */
    public function getCodigoCapacitacionPk()
    {
        return $this->codigoCapacitacionPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuCapacitacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuCapacitacion
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Add capacitacionesDetallesCapacitacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle $capacitacionesDetallesCapacitacionRel
     *
     * @return RhuCapacitacion
     */
    public function addCapacitacionesDetallesCapacitacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle $capacitacionesDetallesCapacitacionRel)
    {
        $this->capacitacionesDetallesCapacitacionRel[] = $capacitacionesDetallesCapacitacionRel;

        return $this;
    }

    /**
     * Remove capacitacionesDetallesCapacitacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle $capacitacionesDetallesCapacitacionRel
     */
    public function removeCapacitacionesDetallesCapacitacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle $capacitacionesDetallesCapacitacionRel)
    {
        $this->capacitacionesDetallesCapacitacionRel->removeElement($capacitacionesDetallesCapacitacionRel);
    }

    /**
     * Get capacitacionesDetallesCapacitacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCapacitacionesDetallesCapacitacionRel()
    {
        return $this->capacitacionesDetallesCapacitacionRel;
    }
}
