<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_periodo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPeriodoRepository")
 */
class TurPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;    
      
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="periodoRel")
     */
    protected $pedidosDetallesPeriodoRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="periodoRel")
     */
    protected $serviciosDetallesPeriodoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurCotizacionDetalle", mappedBy="periodoRel")
     */
    protected $cotizacionesDetallesPeriodoRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDetallesPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPeriodoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoPk()
    {
        return $this->codigoPeriodoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurPeriodo
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurPeriodo
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
     * Add pedidosDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPeriodoRel
     *
     * @return TurPeriodo
     */
    public function addPedidosDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPeriodoRel)
    {
        $this->pedidosDetallesPeriodoRel[] = $pedidosDetallesPeriodoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPeriodoRel
     */
    public function removePedidosDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPeriodoRel)
    {
        $this->pedidosDetallesPeriodoRel->removeElement($pedidosDetallesPeriodoRel);
    }

    /**
     * Get pedidosDetallesPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesPeriodoRel()
    {
        return $this->pedidosDetallesPeriodoRel;
    }

    /**
     * Add cotizacionesDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesPeriodoRel
     *
     * @return TurPeriodo
     */
    public function addCotizacionesDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesPeriodoRel)
    {
        $this->cotizacionesDetallesPeriodoRel[] = $cotizacionesDetallesPeriodoRel;

        return $this;
    }

    /**
     * Remove cotizacionesDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesPeriodoRel
     */
    public function removeCotizacionesDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesPeriodoRel)
    {
        $this->cotizacionesDetallesPeriodoRel->removeElement($cotizacionesDetallesPeriodoRel);
    }

    /**
     * Get cotizacionesDetallesPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesDetallesPeriodoRel()
    {
        return $this->cotizacionesDetallesPeriodoRel;
    }

    /**
     * Add serviciosDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPeriodoRel
     *
     * @return TurPeriodo
     */
    public function addServiciosDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPeriodoRel)
    {
        $this->serviciosDetallesPeriodoRel[] = $serviciosDetallesPeriodoRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesPeriodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPeriodoRel
     */
    public function removeServiciosDetallesPeriodoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPeriodoRel)
    {
        $this->serviciosDetallesPeriodoRel->removeElement($serviciosDetallesPeriodoRel);
    }

    /**
     * Get serviciosDetallesPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesPeriodoRel()
    {
        return $this->serviciosDetallesPeriodoRel;
    }
}
