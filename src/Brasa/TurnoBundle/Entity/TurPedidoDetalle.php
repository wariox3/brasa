<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoDetalleRepository")
 */
class TurPedidoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoDetallePk;  
    
    /**
     * @ORM\Column(name="codigo_pedido_fk", type="integer")
     */    
    private $codigoPedidoFk;
    
    /**
     * @ORM\Column(name="codigo_turno_fk", type="string", length=5)
     */    
    private $codigoTurnoFk;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fecha_desde;     
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fecha_hasta;     
    
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
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;     
    
    /**     
     * @ORM\Column(name="lunes", type="boolean")
     */    
    private $lunes = 0;    
    
    /**     
     * @ORM\Column(name="martes", type="boolean")
     */    
    private $martes = 0;        
    
    /**     
     * @ORM\Column(name="miercoles", type="boolean")
     */    
    private $miercoles = 0;        
    
    /**     
     * @ORM\Column(name="jueves", type="boolean")
     */    
    private $jueves = 0;        
    
    /**     
     * @ORM\Column(name="viernes", type="boolean")
     */    
    private $viernes = 0;    
    
    /**     
     * @ORM\Column(name="sabado", type="boolean")
     */    
    private $sabado = 0;        
    
    /**     
     * @ORM\Column(name="domingo", type="boolean")
     */    
    private $domingo = 0;        
    
    /**     
     * @ORM\Column(name="festivo", type="boolean")
     */    
    private $festivo = 0;        
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPedido", inversedBy="pedidosDetallesPedidoRel")
     * @ORM\JoinColumn(name="codigo_pedido_fk", referencedColumnName="codigo_pedido_pk")
     */
    protected $pedidoRel;       

    /**
     * @ORM\ManyToOne(targetEntity="TurTurno", inversedBy="pedidosDetallesTurnoRel")
     * @ORM\JoinColumn(name="codigo_turno_fk", referencedColumnName="codigo_turno_pk")
     */
    protected $turnoRel;      
    
    /**
     * Get codigoPedidoDetallePk
     *
     * @return integer
     */
    public function getCodigoPedidoDetallePk()
    {
        return $this->codigoPedidoDetallePk;
    }

    /**
     * Set codigoPedidoFk
     *
     * @param integer $codigoPedidoFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoPedidoFk($codigoPedidoFk)
    {
        $this->codigoPedidoFk = $codigoPedidoFk;

        return $this;
    }

    /**
     * Get codigoPedidoFk
     *
     * @return integer
     */
    public function getCodigoPedidoFk()
    {
        return $this->codigoPedidoFk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return TurPedidoDetalle
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fecha_desde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fecha_desde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return TurPedidoDetalle
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fecha_hasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fecha_hasta;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurPedidoDetalle
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurPedidoDetalle
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
     * Set lunes
     *
     * @param boolean $lunes
     *
     * @return TurPedidoDetalle
     */
    public function setLunes($lunes)
    {
        $this->lunes = $lunes;

        return $this;
    }

    /**
     * Get lunes
     *
     * @return boolean
     */
    public function getLunes()
    {
        return $this->lunes;
    }

    /**
     * Set martes
     *
     * @param boolean $martes
     *
     * @return TurPedidoDetalle
     */
    public function setMartes($martes)
    {
        $this->martes = $martes;

        return $this;
    }

    /**
     * Get martes
     *
     * @return boolean
     */
    public function getMartes()
    {
        return $this->martes;
    }

    /**
     * Set miercoles
     *
     * @param boolean $miercoles
     *
     * @return TurPedidoDetalle
     */
    public function setMiercoles($miercoles)
    {
        $this->miercoles = $miercoles;

        return $this;
    }

    /**
     * Get miercoles
     *
     * @return boolean
     */
    public function getMiercoles()
    {
        return $this->miercoles;
    }

    /**
     * Set jueves
     *
     * @param boolean $jueves
     *
     * @return TurPedidoDetalle
     */
    public function setJueves($jueves)
    {
        $this->jueves = $jueves;

        return $this;
    }

    /**
     * Get jueves
     *
     * @return boolean
     */
    public function getJueves()
    {
        return $this->jueves;
    }

    /**
     * Set sabado
     *
     * @param boolean $sabado
     *
     * @return TurPedidoDetalle
     */
    public function setSabado($sabado)
    {
        $this->sabado = $sabado;

        return $this;
    }

    /**
     * Get sabado
     *
     * @return boolean
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * Set domingo
     *
     * @param boolean $domingo
     *
     * @return TurPedidoDetalle
     */
    public function setDomingo($domingo)
    {
        $this->domingo = $domingo;

        return $this;
    }

    /**
     * Get domingo
     *
     * @return boolean
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * Set festivo
     *
     * @param boolean $festivo
     *
     * @return TurPedidoDetalle
     */
    public function setFestivo($festivo)
    {
        $this->festivo = $festivo;

        return $this;
    }

    /**
     * Get festivo
     *
     * @return boolean
     */
    public function getFestivo()
    {
        return $this->festivo;
    }

    /**
     * Set pedidoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedido $pedidoRel
     *
     * @return TurPedidoDetalle
     */
    public function setPedidoRel(\Brasa\TurnoBundle\Entity\TurPedido $pedidoRel = null)
    {
        $this->pedidoRel = $pedidoRel;

        return $this;
    }

    /**
     * Get pedidoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPedido
     */
    public function getPedidoRel()
    {
        return $this->pedidoRel;
    }

    /**
     * Set viernes
     *
     * @param boolean $viernes
     *
     * @return TurPedidoDetalle
     */
    public function setViernes($viernes)
    {
        $this->viernes = $viernes;

        return $this;
    }

    /**
     * Get viernes
     *
     * @return boolean
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * Set codigoTurnoFk
     *
     * @param string $codigoTurnoFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoTurnoFk($codigoTurnoFk)
    {
        $this->codigoTurnoFk = $codigoTurnoFk;

        return $this;
    }

    /**
     * Get codigoTurnoFk
     *
     * @return string
     */
    public function getCodigoTurnoFk()
    {
        return $this->codigoTurnoFk;
    }

    /**
     * Set turnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurTurno $turnoRel
     *
     * @return TurPedidoDetalle
     */
    public function setTurnoRel(\Brasa\TurnoBundle\Entity\TurTurno $turnoRel = null)
    {
        $this->turnoRel = $turnoRel;

        return $this;
    }

    /**
     * Get turnoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurTurno
     */
    public function getTurnoRel()
    {
        return $this->turnoRel;
    }

    /**
     * Set horasDiurnas
     *
     * @param integer $horasDiurnas
     *
     * @return TurPedidoDetalle
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
     * @return TurPedidoDetalle
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
