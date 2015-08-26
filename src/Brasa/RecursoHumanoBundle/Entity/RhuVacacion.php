<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_vacacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuVacacionRepository")
 */
class RhuVacacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_vacacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVacacionPk;                          
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;    
    
    /**
     * @ORM\Column(name="fecha", type="date")
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="fecha_desde_periodo", type="date")
     */    
    private $fechaDesdePeriodo;    
    
    /**
     * @ORM\Column(name="fecha_hasta_periodo", type="date")
     */    
    private $fechaHastaPeriodo;
    
    /**
     * @ORM\Column(name="fecha_desde_disfrute", type="date")
     */    
    private $fechaDesdeDisfrute;    
    
    /**
     * @ORM\Column(name="fecha_hasta_disfrute", type="date")
     */    
    private $fechaHastaDisfrute;
    
    /**
     * @ORM\Column(name="vr_salud", type="float")
     */
    private $vrSalud = 0;
    
    /**
     * @ORM\Column(name="vr_pension", type="float")
     */
    private $vrPension = 0;
    
    /**
     * @ORM\Column(name="vr_ibc", type="float")
     */
    private $vrIbc = 0;
    
    /**
     * @ORM\Column(name="vr_deduccion", type="float")
     */
    private $vrDeduccion = 0;
    
    /**
     * @ORM\Column(name="vr_vacacion", type="float")
     */
    private $vrVacacion = 0;
    
    /**
     * @ORM\Column(name="dias_vacaciones_pagadas", type="integer")
     */
    private $diasVacacionesPagadas = 0;
    
    /**
     * @ORM\Column(name="dias_vacaciones_disfrute", type="integer")
     */
    private $diasVacacionesDisfrute = 0;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;
    
    /**
     * @ORM\Column(name="estado_pagado", type="boolean")
     */
    private $estadoPagado = 0;
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="estado_disfrutadas", type="boolean")
     */
    private $estadoDisfrutadas = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="vacacionesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="vacacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;        
    
    /**
     * @ORM\OneToMany(targetEntity="RhuVacacionCredito", mappedBy="vacacionRel")
     */
    protected $VacacionesCreditosVacacionRel;
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->VacacionesCreditosVacacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoVacacionPk
     *
     * @return integer
     */
    public function getCodigoVacacionPk()
    {
        return $this->codigoVacacionPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuVacacion
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuVacacion
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
     * Set fechaDesdePeriodo
     *
     * @param \DateTime $fechaDesdePeriodo
     *
     * @return RhuVacacion
     */
    public function setFechaDesdePeriodo($fechaDesdePeriodo)
    {
        $this->fechaDesdePeriodo = $fechaDesdePeriodo;

        return $this;
    }

    /**
     * Get fechaDesdePeriodo
     *
     * @return \DateTime
     */
    public function getFechaDesdePeriodo()
    {
        return $this->fechaDesdePeriodo;
    }

    /**
     * Set fechaHastaPeriodo
     *
     * @param \DateTime $fechaHastaPeriodo
     *
     * @return RhuVacacion
     */
    public function setFechaHastaPeriodo($fechaHastaPeriodo)
    {
        $this->fechaHastaPeriodo = $fechaHastaPeriodo;

        return $this;
    }

    /**
     * Get fechaHastaPeriodo
     *
     * @return \DateTime
     */
    public function getFechaHastaPeriodo()
    {
        return $this->fechaHastaPeriodo;
    }

    /**
     * Set fechaDesdeDisfrute
     *
     * @param \DateTime $fechaDesdeDisfrute
     *
     * @return RhuVacacion
     */
    public function setFechaDesdeDisfrute($fechaDesdeDisfrute)
    {
        $this->fechaDesdeDisfrute = $fechaDesdeDisfrute;

        return $this;
    }

    /**
     * Get fechaDesdeDisfrute
     *
     * @return \DateTime
     */
    public function getFechaDesdeDisfrute()
    {
        return $this->fechaDesdeDisfrute;
    }

    /**
     * Set fechaHastaDisfrute
     *
     * @param \DateTime $fechaHastaDisfrute
     *
     * @return RhuVacacion
     */
    public function setFechaHastaDisfrute($fechaHastaDisfrute)
    {
        $this->fechaHastaDisfrute = $fechaHastaDisfrute;

        return $this;
    }

    /**
     * Get fechaHastaDisfrute
     *
     * @return \DateTime
     */
    public function getFechaHastaDisfrute()
    {
        return $this->fechaHastaDisfrute;
    }

    /**
     * Set vrSalud
     *
     * @param float $vrSalud
     *
     * @return RhuVacacion
     */
    public function setVrSalud($vrSalud)
    {
        $this->vrSalud = $vrSalud;

        return $this;
    }

    /**
     * Get vrSalud
     *
     * @return float
     */
    public function getVrSalud()
    {
        return $this->vrSalud;
    }

    /**
     * Set vrPension
     *
     * @param float $vrPension
     *
     * @return RhuVacacion
     */
    public function setVrPension($vrPension)
    {
        $this->vrPension = $vrPension;

        return $this;
    }

    /**
     * Get vrPension
     *
     * @return float
     */
    public function getVrPension()
    {
        return $this->vrPension;
    }

    /**
     * Set vrIbc
     *
     * @param float $vrIbc
     *
     * @return RhuVacacion
     */
    public function setVrIbc($vrIbc)
    {
        $this->vrIbc = $vrIbc;

        return $this;
    }

    /**
     * Get vrIbc
     *
     * @return float
     */
    public function getVrIbc()
    {
        return $this->vrIbc;
    }

    /**
     * Set vrDeduccion
     *
     * @param float $vrDeduccion
     *
     * @return RhuVacacion
     */
    public function setVrDeduccion($vrDeduccion)
    {
        $this->vrDeduccion = $vrDeduccion;

        return $this;
    }

    /**
     * Get vrDeduccion
     *
     * @return float
     */
    public function getVrDeduccion()
    {
        return $this->vrDeduccion;
    }

    /**
     * Set vrVacacion
     *
     * @param float $vrVacacion
     *
     * @return RhuVacacion
     */
    public function setVrVacacion($vrVacacion)
    {
        $this->vrVacacion = $vrVacacion;

        return $this;
    }

    /**
     * Get vrVacacion
     *
     * @return float
     */
    public function getVrVacacion()
    {
        return $this->vrVacacion;
    }

    /**
     * Set diasVacacionesPagadas
     *
     * @param integer $diasVacacionesPagadas
     *
     * @return RhuVacacion
     */
    public function setDiasVacacionesPagadas($diasVacacionesPagadas)
    {
        $this->diasVacacionesPagadas = $diasVacacionesPagadas;

        return $this;
    }

    /**
     * Get diasVacacionesPagadas
     *
     * @return integer
     */
    public function getDiasVacacionesPagadas()
    {
        return $this->diasVacacionesPagadas;
    }

    /**
     * Set diasVacacionesDisfrute
     *
     * @param integer $diasVacacionesDisfrute
     *
     * @return RhuVacacion
     */
    public function setDiasVacacionesDisfrute($diasVacacionesDisfrute)
    {
        $this->diasVacacionesDisfrute = $diasVacacionesDisfrute;

        return $this;
    }

    /**
     * Get diasVacacionesDisfrute
     *
     * @return integer
     */
    public function getDiasVacacionesDisfrute()
    {
        return $this->diasVacacionesDisfrute;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuVacacion
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
     * Set estadoPagado
     *
     * @param boolean $estadoPagado
     *
     * @return RhuVacacion
     */
    public function setEstadoPagado($estadoPagado)
    {
        $this->estadoPagado = $estadoPagado;

        return $this;
    }

    /**
     * Get estadoPagado
     *
     * @return boolean
     */
    public function getEstadoPagado()
    {
        return $this->estadoPagado;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuVacacion
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set estadoDisfrutadas
     *
     * @param boolean $estadoDisfrutadas
     *
     * @return RhuVacacion
     */
    public function setEstadoDisfrutadas($estadoDisfrutadas)
    {
        $this->estadoDisfrutadas = $estadoDisfrutadas;

        return $this;
    }

    /**
     * Get estadoDisfrutadas
     *
     * @return boolean
     */
    public function getEstadoDisfrutadas()
    {
        return $this->estadoDisfrutadas;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuVacacion
     */
    public function setCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuVacacion
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Add vacacionesCreditosVacacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito $vacacionesCreditosVacacionRel
     *
     * @return RhuVacacion
     */
    public function addVacacionesCreditosVacacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito $vacacionesCreditosVacacionRel)
    {
        $this->VacacionesCreditosVacacionRel[] = $vacacionesCreditosVacacionRel;

        return $this;
    }

    /**
     * Remove vacacionesCreditosVacacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito $vacacionesCreditosVacacionRel
     */
    public function removeVacacionesCreditosVacacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito $vacacionesCreditosVacacionRel)
    {
        $this->VacacionesCreditosVacacionRel->removeElement($vacacionesCreditosVacacionRel);
    }

    /**
     * Get vacacionesCreditosVacacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVacacionesCreditosVacacionRel()
    {
        return $this->VacacionesCreditosVacacionRel;
    }
}
