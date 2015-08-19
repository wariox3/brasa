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
     * @ORM\OneToMany(targetEntity="RhuPagoAdicional", mappedBy="centroCostoRel")
     */
    protected $pagosAdicionalesCentroCostoRel;       
    
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
    

}
