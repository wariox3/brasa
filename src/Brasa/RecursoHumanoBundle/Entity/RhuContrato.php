<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contrato")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContratoRepository")
 */
class RhuContrato
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoPk;
    
    /**
     * @ORM\Column(name="codigo_tipo_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoTipoContratoFk;    
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;    

    /**
     * @ORM\Column(name="codigo_periodo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPeriodoPagoFk;    
    
    /**
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */    
    private $fecha_inicio; 
    
    /**
     * @ORM\Column(name="fecha_terminacion", type="date", nullable=true)
     */    
    private $fecha_terminacion;     
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;    

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="contratosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel; 

    /**
     * @ORM\ManyToOne(targetEntity="RhuPeriodoPago", inversedBy="contratosPeriodoPagoRel")
     * @ORM\JoinColumn(name="codigo_periodo_pago_fk", referencedColumnName="codigo_periodo_pago_pk")
     */
    protected $periodoPagoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContratoDetalle", mappedBy="contratoRel")
     */
    protected $contratosDetalleRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoContratoPk
     *
     * @return integer
     */
    public function getCodigoContratoPk()
    {
        return $this->codigoContratoPk;
    }

    /**
     * Set codigoTipoContratoFk
     *
     * @param integer $codigoTipoContratoFk
     *
     * @return RhuContrato
     */
    public function setCodigoTipoContratoFk($codigoTipoContratoFk)
    {
        $this->codigoTipoContratoFk = $codigoTipoContratoFk;

        return $this;
    }

    /**
     * Get codigoTipoContratoFk
     *
     * @return integer
     */
    public function getCodigoTipoContratoFk()
    {
        return $this->codigoTipoContratoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuContrato
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
     * Set codigoPeriodoPagoFk
     *
     * @param integer $codigoPeriodoPagoFk
     *
     * @return RhuContrato
     */
    public function setCodigoPeriodoPagoFk($codigoPeriodoPagoFk)
    {
        $this->codigoPeriodoPagoFk = $codigoPeriodoPagoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoPagoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoPagoFk()
    {
        return $this->codigoPeriodoPagoFk;
    }

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return RhuContrato
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fecha_inicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fecha_inicio;
    }

    /**
     * Set fechaTerminacion
     *
     * @param \DateTime $fechaTerminacion
     *
     * @return RhuContrato
     */
    public function setFechaTerminacion($fechaTerminacion)
    {
        $this->fecha_terminacion = $fechaTerminacion;

        return $this;
    }

    /**
     * Get fechaTerminacion
     *
     * @return \DateTime
     */
    public function getFechaTerminacion()
    {
        return $this->fecha_terminacion;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuContrato
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
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuContrato
     */
    public function setCodigoCargoFk($codigoCargoFk)
    {
        $this->codigoCargoFk = $codigoCargoFk;

        return $this;
    }

    /**
     * Get codigoCargoFk
     *
     * @return integer
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuContrato
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
     * Set periodoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPeriodoPago $periodoPagoRel
     *
     * @return RhuContrato
     */
    public function setPeriodoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPeriodoPago $periodoPagoRel = null)
    {
        $this->periodoPagoRel = $periodoPagoRel;

        return $this;
    }

    /**
     * Get periodoPagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPeriodoPago
     */
    public function getPeriodoPagoRel()
    {
        return $this->periodoPagoRel;
    }

    /**
     * Add contratosDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoDetalle $contratosDetalleRel
     *
     * @return RhuContrato
     */
    public function addContratosDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoDetalle $contratosDetalleRel)
    {
        $this->contratosDetalleRel[] = $contratosDetalleRel;

        return $this;
    }

    /**
     * Remove contratosDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoDetalle $contratosDetalleRel
     */
    public function removeContratosDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoDetalle $contratosDetalleRel)
    {
        $this->contratosDetalleRel->removeElement($contratosDetalleRel);
    }

    /**
     * Get contratosDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosDetalleRel()
    {
        return $this->contratosDetalleRel;
    }
}
