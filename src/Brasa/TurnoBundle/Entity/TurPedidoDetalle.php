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
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;    
    
    /**
     * @ORM\Column(name="codigo_turno_fk", type="string", length=5)
     */    
    private $codigoTurnoFk;    
    
    /**
     * @ORM\Column(name="codigo_modalidad_servicio_fk", type="integer")
     */    
    private $codigoModalidadServicioFk;    
    
    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;     
    
    /**
     * @ORM\Column(name="codigo_plantilla_fk", type="integer", nullable=true)
     */    
    private $codigoPlantillaFk;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fecha_desde;     
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fecha_hasta;     
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */    
    private $dias = 0; 
    
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
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;     
    
    /**
     * @ORM\Column(name="cantidad_recurso", type="integer")
     */    
    private $cantidadRecurso = 0;         
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0; 
    
    /**     
     * @ORM\Column(name="lunes", type="boolean")
     */    
    private $lunes = false;    
    
    /**     
     * @ORM\Column(name="martes", type="boolean")
     */    
    private $martes = false;        
    
    /**     
     * @ORM\Column(name="miercoles", type="boolean")
     */    
    private $miercoles = false;        
    
    /**     
     * @ORM\Column(name="jueves", type="boolean")
     */    
    private $jueves = false;        
    
    /**     
     * @ORM\Column(name="viernes", type="boolean")
     */    
    private $viernes = false;    
    
    /**     
     * @ORM\Column(name="sabado", type="boolean")
     */    
    private $sabado = false;        
    
    /**     
     * @ORM\Column(name="domingo", type="boolean")
     */    
    private $domingo = false;        
    
    /**     
     * @ORM\Column(name="festivo", type="boolean")
     */    
    private $festivo = false;        
    
    /**     
     * @ORM\Column(name="dia_31", type="boolean")
     */    
    private $dia31 = false;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPedido", inversedBy="pedidosDetallesPedidoRel")
     * @ORM\JoinColumn(name="codigo_pedido_fk", referencedColumnName="codigo_pedido_pk")
     */
    protected $pedidoRel;       

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="pedidosDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurTurno", inversedBy="pedidosDetallesTurnoRel")
     * @ORM\JoinColumn(name="codigo_turno_fk", referencedColumnName="codigo_turno_pk")
     */
    protected $turnoRel;      

    /**
     * @ORM\ManyToOne(targetEntity="TurModalidadServicio", inversedBy="pedidosDetallesModalidadServicioRel")
     * @ORM\JoinColumn(name="codigo_modalidad_servicio_fk", referencedColumnName="codigo_modalidad_servicio_pk")
     */
    protected $modalidadServicioRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPeriodo", inversedBy="pedidosDetallesPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $periodoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPlantilla", inversedBy="pedidosDetallesPlantillaRel")
     * @ORM\JoinColumn(name="codigo_plantilla_fk", referencedColumnName="codigo_plantilla_pk")
     */
    protected $plantillaRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleRecurso", mappedBy="pedidoDetalleRel", cascade={"persist", "remove"})
     */
    protected $pedidosDetallesRecursosPedidoDetalleRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $programacionesDetallesPedidoDetalleRel; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesDetallesPedidoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoPuestoFk($codigoPuestoFk)
    {
        $this->codigoPuestoFk = $codigoPuestoFk;

        return $this;
    }

    /**
     * Get codigoPuestoFk
     *
     * @return integer
     */
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
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
     * Set codigoModalidadServicioFk
     *
     * @param integer $codigoModalidadServicioFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoModalidadServicioFk($codigoModalidadServicioFk)
    {
        $this->codigoModalidadServicioFk = $codigoModalidadServicioFk;

        return $this;
    }

    /**
     * Get codigoModalidadServicioFk
     *
     * @return integer
     */
    public function getCodigoModalidadServicioFk()
    {
        return $this->codigoModalidadServicioFk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoPeriodoFk($codigoPeriodoFk)
    {
        $this->codigoPeriodoFk = $codigoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoFk()
    {
        return $this->codigoPeriodoFk;
    }

    /**
     * Set codigoPlantillaFk
     *
     * @param integer $codigoPlantillaFk
     *
     * @return TurPedidoDetalle
     */
    public function setCodigoPlantillaFk($codigoPlantillaFk)
    {
        $this->codigoPlantillaFk = $codigoPlantillaFk;

        return $this;
    }

    /**
     * Get codigoPlantillaFk
     *
     * @return integer
     */
    public function getCodigoPlantillaFk()
    {
        return $this->codigoPlantillaFk;
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
     * Set dias
     *
     * @param integer $dias
     *
     * @return TurPedidoDetalle
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer
     */
    public function getDias()
    {
        return $this->dias;
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
     * Set horasNocturnas
     *
     * @param integer $horasNocturnas
     *
     * @return TurPedidoDetalle
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
     * Set cantidadRecurso
     *
     * @param integer $cantidadRecurso
     *
     * @return TurPedidoDetalle
     */
    public function setCantidadRecurso($cantidadRecurso)
    {
        $this->cantidadRecurso = $cantidadRecurso;

        return $this;
    }

    /**
     * Get cantidadRecurso
     *
     * @return integer
     */
    public function getCantidadRecurso()
    {
        return $this->cantidadRecurso;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return TurPedidoDetalle
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
     * Set dia31
     *
     * @param boolean $dia31
     *
     * @return TurPedidoDetalle
     */
    public function setDia31($dia31)
    {
        $this->dia31 = $dia31;

        return $this;
    }

    /**
     * Get dia31
     *
     * @return boolean
     */
    public function getDia31()
    {
        return $this->dia31;
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
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return TurPedidoDetalle
     */
    public function setPuestoRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestoRel = null)
    {
        $this->puestoRel = $puestoRel;

        return $this;
    }

    /**
     * Get puestoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPuesto
     */
    public function getPuestoRel()
    {
        return $this->puestoRel;
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
     * Set modalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurModalidadServicio $modalidadServicioRel
     *
     * @return TurPedidoDetalle
     */
    public function setModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurModalidadServicio $modalidadServicioRel = null)
    {
        $this->modalidadServicioRel = $modalidadServicioRel;

        return $this;
    }

    /**
     * Get modalidadServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurModalidadServicio
     */
    public function getModalidadServicioRel()
    {
        return $this->modalidadServicioRel;
    }

    /**
     * Set periodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPeriodo $periodoRel
     *
     * @return TurPedidoDetalle
     */
    public function setPeriodoRel(\Brasa\TurnoBundle\Entity\TurPeriodo $periodoRel = null)
    {
        $this->periodoRel = $periodoRel;

        return $this;
    }

    /**
     * Get periodoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPeriodo
     */
    public function getPeriodoRel()
    {
        return $this->periodoRel;
    }

    /**
     * Set plantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPlantilla $plantillaRel
     *
     * @return TurPedidoDetalle
     */
    public function setPlantillaRel(\Brasa\TurnoBundle\Entity\TurPlantilla $plantillaRel = null)
    {
        $this->plantillaRel = $plantillaRel;

        return $this;
    }

    /**
     * Get plantillaRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPlantilla
     */
    public function getPlantillaRel()
    {
        return $this->plantillaRel;
    }

    /**
     * Add programacionesDetallesPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPedidoDetalleRel
     *
     * @return TurPedidoDetalle
     */
    public function addProgramacionesDetallesPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPedidoDetalleRel)
    {
        $this->programacionesDetallesPedidoDetalleRel[] = $programacionesDetallesPedidoDetalleRel;

        return $this;
    }

    /**
     * Remove programacionesDetallesPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPedidoDetalleRel
     */
    public function removeProgramacionesDetallesPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPedidoDetalleRel)
    {
        $this->programacionesDetallesPedidoDetalleRel->removeElement($programacionesDetallesPedidoDetalleRel);
    }

    /**
     * Get programacionesDetallesPedidoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesDetallesPedidoDetalleRel()
    {
        return $this->programacionesDetallesPedidoDetalleRel;
    }

    /**
     * Add pedidosDetallesRecursosPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosPedidoDetalleRel
     *
     * @return TurPedidoDetalle
     */
    public function addPedidosDetallesRecursosPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosPedidoDetalleRel)
    {
        $this->pedidosDetallesRecursosPedidoDetalleRel[] = $pedidosDetallesRecursosPedidoDetalleRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesRecursosPedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosPedidoDetalleRel
     */
    public function removePedidosDetallesRecursosPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosPedidoDetalleRel)
    {
        $this->pedidosDetallesRecursosPedidoDetalleRel->removeElement($pedidosDetallesRecursosPedidoDetalleRel);
    }

    /**
     * Get pedidosDetallesRecursosPedidoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesRecursosPedidoDetalleRel()
    {
        return $this->pedidosDetallesRecursosPedidoDetalleRel;
    }
}
