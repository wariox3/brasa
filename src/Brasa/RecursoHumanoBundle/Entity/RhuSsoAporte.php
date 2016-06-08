<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_aporte")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoAporteRepository")
 */
class RhuSsoAporte
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aporte_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAportePk;   
    
    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;    
    
    /**
     * @ORM\Column(name="codigo_periodo_detalle_fk", type="integer")
     */    
    private $codigoPeriodoDetalleFk;     
    
    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="integer")
     */    
    private $codigoSucursalFk; 

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;  
    
    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;
    
    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    
    
    
    /**
     * @ORM\Column(name="tipo_registro", type="bigint")
     */    
    private $tipoRegistro;    

    /**
     * @ORM\Column(name="secuencia", type="smallint")
     */    
    private $secuencia;        

    /**
     * @ORM\Column(name="tipo_documento", type="string", length=2)
     */    
    private $tipoDocumento;    
    
    /**
     * @ORM\Column(name="tipo_cotizante", type="smallint")
     */    
    private $tipoCotizante;    

    /**
     * @ORM\Column(name="subtipo_cotizante", type="smallint")
     */    
    private $subtipoCotizante;    
    
    /**
     * @ORM\Column(name="extranjero_no_obligado_cotizar_pension", type="string", length=1)
     */    
    private $extranjeroNoObligadoCotizarPension;
    
    /**
     * @ORM\Column(name="colombiano_residente_exterior", type="string", length=1)
     */    
    private $colombianoResidenteExterior;    

    /**
     * @ORM\Column(name="codigo_departamento_ubicacion_laboral", type="string", length=2)
     */    
    private $codigoDepartamentoUbicacionlaboral;    

    /**
     * @ORM\Column(name="codigo_municipio_ubicacion_laboral", type="string", length=3)
     */    
    private $codigoMunicipioUbicacionlaboral;        
    
    /**
     * @ORM\Column(name="primer_nombre", type="string", length=20)
     */
    private $primerNombre;     

    /**
     * @ORM\Column(name="segundo_nombre", type="string", length=30, nullable=true)
     */
    private $segundoNombre;    
    
    /**
     * @ORM\Column(name="primer_apellido", type="string", length=20)
     */
    private $primerApellido;

    /**
     * @ORM\Column(name="segundo_apellido", type="string", length=30, nullable=true)
     */
    private $segundoApellido;    
    
    /**
     * @ORM\Column(name="ingreso", type="string", length=1)
     */
    private $ingreso = ' ';    

    /**
     * @ORM\Column(name="retiro", type="string", length=1)
     */
    private $retiro = ' ';    
    
    /**
     * @ORM\Column(name="traslado_desde_otra_eps", type="string", length=1)
     */
    private $trasladoDesdeOtraEps = ' ';

    /**
     * @ORM\Column(name="traslado_a_otra_eps", type="string", length=1)
     */
    private $trasladoAOtraEps = ' ';    
    
    /**
     * @ORM\Column(name="traslado_desde_otra_pension", type="string", length=1)
     */
    private $trasladoDesdeOtraPension = ' ';

    /**
     * @ORM\Column(name="traslado_a_otra_pension", type="string", length=1)
     */
    private $trasladoAOtraPension = ' ';    

    /**
     * @ORM\Column(name="variacion_permanente_salario", type="string", length=1)
     */
    private $variacionPermanenteSalario = ' ';    
    
    /**
     * @ORM\Column(name="correcciones", type="string", length=1)
     */
    private $correcciones = ' '; 
    
    /**
     * @ORM\Column(name="variacion_transitoria_salario", type="string", length=1)
     */
    private $variacionTransitoriaSalario = ' ';      
    
    /**
     * @ORM\Column(name="suspension_temporal_contrato_licencia_servicios", type="string", length=1)
     */
    private $suspensionTemporalContratoLicenciaServicios = ' ';    
    
    /**
     * @ORM\Column(name="dias_licencia", type="integer")
     */    
    private $diasLicencia = 0;     
    
    /**
     * @ORM\Column(name="incapacidad_general", type="string", length=1)
     */
    private $incapacidadGeneral = ' ';    
    
    /**
     * @ORM\Column(name="dias_incapacidad_general", type="integer")
     */
    private $diasIncapacidadGeneral = 0;    
    
    /**
     * @ORM\Column(name="licencia_maternidad", type="string", length=1)
     */
    private $licenciaMaternidad = ' ';        

    /**
     * @ORM\Column(name="dias_licencia_maternidad", type="integer")
     */
    private $diasLicenciaMaternidad = 0;    
    
    /**
     * @ORM\Column(name="vacaciones", type="string", length=1)
     */
    private $vacaciones = ' ';     

    /**
     * @ORM\Column(name="dias_vacaciones", type="integer")
     */
    private $diasVacaciones = 0;     
    
    /**
     * @ORM\Column(name="aporte_voluntario", type="string", length=1)
     */
    private $aporteVoluntario = ' ';     

    /**
     * @ORM\Column(name="variacion_centros_trabajo", type="string", length=1)
     */
    private $variacionCentrosTrabajo = ' ';         
    
    /**
     * @ORM\Column(name="incapacidad_accidente_trabajo_enfermedad_profesional", type="integer")
     */
    private $incapacidadAccidenteTrabajoEnfermedadProfesional = 0;    
    
    /**
     * @ORM\Column(name="codigo_entidad_pension_pertenece", type="string", length=6)
     */
    private $codigoEntidadPensionPertenece;     
    
    /**
     * @ORM\Column(name="codigo_entidad_pension_traslada", type="string", length=6, nullable=true)
     */
    private $codigoEntidadPensionTraslada;    
    
    /**
     * @ORM\Column(name="codigo_entidad_salud_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadSaludPertenece;     
    
    /**
     * @ORM\Column(name="codigo_entidad_salud_traslada", type="string", length=6, nullable=true)
     */
    private $codigoEntidadSaludTraslada;    
    
    /**
     * @ORM\Column(name="codigo_entidad_caja_pertenece", type="string", length=6)
     */
    private $codigoEntidadCajaPertenece;    
    
    /**
     * @ORM\Column(name="dias_cotizados_pension", type="integer")
     */
    private $diasCotizadosPension = 0;
    
    /**
     * @ORM\Column(name="dias_cotizados_salud", type="integer")
     */
    private $diasCotizadosSalud = 0;    

    /**
     * @ORM\Column(name="dias_cotizados_riesgos_profesionales", type="integer")
     */
    private $diasCotizadosRiesgosProfesionales = 0;    
        
    /**
     * @ORM\Column(name="dias_cotizados_caja_compensacion", type="integer")
     */
    private $diasCotizadosCajaCompensacion = 0;    
    
    /**
     * @ORM\Column(name="salario_basico", type="float")
     */
    private $salarioBasico = 0;
    
    /**
     * @ORM\Column(name="salario_mes_anterior", type="float")
     */
    private $salarioMesAnterior = 0;    
    
    /**
     * @ORM\Column(name="salario_integral", type="string", length=1)
     */
    private $salarioIntegral = ' ';    
    
    /**
     * @ORM\Column(name="suplementario", type="float")
     */
    private $suplementario = 0;     
    
    /**
     * @ORM\Column(name="ibc_pension", type="float")
     */
    private $ibcPension = 0;     

    /**
     * @ORM\Column(name="ibc_salud", type="float")
     */
    private $ibcSalud = 0;    
    
    /**
     * @ORM\Column(name="ibc_riesgos_profesionales", type="float")
     */
    private $ibcRiesgosProfesionales = 0;    
    
    /**
     * @ORM\Column(name="ibc_caja", type="float")
     */
    private $ibcCaja = 0;     
    
    /**
     * @ORM\Column(name="tarifa_pension", type="float")
     */
    private $tarifaPension = 0;         

    /**
     * @ORM\Column(name="tarifa_salud", type="float")
     */
    private $tarifaSalud = 0;
    
    /**
     * @ORM\Column(name="tarifa_riesgos", type="float")
     */
    private $tarifaRiesgos = 0;    
    
    /**
     * @ORM\Column(name="tarifa_caja", type="float")
     */
    private $tarifaCaja = 0;  
    
    /**
     * @ORM\Column(name="tarifa_sena", type="float")
     */
    private $tarifaSena = 0;     
    
    /**
     * @ORM\Column(name="tarifa_icbf", type="float")
     */
    private $tarifaIcbf = 0;     
    
    /**
     * @ORM\Column(name="cotizacion_pension", type="float")
     */
    private $cotizacionPension = 0;    

    /**
     * @ORM\Column(name="cotizacion_salud", type="float")
     */
    private $cotizacionSalud = 0; 
    
    /**
     * @ORM\Column(name="cotizacion_riesgos", type="float")
     */
    private $cotizacionRiesgos = 0;
    
    /**
     * @ORM\Column(name="cotizacion_caja", type="float")
     */
    private $cotizacionCaja = 0;
    
    /**
     * @ORM\Column(name="cotizacion_sena", type="float")
     */
    private $cotizacionSena = 0;    
    
    /**
     * @ORM\Column(name="cotizacion_icbf", type="float")
     */
    private $cotizacionIcbf = 0;    
    
    /**
     * @ORM\Column(name="aporte_voluntario_fondo_pensiones_obligatorias", type="string", length=9, nullable=true)
     */
    private $aporteVoluntarioFondoPensionesObligatorias;    
    
    /**
     * @ORM\Column(name="cotizacion_voluntario_fondo_pensiones_obligatorias", type="string", length=9, nullable=true)
     */
    private $cotizacionVoluntarioFondoPensionesObligatorias;    
    
    /**
     * @ORM\Column(name="total_cotizacion", type="float")
     */
    private $totalCotizacion;    
    
    /**
     * @ORM\Column(name="aportes_fondo_solidaridad_pensional_solidaridad", type="float")
     */
    private $aportesFondoSolidaridadPensionalSolidaridad = 0;    
    
    /**
     * @ORM\Column(name="aportes_fondo_solidaridad_pensional_subsistencia", type="float")
     */
    private $aportesFondoSolidaridadPensionalSubsistencia = 0;
    
    /**
     * @ORM\Column(name="valor_upc_adicional", type="float")
     */
    private $valorUpcAdicional = 0;
    
    /**
     * @ORM\Column(name="numero_autorizacion_incapacidad_enfermedad_general", type="string", length=30, nullable=true)
     */
    private $numeroAutorizacionIncapacidadEnfermedadGeneral;
    
    /**
     * @ORM\Column(name="valor_incapacidad_enfermedad_general", type="float")
     */
    private $valorIncapacidadEnfermedadGeneral = 0;
    
    /**
     * @ORM\Column(name="numero_autorizacion_licencia_maternidad_paternidad", type="string", length=30, nullable=true)
     */
    private $numeroAutorizacionLicenciaMaternidadPaternidad;
    
    /**
     * @ORM\Column(name="valor_incapacidad_licencia_maternidad_paternidad", type="float")
     */
    private $valorIncapacidadLicenciaMaternidadPaternidad = 0;
    
    /**
     * @ORM\Column(name="centro_trabajo_codigo_ct", type="string", length=30, nullable=true)
     */
    private $centroTrabajoCodigoCt;
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;
    
    /**
     * @ORM\Column(name="tarifa_aporte_esap", type="float")
     */
    private $tarifaAportesESAP = 0;
    
    /**
     * @ORM\Column(name="valor_aporte_esap", type="float")
     */
    private $valorAportesESAP = 0;
    
    /**
     * @ORM\Column(name="tarifa_aporte_men", type="float")
     */
    private $tarifaAportesMEN = 0;
    
    /**
     * @ORM\Column(name="valor_aporte_men", type="float")
     */
    private $valorAportesMEN = 0;
    
    /**
     * @ORM\Column(name="tipo_documento_responsable_upc", type="string", length=4, nullable=true)
     */    
    private $tipoDocumentoResponsableUPC;
    
    /**
     * @ORM\Column(name="numero_identificacion_responsable_upc_adicional", type="string", length=30, nullable=true)
     */    
    private $numeroIdentificacionResponsableUPCAdicional;
    
    /**
     * @ORM\Column(name="cotizante_exonerado_pago_aporte_parafiscales_salud", type="string", length=20, nullable=true)
     */    
    private $cotizanteExoneradoPagoAporteParafiscalesSalud;
    
    /**
     * @ORM\Column(name="codigo_administradora_riesgos_laborales", type="string", length=20, nullable=true)
     */    
    private $codigoAdministradoraRiesgosLaborales;
    
    /**
     * @ORM\Column(name="clase_riesgo_afiliado", type="string", length=20, nullable=true)
     */    
    private $claseRiesgoAfiliado;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoPeriodo", inversedBy="ssoAportesSsoPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $ssoPeriodoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoPeriodoDetalle", inversedBy="ssoAportesSsoPeriodoDetalleRel")
     * @ORM\JoinColumn(name="codigo_periodo_detalle_fk", referencedColumnName="codigo_periodo_detalle_pk")
     */
    protected $ssoPeriodoDetalleRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoSucursal", inversedBy="ssoAportesSsoSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk", referencedColumnName="codigo_sucursal_pk")
     */
    protected $ssoSucursalRel;       
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="ssoAportesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;      

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="ssoAportesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;          

    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="ssoAportesCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;     
    


}
