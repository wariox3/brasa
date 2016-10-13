<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_proyeccion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuProyeccionRepository")
 */
class RhuProyeccion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_proyeccion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProyeccionPk;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;     
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;      

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoContratoFk;        

    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;     

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;     
    
    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $vrVacaciones = 0;         
    
    /**
     * @ORM\Column(name="dias_prima", type="integer")
     */
    private $diasPrima = 0;    
    
    /**
     * @ORM\Column(name="fecha_desde_prima", type="date", nullable=true)
     */    
    private $fechaDesdePrima;    
    
    /**
     * @ORM\Column(name="vr_primas", type="float")
     */
    private $vrPrimas = 0;     
    
    /**
     * @ORM\Column(name="dias_cesantias", type="integer")
     */
    private $diasCesantias = 0;    
    
    /**
     * @ORM\Column(name="fecha_desde_cesantias", type="date", nullable=true)
     */    
    private $fechaDesdeCesantias;    
    
    /**
     * @ORM\Column(name="vr_cesantias", type="float")
     */
    private $vrCesantias = 0;         
    
    /**
     * @ORM\Column(name="vr_intereses_cesantias", type="float")
     */
    private $vrInteresesCesantias = 0;         
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="proyeccionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="proyeccionesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;     



    /**
     * Get codigoProyeccionPk
     *
     * @return integer
     */
    public function getCodigoProyeccionPk()
    {
        return $this->codigoProyeccionPk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuProyeccion
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
     * @return RhuProyeccion
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuProyeccion
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
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuProyeccion
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
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuProyeccion
     */
    public function setVrVacaciones($vrVacaciones)
    {
        $this->vrVacaciones = $vrVacaciones;

        return $this;
    }

    /**
     * Get vrVacaciones
     *
     * @return float
     */
    public function getVrVacaciones()
    {
        return $this->vrVacaciones;
    }

    /**
     * Set vrPrimas
     *
     * @param float $vrPrimas
     *
     * @return RhuProyeccion
     */
    public function setVrPrimas($vrPrimas)
    {
        $this->vrPrimas = $vrPrimas;

        return $this;
    }

    /**
     * Get vrPrimas
     *
     * @return float
     */
    public function getVrPrimas()
    {
        return $this->vrPrimas;
    }

    /**
     * Set vrCesantias
     *
     * @param float $vrCesantias
     *
     * @return RhuProyeccion
     */
    public function setVrCesantias($vrCesantias)
    {
        $this->vrCesantias = $vrCesantias;

        return $this;
    }

    /**
     * Get vrCesantias
     *
     * @return float
     */
    public function getVrCesantias()
    {
        return $this->vrCesantias;
    }

    /**
     * Set vrInteresesCesantias
     *
     * @param float $vrInteresesCesantias
     *
     * @return RhuProyeccion
     */
    public function setVrInteresesCesantias($vrInteresesCesantias)
    {
        $this->vrInteresesCesantias = $vrInteresesCesantias;

        return $this;
    }

    /**
     * Get vrInteresesCesantias
     *
     * @return float
     */
    public function getVrInteresesCesantias()
    {
        return $this->vrInteresesCesantias;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuProyeccion
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
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuProyeccion
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
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuProyeccion
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuProyeccion
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
     * Set diasPrima
     *
     * @param integer $diasPrima
     *
     * @return RhuProyeccion
     */
    public function setDiasPrima($diasPrima)
    {
        $this->diasPrima = $diasPrima;

        return $this;
    }

    /**
     * Get diasPrima
     *
     * @return integer
     */
    public function getDiasPrima()
    {
        return $this->diasPrima;
    }

    /**
     * Set fechaDesdePrima
     *
     * @param \DateTime $fechaDesdePrima
     *
     * @return RhuProyeccion
     */
    public function setFechaDesdePrima($fechaDesdePrima)
    {
        $this->fechaDesdePrima = $fechaDesdePrima;

        return $this;
    }

    /**
     * Get fechaDesdePrima
     *
     * @return \DateTime
     */
    public function getFechaDesdePrima()
    {
        return $this->fechaDesdePrima;
    }

    /**
     * Set diasCesantias
     *
     * @param integer $diasCesantias
     *
     * @return RhuProyeccion
     */
    public function setDiasCesantias($diasCesantias)
    {
        $this->diasCesantias = $diasCesantias;

        return $this;
    }

    /**
     * Get diasCesantias
     *
     * @return integer
     */
    public function getDiasCesantias()
    {
        return $this->diasCesantias;
    }

    /**
     * Set fechaDesdeCesantias
     *
     * @param \DateTime $fechaDesdeCesantias
     *
     * @return RhuProyeccion
     */
    public function setFechaDesdeCesantias($fechaDesdeCesantias)
    {
        $this->fechaDesdeCesantias = $fechaDesdeCesantias;

        return $this;
    }

    /**
     * Get fechaDesdeCesantias
     *
     * @return \DateTime
     */
    public function getFechaDesdeCesantias()
    {
        return $this->fechaDesdeCesantias;
    }
}
