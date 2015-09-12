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
    private $tipo_documento;    
    
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
     * @ORM\Column(name="segundo_nombre", type="string", length=30)
     */
    private $segundoNombre;    
    
    /**
     * @ORM\Column(name="primer_apellido", type="string", length=20)
     */
    private $primerApellido;

    /**
     * @ORM\Column(name="segundo_apellido", type="string", length=30)
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
    private $salarioIntegral = 0;    
    
    /**
     * @ORM\Column(name="suplementario", type="float")
     */
    private $suplementario = 0;     
    
    
    
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
     * Get codigoAportePk
     *
     * @return integer
     */
    public function getCodigoAportePk()
    {
        return $this->codigoAportePk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return RhuSsoAporte
     */
    public function setCodigoPeriodoFk($codigoPeriodoFk)
    {
        $this->codigoPeriodoFk = $codigoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoFk()
    {
        return $this->codigoPeriodoFk;
    }

    /**
     * Set codigoPeriodoDetalleFk
     *
     * @param integer $codigoPeriodoDetalleFk
     *
     * @return RhuSsoAporte
     */
    public function setCodigoPeriodoDetalleFk($codigoPeriodoDetalleFk)
    {
        $this->codigoPeriodoDetalleFk = $codigoPeriodoDetalleFk;

        return $this;
    }

    /**
     * Get codigoPeriodoDetalleFk
     *
     * @return integer
     */
    public function getCodigoPeriodoDetalleFk()
    {
        return $this->codigoPeriodoDetalleFk;
    }

    /**
     * Set codigoSucursalFk
     *
     * @param integer $codigoSucursalFk
     *
     * @return RhuSsoAporte
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuSsoAporte
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
     * @return RhuSsoAporte
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
     * Set tipoRegistro
     *
     * @param integer $tipoRegistro
     *
     * @return RhuSsoAporte
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;

        return $this;
    }

    /**
     * Get tipoRegistro
     *
     * @return integer
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * Set secuencia
     *
     * @param integer $secuencia
     *
     * @return RhuSsoAporte
     */
    public function setSecuencia($secuencia)
    {
        $this->secuencia = $secuencia;

        return $this;
    }

    /**
     * Get secuencia
     *
     * @return integer
     */
    public function getSecuencia()
    {
        return $this->secuencia;
    }

    /**
     * Set tipoDocumento
     *
     * @param string $tipoDocumento
     *
     * @return RhuSsoAporte
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipo_documento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return string
     */
    public function getTipoDocumento()
    {
        return $this->tipo_documento;
    }

    /**
     * Set tipoCotizante
     *
     * @param integer $tipoCotizante
     *
     * @return RhuSsoAporte
     */
    public function setTipoCotizante($tipoCotizante)
    {
        $this->tipoCotizante = $tipoCotizante;

        return $this;
    }

    /**
     * Get tipoCotizante
     *
     * @return integer
     */
    public function getTipoCotizante()
    {
        return $this->tipoCotizante;
    }

    /**
     * Set subtipoCotizante
     *
     * @param integer $subtipoCotizante
     *
     * @return RhuSsoAporte
     */
    public function setSubtipoCotizante($subtipoCotizante)
    {
        $this->subtipoCotizante = $subtipoCotizante;

        return $this;
    }

    /**
     * Get subtipoCotizante
     *
     * @return integer
     */
    public function getSubtipoCotizante()
    {
        return $this->subtipoCotizante;
    }

    /**
     * Set extranjeroNoObligadoCotizarPension
     *
     * @param string $extranjeroNoObligadoCotizarPension
     *
     * @return RhuSsoAporte
     */
    public function setExtranjeroNoObligadoCotizarPension($extranjeroNoObligadoCotizarPension)
    {
        $this->extranjeroNoObligadoCotizarPension = $extranjeroNoObligadoCotizarPension;

        return $this;
    }

    /**
     * Get extranjeroNoObligadoCotizarPension
     *
     * @return string
     */
    public function getExtranjeroNoObligadoCotizarPension()
    {
        return $this->extranjeroNoObligadoCotizarPension;
    }

    /**
     * Set colombianoResidenteExterior
     *
     * @param string $colombianoResidenteExterior
     *
     * @return RhuSsoAporte
     */
    public function setColombianoResidenteExterior($colombianoResidenteExterior)
    {
        $this->colombianoResidenteExterior = $colombianoResidenteExterior;

        return $this;
    }

    /**
     * Get colombianoResidenteExterior
     *
     * @return string
     */
    public function getColombianoResidenteExterior()
    {
        return $this->colombianoResidenteExterior;
    }

    /**
     * Set codigoDepartamentoUbicacionlaboral
     *
     * @param string $codigoDepartamentoUbicacionlaboral
     *
     * @return RhuSsoAporte
     */
    public function setCodigoDepartamentoUbicacionlaboral($codigoDepartamentoUbicacionlaboral)
    {
        $this->codigoDepartamentoUbicacionlaboral = $codigoDepartamentoUbicacionlaboral;

        return $this;
    }

    /**
     * Get codigoDepartamentoUbicacionlaboral
     *
     * @return string
     */
    public function getCodigoDepartamentoUbicacionlaboral()
    {
        return $this->codigoDepartamentoUbicacionlaboral;
    }

    /**
     * Set codigoMunicipioUbicacionlaboral
     *
     * @param string $codigoMunicipioUbicacionlaboral
     *
     * @return RhuSsoAporte
     */
    public function setCodigoMunicipioUbicacionlaboral($codigoMunicipioUbicacionlaboral)
    {
        $this->codigoMunicipioUbicacionlaboral = $codigoMunicipioUbicacionlaboral;

        return $this;
    }

    /**
     * Get codigoMunicipioUbicacionlaboral
     *
     * @return string
     */
    public function getCodigoMunicipioUbicacionlaboral()
    {
        return $this->codigoMunicipioUbicacionlaboral;
    }

    /**
     * Set primerNombre
     *
     * @param string $primerNombre
     *
     * @return RhuSsoAporte
     */
    public function setPrimerNombre($primerNombre)
    {
        $this->primerNombre = $primerNombre;

        return $this;
    }

    /**
     * Get primerNombre
     *
     * @return string
     */
    public function getPrimerNombre()
    {
        return $this->primerNombre;
    }

    /**
     * Set segundoNombre
     *
     * @param string $segundoNombre
     *
     * @return RhuSsoAporte
     */
    public function setSegundoNombre($segundoNombre)
    {
        $this->segundoNombre = $segundoNombre;

        return $this;
    }

    /**
     * Get segundoNombre
     *
     * @return string
     */
    public function getSegundoNombre()
    {
        return $this->segundoNombre;
    }

    /**
     * Set primerApellido
     *
     * @param string $primerApellido
     *
     * @return RhuSsoAporte
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    /**
     * Get primerApellido
     *
     * @return string
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Set segundoApellido
     *
     * @param string $segundoApellido
     *
     * @return RhuSsoAporte
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    /**
     * Get segundoApellido
     *
     * @return string
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Set ingreso
     *
     * @param string $ingreso
     *
     * @return RhuSsoAporte
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return string
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set retiro
     *
     * @param string $retiro
     *
     * @return RhuSsoAporte
     */
    public function setRetiro($retiro)
    {
        $this->retiro = $retiro;

        return $this;
    }

    /**
     * Get retiro
     *
     * @return string
     */
    public function getRetiro()
    {
        return $this->retiro;
    }

    /**
     * Set trasladoDesdeOtraEps
     *
     * @param string $trasladoDesdeOtraEps
     *
     * @return RhuSsoAporte
     */
    public function setTrasladoDesdeOtraEps($trasladoDesdeOtraEps)
    {
        $this->trasladoDesdeOtraEps = $trasladoDesdeOtraEps;

        return $this;
    }

    /**
     * Get trasladoDesdeOtraEps
     *
     * @return string
     */
    public function getTrasladoDesdeOtraEps()
    {
        return $this->trasladoDesdeOtraEps;
    }

    /**
     * Set trasladoAOtraEps
     *
     * @param string $trasladoAOtraEps
     *
     * @return RhuSsoAporte
     */
    public function setTrasladoAOtraEps($trasladoAOtraEps)
    {
        $this->trasladoAOtraEps = $trasladoAOtraEps;

        return $this;
    }

    /**
     * Get trasladoAOtraEps
     *
     * @return string
     */
    public function getTrasladoAOtraEps()
    {
        return $this->trasladoAOtraEps;
    }

    /**
     * Set trasladoDesdeOtraPension
     *
     * @param string $trasladoDesdeOtraPension
     *
     * @return RhuSsoAporte
     */
    public function setTrasladoDesdeOtraPension($trasladoDesdeOtraPension)
    {
        $this->trasladoDesdeOtraPension = $trasladoDesdeOtraPension;

        return $this;
    }

    /**
     * Get trasladoDesdeOtraPension
     *
     * @return string
     */
    public function getTrasladoDesdeOtraPension()
    {
        return $this->trasladoDesdeOtraPension;
    }

    /**
     * Set trasladoAOtraPension
     *
     * @param string $trasladoAOtraPension
     *
     * @return RhuSsoAporte
     */
    public function setTrasladoAOtraPension($trasladoAOtraPension)
    {
        $this->trasladoAOtraPension = $trasladoAOtraPension;

        return $this;
    }

    /**
     * Get trasladoAOtraPension
     *
     * @return string
     */
    public function getTrasladoAOtraPension()
    {
        return $this->trasladoAOtraPension;
    }

    /**
     * Set variacionPermanenteSalario
     *
     * @param string $variacionPermanenteSalario
     *
     * @return RhuSsoAporte
     */
    public function setVariacionPermanenteSalario($variacionPermanenteSalario)
    {
        $this->variacionPermanenteSalario = $variacionPermanenteSalario;

        return $this;
    }

    /**
     * Get variacionPermanenteSalario
     *
     * @return string
     */
    public function getVariacionPermanenteSalario()
    {
        return $this->variacionPermanenteSalario;
    }

    /**
     * Set correcciones
     *
     * @param string $correcciones
     *
     * @return RhuSsoAporte
     */
    public function setCorrecciones($correcciones)
    {
        $this->correcciones = $correcciones;

        return $this;
    }

    /**
     * Get correcciones
     *
     * @return string
     */
    public function getCorrecciones()
    {
        return $this->correcciones;
    }

    /**
     * Set variacionTransitoriaSalario
     *
     * @param string $variacionTransitoriaSalario
     *
     * @return RhuSsoAporte
     */
    public function setVariacionTransitoriaSalario($variacionTransitoriaSalario)
    {
        $this->variacionTransitoriaSalario = $variacionTransitoriaSalario;

        return $this;
    }

    /**
     * Get variacionTransitoriaSalario
     *
     * @return string
     */
    public function getVariacionTransitoriaSalario()
    {
        return $this->variacionTransitoriaSalario;
    }

    /**
     * Set suspensionTemporalContratoLicenciaServicios
     *
     * @param string $suspensionTemporalContratoLicenciaServicios
     *
     * @return RhuSsoAporte
     */
    public function setSuspensionTemporalContratoLicenciaServicios($suspensionTemporalContratoLicenciaServicios)
    {
        $this->suspensionTemporalContratoLicenciaServicios = $suspensionTemporalContratoLicenciaServicios;

        return $this;
    }

    /**
     * Get suspensionTemporalContratoLicenciaServicios
     *
     * @return string
     */
    public function getSuspensionTemporalContratoLicenciaServicios()
    {
        return $this->suspensionTemporalContratoLicenciaServicios;
    }

    /**
     * Set diasLicencia
     *
     * @param integer $diasLicencia
     *
     * @return RhuSsoAporte
     */
    public function setDiasLicencia($diasLicencia)
    {
        $this->diasLicencia = $diasLicencia;

        return $this;
    }

    /**
     * Get diasLicencia
     *
     * @return integer
     */
    public function getDiasLicencia()
    {
        return $this->diasLicencia;
    }

    /**
     * Set incapacidadGeneral
     *
     * @param string $incapacidadGeneral
     *
     * @return RhuSsoAporte
     */
    public function setIncapacidadGeneral($incapacidadGeneral)
    {
        $this->incapacidadGeneral = $incapacidadGeneral;

        return $this;
    }

    /**
     * Get incapacidadGeneral
     *
     * @return string
     */
    public function getIncapacidadGeneral()
    {
        return $this->incapacidadGeneral;
    }

    /**
     * Set diasIncapacidadGeneral
     *
     * @param integer $diasIncapacidadGeneral
     *
     * @return RhuSsoAporte
     */
    public function setDiasIncapacidadGeneral($diasIncapacidadGeneral)
    {
        $this->diasIncapacidadGeneral = $diasIncapacidadGeneral;

        return $this;
    }

    /**
     * Get diasIncapacidadGeneral
     *
     * @return integer
     */
    public function getDiasIncapacidadGeneral()
    {
        return $this->diasIncapacidadGeneral;
    }

    /**
     * Set licenciaMaternidad
     *
     * @param string $licenciaMaternidad
     *
     * @return RhuSsoAporte
     */
    public function setLicenciaMaternidad($licenciaMaternidad)
    {
        $this->licenciaMaternidad = $licenciaMaternidad;

        return $this;
    }

    /**
     * Get licenciaMaternidad
     *
     * @return string
     */
    public function getLicenciaMaternidad()
    {
        return $this->licenciaMaternidad;
    }

    /**
     * Set diasLicenciaMaternidad
     *
     * @param integer $diasLicenciaMaternidad
     *
     * @return RhuSsoAporte
     */
    public function setDiasLicenciaMaternidad($diasLicenciaMaternidad)
    {
        $this->diasLicenciaMaternidad = $diasLicenciaMaternidad;

        return $this;
    }

    /**
     * Get diasLicenciaMaternidad
     *
     * @return integer
     */
    public function getDiasLicenciaMaternidad()
    {
        return $this->diasLicenciaMaternidad;
    }

    /**
     * Set vacaciones
     *
     * @param string $vacaciones
     *
     * @return RhuSsoAporte
     */
    public function setVacaciones($vacaciones)
    {
        $this->vacaciones = $vacaciones;

        return $this;
    }

    /**
     * Get vacaciones
     *
     * @return string
     */
    public function getVacaciones()
    {
        return $this->vacaciones;
    }

    /**
     * Set salarioBasico
     *
     * @param float $salarioBasico
     *
     * @return RhuSsoAporte
     */
    public function setSalarioBasico($salarioBasico)
    {
        $this->salarioBasico = $salarioBasico;

        return $this;
    }

    /**
     * Get salarioBasico
     *
     * @return float
     */
    public function getSalarioBasico()
    {
        return $this->salarioBasico;
    }

    /**
     * Set salarioMesAnterior
     *
     * @param float $salarioMesAnterior
     *
     * @return RhuSsoAporte
     */
    public function setSalarioMesAnterior($salarioMesAnterior)
    {
        $this->salarioMesAnterior = $salarioMesAnterior;

        return $this;
    }

    /**
     * Get salarioMesAnterior
     *
     * @return float
     */
    public function getSalarioMesAnterior()
    {
        return $this->salarioMesAnterior;
    }

    /**
     * Set salarioIntegral
     *
     * @param string $salarioIntegral
     *
     * @return RhuSsoAporte
     */
    public function setSalarioIntegral($salarioIntegral)
    {
        $this->salarioIntegral = $salarioIntegral;

        return $this;
    }

    /**
     * Get salarioIntegral
     *
     * @return string
     */
    public function getSalarioIntegral()
    {
        return $this->salarioIntegral;
    }

    /**
     * Set suplementario
     *
     * @param float $suplementario
     *
     * @return RhuSsoAporte
     */
    public function setSuplementario($suplementario)
    {
        $this->suplementario = $suplementario;

        return $this;
    }

    /**
     * Get suplementario
     *
     * @return float
     */
    public function getSuplementario()
    {
        return $this->suplementario;
    }

    /**
     * Set ssoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo $ssoPeriodoRel
     *
     * @return RhuSsoAporte
     */
    public function setSsoPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo $ssoPeriodoRel = null)
    {
        $this->ssoPeriodoRel = $ssoPeriodoRel;

        return $this;
    }

    /**
     * Get ssoPeriodoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo
     */
    public function getSsoPeriodoRel()
    {
        return $this->ssoPeriodoRel;
    }

    /**
     * Set ssoPeriodoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodoDetalleRel
     *
     * @return RhuSsoAporte
     */
    public function setSsoPeriodoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodoDetalleRel = null)
    {
        $this->ssoPeriodoDetalleRel = $ssoPeriodoDetalleRel;

        return $this;
    }

    /**
     * Get ssoPeriodoDetalleRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle
     */
    public function getSsoPeriodoDetalleRel()
    {
        return $this->ssoPeriodoDetalleRel;
    }

    /**
     * Set ssoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal $ssoSucursalRel
     *
     * @return RhuSsoAporte
     */
    public function setSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal $ssoSucursalRel = null)
    {
        $this->ssoSucursalRel = $ssoSucursalRel;

        return $this;
    }

    /**
     * Get ssoSucursalRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal
     */
    public function getSsoSucursalRel()
    {
        return $this->ssoSucursalRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuSsoAporte
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
     * @return RhuSsoAporte
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
     * Set aporteVoluntario
     *
     * @param string $aporteVoluntario
     *
     * @return RhuSsoAporte
     */
    public function setAporteVoluntario($aporteVoluntario)
    {
        $this->aporteVoluntario = $aporteVoluntario;

        return $this;
    }

    /**
     * Get aporteVoluntario
     *
     * @return string
     */
    public function getAporteVoluntario()
    {
        return $this->aporteVoluntario;
    }

    /**
     * Set variacionCentrosTrabajo
     *
     * @param string $variacionCentrosTrabajo
     *
     * @return RhuSsoAporte
     */
    public function setVariacionCentrosTrabajo($variacionCentrosTrabajo)
    {
        $this->variacionCentrosTrabajo = $variacionCentrosTrabajo;

        return $this;
    }

    /**
     * Get variacionCentrosTrabajo
     *
     * @return string
     */
    public function getVariacionCentrosTrabajo()
    {
        return $this->variacionCentrosTrabajo;
    }

    /**
     * Set incapacidadAccidenteTrabajoEnfermedadProfesional
     *
     * @param integer $incapacidadAccidenteTrabajoEnfermedadProfesional
     *
     * @return RhuSsoAporte
     */
    public function setIncapacidadAccidenteTrabajoEnfermedadProfesional($incapacidadAccidenteTrabajoEnfermedadProfesional)
    {
        $this->incapacidadAccidenteTrabajoEnfermedadProfesional = $incapacidadAccidenteTrabajoEnfermedadProfesional;

        return $this;
    }

    /**
     * Get incapacidadAccidenteTrabajoEnfermedadProfesional
     *
     * @return integer
     */
    public function getIncapacidadAccidenteTrabajoEnfermedadProfesional()
    {
        return $this->incapacidadAccidenteTrabajoEnfermedadProfesional;
    }
}
