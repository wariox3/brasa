<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_centro_costo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCentroCostoRepository")
 */
class RhuCentroCosto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_costo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCentroCostoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_periodo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPeriodoPagoFk;       

    /**
     * @ORM\Column(name="fecha_ultimo_pago", type="date", nullable=true)
     */    
    private $fechaUltimoPago;     
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_programado", type="date", nullable=true)
     */    
    private $fechaUltimoPagoProgramado;    
    
    /**
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     */    
    private $correo;    
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=20, nullable=true)
     */    
    private $codigoInterface;    
    
    /**
     * Si existen programaciones de pago pendientes
     * @ORM\Column(name="pago_abierto", type="boolean")
     */    
    private $pagoAbierto = 0;    
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = 0;     

    /**     
     * @ORM\Column(name="generar_pago_automatico", type="boolean")
     */    
    private $generarPagoAutomatico = 0;    
    
    /**
     * @ORM\Column(name="hora_pago_automatico", type="time", nullable=true)
     */    
    private $horaPagoAutomatico;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\Column(name="porcentaje_administracion", type="float")
     */
    private $porcentajeAdministracion = 0;    
    
    /**
     * @ORM\Column(name="valor_administracion", type="float")
     */
    private $valorAdministracion = 0;     
    
    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="integer", nullable=true)
     */    
    private $codigoSucursalFk;    
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_prima", type="date", nullable=true)
     */    
    private $fechaUltimoPagoPrima;    
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias", type="date", nullable=true)
     */    
    private $fechaUltimoPagoCesantias;
    
    /**
     * @ORM\Column(name="dias_pago", type="string", length=8, nullable=true)
     */    
    private $diasPago;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPeriodoPago", inversedBy="centrosCostosPeriodoPagoRel")
     * @ORM\JoinColumn(name="codigo_periodo_pago_fk", referencedColumnName="codigo_periodo_pago_pk")
     */
    protected $periodoPagoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuCentroCostosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoSucursal", inversedBy="centrosCostosSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk", referencedColumnName="codigo_sucursal_pk")
     */
    protected $sucursalRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPago", mappedBy="centroCostoRel")
     */
    protected $programacionesPagosCentroCostoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="centroCostoRel")
     */
    protected $empleadosCentroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="centroCostoRel")
     */
    protected $seleccionesCentroCostoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionGrupo", mappedBy="centroCostoRel")
     */
    protected $seleccionesGruposCentroCostoRel;              
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="centroCostoRel")
     */
    protected $incapacidadesCentroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLicencia", mappedBy="centroCostoRel")
     */
    protected $licenciasCentroCostoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="centroCostoRel")
     */
    protected $pagosCentroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuServicioCobrar", mappedBy="centroCostoRel")
     */
    protected $serviciosCobrarCentroCostoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuFactura", mappedBy="centroCostoRel")
     */
    protected $facturasCentroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSede", mappedBy="centroCostoRel")
     */
    protected $sedesCentroCostoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="centroCostoRel")
     */
    protected $examenesCentroCostoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacion", mappedBy="centroCostoRel")
     */
    protected $liquidacionesCentroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuVacacion", mappedBy="centroCostoRel")
     */
    protected $vacacionesCentroCostoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDotacion", mappedBy="centroCostoRel")
     */
    protected $dotacionesCentroCostoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuAccidenteTrabajo", mappedBy="centroCostoRel")
     */
    protected $accidentesTrabajoCentroCostoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuFacturaDetalle", mappedBy="centroCostoRel")
     */
    protected $facturasDetallesCentroCostoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="centroCostoRel")
     */
    protected $creditosCentroCostoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinario", mappedBy="centroCostoRel")
     */
    protected $disciplinariosCentroCostoRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle", mappedBy="centroCostoRel")
     */
    protected $asientosDetallesCentroCostoRel;
    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesPagosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesGruposCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->licenciasCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosCobrarCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sedesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->examenesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->liquidacionesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vacacionesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dotacionesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accidentesTrabajoCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creditosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplinariosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->asientosDetallesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCentroCostoPk
     *
     * @return integer
     */
    public function getCodigoCentroCostoPk()
    {
        return $this->codigoCentroCostoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCentroCosto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuCentroCosto
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set codigoPeriodoPagoFk
     *
     * @param integer $codigoPeriodoPagoFk
     *
     * @return RhuCentroCosto
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
     * Set fechaUltimoPago
     *
     * @param \DateTime $fechaUltimoPago
     *
     * @return RhuCentroCosto
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
     * Set fechaUltimoPagoProgramado
     *
     * @param \DateTime $fechaUltimoPagoProgramado
     *
     * @return RhuCentroCosto
     */
    public function setFechaUltimoPagoProgramado($fechaUltimoPagoProgramado)
    {
        $this->fechaUltimoPagoProgramado = $fechaUltimoPagoProgramado;

        return $this;
    }

    /**
     * Get fechaUltimoPagoProgramado
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoProgramado()
    {
        return $this->fechaUltimoPagoProgramado;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return RhuCentroCosto
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return RhuCentroCosto
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * Set pagoAbierto
     *
     * @param boolean $pagoAbierto
     *
     * @return RhuCentroCosto
     */
    public function setPagoAbierto($pagoAbierto)
    {
        $this->pagoAbierto = $pagoAbierto;

        return $this;
    }

    /**
     * Get pagoAbierto
     *
     * @return boolean
     */
    public function getPagoAbierto()
    {
        return $this->pagoAbierto;
    }

    /**
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return RhuCentroCosto
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
     * Set generarPagoAutomatico
     *
     * @param boolean $generarPagoAutomatico
     *
     * @return RhuCentroCosto
     */
    public function setGenerarPagoAutomatico($generarPagoAutomatico)
    {
        $this->generarPagoAutomatico = $generarPagoAutomatico;

        return $this;
    }

    /**
     * Get generarPagoAutomatico
     *
     * @return boolean
     */
    public function getGenerarPagoAutomatico()
    {
        return $this->generarPagoAutomatico;
    }

    /**
     * Set horaPagoAutomatico
     *
     * @param \DateTime $horaPagoAutomatico
     *
     * @return RhuCentroCosto
     */
    public function setHoraPagoAutomatico($horaPagoAutomatico)
    {
        $this->horaPagoAutomatico = $horaPagoAutomatico;

        return $this;
    }

    /**
     * Get horaPagoAutomatico
     *
     * @return \DateTime
     */
    public function getHoraPagoAutomatico()
    {
        return $this->horaPagoAutomatico;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuCentroCosto
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
     * Set porcentajeAdministracion
     *
     * @param float $porcentajeAdministracion
     *
     * @return RhuCentroCosto
     */
    public function setPorcentajeAdministracion($porcentajeAdministracion)
    {
        $this->porcentajeAdministracion = $porcentajeAdministracion;

        return $this;
    }

    /**
     * Get porcentajeAdministracion
     *
     * @return float
     */
    public function getPorcentajeAdministracion()
    {
        return $this->porcentajeAdministracion;
    }

    /**
     * Set valorAdministracion
     *
     * @param float $valorAdministracion
     *
     * @return RhuCentroCosto
     */
    public function setValorAdministracion($valorAdministracion)
    {
        $this->valorAdministracion = $valorAdministracion;

        return $this;
    }

    /**
     * Get valorAdministracion
     *
     * @return float
     */
    public function getValorAdministracion()
    {
        return $this->valorAdministracion;
    }

    /**
     * Set codigoSucursalFk
     *
     * @param integer $codigoSucursalFk
     *
     * @return RhuCentroCosto
     */
    public function setCodigoSucursalFk($codigoSucursalFk)
    {
        $this->codigoSucursalFk = $codigoSucursalFk;

        return $this;
    }

    /**
     * Get codigoSucursalFk
     *
     * @return integer
     */
    public function getCodigoSucursalFk()
    {
        return $this->codigoSucursalFk;
    }

    /**
     * Set fechaUltimoPagoPrima
     *
     * @param \DateTime $fechaUltimoPagoPrima
     *
     * @return RhuCentroCosto
     */
    public function setFechaUltimoPagoPrima($fechaUltimoPagoPrima)
    {
        $this->fechaUltimoPagoPrima = $fechaUltimoPagoPrima;

        return $this;
    }

    /**
     * Get fechaUltimoPagoPrima
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoPrima()
    {
        return $this->fechaUltimoPagoPrima;
    }

    /**
     * Set fechaUltimoPagoCesantias
     *
     * @param \DateTime $fechaUltimoPagoCesantias
     *
     * @return RhuCentroCosto
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
     * Set diasPago
     *
     * @param string $diasPago
     *
     * @return RhuCentroCosto
     */
    public function setDiasPago($diasPago)
    {
        $this->diasPago = $diasPago;

        return $this;
    }

    /**
     * Get diasPago
     *
     * @return string
     */
    public function getDiasPago()
    {
        return $this->diasPago;
    }

    /**
     * Set periodoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPeriodoPago $periodoPagoRel
     *
     * @return RhuCentroCosto
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
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuCentroCosto
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * Set sucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal $sucursalRel
     *
     * @return RhuCentroCosto
     */
    public function setSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal $sucursalRel = null)
    {
        $this->sucursalRel = $sucursalRel;

        return $this;
    }

    /**
     * Get sucursalRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal
     */
    public function getSucursalRel()
    {
        return $this->sucursalRel;
    }

    /**
     * Add programacionesPagosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addProgramacionesPagosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosCentroCostoRel)
    {
        $this->programacionesPagosCentroCostoRel[] = $programacionesPagosCentroCostoRel;

        return $this;
    }

    /**
     * Remove programacionesPagosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosCentroCostoRel
     */
    public function removeProgramacionesPagosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosCentroCostoRel)
    {
        $this->programacionesPagosCentroCostoRel->removeElement($programacionesPagosCentroCostoRel);
    }

    /**
     * Get programacionesPagosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosCentroCostoRel()
    {
        return $this->programacionesPagosCentroCostoRel;
    }

    /**
     * Add empleadosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addEmpleadosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCentroCostoRel)
    {
        $this->empleadosCentroCostoRel[] = $empleadosCentroCostoRel;

        return $this;
    }

    /**
     * Remove empleadosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCentroCostoRel
     */
    public function removeEmpleadosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCentroCostoRel)
    {
        $this->empleadosCentroCostoRel->removeElement($empleadosCentroCostoRel);
    }

    /**
     * Get empleadosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosCentroCostoRel()
    {
        return $this->empleadosCentroCostoRel;
    }

    /**
     * Add seleccionesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addSeleccionesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCentroCostoRel)
    {
        $this->seleccionesCentroCostoRel[] = $seleccionesCentroCostoRel;

        return $this;
    }

    /**
     * Remove seleccionesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCentroCostoRel
     */
    public function removeSeleccionesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCentroCostoRel)
    {
        $this->seleccionesCentroCostoRel->removeElement($seleccionesCentroCostoRel);
    }

    /**
     * Get seleccionesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesCentroCostoRel()
    {
        return $this->seleccionesCentroCostoRel;
    }

    /**
     * Add seleccionesGruposCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo $seleccionesGruposCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addSeleccionesGruposCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo $seleccionesGruposCentroCostoRel)
    {
        $this->seleccionesGruposCentroCostoRel[] = $seleccionesGruposCentroCostoRel;

        return $this;
    }

    /**
     * Remove seleccionesGruposCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo $seleccionesGruposCentroCostoRel
     */
    public function removeSeleccionesGruposCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo $seleccionesGruposCentroCostoRel)
    {
        $this->seleccionesGruposCentroCostoRel->removeElement($seleccionesGruposCentroCostoRel);
    }

    /**
     * Get seleccionesGruposCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesGruposCentroCostoRel()
    {
        return $this->seleccionesGruposCentroCostoRel;
    }

    /**
     * Add incapacidadesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addIncapacidadesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCentroCostoRel)
    {
        $this->incapacidadesCentroCostoRel[] = $incapacidadesCentroCostoRel;

        return $this;
    }

    /**
     * Remove incapacidadesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCentroCostoRel
     */
    public function removeIncapacidadesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCentroCostoRel)
    {
        $this->incapacidadesCentroCostoRel->removeElement($incapacidadesCentroCostoRel);
    }

    /**
     * Get incapacidadesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesCentroCostoRel()
    {
        return $this->incapacidadesCentroCostoRel;
    }

    /**
     * Add licenciasCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addLicenciasCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasCentroCostoRel)
    {
        $this->licenciasCentroCostoRel[] = $licenciasCentroCostoRel;

        return $this;
    }

    /**
     * Remove licenciasCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasCentroCostoRel
     */
    public function removeLicenciasCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasCentroCostoRel)
    {
        $this->licenciasCentroCostoRel->removeElement($licenciasCentroCostoRel);
    }

    /**
     * Get licenciasCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLicenciasCentroCostoRel()
    {
        return $this->licenciasCentroCostoRel;
    }

    /**
     * Add pagosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addPagosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosCentroCostoRel)
    {
        $this->pagosCentroCostoRel[] = $pagosCentroCostoRel;

        return $this;
    }

    /**
     * Remove pagosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosCentroCostoRel
     */
    public function removePagosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosCentroCostoRel)
    {
        $this->pagosCentroCostoRel->removeElement($pagosCentroCostoRel);
    }

    /**
     * Get pagosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosCentroCostoRel()
    {
        return $this->pagosCentroCostoRel;
    }

    /**
     * Add serviciosCobrarCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addServiciosCobrarCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCentroCostoRel)
    {
        $this->serviciosCobrarCentroCostoRel[] = $serviciosCobrarCentroCostoRel;

        return $this;
    }

    /**
     * Remove serviciosCobrarCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCentroCostoRel
     */
    public function removeServiciosCobrarCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCentroCostoRel)
    {
        $this->serviciosCobrarCentroCostoRel->removeElement($serviciosCobrarCentroCostoRel);
    }

    /**
     * Get serviciosCobrarCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosCobrarCentroCostoRel()
    {
        return $this->serviciosCobrarCentroCostoRel;
    }

    /**
     * Add facturasCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addFacturasCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasCentroCostoRel)
    {
        $this->facturasCentroCostoRel[] = $facturasCentroCostoRel;

        return $this;
    }

    /**
     * Remove facturasCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasCentroCostoRel
     */
    public function removeFacturasCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasCentroCostoRel)
    {
        $this->facturasCentroCostoRel->removeElement($facturasCentroCostoRel);
    }

    /**
     * Get facturasCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasCentroCostoRel()
    {
        return $this->facturasCentroCostoRel;
    }

    /**
     * Add sedesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSede $sedesCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addSedesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSede $sedesCentroCostoRel)
    {
        $this->sedesCentroCostoRel[] = $sedesCentroCostoRel;

        return $this;
    }

    /**
     * Remove sedesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSede $sedesCentroCostoRel
     */
    public function removeSedesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSede $sedesCentroCostoRel)
    {
        $this->sedesCentroCostoRel->removeElement($sedesCentroCostoRel);
    }

    /**
     * Get sedesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSedesCentroCostoRel()
    {
        return $this->sedesCentroCostoRel;
    }

    /**
     * Add examenesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addExamenesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCentroCostoRel)
    {
        $this->examenesCentroCostoRel[] = $examenesCentroCostoRel;

        return $this;
    }

    /**
     * Remove examenesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCentroCostoRel
     */
    public function removeExamenesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCentroCostoRel)
    {
        $this->examenesCentroCostoRel->removeElement($examenesCentroCostoRel);
    }

    /**
     * Get examenesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesCentroCostoRel()
    {
        return $this->examenesCentroCostoRel;
    }

    /**
     * Add liquidacionesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addLiquidacionesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesCentroCostoRel)
    {
        $this->liquidacionesCentroCostoRel[] = $liquidacionesCentroCostoRel;

        return $this;
    }

    /**
     * Remove liquidacionesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesCentroCostoRel
     */
    public function removeLiquidacionesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionesCentroCostoRel)
    {
        $this->liquidacionesCentroCostoRel->removeElement($liquidacionesCentroCostoRel);
    }

    /**
     * Get liquidacionesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLiquidacionesCentroCostoRel()
    {
        return $this->liquidacionesCentroCostoRel;
    }

    /**
     * Add vacacionesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addVacacionesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesCentroCostoRel)
    {
        $this->vacacionesCentroCostoRel[] = $vacacionesCentroCostoRel;

        return $this;
    }

    /**
     * Remove vacacionesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesCentroCostoRel
     */
    public function removeVacacionesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionesCentroCostoRel)
    {
        $this->vacacionesCentroCostoRel->removeElement($vacacionesCentroCostoRel);
    }

    /**
     * Get vacacionesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVacacionesCentroCostoRel()
    {
        return $this->vacacionesCentroCostoRel;
    }

    /**
     * Add dotacionesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addDotacionesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesCentroCostoRel)
    {
        $this->dotacionesCentroCostoRel[] = $dotacionesCentroCostoRel;

        return $this;
    }

    /**
     * Remove dotacionesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesCentroCostoRel
     */
    public function removeDotacionesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacion $dotacionesCentroCostoRel)
    {
        $this->dotacionesCentroCostoRel->removeElement($dotacionesCentroCostoRel);
    }

    /**
     * Get dotacionesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDotacionesCentroCostoRel()
    {
        return $this->dotacionesCentroCostoRel;
    }

    /**
     * Add accidentesTrabajoCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addAccidentesTrabajoCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoCentroCostoRel)
    {
        $this->accidentesTrabajoCentroCostoRel[] = $accidentesTrabajoCentroCostoRel;

        return $this;
    }

    /**
     * Remove accidentesTrabajoCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoCentroCostoRel
     */
    public function removeAccidentesTrabajoCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoCentroCostoRel)
    {
        $this->accidentesTrabajoCentroCostoRel->removeElement($accidentesTrabajoCentroCostoRel);
    }

    /**
     * Get accidentesTrabajoCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccidentesTrabajoCentroCostoRel()
    {
        return $this->accidentesTrabajoCentroCostoRel;
    }

    /**
     * Add facturasDetallesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addFacturasDetallesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesCentroCostoRel)
    {
        $this->facturasDetallesCentroCostoRel[] = $facturasDetallesCentroCostoRel;

        return $this;
    }

    /**
     * Remove facturasDetallesCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesCentroCostoRel
     */
    public function removeFacturasDetallesCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesCentroCostoRel)
    {
        $this->facturasDetallesCentroCostoRel->removeElement($facturasDetallesCentroCostoRel);
    }

    /**
     * Get facturasDetallesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesCentroCostoRel()
    {
        return $this->facturasDetallesCentroCostoRel;
    }

    /**
     * Add creditosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addCreditosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCentroCostoRel)
    {
        $this->creditosCentroCostoRel[] = $creditosCentroCostoRel;

        return $this;
    }

    /**
     * Remove creditosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCentroCostoRel
     */
    public function removeCreditosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCentroCostoRel)
    {
        $this->creditosCentroCostoRel->removeElement($creditosCentroCostoRel);
    }

    /**
     * Get creditosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreditosCentroCostoRel()
    {
        return $this->creditosCentroCostoRel;
    }

    /**
     * Add disciplinariosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addDisciplinariosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosCentroCostoRel)
    {
        $this->disciplinariosCentroCostoRel[] = $disciplinariosCentroCostoRel;

        return $this;
    }

    /**
     * Remove disciplinariosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosCentroCostoRel
     */
    public function removeDisciplinariosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinariosCentroCostoRel)
    {
        $this->disciplinariosCentroCostoRel->removeElement($disciplinariosCentroCostoRel);
    }

    /**
     * Get disciplinariosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinariosCentroCostoRel()
    {
        return $this->disciplinariosCentroCostoRel;
    }

    /**
     * Add asientosDetallesCentroCostoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel
     *
     * @return RhuCentroCosto
     */
    public function addAsientosDetallesCentroCostoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel)
    {
        $this->asientosDetallesCentroCostoRel[] = $asientosDetallesCentroCostoRel;

        return $this;
    }

    /**
     * Remove asientosDetallesCentroCostoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel
     */
    public function removeAsientosDetallesCentroCostoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel)
    {
        $this->asientosDetallesCentroCostoRel->removeElement($asientosDetallesCentroCostoRel);
    }

    /**
     * Get asientosDetallesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsientosDetallesCentroCostoRel()
    {
        return $this->asientosDetallesCentroCostoRel;
    }
}
