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
     * @ORM\Column(name="codigo_cliente_fk", type="integer")
     */    
    private $codigoClienteFk;    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;     
    
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
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="cotizacionesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurCotizacion
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
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
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurCotizacion
     */
    public function setClienteRel(\Brasa\TurnoBundle\Entity\TurCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
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
