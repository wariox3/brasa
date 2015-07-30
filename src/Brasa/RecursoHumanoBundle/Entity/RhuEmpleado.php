<?php

namespace Brasa\RecursoHumanoBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"numeroIdentificacion"},message="Ya existe este número de identificación")
 * @DoctrineAssert\UniqueEntity(fields={"correo"},message="Ya existe este correo") 
 */

class RhuEmpleado
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoPk;
    
    /**
     * @ORM\Column(name="codigo_tipo_identificacion_fk", type="string", length=1, nullable=true)
     */    
    private $codigoTipoIdentificacionFk;     
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false, unique=true)
     */
         
    private $numeroIdentificacion;
    
    /**
     * @ORM\Column(name="libreta_militar", type="string", length=20, nullable=false)
     */
         
    private $libretaMilitar;
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto;    

    /**
     * @ORM\Column(name="nombre1", type="string", length=30, nullable=true)
     */    
    private $nombre1;        
    
    /**
     * @ORM\Column(name="nombre2", type="string", length=30, nullable=true)
     */    
    private $nombre2;    
    
    /**
     * @ORM\Column(name="apellido1", type="string", length=30, nullable=true)
     */    
    private $apellido1;    

    /**
     * @ORM\Column(name="apellido2", type="string", length=30, nullable=true)
     */    
    private $apellido2;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono;    
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */    
    private $celular; 
    
    /**
     * @ORM\Column(name="direccion", type="string", length=30, nullable=true)
     */    
    private $direccion; 
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadFk;
    
    /**
     * @ORM\Column(name="codigo_ciudad_expedicion_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadExpedicionFk;
    
    /**
     * @ORM\Column(name="codigo_barrio_fk", type="integer", length=80, nullable=true)
     */    
    private $codigoBarrioFk;    
    
    /**
     * @ORM\Column(name="codigo_rh_fk", type="integer", nullable=true)
     */    
    private $codigoRhPk;     
    
    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */    
    private $codigoSexoFk;     
    
    /**
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     * @Assert\Email(
     * message = "Correo incorrecto",
     * )
     */    
    private $correo;     
        
    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */ 
    
    private $fecha_nacimiento; 
    
    /**
     * @ORM\Column(name="codigo_ciudad_nacimiento_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadNacimientoFk;
    
     /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    
    private $codigoEstadoCivilFk;
    
    /**
     * @ORM\Column(name="cuenta", type="string", length=80, nullable=true)
     */    
    private $cuenta;    
    
    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer", nullable=true)
     */    
    private $codigoBancoFk;         
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;           
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;    
    
    /**
     * @ORM\Column(name="cargo_descripcion", type="string", length=60, nullable=true)
     */    
    private $cargoDescripcion;      
    
    /**
     * @ORM\Column(name="auxilio_transporte", type="boolean")
     */    
    private $auxilioTransporte = 0;     
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;         
    
    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadSaludFk;    

    /**
     * @ORM\Column(name="codigo_entidad_pension_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadPensionFk;    
    
    /**
     * @ORM\Column(name="codigo_tipo_pension_fk", type="integer", nullable=true)
     */    
    private $codigoTipoPensionFk;     

    /**
     * @ORM\Column(name="codigo_entidad_caja_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadCajaFk;     
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = 1;
    
    /**     
     * @ORM\Column(name="cabeza_hogar", type="boolean")
     */    
    private $cabezaHogar= 0;
    
    
    /**     
     * @ORM\Column(name="camisa", type="string", length=10, nullable=true)
     */    
    private $camisa;
    
    /**     
     * @ORM\Column(name="jeans", type="string", length=10, nullable=true)
     */    
    private $jeans;
    
    /**     
     * @ORM\Column(name="calzado", type="string", length=10,  nullable=true)
     */    
    private $calzado;
    
    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer", nullable=true)
     */    
    private $codigoClasificacionRiesgoFk;     
    
    /**
     * @ORM\Column(name="fecha_contrato", type="date", nullable=true)
     */    
    private $fechaContrato;   
    
    /**
     * @ORM\Column(name="fecha_finaliza_contrato", type="date", nullable=true)
     */    
    private $fechaFinalizaContrato;    
    
    /**     
     * @ORM\Column(name="contrato_indefinido", type="boolean")
     */    
    private $contratoIndefinido = 0;
    
    /**     
     * Empleado pagado por la entidad de salud, exonerado de los pagos
     * @ORM\Column(name="pagado_entidad_salud", type="boolean")
     */    
    private $pagadoEntidadSalud = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\Column(name="codigo_tipo_tiempo_fk", type="integer", nullable=true)
     */    
    private $codigoTipoTiempoFk;     
    
    /**
     * @ORM\Column(name="horas_laboradas_periodo", type="float")
     */
    private $horasLaboradasPeriodo = 0;
    
    /**
     * @ORM\Column(name="padre_familia", type="float")
     */
    private $padreFamilia = 0;
    
    /**
     * @ORM\Column(name="codigo_empleado_estudio_tipo_fk", type="integer", length=4, nullable=true)
     */    
    private $codigoEmpleadoEstudioTipoFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuClasificacionRiesgo", inversedBy="empleadosClasificacionRiesgoRel")
     * @ORM\JoinColumn(name="codigo_clasificacion_riesgo_fk", referencedColumnName="codigo_clasificacion_riesgo_pk")
     */
    protected $clasificacionRiesgoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoEstudioTipo", inversedBy="empleadosEmpleadoEstudioTipoRel")
     * @ORM\JoinColumn(name="codigo_empleado_estudio_tipo_fk", referencedColumnName="codigo_empleado_estudio_tipo_pk")
     */
    protected $empleadoEstudioTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoIdentificacion", inversedBy="empleadosTipoIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_tipo_identificacion_fk", referencedColumnName="codigo_tipo_identificacion_pk")
     */
    protected $tipoIdentificacionRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEstadoCivil", inversedBy="empleadosEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="empleadosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;                 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuBanco", inversedBy="empleadosBancoRel")
     * @ORM\JoinColumn(name="codigo_banco_fk", referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadSalud", inversedBy="empleadosEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk", referencedColumnName="codigo_entidad_salud_pk")
     */
    protected $entidadSaludRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadPension", inversedBy="empleadosEntidadPensionRel")
     * @ORM\JoinColumn(name="codigo_entidad_pension_fk", referencedColumnName="codigo_entidad_pension_pk")
     */
    protected $entidadPensionRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadCaja", inversedBy="empleadosEntidadCajaRel")
     * @ORM\JoinColumn(name="codigo_entidad_caja_fk", referencedColumnName="codigo_entidad_caja_pk")
     */
    protected $entidadCajaRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoTiempo", inversedBy="empleadosTipoTiempoRel")
     * @ORM\JoinColumn(name="codigo_tipo_tiempo_fk", referencedColumnName="codigo_tipo_tiempo_pk")
     */
    protected $tipoTiempoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadosCiudadNacimientoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_nacimiento_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadNacimientoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadosCiudadExpedicionRel")
     * @ORM\JoinColumn(name="codigo_ciudad_expedicion_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadExpedicionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="empleadosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoPension", inversedBy="empleadosTipoPensionRel")
     * @ORM\JoinColumn(name="codigo_tipo_pension_fk", referencedColumnName="codigo_tipo_pension_pk")
     */
    protected $tipoPensionRel;         
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="empleadoRel")
     */
    protected $pagosEmpleadoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuServicioCobrar", mappedBy="empleadoRel")
     */
    protected $serviciosCobrarEmpleadoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoAdicional", mappedBy="empleadoRel")
     */
    protected $pagosAdicionalesEmpleadoRel;      
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="empleadoRel")
     */
    protected $creditosEmpleadoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="empleadoRel")
     */
    protected $incapacidadesEmpleadoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuLicencia", mappedBy="empleadoRel")
     */
    protected $licenciasEmpleadoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="empleadoRel")
     */
    protected $contratosEmpleadoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPagoDetalle", mappedBy="empleadoRel")
     */
    protected $programacionesPagosDetallesEmpleadoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacion", mappedBy="empleadoRel")
     */
    protected $liquidacionesEmpleadoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinario", mappedBy="empleadoRel")
     */
    protected $disciplinariosEmpleadoRel;         
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSSPeriodoDetalle", mappedBy="empleadoRel")
     */
    protected $SSPeriodosDetallesEmpleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenBarrio", inversedBy="rhuEmpleadosBarrioRel")
     * @ORM\JoinColumn(name="codigo_barrio_fk", referencedColumnName="codigo_barrio_pk")
     */
    protected $barrioRel;
    
        /**
     * @ORM\ManyToOne(targetEntity="RhuRh", inversedBy="empleadosRhRel")
     * @ORM\JoinColumn(name="codigo_rh_fk", referencedColumnName="codigo_rh_pk")
     */
    protected $rhRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoFamilia", mappedBy="empleadoRel")
     */
    protected $empleadosFamiliasEmpleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoEstudio", mappedBy="empleadoRel")
     */
    protected $empleadosEstudiosEmpleadoRel;
   
 
}
