<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_programacion_pago_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuProgramacionPagoDetalleRepository")
 */
class RhuProgramacionPagoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pago_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPagoDetallePk;
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoFk;   
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;           

    /**
     * @ORM\Column(name="horas_periodo", type="integer")
     */
    private $horasPeriodo = 0;     
    
    /**
     * Para el auxilio de transporte
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0; 

    /**
     * Para el auxilio de transporte
     * @ORM\Column(name="factor_dia", type="integer")
     */
    private $factor_dia = 0;    
    
    /**
     * @ORM\Column(name="horas_periodo_reales", type="integer")
     */
    private $horasPeriodoReales = 0;    
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    
    
    /**     
     * @ORM\Column(name="indefinido", type="boolean")
     */    
    private $indefinido = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPago", inversedBy="programacionesPagosDetallesProgramacionPagoRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_fk", referencedColumnName="codigo_programacion_pago_pk")
     */
    protected $programacionPagoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="programacionesPagosDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;        
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPagoDetalleSede", mappedBy="programacionPagoDetalleRel")
     */
    protected $programacionesPagosDetallesSedesProgramacionPagoDetalleRel; 

    /**
     * Get codigoProgramacionPagoDetallePk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoDetallePk()
    {
        return $this->codigoProgramacionPagoDetallePk;
    }

    /**
     * Set codigoProgramacionPagoFk
     *
     * @param integer $codigoProgramacionPagoFk
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setCodigoProgramacionPagoFk($codigoProgramacionPagoFk)
    {
        $this->codigoProgramacionPagoFk = $codigoProgramacionPagoFk;

        return $this;
    }

    /**
     * Get codigoProgramacionPagoFk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoFk()
    {
        return $this->codigoProgramacionPagoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuProgramacionPagoDetalle
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
     * Set horasPeriodo
     *
     * @param integer $horasPeriodo
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasPeriodo($horasPeriodo)
    {
        $this->horasPeriodo = $horasPeriodo;

        return $this;
    }

    /**
     * Get horasPeriodo
     *
     * @return integer
     */
    public function getHorasPeriodo()
    {
        return $this->horasPeriodo;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setVrSalario($vrSalario)
    {
        $this->vrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * Set programacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel = null)
    {
        $this->programacionPagoRel = $programacionPagoRel;

        return $this;
    }

    /**
     * Get programacionPagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago
     */
    public function getProgramacionPagoRel()
    {
        return $this->programacionPagoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuProgramacionPagoDetalle
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
     * Set horasPeriodoReales
     *
     * @param integer $horasPeriodoReales
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setHorasPeriodoReales($horasPeriodoReales)
    {
        $this->horasPeriodoReales = $horasPeriodoReales;

        return $this;
    }

    /**
     * Get horasPeriodoReales
     *
     * @return integer
     */
    public function getHorasPeriodoReales()
    {
        return $this->horasPeriodoReales;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuProgramacionPagoDetalle
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
     * Set factorDia
     *
     * @param integer $factorDia
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setFactorDia($factorDia)
    {
        $this->factor_dia = $factorDia;

        return $this;
    }

    /**
     * Get factorDia
     *
     * @return integer
     */
    public function getFactorDia()
    {
        return $this->factor_dia;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set indefinido
     *
     * @param boolean $indefinido
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function setIndefinido($indefinido)
    {
        $this->indefinido = $indefinido;

        return $this;
    }

    /**
     * Get indefinido
     *
     * @return boolean
     */
    public function getIndefinido()
    {
        return $this->indefinido;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesPagosDetallesSedesProgramacionPagoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add programacionesPagosDetallesSedesProgramacionPagoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedesProgramacionPagoDetalleRel
     *
     * @return RhuProgramacionPagoDetalle
     */
    public function addProgramacionesPagosDetallesSedesProgramacionPagoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedesProgramacionPagoDetalleRel)
    {
        $this->programacionesPagosDetallesSedesProgramacionPagoDetalleRel[] = $programacionesPagosDetallesSedesProgramacionPagoDetalleRel;

        return $this;
    }

    /**
     * Remove programacionesPagosDetallesSedesProgramacionPagoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedesProgramacionPagoDetalleRel
     */
    public function removeProgramacionesPagosDetallesSedesProgramacionPagoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedesProgramacionPagoDetalleRel)
    {
        $this->programacionesPagosDetallesSedesProgramacionPagoDetalleRel->removeElement($programacionesPagosDetallesSedesProgramacionPagoDetalleRel);
    }

    /**
     * Get programacionesPagosDetallesSedesProgramacionPagoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosDetallesSedesProgramacionPagoDetalleRel()
    {
        return $this->programacionesPagosDetallesSedesProgramacionPagoDetalleRel;
    }
}
