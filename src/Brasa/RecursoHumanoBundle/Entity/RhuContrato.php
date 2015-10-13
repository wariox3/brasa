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
     * @ORM\Column(name="codigo_contrato_tipo_fk", type="integer")
     */    
    private $codigoContratoTipoFk;     
    
    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer")
     */    
    private $codigoClasificacionRiesgoFk;
    
    /**
     * @ORM\Column(name="codigo_motivo_terminacion_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoMotivoTerminacionContratoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     
    
    /**
     * @ORM\Column(name="codigo_tipo_tiempo_fk", type="integer")
     */    
    private $codigoTipoTiempoFk;    

    /**
     * @ORM\Column(name="codigo_tipo_pension_fk", type="integer")
     */    
    private $codigoTipoPensionFk;    
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta; 
    
    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */    
    private $numero;     
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer")
     */    
    private $codigoCargoFk;    
    
    /**
     * @ORM\Column(name="cargo_descripcion", type="string", length=60, nullable=true)
     */    
    private $cargoDescripcion;
    
    /**
     * @ORM\Column(name="horario_trabajo", type="string", length=100, nullable=true)
     */    
    private $horarioTrabajo;
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;    
    
    /**
     * @ORM\Column(name="vr_salario_pago", type="float")
     */
    private $VrSalarioPago = 0;    
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = 1;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    
    
    /**     
     * @ORM\Column(name="indefinido", type="boolean")
     */    
    private $indefinido = 0;     
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer")
     */    
    private $codigoCentroCostoFk;     
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias", type="date", nullable=true)
     */    
    private $fechaUltimoPagoCesantias;    

    /**
     * @ORM\Column(name="fecha_ultimo_pago_vacaciones", type="date", nullable=true)
     */    
    private $fechaUltimoPagoVacaciones;    
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_primas", type="date", nullable=true)
     */    
    private $fechaUltimoPagoPrimas;        
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago", type="date", nullable=true)
     */    
    private $fechaUltimoPago;             
    
    /**     
     * @ORM\Column(name="estado_liquidado", type="boolean")
     */    
    private $estadoLiquidado = 0;   
    
    /**
     * Este factor se utiliza para saber de cuantas horas se compone un dia
     * @ORM\Column(name="factor", type="integer", nullable=true)     
     */    
    private $factor = 0;     
    
    /**
     * @ORM\Column(name="factor_horas_dia", type="integer", nullable=true)
     */    
    private $factorHorasDia = 0;    
    
    /**
     * @ORM\Column(name="codigo_tipo_cotizante_fk", type="integer", nullable=false)
     */    
    private $codigoTipoCotizanteFk;    

    /**
     * @ORM\Column(name="codigo_subtipo_cotizante_fk", type="integer", nullable=false)
     */    
    private $codigoSubtipoCotizanteFk;     
    
    /**     
     * @ORM\Column(name="salario_integral", type="boolean")
     */    
    private $salarioIntegral = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="contratosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoTiempo", inversedBy="contratosTipoTiempoRel")
     * @ORM\JoinColumn(name="codigo_tipo_tiempo_fk", referencedColumnName="codigo_tipo_tiempo_pk")
     */
    protected $tipoTiempoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="empleadosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuContratoTipo", inversedBy="contratosContratoTipoRel")
     * @ORM\JoinColumn(name="codigo_contrato_tipo_fk", referencedColumnName="codigo_contrato_tipo_pk")
     */
    protected $contratoTipoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuClasificacionRiesgo", inversedBy="contratosClasificacionRiesgoRel")
     * @ORM\JoinColumn(name="codigo_clasificacion_riesgo_fk", referencedColumnName="codigo_clasificacion_riesgo_pk")
     */
    protected $clasificacionRiesgoRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="contratosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoPension", inversedBy="contratosTipoPensionRel")
     * @ORM\JoinColumn(name="codigo_tipo_pension_fk", referencedColumnName="codigo_tipo_pension_pk")
     */
    protected $tipoPensionRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoTipoCotizante", inversedBy="contratosSsoTipoCotizanteRel")
     * @ORM\JoinColumn(name="codigo_tipo_cotizante_fk", referencedColumnName="codigo_tipo_cotizante_pk")
     */
    protected $ssoTipoCotizanteRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoSubtipoCotizante", inversedBy="contratosSsoSubtipoCotizanteRel")
     * @ORM\JoinColumn(name="codigo_subtipo_cotizante_fk", referencedColumnName="codigo_subtipo_cotizante_pk")
     */
    protected $ssoSubtipoCotizanteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuMotivoTerminacionContrato", inversedBy="contratosMotivoTerminacionContratoRel")
     * @ORM\JoinColumn(name="codigo_motivo_terminacion_contrato_fk", referencedColumnName="codigo_motivo_terminacion_contrato_pk")
     */
    protected $terminacionContratoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacion", mappedBy="contratoRel")
     */
    protected $liquidacionesContratoRel; 

    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPagoDetalle", mappedBy="contratoRel")
     */
    protected $programacionesPagosDetallesContratoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="contratoRel")
     */
    protected $pagosContratoRel;      
    
     /**
     * @ORM\OneToMany(targetEntity="RhuSsoPeriodoEmpleado", mappedBy="contratoRel")
     */
    protected $ssoPeriodosEmpleadosContratoRel;    

     /**
     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="contratoRel")
     */
    protected $ssoAportesContratoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCambioSalario", mappedBy="contratoRel")
     */
    protected $cambiosSalariosContratoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuVacacion", mappedBy="contratoRel")
     */
    protected $vacacionesContratoRel;             
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIngresoBase", mappedBy="contratoRel")
     */
    protected $ingresosBasesContratoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuContratoSede", mappedBy="contratoRel")
     */
    protected $contratosSedesContratoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProyeccion", mappedBy="contratoRel")
     */
    protected $proyeccionesContratoRel; 
    


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->liquidacionesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesPagosDetallesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ssoPeriodosEmpleadosContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ssoAportesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cambiosSalariosContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vacacionesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ingresosBasesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosSedesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proyeccionesContratoRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set codigoContratoTipoFk
     *
     * @param integer $codigoContratoTipoFk
     *
     * @return RhuContrato
     */
    public function setCodigoContratoTipoFk($codigoContratoTipoFk)
    {
        $this->codigoContratoTipoFk = $codigoContratoTipoFk;

        return $this;
    }

    /**
     * Get codigoContratoTipoFk
     *
     * @return integer
     */
    public function getCodigoContratoTipoFk()
    {
        return $this->codigoContratoTipoFk;
    }

    /**
     * Set codigoClasificacionRiesgoFk
     *
     * @param integer $codigoClasificacionRiesgoFk
     *
     * @return RhuContrato
     */
    public function setCodigoClasificacionRiesgoFk($codigoClasificacionRiesgoFk)
    {
        $this->codigoClasificacionRiesgoFk = $codigoClasificacionRiesgoFk;

        return $this;
    }

    /**
     * Get codigoClasificacionRiesgoFk
     *
     * @return integer
     */
    public function getCodigoClasificacionRiesgoFk()
    {
        return $this->codigoClasificacionRiesgoFk;
    }

    /**
     * Set codigoMotivoTerminacionContratoFk
     *
     * @param integer $codigoMotivoTerminacionContratoFk
     *
     * @return RhuContrato
     */
    public function setCodigoMotivoTerminacionContratoFk($codigoMotivoTerminacionContratoFk)
    {
        $this->codigoMotivoTerminacionContratoFk = $codigoMotivoTerminacionContratoFk;

        return $this;
    }

    /**
     * Get codigoMotivoTerminacionContratoFk
     *
     * @return integer
     */
    public function getCodigoMotivoTerminacionContratoFk()
    {
        return $this->codigoMotivoTerminacionContratoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuContrato
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
     * Set codigoTipoTiempoFk
     *
     * @param integer $codigoTipoTiempoFk
     *
     * @return RhuContrato
     */
    public function setCodigoTipoTiempoFk($codigoTipoTiempoFk)
    {
        $this->codigoTipoTiempoFk = $codigoTipoTiempoFk;

        return $this;
    }

    /**
     * Get codigoTipoTiempoFk
     *
     * @return integer
     */
    public function getCodigoTipoTiempoFk()
    {
        return $this->codigoTipoTiempoFk;
    }

    /**
     * Set codigoTipoPensionFk
     *
     * @param integer $codigoTipoPensionFk
     *
     * @return RhuContrato
     */
    public function setCodigoTipoPensionFk($codigoTipoPensionFk)
    {
        $this->codigoTipoPensionFk = $codigoTipoPensionFk;

        return $this;
    }

    /**
     * Get codigoTipoPensionFk
     *
     * @return integer
     */
    public function getCodigoTipoPensionFk()
    {
        return $this->codigoTipoPensionFk;
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuContrato
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
     * @return RhuContrato
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
     * Set numero
     *
     * @param string $numero
     *
     * @return RhuContrato
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
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
     * Set cargoDescripcion
     *
     * @param string $cargoDescripcion
     *
     * @return RhuContrato
     */
    public function setCargoDescripcion($cargoDescripcion)
    {
        $this->cargoDescripcion = $cargoDescripcion;

        return $this;
    }

    /**
     * Get cargoDescripcion
     *
     * @return string
     */
    public function getCargoDescripcion()
    {
        return $this->cargoDescripcion;
    }

    /**
     * Set horarioTrabajo
     *
     * @param string $horarioTrabajo
     *
     * @return RhuContrato
     */
    public function setHorarioTrabajo($horarioTrabajo)
    {
        $this->horarioTrabajo = $horarioTrabajo;

        return $this;
    }

    /**
     * Get horarioTrabajo
     *
     * @return string
     */
    public function getHorarioTrabajo()
    {
        return $this->horarioTrabajo;
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
     * Set vrSalarioPago
     *
     * @param float $vrSalarioPago
     *
     * @return RhuContrato
     */
    public function setVrSalarioPago($vrSalarioPago)
    {
        $this->VrSalarioPago = $vrSalarioPago;

        return $this;
    }

    /**
     * Get vrSalarioPago
     *
     * @return float
     */
    public function getVrSalarioPago()
    {
        return $this->VrSalarioPago;
    }

    /**
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return RhuContrato
     */
    public function setEstadoActivo($estadoActivo)
    {
        $this->estadoActivo = $estadoActivo;

        return $this;
    }

    /**
     * Get estadoActivo
     *
     * @return boolean
     */
    public function getEstadoActivo()
    {
        return $this->estadoActivo;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuContrato
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
     * Set indefinido
     *
     * @param boolean $indefinido
     *
     * @return RhuContrato
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuContrato
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
     * Set fechaUltimoPagoCesantias
     *
     * @param \DateTime $fechaUltimoPagoCesantias
     *
     * @return RhuContrato
     */
    public function setFechaUltimoPagoCesantias($fechaUltimoPagoCesantias)
    {
        $this->fechaUltimoPagoCesantias = $fechaUltimoPagoCesantias;

        return $this;
    }

    /**
     * Get fechaUltimoPagoCesantias
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoCesantias()
    {
        return $this->fechaUltimoPagoCesantias;
    }

    /**
     * Set fechaUltimoPagoVacaciones
     *
     * @param \DateTime $fechaUltimoPagoVacaciones
     *
     * @return RhuContrato
     */
    public function setFechaUltimoPagoVacaciones($fechaUltimoPagoVacaciones)
    {
        $this->fechaUltimoPagoVacaciones = $fechaUltimoPagoVacaciones;

        return $this;
    }

    /**
     * Get fechaUltimoPagoVacaciones
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoVacaciones()
    {
        return $this->fechaUltimoPagoVacaciones;
    }

    /**
     * Set fechaUltimoPagoPrimas
     *
     * @param \DateTime $fechaUltimoPagoPrimas
     *
     * @return RhuContrato
     */
    public function setFechaUltimoPagoPrimas($fechaUltimoPagoPrimas)
    {
        $this->fechaUltimoPagoPrimas = $fechaUltimoPagoPrimas;

        return $this;
    }

    /**
     * Get fechaUltimoPagoPrimas
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoPrimas()
    {
        return $this->fechaUltimoPagoPrimas;
    }

    /**
     * Set fechaUltimoPago
     *
     * @param \DateTime $fechaUltimoPago
     *
     * @return RhuContrato
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
     * Set estadoLiquidado
     *
     * @param boolean $estadoLiquidado
     *
     * @return RhuContrato
     */
    public function setEstadoLiquidado($estadoLiquidado)
    {
        $this->estadoLiquidado = $estadoLiquidado;

        return $this;
    }

    /**
     * Get estadoLiquidado
     *
     * @return boolean
     */
    public function getEstadoLiquidado()
    {
        return $this->estadoLiquidado;
    }

    /**
     * Set factor
     *
     * @param integer $factor
     *
     * @return RhuContrato
     */
    public function setFactor($factor)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * Get factor
     *
     * @return integer
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set factorHorasDia
     *
     * @param integer $factorHorasDia
     *
     * @return RhuContrato
     */
    public function setFactorHorasDia($factorHorasDia)
    {
        $this->factorHorasDia = $factorHorasDia;

        return $this;
    }

    /**
     * Get factorHorasDia
     *
     * @return integer
     */
    public function getFactorHorasDia()
    {
        return $this->factorHorasDia;
    }

    /**
     * Set codigoTipoCotizanteFk
     *
     * @param integer $codigoTipoCotizanteFk
     *
     * @return RhuContrato
     */
    public function setCodigoTipoCotizanteFk($codigoTipoCotizanteFk)
    {
        $this->codigoTipoCotizanteFk = $codigoTipoCotizanteFk;

        return $this;
    }

    /**
     * Get codigoTipoCotizanteFk
     *
     * @return integer
     */
    public function getCodigoTipoCotizanteFk()
    {
        return $this->codigoTipoCotizanteFk;
    }

    /**
     * Set codigoSubtipoCotizanteFk
     *
     * @param integer $codigoSubtipoCotizanteFk
     *
     * @return RhuContrato
     */
    public function setCodigoSubtipoCotizanteFk($codigoSubtipoCotizanteFk)
    {
        $this->codigoSubtipoCotizanteFk = $codigoSubtipoCotizanteFk;

        return $this;
    }

    /**
     * Get codigoSubtipoCotizanteFk
     *
     * @return integer
     */
    public function getCodigoSubtipoCotizanteFk()
    {
        return $this->codigoSubtipoCotizanteFk;
    }

    /**
     * Set salarioIntegral
     *
     * @param boolean $salarioIntegral
     *
     * @return RhuContrato
     */
    public function setSalarioIntegral($salarioIntegral)
    {
        $this->salarioIntegral = $salarioIntegral;

        return $this;
    }

    /**
     * Get salarioIntegral
     *
     * @return boolean
     */
    public function getSalarioIntegral()
    {
        return $this->salarioIntegral;
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
     * Set tipoTiempoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoTiempo $tipoTiempoRel
     *
     * @return RhuContrato
     */
    public function setTipoTiempoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoTiempo $tipoTiempoRel = null)
    {
        $this->tipoTiempoRel = $tipoTiempoRel;

        return $this;
    }

    /**
     * Get tipoTiempoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuTipoTiempo
     */
    public function getTipoTiempoRel()
    {
        return $this->tipoTiempoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuContrato
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
     * Set contratoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratoTipoRel
     *
     * @return RhuContrato
     */
    public function setContratoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratoTipoRel = null)
    {
        $this->contratoTipoRel = $contratoTipoRel;

        return $this;
    }

    /**
     * Get contratoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo
     */
    public function getContratoTipoRel()
    {
        return $this->contratoTipoRel;
    }

    /**
     * Set clasificacionRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo $clasificacionRiesgoRel
     *
     * @return RhuContrato
     */
    public function setClasificacionRiesgoRel(\Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo $clasificacionRiesgoRel = null)
    {
        $this->clasificacionRiesgoRel = $clasificacionRiesgoRel;

        return $this;
    }

    /**
     * Get clasificacionRiesgoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo
     */
    public function getClasificacionRiesgoRel()
    {
        return $this->clasificacionRiesgoRel;
    }

    /**
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuContrato
     */
    public function setCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel = null)
    {
        $this->cargoRel = $cargoRel;

        return $this;
    }

    /**
     * Get cargoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCargo
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * Set tipoPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tipoPensionRel
     *
     * @return RhuContrato
     */
    public function setTipoPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tipoPensionRel = null)
    {
        $this->tipoPensionRel = $tipoPensionRel;

        return $this;
    }

    /**
     * Get tipoPensionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension
     */
    public function getTipoPensionRel()
    {
        return $this->tipoPensionRel;
    }

    /**
     * Set ssoTipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoTipoCotizante $ssoTipoCotizanteRel
     *
     * @return RhuContrato
     */
    public function setSsoTipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoTipoCotizante $ssoTipoCotizanteRel = null)
    {
        $this->ssoTipoCotizanteRel = $ssoTipoCotizanteRel;

        return $this;
    }

    /**
     * Get ssoTipoCotizanteRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoTipoCotizante
     */
    public function getSsoTipoCotizanteRel()
    {
        return $this->ssoTipoCotizanteRel;
    }

    /**
     * Set ssoSubtipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoSubtipoCotizante $ssoSubtipoCotizanteRel
     *
     * @return RhuContrato
     */
    public function setSsoSubtipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoSubtipoCotizante $ssoSubtipoCotizanteRel = null)
    {
        $this->ssoSubtipoCotizanteRel = $ssoSubtipoCotizanteRel;

        return $this;
    }

    /**
     * Get ssoSubtipoCotizanteRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoSubtipoCotizante
     */
    public function getSsoSubtipoCotizanteRel()
    {
        return $this->ssoSubtipoCotizanteRel;
    }

    /**
     * Set terminacionContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuMotivoTerminacionContrato $terminacionContratoRel
     *
     * @return RhuContrato
     */
    public function setTerminacionContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuMotivoTerminacionContrato $terminacionContratoRel = null)
    {
        $this->terminacionContratoRel = $terminacionContratoRel;

        return $this;
    }

    /**
     * Get terminacionContratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuMotivoTerminacionContrato
     */
    public function getTerminacionContratoRel()
    {
        return $this->terminacionContratoRel;
    }

    /**
     * Add liquidacionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesContratoRel
     *
     * @return RhuContrato
     */
    public function addLiquidacionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesContratoRel)
    {
        $this->liquidacionesContratoRel[] = $liquidacionesContratoRel;

        return $this;
    }

    /**
     * Remove liquidacionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesContratoRel
     */
    public function removeLiquidacionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesContratoRel)
    {
        $this->liquidacionesContratoRel->removeElement($liquidacionesContratoRel);
    }

    /**
     * Get liquidacionesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLiquidacionesContratoRel()
    {
        return $this->liquidacionesContratoRel;
    }

    /**
     * Add programacionesPagosDetallesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesContratoRel
     *
     * @return RhuContrato
     */
    public function addProgramacionesPagosDetallesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesContratoRel)
    {
        $this->programacionesPagosDetallesContratoRel[] = $programacionesPagosDetallesContratoRel;

        return $this;
    }

    /**
     * Remove programacionesPagosDetallesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesContratoRel
     */
    public function removeProgramacionesPagosDetallesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle $programacionesPagosDetallesContratoRel)
    {
        $this->programacionesPagosDetallesContratoRel->removeElement($programacionesPagosDetallesContratoRel);
    }

    /**
     * Get programacionesPagosDetallesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosDetallesContratoRel()
    {
        return $this->programacionesPagosDetallesContratoRel;
    }

    /**
     * Add pagosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosContratoRel
     *
     * @return RhuContrato
     */
    public function addPagosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosContratoRel)
    {
        $this->pagosContratoRel[] = $pagosContratoRel;

        return $this;
    }

    /**
     * Remove pagosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosContratoRel
     */
    public function removePagosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosContratoRel)
    {
        $this->pagosContratoRel->removeElement($pagosContratoRel);
    }

    /**
     * Get pagosContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosContratoRel()
    {
        return $this->pagosContratoRel;
    }

    /**
     * Add ssoPeriodosEmpleadosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosContratoRel
     *
     * @return RhuContrato
     */
    public function addSsoPeriodosEmpleadosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosContratoRel)
    {
        $this->ssoPeriodosEmpleadosContratoRel[] = $ssoPeriodosEmpleadosContratoRel;

        return $this;
    }

    /**
     * Remove ssoPeriodosEmpleadosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosContratoRel
     */
    public function removeSsoPeriodosEmpleadosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosContratoRel)
    {
        $this->ssoPeriodosEmpleadosContratoRel->removeElement($ssoPeriodosEmpleadosContratoRel);
    }

    /**
     * Get ssoPeriodosEmpleadosContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoPeriodosEmpleadosContratoRel()
    {
        return $this->ssoPeriodosEmpleadosContratoRel;
    }

    /**
     * Add ssoAportesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesContratoRel
     *
     * @return RhuContrato
     */
    public function addSsoAportesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesContratoRel)
    {
        $this->ssoAportesContratoRel[] = $ssoAportesContratoRel;

        return $this;
    }

    /**
     * Remove ssoAportesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesContratoRel
     */
    public function removeSsoAportesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesContratoRel)
    {
        $this->ssoAportesContratoRel->removeElement($ssoAportesContratoRel);
    }

    /**
     * Get ssoAportesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoAportesContratoRel()
    {
        return $this->ssoAportesContratoRel;
    }

    /**
     * Add cambiosSalariosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosContratoRel
     *
     * @return RhuContrato
     */
    public function addCambiosSalariosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosContratoRel)
    {
        $this->cambiosSalariosContratoRel[] = $cambiosSalariosContratoRel;

        return $this;
    }

    /**
     * Remove cambiosSalariosContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosContratoRel
     */
    public function removeCambiosSalariosContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario $cambiosSalariosContratoRel)
    {
        $this->cambiosSalariosContratoRel->removeElement($cambiosSalariosContratoRel);
    }

    /**
     * Get cambiosSalariosContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCambiosSalariosContratoRel()
    {
        return $this->cambiosSalariosContratoRel;
    }

    /**
     * Add vacacionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesContratoRel
     *
     * @return RhuContrato
     */
    public function addVacacionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesContratoRel)
    {
        $this->vacacionesContratoRel[] = $vacacionesContratoRel;

        return $this;
    }

    /**
     * Remove vacacionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesContratoRel
     */
    public function removeVacacionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesContratoRel)
    {
        $this->vacacionesContratoRel->removeElement($vacacionesContratoRel);
    }

    /**
     * Get vacacionesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVacacionesContratoRel()
    {
        return $this->vacacionesContratoRel;
    }

    /**
     * Add ingresosBasesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesContratoRel
     *
     * @return RhuContrato
     */
    public function addIngresosBasesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesContratoRel)
    {
        $this->ingresosBasesContratoRel[] = $ingresosBasesContratoRel;

        return $this;
    }

    /**
     * Remove ingresosBasesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesContratoRel
     */
    public function removeIngresosBasesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase $ingresosBasesContratoRel)
    {
        $this->ingresosBasesContratoRel->removeElement($ingresosBasesContratoRel);
    }

    /**
     * Get ingresosBasesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngresosBasesContratoRel()
    {
        return $this->ingresosBasesContratoRel;
    }

    /**
     * Add contratosSedesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesContratoRel
     *
     * @return RhuContrato
     */
    public function addContratosSedesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesContratoRel)
    {
        $this->contratosSedesContratoRel[] = $contratosSedesContratoRel;

        return $this;
    }

    /**
     * Remove contratosSedesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesContratoRel
     */
    public function removeContratosSedesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesContratoRel)
    {
        $this->contratosSedesContratoRel->removeElement($contratosSedesContratoRel);
    }

    /**
     * Get contratosSedesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosSedesContratoRel()
    {
        return $this->contratosSedesContratoRel;
    }

    /**
     * Add proyeccionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesContratoRel
     *
     * @return RhuContrato
     */
    public function addProyeccionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesContratoRel)
    {
        $this->proyeccionesContratoRel[] = $proyeccionesContratoRel;

        return $this;
    }

    /**
     * Remove proyeccionesContratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesContratoRel
     */
    public function removeProyeccionesContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProyeccion $proyeccionesContratoRel)
    {
        $this->proyeccionesContratoRel->removeElement($proyeccionesContratoRel);
    }

    /**
     * Get proyeccionesContratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProyeccionesContratoRel()
    {
        return $this->proyeccionesContratoRel;
    }
}
