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
     * @ORM\Column(name="codigo_capacitacion_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCapacitacionTipoFk;     
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;             

    /**
     * @ORM\Column(name="tema", type="string", length=150, nullable=true)
     */    
    private $tema;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;           

    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCapacitacionTipo", inversedBy="capacitacionesCapacitacionTipoRel")
     * @ORM\JoinColumn(name="codigo_capacitacion_tipo_fk", referencedColumnName="codigo_capacitacion_tipo_pk")
     */
    protected $capacitacionTipoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCapacitacionDetalle", mappedBy="capacitacionRel", cascade={"persist", "remove"})
     */
    protected $capacitacionesDetallesCapacitacionRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuCapacitacionNota", mappedBy="capacitacionRel", cascade={"persist", "remove"})
     */
    protected $capacitacionesNotasCapacitacionRel;        
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->capacitacionesDetallesCapacitacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->capacitacionesNotasCapacitacionRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set codigoCapacitacionTipoFk
     *
     * @param integer $codigoCapacitacionTipoFk
     *
     * @return RhuCapacitacion
     */
    public function setCodigoCapacitacionTipoFk($codigoCapacitacionTipoFk)
    {
        $this->codigoCapacitacionTipoFk = $codigoCapacitacionTipoFk;

        return $this;
    }

    /**
     * Get codigoCapacitacionTipoFk
     *
     * @return integer
     */
    public function getCodigoCapacitacionTipoFk()
    {
        return $this->codigoCapacitacionTipoFk;
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
     * Set tema
     *
     * @param string $tema
     *
     * @return RhuCapacitacion
     */
    public function setTema($tema)
    {
        $this->tema = $tema;

        return $this;
    }

    /**
     * Get tema
     *
     * @return string
     */
    public function getTema()
    {
        return $this->tema;
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuCapacitacion
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set capacitacionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionTipo $capacitacionTipoRel
     *
     * @return RhuCapacitacion
     */
    public function setCapacitacionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionTipo $capacitacionTipoRel = null)
    {
        $this->capacitacionTipoRel = $capacitacionTipoRel;

        return $this;
    }

    /**
     * Get capacitacionTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionTipo
     */
    public function getCapacitacionTipoRel()
    {
        return $this->capacitacionTipoRel;
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

    /**
     * Add capacitacionesNotasCapacitacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionNota $capacitacionesNotasCapacitacionRel
     *
     * @return RhuCapacitacion
     */
    public function addCapacitacionesNotasCapacitacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionNota $capacitacionesNotasCapacitacionRel)
    {
        $this->capacitacionesNotasCapacitacionRel[] = $capacitacionesNotasCapacitacionRel;

        return $this;
    }

    /**
     * Remove capacitacionesNotasCapacitacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionNota $capacitacionesNotasCapacitacionRel
     */
    public function removeCapacitacionesNotasCapacitacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionNota $capacitacionesNotasCapacitacionRel)
    {
        $this->capacitacionesNotasCapacitacionRel->removeElement($capacitacionesNotasCapacitacionRel);
    }

    /**
     * Get capacitacionesNotasCapacitacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCapacitacionesNotasCapacitacionRel()
    {
        return $this->capacitacionesNotasCapacitacionRel;
    }
}
