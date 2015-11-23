<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cotizacion")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCotizacionRepository")
 */
class TurCotizacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cotizacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCotizacionPk;         
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */    
    private $fechaVence;    
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    

    /**
     * @ORM\Column(name="codigo_sector_fk", type="integer", nullable=true)
     */    
    private $codigoSectorFk;    
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;     

    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = false;    
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;    
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="horas_diurnas", type="integer")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="integer")
     */    
    private $horasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;            
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTercero", inversedBy="turCotizacionesTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSector", inversedBy="cotizacionesSectorRel")
     * @ORM\JoinColumn(name="codigo_sector_fk", referencedColumnName="codigo_sector_pk")
     */
    protected $sectorRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCotizacionDetalle", mappedBy="cotizacionRel", cascade={"persist", "remove"})
     */
    protected $cotizacionesDetallesCotizacionRel; 


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cotizacionesDetallesCotizacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCotizacionPk
     *
     * @return integer
     */
    public function getCodigoCotizacionPk()
    {
        return $this->codigoCotizacionPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurCotizacion
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
     * Set fechaVence
     *
     * @param \DateTime $fechaVence
     *
     * @return TurCotizacion
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;

        return $this;
    }

    /**
     * Get fechaVence
     *
     * @return \DateTime
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return TurCotizacion
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
     * Set codigoSectorFk
     *
     * @param integer $codigoSectorFk
     *
     * @return TurCotizacion
     */
    public function setCodigoSectorFk($codigoSectorFk)
    {
        $this->codigoSectorFk = $codigoSectorFk;

        return $this;
    }

    /**
     * Get codigoSectorFk
     *
     * @return integer
     */
    public function getCodigoSectorFk()
    {
        return $this->codigoSectorFk;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return TurCotizacion
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
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return TurCotizacion
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

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurCotizacion
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurCotizacion
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
     * Set horasDiurnas
     *
     * @param integer $horasDiurnas
     *
     * @return TurCotizacion
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return integer
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param integer $horasNocturnas
     *
     * @return TurCotizacion
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return integer
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return TurCotizacion
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurCotizacion
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
     * Set terceroRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $terceroRel
     *
     * @return TurCotizacion
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
     * Set sectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSector $sectorRel
     *
     * @return TurCotizacion
     */
    public function setSectorRel(\Brasa\TurnoBundle\Entity\TurSector $sectorRel = null)
    {
        $this->sectorRel = $sectorRel;

        return $this;
    }

    /**
     * Get sectorRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurSector
     */
    public function getSectorRel()
    {
        return $this->sectorRel;
    }

    /**
     * Add cotizacionesDetallesCotizacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesCotizacionRel
     *
     * @return TurCotizacion
     */
    public function addCotizacionesDetallesCotizacionRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesCotizacionRel)
    {
        $this->cotizacionesDetallesCotizacionRel[] = $cotizacionesDetallesCotizacionRel;

        return $this;
    }

    /**
     * Remove cotizacionesDetallesCotizacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesCotizacionRel
     */
    public function removeCotizacionesDetallesCotizacionRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesCotizacionRel)
    {
        $this->cotizacionesDetallesCotizacionRel->removeElement($cotizacionesDetallesCotizacionRel);
    }

    /**
     * Get cotizacionesDetallesCotizacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesDetallesCotizacionRel()
    {
        return $this->cotizacionesDetallesCotizacionRel;
    }
}
