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
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=false)
     */    
    private $codigoContratoFk;    
    
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
     * @ORM\Column(name="fecha_desde_pago", type="date")
     */    
    private $fechaDesdePago;    
    
    /**
     * @ORM\Column(name="fecha_hasta_pago", type="date")
     */    
    private $fechaHastaPago;    
    
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
     * @ORM\Column(name="dias_vacaciones", type="integer")
     */
    private $diasVacaciones = 0;   
    
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
     * @ORM\Column(name="vr_salario_actual", type="float")
     */
    private $vrSalarioActual = 0;     

    /**
     * @ORM\Column(name="vr_salario_promedio", type="float")
     */
    private $vrSalarioPromedio = 0;         

    /**
     * @ORM\Column(name="vr_vacacion_bruto", type="float")
     */
    private $vrVacacionBruto = 0;    
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;    
    
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
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="vacacionesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;    
    
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
     * Set diasVacaciones
     *
     * @param integer $diasVacaciones
     *
     * @return RhuVacacion
     */
    public function setDiasVacaciones($diasVacaciones)
    {
        $this->diasVacaciones = $diasVacaciones;

        return $this;
    }

    /**
     * Get diasVacaciones
     *
     * @return integer
     */
    public function getDiasVacaciones()
    {
        return $this->diasVacaciones;
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

    /**
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuVacacion
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuVacacion
     */
    public function setContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * Set vrSalarioActual
     *
     * @param float $vrSalarioActual
     *
     * @return RhuVacacion
     */
    public function setVrSalarioActual($vrSalarioActual)
    {
        $this->vrSalarioActual = $vrSalarioActual;

        return $this;
    }

    /**
     * Get vrSalarioActual
     *
     * @return float
     */
    public function getVrSalarioActual()
    {
        return $this->vrSalarioActual;
    }

    /**
     * Set vrSalarioPromedio
     *
     * @param float $vrSalarioPromedio
     *
     * @return RhuVacacion
     */
    public function setVrSalarioPromedio($vrSalarioPromedio)
    {
        $this->vrSalarioPromedio = $vrSalarioPromedio;

        return $this;
    }

    /**
     * Get vrSalarioPromedio
     *
     * @return float
     */
    public function getVrSalarioPromedio()
    {
        return $this->vrSalarioPromedio;
    }

    /**
     * Set vrVacacionBruto
     *
     * @param float $vrVacacionBruto
     *
     * @return RhuVacacion
     */
    public function setVrVacacionBruto($vrVacacionBruto)
    {
        $this->vrVacacionBruto = $vrVacacionBruto;

        return $this;
    }

    /**
     * Get vrVacacionBruto
     *
     * @return float
     */
    public function getVrVacacionBruto()
    {
        return $this->vrVacacionBruto;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuVacacion
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
     * Set fechaDesdePago
     *
     * @param \DateTime $fechaDesdePago
     *
     * @return RhuVacacion
     */
    public function setFechaDesdePago($fechaDesdePago)
    {
        $this->fechaDesdePago = $fechaDesdePago;

        return $this;
    }

    /**
     * Get fechaDesdePago
     *
     * @return \DateTime
     */
    public function getFechaDesdePago()
    {
        return $this->fechaDesdePago;
    }

    /**
     * Set fechaHastaPago
     *
     * @param \DateTime $fechaHastaPago
     *
     * @return RhuVacacion
     */
    public function setFechaHastaPago($fechaHastaPago)
    {
        $this->fechaHastaPago = $fechaHastaPago;

        return $this;
    }

    /**
     * Get fechaHastaPago
     *
     * @return \DateTime
     */
    public function getFechaHastaPago()
    {
        return $this->fechaHastaPago;
    }
}
