<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoRepository")
 */
class RhuPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoPk;
    
    /**
     * @ORM\Column(name="codigo_pago_tipo_fk", type="integer", nullable=false)
     */    
    private $codigoPagoTipoFk;     
    
    /**
     * @ORM\Column(name="numero", type="integer")
     */    
    private $numero = 0;     
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;      

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoContratoFk;    
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoFk; 
    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    

    /**
     * @ORM\Column(name="fecha_desde_pago", type="date", nullable=true)
     */    
    private $fechaDesdePago;    
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;     

    /**
     * Es el salario corerspondiente a los dias * VrDia
     * @ORM\Column(name="vr_salario_periodo", type="float")
     */
    private $vrSalarioPeriodo = 0;    

    /**
     * Es el salario que tenia el empleado cuando se genero el pago
     * @ORM\Column(name="vr_salario_empleado", type="float")
     */
    private $vrSalarioEmpleado = 0;        
    
    /**
     * @ORM\Column(name="vr_devengado", type="float")
     */
    private $vrDevengado = 0;    

    /**
     * @ORM\Column(name="vr_deducciones", type="float")
     */
    private $vrDeducciones = 0;    

    /**
     * @ORM\Column(name="vr_adicional_tiempo", type="float")
     */
    private $vrAdicionalTiempo = 0;     

    /**
     * @ORM\Column(name="vr_adicional_valor", type="float")
     */
    private $vrAdicionalValor = 0;    
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */
    private $vrAuxilioTransporte = 0;    
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte_cotizacion", type="float")
     */
    private $vrAuxilioTransporteCotizacion = 0;    
    
    /**
     * @ORM\Column(name="vr_arp", type="float")
     */
    private $vrArp = 0;    
    
    /**
     * @ORM\Column(name="vr_eps", type="float")
     */
    private $vrEps = 0;    
    
    /**
     * @ORM\Column(name="vr_pension", type="float")
     */
    private $vrPension = 0;    
    
    /**
     * @ORM\Column(name="vr_caja", type="float")
     */
    private $vrCaja = 0;    
    
    /**
     * @ORM\Column(name="vr_sena", type="float")
     */
    private $vrSena = 0;    
    
    /**
     * @ORM\Column(name="vr_icbf", type="float")
     */
    private $vrIcbf = 0;    
    
    /**
     * @ORM\Column(name="vr_cesantias", type="float")
     */
    private $vrCesantias = 0;    
    
    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $vrVacaciones = 0;    
    
    /**
     * @ORM\Column(name="vr_administracion", type="float")
     */
    private $vrAdministracion = 0;    
    
    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;    
    
    /**
     * @ORM\Column(name="vr_bruto", type="float")
     */
    private $vrBruto = 0;                
    
    /**
     * @ORM\Column(name="vr_total_cobrar", type="float")
     */
    private $vrTotalCobrar = 0;    

    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;     
    
    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $vrIngresoBaseCotizacion = 0;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="estado_cobrado", type="boolean")
     */    
    private $estadoCobrado = 0;     
    
    /**
     * @ORM\Column(name="dias_periodo", type="integer")
     */
    private $diasPeriodo = 0;   
    
    /**
     * @ORM\Column(name="estado_pagado", type="boolean")
     */    
    private $estadoPagado = 0;         
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;    
    
    /**
     * @ORM\Column(name="exportado_contabilidad", type="boolean")
     */    
    private $exportadoContabilidad = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoTipo", inversedBy="pagosPagoTipoRel")
     * @ORM\JoinColumn(name="codigo_pago_tipo_fk", referencedColumnName="codigo_pago_tipo_pk")
     */
    protected $pagoTipoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="pagosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="pagosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="pagosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPago", inversedBy="pagosProgramacionPagoRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_fk", referencedColumnName="codigo_programacion_pago_pk")
     */
    protected $programacionPagoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoDetalle", mappedBy="pagoRel")
     */
    protected $pagosDetallesPagoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoDetalleSede", mappedBy="pagoRel")
     */
    protected $pagosDetallesSedesPagoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuServicioCobrar", mappedBy="pagoRel")
     */
    protected $serviciosCobrarPagoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuFacturaDetallePago", mappedBy="pagoRel")
     */
    protected $facturasDetallesPagosPagoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCreditoPago", mappedBy="pagoRel")
     */
    protected $creditosPagosPagoRel;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosDetallesPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosDetallesSedesPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosCobrarPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesPagosPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creditosPagosPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPagoPk
     *
     * @return integer
     */
    public function getCodigoPagoPk()
    {
        return $this->codigoPagoPk;
    }

    /**
     * Set codigoPagoTipoFk
     *
     * @param integer $codigoPagoTipoFk
     *
     * @return RhuPago
     */
    public function setCodigoPagoTipoFk($codigoPagoTipoFk)
    {
        $this->codigoPagoTipoFk = $codigoPagoTipoFk;

        return $this;
    }

    /**
     * Get codigoPagoTipoFk
     *
     * @return integer
     */
    public function getCodigoPagoTipoFk()
    {
        return $this->codigoPagoTipoFk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return RhuPago
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuPago
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
     * Set codigoProgramacionPagoFk
     *
     * @param integer $codigoProgramacionPagoFk
     *
     * @return RhuPago
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuPago
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
     * @return RhuPago
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuPago
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
     * Set vrSalarioPeriodo
     *
     * @param float $vrSalarioPeriodo
     *
     * @return RhuPago
     */
    public function setVrSalarioPeriodo($vrSalarioPeriodo)
    {
        $this->vrSalarioPeriodo = $vrSalarioPeriodo;

        return $this;
    }

    /**
     * Get vrSalarioPeriodo
     *
     * @return float
     */
    public function getVrSalarioPeriodo()
    {
        return $this->vrSalarioPeriodo;
    }

    /**
     * Set vrSalarioEmpleado
     *
     * @param float $vrSalarioEmpleado
     *
     * @return RhuPago
     */
    public function setVrSalarioEmpleado($vrSalarioEmpleado)
    {
        $this->vrSalarioEmpleado = $vrSalarioEmpleado;

        return $this;
    }

    /**
     * Get vrSalarioEmpleado
     *
     * @return float
     */
    public function getVrSalarioEmpleado()
    {
        return $this->vrSalarioEmpleado;
    }

    /**
     * Set vrDevengado
     *
     * @param float $vrDevengado
     *
     * @return RhuPago
     */
    public function setVrDevengado($vrDevengado)
    {
        $this->vrDevengado = $vrDevengado;

        return $this;
    }

    /**
     * Get vrDevengado
     *
     * @return float
     */
    public function getVrDevengado()
    {
        return $this->vrDevengado;
    }

    /**
     * Set vrDeducciones
     *
     * @param float $vrDeducciones
     *
     * @return RhuPago
     */
    public function setVrDeducciones($vrDeducciones)
    {
        $this->vrDeducciones = $vrDeducciones;

        return $this;
    }

    /**
     * Get vrDeducciones
     *
     * @return float
     */
    public function getVrDeducciones()
    {
        return $this->vrDeducciones;
    }

    /**
     * Set vrAdicionalTiempo
     *
     * @param float $vrAdicionalTiempo
     *
     * @return RhuPago
     */
    public function setVrAdicionalTiempo($vrAdicionalTiempo)
    {
        $this->vrAdicionalTiempo = $vrAdicionalTiempo;

        return $this;
    }

    /**
     * Get vrAdicionalTiempo
     *
     * @return float
     */
    public function getVrAdicionalTiempo()
    {
        return $this->vrAdicionalTiempo;
    }

    /**
     * Set vrAdicionalValor
     *
     * @param float $vrAdicionalValor
     *
     * @return RhuPago
     */
    public function setVrAdicionalValor($vrAdicionalValor)
    {
        $this->vrAdicionalValor = $vrAdicionalValor;

        return $this;
    }

    /**
     * Get vrAdicionalValor
     *
     * @return float
     */
    public function getVrAdicionalValor()
    {
        return $this->vrAdicionalValor;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return RhuPago
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte)
    {
        $this->vrAuxilioTransporte = $vrAuxilioTransporte;

        return $this;
    }

    /**
     * Get vrAuxilioTransporte
     *
     * @return float
     */
    public function getVrAuxilioTransporte()
    {
        return $this->vrAuxilioTransporte;
    }

    /**
     * Set vrAuxilioTransporteCotizacion
     *
     * @param float $vrAuxilioTransporteCotizacion
     *
     * @return RhuPago
     */
    public function setVrAuxilioTransporteCotizacion($vrAuxilioTransporteCotizacion)
    {
        $this->vrAuxilioTransporteCotizacion = $vrAuxilioTransporteCotizacion;

        return $this;
    }

    /**
     * Get vrAuxilioTransporteCotizacion
     *
     * @return float
     */
    public function getVrAuxilioTransporteCotizacion()
    {
        return $this->vrAuxilioTransporteCotizacion;
    }

    /**
     * Set vrArp
     *
     * @param float $vrArp
     *
     * @return RhuPago
     */
    public function setVrArp($vrArp)
    {
        $this->vrArp = $vrArp;

        return $this;
    }

    /**
     * Get vrArp
     *
     * @return float
     */
    public function getVrArp()
    {
        return $this->vrArp;
    }

    /**
     * Set vrEps
     *
     * @param float $vrEps
     *
     * @return RhuPago
     */
    public function setVrEps($vrEps)
    {
        $this->vrEps = $vrEps;

        return $this;
    }

    /**
     * Get vrEps
     *
     * @return float
     */
    public function getVrEps()
    {
        return $this->vrEps;
    }

    /**
     * Set vrPension
     *
     * @param float $vrPension
     *
     * @return RhuPago
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
     * Set vrCaja
     *
     * @param float $vrCaja
     *
     * @return RhuPago
     */
    public function setVrCaja($vrCaja)
    {
        $this->vrCaja = $vrCaja;

        return $this;
    }

    /**
     * Get vrCaja
     *
     * @return float
     */
    public function getVrCaja()
    {
        return $this->vrCaja;
    }

    /**
     * Set vrSena
     *
     * @param float $vrSena
     *
     * @return RhuPago
     */
    public function setVrSena($vrSena)
    {
        $this->vrSena = $vrSena;

        return $this;
    }

    /**
     * Get vrSena
     *
     * @return float
     */
    public function getVrSena()
    {
        return $this->vrSena;
    }

    /**
     * Set vrIcbf
     *
     * @param float $vrIcbf
     *
     * @return RhuPago
     */
    public function setVrIcbf($vrIcbf)
    {
        $this->vrIcbf = $vrIcbf;

        return $this;
    }

    /**
     * Get vrIcbf
     *
     * @return float
     */
    public function getVrIcbf()
    {
        return $this->vrIcbf;
    }

    /**
     * Set vrCesantias
     *
     * @param float $vrCesantias
     *
     * @return RhuPago
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
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuPago
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
     * Set vrAdministracion
     *
     * @param float $vrAdministracion
     *
     * @return RhuPago
     */
    public function setVrAdministracion($vrAdministracion)
    {
        $this->vrAdministracion = $vrAdministracion;

        return $this;
    }

    /**
     * Get vrAdministracion
     *
     * @return float
     */
    public function getVrAdministracion()
    {
        return $this->vrAdministracion;
    }

    /**
     * Set vrNeto
     *
     * @param float $vrNeto
     *
     * @return RhuPago
     */
    public function setVrNeto($vrNeto)
    {
        $this->vrNeto = $vrNeto;

        return $this;
    }

    /**
     * Get vrNeto
     *
     * @return float
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * Set vrBruto
     *
     * @param float $vrBruto
     *
     * @return RhuPago
     */
    public function setVrBruto($vrBruto)
    {
        $this->vrBruto = $vrBruto;

        return $this;
    }

    /**
     * Get vrBruto
     *
     * @return float
     */
    public function getVrBruto()
    {
        return $this->vrBruto;
    }

    /**
     * Set vrTotalCobrar
     *
     * @param float $vrTotalCobrar
     *
     * @return RhuPago
     */
    public function setVrTotalCobrar($vrTotalCobrar)
    {
        $this->vrTotalCobrar = $vrTotalCobrar;

        return $this;
    }

    /**
     * Get vrTotalCobrar
     *
     * @return float
     */
    public function getVrTotalCobrar()
    {
        return $this->vrTotalCobrar;
    }

    /**
     * Set vrCosto
     *
     * @param float $vrCosto
     *
     * @return RhuPago
     */
    public function setVrCosto($vrCosto)
    {
        $this->vrCosto = $vrCosto;

        return $this;
    }

    /**
     * Get vrCosto
     *
     * @return float
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * Set vrIngresoBaseCotizacion
     *
     * @param float $vrIngresoBaseCotizacion
     *
     * @return RhuPago
     */
    public function setVrIngresoBaseCotizacion($vrIngresoBaseCotizacion)
    {
        $this->vrIngresoBaseCotizacion = $vrIngresoBaseCotizacion;

        return $this;
    }

    /**
     * Get vrIngresoBaseCotizacion
     *
     * @return float
     */
    public function getVrIngresoBaseCotizacion()
    {
        return $this->vrIngresoBaseCotizacion;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuPago
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
     * Set estadoCobrado
     *
     * @param boolean $estadoCobrado
     *
     * @return RhuPago
     */
    public function setEstadoCobrado($estadoCobrado)
    {
        $this->estadoCobrado = $estadoCobrado;

        return $this;
    }

    /**
     * Get estadoCobrado
     *
     * @return boolean
     */
    public function getEstadoCobrado()
    {
        return $this->estadoCobrado;
    }

    /**
     * Set diasPeriodo
     *
     * @param integer $diasPeriodo
     *
     * @return RhuPago
     */
    public function setDiasPeriodo($diasPeriodo)
    {
        $this->diasPeriodo = $diasPeriodo;

        return $this;
    }

    /**
     * Get diasPeriodo
     *
     * @return integer
     */
    public function getDiasPeriodo()
    {
        return $this->diasPeriodo;
    }

    /**
     * Set pagoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoTipo $pagoTipoRel
     *
     * @return RhuPago
     */
    public function setPagoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoTipo $pagoTipoRel = null)
    {
        $this->pagoTipoRel = $pagoTipoRel;

        return $this;
    }

    /**
     * Get pagoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoTipo
     */
    public function getPagoTipoRel()
    {
        return $this->pagoTipoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuPago
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
     * @return RhuPago
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
     * Set programacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel
     *
     * @return RhuPago
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
     * Add pagosDetallesPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesPagoRel
     *
     * @return RhuPago
     */
    public function addPagosDetallesPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesPagoRel)
    {
        $this->pagosDetallesPagoRel[] = $pagosDetallesPagoRel;

        return $this;
    }

    /**
     * Remove pagosDetallesPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesPagoRel
     */
    public function removePagosDetallesPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesPagoRel)
    {
        $this->pagosDetallesPagoRel->removeElement($pagosDetallesPagoRel);
    }

    /**
     * Get pagosDetallesPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosDetallesPagoRel()
    {
        return $this->pagosDetallesPagoRel;
    }

    /**
     * Add pagosDetallesSedesPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesPagoRel
     *
     * @return RhuPago
     */
    public function addPagosDetallesSedesPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesPagoRel)
    {
        $this->pagosDetallesSedesPagoRel[] = $pagosDetallesSedesPagoRel;

        return $this;
    }

    /**
     * Remove pagosDetallesSedesPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesPagoRel
     */
    public function removePagosDetallesSedesPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesPagoRel)
    {
        $this->pagosDetallesSedesPagoRel->removeElement($pagosDetallesSedesPagoRel);
    }

    /**
     * Get pagosDetallesSedesPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosDetallesSedesPagoRel()
    {
        return $this->pagosDetallesSedesPagoRel;
    }

    /**
     * Add serviciosCobrarPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarPagoRel
     *
     * @return RhuPago
     */
    public function addServiciosCobrarPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarPagoRel)
    {
        $this->serviciosCobrarPagoRel[] = $serviciosCobrarPagoRel;

        return $this;
    }

    /**
     * Remove serviciosCobrarPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarPagoRel
     */
    public function removeServiciosCobrarPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarPagoRel)
    {
        $this->serviciosCobrarPagoRel->removeElement($serviciosCobrarPagoRel);
    }

    /**
     * Get serviciosCobrarPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosCobrarPagoRel()
    {
        return $this->serviciosCobrarPagoRel;
    }

    /**
     * Add facturasDetallesPagosPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetallePago $facturasDetallesPagosPagoRel
     *
     * @return RhuPago
     */
    public function addFacturasDetallesPagosPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetallePago $facturasDetallesPagosPagoRel)
    {
        $this->facturasDetallesPagosPagoRel[] = $facturasDetallesPagosPagoRel;

        return $this;
    }

    /**
     * Remove facturasDetallesPagosPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetallePago $facturasDetallesPagosPagoRel
     */
    public function removeFacturasDetallesPagosPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetallePago $facturasDetallesPagosPagoRel)
    {
        $this->facturasDetallesPagosPagoRel->removeElement($facturasDetallesPagosPagoRel);
    }

    /**
     * Get facturasDetallesPagosPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesPagosPagoRel()
    {
        return $this->facturasDetallesPagosPagoRel;
    }

    /**
     * Add creditosPagosPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosPagoRel
     *
     * @return RhuPago
     */
    public function addCreditosPagosPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosPagoRel)
    {
        $this->creditosPagosPagoRel[] = $creditosPagosPagoRel;

        return $this;
    }

    /**
     * Remove creditosPagosPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosPagoRel
     */
    public function removeCreditosPagosPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosPagoRel)
    {
        $this->creditosPagosPagoRel->removeElement($creditosPagosPagoRel);
    }

    /**
     * Get creditosPagosPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreditosPagosPagoRel()
    {
        return $this->creditosPagosPagoRel;
    }

    /**
     * Set estadoPagado
     *
     * @param boolean $estadoPagado
     *
     * @return RhuPago
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
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuPago
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
     * @return RhuPago
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
     * Set fechaDesdePago
     *
     * @param \DateTime $fechaDesdePago
     *
     * @return RhuPago
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuPago
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
     * Set exportadoContabilidad
     *
     * @param boolean $exportadoContabilidad
     *
     * @return RhuPago
     */
    public function setExportadoContabilidad($exportadoContabilidad)
    {
        $this->exportadoContabilidad = $exportadoContabilidad;

        return $this;
    }

    /**
     * Get exportadoContabilidad
     *
     * @return boolean
     */
    public function getExportadoContabilidad()
    {
        return $this->exportadoContabilidad;
    }
}
