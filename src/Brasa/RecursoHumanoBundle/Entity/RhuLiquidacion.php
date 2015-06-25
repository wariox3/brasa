<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_liquidacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuLiquidacionRepository")
 */
class RhuLiquidacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLiquidacionPk;            

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;               
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer")
     */    
    private $codigoCentroCostoFk;         
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta; 
    
    /**
     * @ORM\Column(name="numero_dias", type="string", length=30, nullable=true)
     */    
    private $numeroDias;                 
    
    /**
     * @ORM\Column(name="vr_cesantias", type="float")
     */
    private $VrCesantias = 0;    

    /**
     * @ORM\Column(name="vr_intereses_cesantias", type="float")
     */
    private $VrInteresesCesantias = 0;        

    /**
     * @ORM\Column(name="vr_prima", type="float")
     */
    private $VrPrima = 0;    

    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $VrVacaciones = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;        
    
    /**
     * @ORM\Column(name="dias_cesantias", type="integer")
     */    
    private $diasCesantias = 0;    

    /**
     * @ORM\Column(name="dias_vacaciones", type="integer")
     */    
    private $diasVacaciones = 0;        
    
    /**
     * @ORM\Column(name="dias_primas", type="integer")
     */    
    private $diasPrimas = 0;        

    /**
     * @ORM\Column(name="dias_laborados", type="integer")
     */    
    private $diasLaborados = 0;    
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago", type="date", nullable=true)
     */    
    private $fechaUltimoPago;    

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $VrIngresoBaseCotizacion = 0;     
    
    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion_adicional", type="float")
     */
    private $VrIngresoBaseCotizacionAdicional = 0;    

    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion_total", type="float")
     */
    private $VrIngresoBaseCotizacionTotal = 0;     
    
    /**
     * @ORM\Column(name="dias_adicionales_ibc", type="integer")
     */    
    private $diasAdicionalesIBC = 0;            
    
    /**
     * @ORM\Column(name="vr_base_prestaciones", type="float")
     */
    private $VrBasePrestaciones = 0;    

    /**
     * @ORM\Column(name="vr_base_prestaciones_total", type="float")
     */
    private $VrBasePrestacionesTotal = 0;    
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */
    private $VrAuxilioTransporte = 0;    
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;     
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $VrTotal = 0;    
    
    /**     
     * @ORM\Column(name="liquidar_cesantias", type="boolean")
     */    
    private $liquidarCesantias = 0;

    /**     
     * @ORM\Column(name="liquidar_vacaciones", type="boolean")
     */    
    private $liquidarVacaciones = 0;    

    /**     
     * @ORM\Column(name="liquidar_prima", type="boolean")
     */    
    private $liquidarPrima = 0;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="liquidacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="liquidacionesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="liquidacionesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacionDeduccion", mappedBy="liquidacionRel")
     */
    protected $liquidacionesDeduccionesLiquidacionRel;  


    /**
     * Get codigoLiquidacionPk
     *
     * @return integer
     */
    public function getCodigoLiquidacionPk()
    {
        return $this->codigoLiquidacionPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuLiquidacion
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuLiquidacion
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
     * @return RhuLiquidacion
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuLiquidacion
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuLiquidacion
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
     * @return RhuLiquidacion
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
     * Set numeroDias
     *
     * @param string $numeroDias
     *
     * @return RhuLiquidacion
     */
    public function setNumeroDias($numeroDias)
    {
        $this->numeroDias = $numeroDias;

        return $this;
    }

    /**
     * Get numeroDias
     *
     * @return string
     */
    public function getNumeroDias()
    {
        return $this->numeroDias;
    }

    /**
     * Set vrCesantias
     *
     * @param float $vrCesantias
     *
     * @return RhuLiquidacion
     */
    public function setVrCesantias($vrCesantias)
    {
        $this->VrCesantias = $vrCesantias;

        return $this;
    }

    /**
     * Get vrCesantias
     *
     * @return float
     */
    public function getVrCesantias()
    {
        return $this->VrCesantias;
    }

    /**
     * Set vrInteresesCesantias
     *
     * @param float $vrInteresesCesantias
     *
     * @return RhuLiquidacion
     */
    public function setVrInteresesCesantias($vrInteresesCesantias)
    {
        $this->VrInteresesCesantias = $vrInteresesCesantias;

        return $this;
    }

    /**
     * Get vrInteresesCesantias
     *
     * @return float
     */
    public function getVrInteresesCesantias()
    {
        return $this->VrInteresesCesantias;
    }

    /**
     * Set vrPrima
     *
     * @param float $vrPrima
     *
     * @return RhuLiquidacion
     */
    public function setVrPrima($vrPrima)
    {
        $this->VrPrima = $vrPrima;

        return $this;
    }

    /**
     * Get vrPrima
     *
     * @return float
     */
    public function getVrPrima()
    {
        return $this->VrPrima;
    }

    /**
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuLiquidacion
     */
    public function setVrVacaciones($vrVacaciones)
    {
        $this->VrVacaciones = $vrVacaciones;

        return $this;
    }

    /**
     * Get vrVacaciones
     *
     * @return float
     */
    public function getVrVacaciones()
    {
        return $this->VrVacaciones;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuLiquidacion
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
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuLiquidacion
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
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuLiquidacion
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
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuLiquidacion
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
     * Set diasCesantias
     *
     * @param integer $diasCesantias
     *
     * @return RhuLiquidacion
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
     * Set diasVacaciones
     *
     * @param integer $diasVacaciones
     *
     * @return RhuLiquidacion
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
     * Set diasPrimas
     *
     * @param integer $diasPrimas
     *
     * @return RhuLiquidacion
     */
    public function setDiasPrimas($diasPrimas)
    {
        $this->diasPrimas = $diasPrimas;

        return $this;
    }

    /**
     * Get diasPrimas
     *
     * @return integer
     */
    public function getDiasPrimas()
    {
        return $this->diasPrimas;
    }

    /**
     * Set fechaUltimoPago
     *
     * @param \DateTime $fechaUltimoPago
     *
     * @return RhuLiquidacion
     */
    public function setFechaUltimoPago($fechaUltimoPago)
    {
        $this->fechaUltimoPago = $fechaUltimoPago;

        return $this;
    }

    /**
     * Get fechaUltimoPago
     *
     * @return \DateTime
     */
    public function getFechaUltimoPago()
    {
        return $this->fechaUltimoPago;
    }

    /**
     * Set vrIngresoBaseCotizacionAdicional
     *
     * @param float $vrIngresoBaseCotizacionAdicional
     *
     * @return RhuLiquidacion
     */
    public function setVrIngresoBaseCotizacionAdicional($vrIngresoBaseCotizacionAdicional)
    {
        $this->VrIngresoBaseCotizacionAdicional = $vrIngresoBaseCotizacionAdicional;

        return $this;
    }

    /**
     * Get vrIngresoBaseCotizacionAdicional
     *
     * @return float
     */
    public function getVrIngresoBaseCotizacionAdicional()
    {
        return $this->VrIngresoBaseCotizacionAdicional;
    }

    /**
     * Set diasAdicionalesIBC
     *
     * @param integer $diasAdicionalesIBC
     *
     * @return RhuLiquidacion
     */
    public function setDiasAdicionalesIBC($diasAdicionalesIBC)
    {
        $this->diasAdicionalesIBC = $diasAdicionalesIBC;

        return $this;
    }

    /**
     * Get diasAdicionalesIBC
     *
     * @return integer
     */
    public function getDiasAdicionalesIBC()
    {
        return $this->diasAdicionalesIBC;
    }

    /**
     * Set liquidarCesantias
     *
     * @param boolean $liquidarCesantias
     *
     * @return RhuLiquidacion
     */
    public function setLiquidarCesantias($liquidarCesantias)
    {
        $this->liquidarCesantias = $liquidarCesantias;

        return $this;
    }

    /**
     * Get liquidarCesantias
     *
     * @return boolean
     */
    public function getLiquidarCesantias()
    {
        return $this->liquidarCesantias;
    }

    /**
     * Set liquidarVacaciones
     *
     * @param boolean $liquidarVacaciones
     *
     * @return RhuLiquidacion
     */
    public function setLiquidarVacaciones($liquidarVacaciones)
    {
        $this->liquidarVacaciones = $liquidarVacaciones;

        return $this;
    }

    /**
     * Get liquidarVacaciones
     *
     * @return boolean
     */
    public function getLiquidarVacaciones()
    {
        return $this->liquidarVacaciones;
    }

    /**
     * Set liquidarPrima
     *
     * @param boolean $liquidarPrima
     *
     * @return RhuLiquidacion
     */
    public function setLiquidarPrima($liquidarPrima)
    {
        $this->liquidarPrima = $liquidarPrima;

        return $this;
    }

    /**
     * Get liquidarPrima
     *
     * @return boolean
     */
    public function getLiquidarPrima()
    {
        return $this->liquidarPrima;
    }

    /**
     * Set vrIngresoBaseCotizacion
     *
     * @param float $vrIngresoBaseCotizacion
     *
     * @return RhuLiquidacion
     */
    public function setVrIngresoBaseCotizacion($vrIngresoBaseCotizacion)
    {
        $this->VrIngresoBaseCotizacion = $vrIngresoBaseCotizacion;

        return $this;
    }

    /**
     * Get vrIngresoBaseCotizacion
     *
     * @return float
     */
    public function getVrIngresoBaseCotizacion()
    {
        return $this->VrIngresoBaseCotizacion;
    }

    /**
     * Set vrBasePrestaciones
     *
     * @param float $vrBasePrestaciones
     *
     * @return RhuLiquidacion
     */
    public function setVrBasePrestaciones($vrBasePrestaciones)
    {
        $this->VrBasePrestaciones = $vrBasePrestaciones;

        return $this;
    }

    /**
     * Get vrBasePrestaciones
     *
     * @return float
     */
    public function getVrBasePrestaciones()
    {
        return $this->VrBasePrestaciones;
    }

    /**
     * Set vrBasePrestacionesTotal
     *
     * @param float $vrBasePrestacionesTotal
     *
     * @return RhuLiquidacion
     */
    public function setVrBasePrestacionesTotal($vrBasePrestacionesTotal)
    {
        $this->VrBasePrestacionesTotal = $vrBasePrestacionesTotal;

        return $this;
    }

    /**
     * Get vrBasePrestacionesTotal
     *
     * @return float
     */
    public function getVrBasePrestacionesTotal()
    {
        return $this->VrBasePrestacionesTotal;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return RhuLiquidacion
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte)
    {
        $this->VrAuxilioTransporte = $vrAuxilioTransporte;

        return $this;
    }

    /**
     * Get vrAuxilioTransporte
     *
     * @return float
     */
    public function getVrAuxilioTransporte()
    {
        return $this->VrAuxilioTransporte;
    }

    /**
     * Set diasLaborados
     *
     * @param integer $diasLaborados
     *
     * @return RhuLiquidacion
     */
    public function setDiasLaborados($diasLaborados)
    {
        $this->diasLaborados = $diasLaborados;

        return $this;
    }

    /**
     * Get diasLaborados
     *
     * @return integer
     */
    public function getDiasLaborados()
    {
        return $this->diasLaborados;
    }

    /**
     * Set vrIngresoBaseCotizacionTotal
     *
     * @param float $vrIngresoBaseCotizacionTotal
     *
     * @return RhuLiquidacion
     */
    public function setVrIngresoBaseCotizacionTotal($vrIngresoBaseCotizacionTotal)
    {
        $this->VrIngresoBaseCotizacionTotal = $vrIngresoBaseCotizacionTotal;

        return $this;
    }

    /**
     * Get vrIngresoBaseCotizacionTotal
     *
     * @return float
     */
    public function getVrIngresoBaseCotizacionTotal()
    {
        return $this->VrIngresoBaseCotizacionTotal;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuLiquidacion
     */
    public function setVrSalario($vrSalario)
    {
        $this->VrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->VrSalario;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuLiquidacion
     */
    public function setVrTotal($vrTotal)
    {
        $this->VrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->VrTotal;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->liquidacionesDeduccionesLiquidacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add liquidacionesDeduccionesLiquidacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion $liquidacionesDeduccionesLiquidacionRel
     *
     * @return RhuLiquidacion
     */
    public function addLiquidacionesDeduccionesLiquidacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion $liquidacionesDeduccionesLiquidacionRel)
    {
        $this->liquidacionesDeduccionesLiquidacionRel[] = $liquidacionesDeduccionesLiquidacionRel;

        return $this;
    }

    /**
     * Remove liquidacionesDeduccionesLiquidacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion $liquidacionesDeduccionesLiquidacionRel
     */
    public function removeLiquidacionesDeduccionesLiquidacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion $liquidacionesDeduccionesLiquidacionRel)
    {
        $this->liquidacionesDeduccionesLiquidacionRel->removeElement($liquidacionesDeduccionesLiquidacionRel);
    }

    /**
     * Get liquidacionesDeduccionesLiquidacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLiquidacionesDeduccionesLiquidacionRel()
    {
        return $this->liquidacionesDeduccionesLiquidacionRel;
    }
}
