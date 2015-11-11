<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoRepository")
 */
class TurPedido
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoPk;    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;     
    
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
    private $horasNoturnas = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="pedidoRel", cascade={"persist", "remove"})
     */
    protected $pedidosDetallesPedidoRel; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDetallesPedidoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPedidoPk
     *
     * @return integer
     */
    public function getCodigoPedidoPk()
    {
        return $this->codigoPedidoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurPedido
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
     * @return TurPedido
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
     * @return TurPedido
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
     * @return TurPedido
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
     * Add pedidosDetallesPedidoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPedidoRel
     *
     * @return TurPedido
     */
    public function addPedidosDetallesPedidoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPedidoRel)
    {
        $this->pedidosDetallesPedidoRel[] = $pedidosDetallesPedidoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesPedidoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPedidoRel
     */
    public function removePedidosDetallesPedidoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPedidoRel)
    {
        $this->pedidosDetallesPedidoRel->removeElement($pedidosDetallesPedidoRel);
    }

    /**
     * Get pedidosDetallesPedidoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesPedidoRel()
    {
        return $this->pedidosDetallesPedidoRel;
    }

    /**
     * Set horasDiurnas
     *
     * @param integer $horasDiurnas
     *
     * @return TurPedido
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
     * Set horasNoturnas
     *
     * @param integer $horasNoturnas
     *
     * @return TurPedido
     */
    public function setHorasNoturnas($horasNoturnas)
    {
        $this->horasNoturnas = $horasNoturnas;

        return $this;
    }

    /**
     * Get horasNoturnas
     *
     * @return integer
     */
    public function getHorasNoturnas()
    {
        return $this->horasNoturnas;
    }
}
