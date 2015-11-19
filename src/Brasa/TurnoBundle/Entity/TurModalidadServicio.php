<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_modalidad_servicio")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurModalidadServicioRepository")
 */
class TurModalidadServicio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_modalidad_servicio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoModalidadServicioPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;    

    /**
     * @ORM\Column(name="tipo", type="integer")
     */    
    private $tipo = 0;    

    /**
     * @ORM\Column(name="porcentaje", type="float")
     */    
    private $porcentaje = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="modalidadServicioRel")
     */
    protected $pedidosDetallesModalidadServicioRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurCotizacionDetalle", mappedBy="modalidadServicioRel")
     */
    protected $cotizacionesDetallesModalidadServicioRel; 
    
    /**
     * Get codigoModalidadServicioPk
     *
     * @return integer
     */
    public function getCodigoModalidadServicioPk()
    {
        return $this->codigoModalidadServicioPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurModalidadServicio
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
     * @return TurModalidadServicio
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
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return TurModalidadServicio
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set porcentaje
     *
     * @param float $porcentaje
     *
     * @return TurModalidadServicio
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return float
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDetallesModalidadServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pedidosDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesModalidadServicioRel
     *
     * @return TurModalidadServicio
     */
    public function addPedidosDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesModalidadServicioRel)
    {
        $this->pedidosDetallesModalidadServicioRel[] = $pedidosDetallesModalidadServicioRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesModalidadServicioRel
     */
    public function removePedidosDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesModalidadServicioRel)
    {
        $this->pedidosDetallesModalidadServicioRel->removeElement($pedidosDetallesModalidadServicioRel);
    }

    /**
     * Get pedidosDetallesModalidadServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesModalidadServicioRel()
    {
        return $this->pedidosDetallesModalidadServicioRel;
    }

    /**
     * Add cotizacionesDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesModalidadServicioRel
     *
     * @return TurModalidadServicio
     */
    public function addCotizacionesDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesModalidadServicioRel)
    {
        $this->cotizacionesDetallesModalidadServicioRel[] = $cotizacionesDetallesModalidadServicioRel;

        return $this;
    }

    /**
     * Remove cotizacionesDetallesModalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesModalidadServicioRel
     */
    public function removeCotizacionesDetallesModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurCotizacionDetalle $cotizacionesDetallesModalidadServicioRel)
    {
        $this->cotizacionesDetallesModalidadServicioRel->removeElement($cotizacionesDetallesModalidadServicioRel);
    }

    /**
     * Get cotizacionesDetallesModalidadServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesDetallesModalidadServicioRel()
    {
        return $this->cotizacionesDetallesModalidadServicioRel;
    }
}
