<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_programacion")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurProgramacionRepository")
 */
class TurProgramacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPk;             
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;     
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;        

    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = false;    
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;             
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTercero", inversedBy="turProgramacionesTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="programacionRel", cascade={"persist", "remove"})
     */
    protected $programacionesDetallesProgramacionRel; 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesDetallesProgramacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoProgramacionPk
     *
     * @return integer
     */
    public function getCodigoProgramacionPk()
    {
        return $this->codigoProgramacionPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurProgramacion
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return TurProgramacion
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
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurProgramacion
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurProgramacion
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
     * Add programacionesDetallesProgramacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesProgramacionRel
     *
     * @return TurProgramacion
     */
    public function addProgramacionesDetallesProgramacionRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesProgramacionRel)
    {
        $this->programacionesDetallesProgramacionRel[] = $programacionesDetallesProgramacionRel;

        return $this;
    }

    /**
     * Remove programacionesDetallesProgramacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesProgramacionRel
     */
    public function removeProgramacionesDetallesProgramacionRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesProgramacionRel)
    {
        $this->programacionesDetallesProgramacionRel->removeElement($programacionesDetallesProgramacionRel);
    }

    /**
     * Get programacionesDetallesProgramacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesDetallesProgramacionRel()
    {
        return $this->programacionesDetallesProgramacionRel;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return TurProgramacion
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;

        return $this;
    }

    /**
     * Get codigoTerceroFk
     *
     * @return integer
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * Set terceroRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $terceroRel
     *
     * @return TurProgramacion
     */
    public function setTerceroRel(\Brasa\GeneralBundle\Entity\GenTercero $terceroRel = null)
    {
        $this->terceroRel = $terceroRel;

        return $this;
    }

    /**
     * Get terceroRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenTercero
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return TurProgramacion
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }
}