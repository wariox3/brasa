<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoRepository")
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
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false)
     */    
    private $numeroIdentificacion;        
    
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
     * @ORM\Column(name="barrio", type="string", length=80, nullable=true)
     */    
    private $barrio;    
    
    /**
     * @ORM\Column(name="codigo_rh_fk", type="string", length=2, nullable=true)
     */    
    private $codigoRhFk;     
    
    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */    
    private $codigoSexoFk;     
    
    /**
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     */    
    private $correo;     
        
    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */    
    private $fecha_nacimiento;     
    
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
     * @ORM\Column(name="auxilio_transporte", type="boolean")
     */    
    private $auxilioTransporte = 0;     
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;     
    
    /**
     * @ORM\Column(name="rh", type="string", length=2, nullable=true)
     */    
    private $rh;    
    
    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadSaludFk;    

    /**
     * @ORM\Column(name="codigo_entidad_pension_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadPensionFk;    
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = 1;    
    
    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer", nullable=true)
     */    
    private $codigoClasificacionRiesgoFk;     
    
    /**
     * @ORM\Column(name="fecha_contrato", type="date", nullable=true)
     */    
    private $fecha_contrato;   
    
    /**
     * @ORM\Column(name="fecha_finaliza_contrato", type="date", nullable=true)
     */    
    private $fecha_finaliza_contrato;    
    
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
     * @ORM\ManyToOne(targetEntity="RhuClasificacionRiesgo", inversedBy="empleadosClasificacionRiesgoRel")
     * @ORM\JoinColumn(name="codigo_clasificacion_riesgo_fk", referencedColumnName="codigo_clasificacion_riesgo_pk")
     */
    protected $clasificacionRiesgoRel;    
    
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
     * @ORM\ManyToOne(targetEntity="RhuTipoTiempo", inversedBy="empleadosTipoTiempoRel")
     * @ORM\JoinColumn(name="codigo_tipo_tiempo_fk", referencedColumnName="codigo_tipo_tiempo_pk")
     */
    protected $tipoTiempoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="empleadoRel")
     */
    protected $pagosEmpleadoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoAdicional", mappedBy="empleadoRel")
     */
    protected $pagosAdicionalesEmpleadoRel;      

    /**
     * @ORM\OneToMany(targetEntity="RhuDescuentoAdicional", mappedBy="empleadoRel")
     */
    protected $descuentosAdicionalesEmpleadoRel;     
    
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
     * Constructor
     */
    public function __construct()
    {
        $this->pagosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosAdicionalesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creditosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEmpleadoPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoPk()
    {
        return $this->codigoEmpleadoPk;
    }

    /**
     * Set codigoTipoIdentificacionFk
     *
     * @param string $codigoTipoIdentificacionFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoTipoIdentificacionFk($codigoTipoIdentificacionFk)
    {
        $this->codigoTipoIdentificacionFk = $codigoTipoIdentificacionFk;

        return $this;
    }

    /**
     * Get codigoTipoIdentificacionFk
     *
     * @return string
     */
    public function getCodigoTipoIdentificacionFk()
    {
        return $this->codigoTipoIdentificacionFk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuEmpleado
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuEmpleado
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set nombre1
     *
     * @param string $nombre1
     *
     * @return RhuEmpleado
     */
    public function setNombre1($nombre1)
    {
        $this->nombre1 = $nombre1;

        return $this;
    }

    /**
     * Get nombre1
     *
     * @return string
     */
    public function getNombre1()
    {
        return $this->nombre1;
    }

    /**
     * Set nombre2
     *
     * @param string $nombre2
     *
     * @return RhuEmpleado
     */
    public function setNombre2($nombre2)
    {
        $this->nombre2 = $nombre2;

        return $this;
    }

    /**
     * Get nombre2
     *
     * @return string
     */
    public function getNombre2()
    {
        return $this->nombre2;
    }

    /**
     * Set apellido1
     *
     * @param string $apellido1
     *
     * @return RhuEmpleado
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido1
     *
     * @return string
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Set apellido2
     *
     * @param string $apellido2
     *
     * @return RhuEmpleado
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido2
     *
     * @return string
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuEmpleado
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set celular
     *
     * @param string $celular
     *
     * @return RhuEmpleado
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuEmpleado
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuEmpleado
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
     * Set barrio
     *
     * @param string $barrio
     *
     * @return RhuEmpleado
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return string
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set codigoRhFk
     *
     * @param string $codigoRhFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoRhFk($codigoRhFk)
    {
        $this->codigoRhFk = $codigoRhFk;

        return $this;
    }

    /**
     * Get codigoRhFk
     *
     * @return string
     */
    public function getCodigoRhFk()
    {
        return $this->codigoRhFk;
    }

    /**
     * Set codigoSexoFk
     *
     * @param string $codigoSexoFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoSexoFk($codigoSexoFk)
    {
        $this->codigoSexoFk = $codigoSexoFk;

        return $this;
    }

    /**
     * Get codigoSexoFk
     *
     * @return string
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return RhuEmpleado
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
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return RhuEmpleado
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fecha_nacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fecha_nacimiento;
    }

    /**
     * Set codigoEstadoCivilFk
     *
     * @param string $codigoEstadoCivilFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoEstadoCivilFk($codigoEstadoCivilFk)
    {
        $this->codigoEstadoCivilFk = $codigoEstadoCivilFk;

        return $this;
    }

    /**
     * Get codigoEstadoCivilFk
     *
     * @return string
     */
    public function getCodigoEstadoCivilFk()
    {
        return $this->codigoEstadoCivilFk;
    }

    /**
     * Set cuenta
     *
     * @param string $cuenta
     *
     * @return RhuEmpleado
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return string
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set codigoBancoFk
     *
     * @param integer $codigoBancoFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoBancoFk($codigoBancoFk)
    {
        $this->codigoBancoFk = $codigoBancoFk;

        return $this;
    }

    /**
     * Get codigoBancoFk
     *
     * @return integer
     */
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuEmpleado
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
     * Set auxilioTransporte
     *
     * @param boolean $auxilioTransporte
     *
     * @return RhuEmpleado
     */
    public function setAuxilioTransporte($auxilioTransporte)
    {
        $this->auxilioTransporte = $auxilioTransporte;

        return $this;
    }

    /**
     * Get auxilioTransporte
     *
     * @return boolean
     */
    public function getAuxilioTransporte()
    {
        return $this->auxilioTransporte;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuEmpleado
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
     * Set rh
     *
     * @param string $rh
     *
     * @return RhuEmpleado
     */
    public function setRh($rh)
    {
        $this->rh = $rh;

        return $this;
    }

    /**
     * Get rh
     *
     * @return string
     */
    public function getRh()
    {
        return $this->rh;
    }

    /**
     * Set codigoEntidadSaludFk
     *
     * @param integer $codigoEntidadSaludFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoEntidadSaludFk($codigoEntidadSaludFk)
    {
        $this->codigoEntidadSaludFk = $codigoEntidadSaludFk;

        return $this;
    }

    /**
     * Get codigoEntidadSaludFk
     *
     * @return integer
     */
    public function getCodigoEntidadSaludFk()
    {
        return $this->codigoEntidadSaludFk;
    }

    /**
     * Set codigoEntidadPensionFk
     *
     * @param integer $codigoEntidadPensionFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoEntidadPensionFk($codigoEntidadPensionFk)
    {
        $this->codigoEntidadPensionFk = $codigoEntidadPensionFk;

        return $this;
    }

    /**
     * Get codigoEntidadPensionFk
     *
     * @return integer
     */
    public function getCodigoEntidadPensionFk()
    {
        return $this->codigoEntidadPensionFk;
    }

    /**
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return RhuEmpleado
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
     * Set codigoClasificacionRiesgoFk
     *
     * @param integer $codigoClasificacionRiesgoFk
     *
     * @return RhuEmpleado
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
     * Set clasificacionRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuClasificacionRiesgo $clasificacionRiesgoRel
     *
     * @return RhuEmpleado
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
     * Set tipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoIdentificacion $tipoIdentificacionRel
     *
     * @return RhuEmpleado
     */
    public function setTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoIdentificacion $tipoIdentificacionRel = null)
    {
        $this->tipoIdentificacionRel = $tipoIdentificacionRel;

        return $this;
    }

    /**
     * Get tipoIdentificacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuTipoIdentificacion
     */
    public function getTipoIdentificacionRel()
    {
        return $this->tipoIdentificacionRel;
    }

    /**
     * Set estadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil $estadoCivilRel
     *
     * @return RhuEmpleado
     */
    public function setEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil $estadoCivilRel = null)
    {
        $this->estadoCivilRel = $estadoCivilRel;

        return $this;
    }

    /**
     * Get estadoCivilRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil
     */
    public function getEstadoCivilRel()
    {
        return $this->estadoCivilRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuEmpleado
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
     * Set bancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuBanco $bancoRel
     *
     * @return RhuEmpleado
     */
    public function setBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuBanco $bancoRel = null)
    {
        $this->bancoRel = $bancoRel;

        return $this;
    }

    /**
     * Get bancoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuBanco
     */
    public function getBancoRel()
    {
        return $this->bancoRel;
    }

    /**
     * Set entidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel
     *
     * @return RhuEmpleado
     */
    public function setEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel = null)
    {
        $this->entidadSaludRel = $entidadSaludRel;

        return $this;
    }

    /**
     * Get entidadSaludRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud
     */
    public function getEntidadSaludRel()
    {
        return $this->entidadSaludRel;
    }

    /**
     * Set entidadPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension $entidadPensionRel
     *
     * @return RhuEmpleado
     */
    public function setEntidadPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension $entidadPensionRel = null)
    {
        $this->entidadPensionRel = $entidadPensionRel;

        return $this;
    }

    /**
     * Get entidadPensionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension
     */
    public function getEntidadPensionRel()
    {
        return $this->entidadPensionRel;
    }

    /**
     * Add pagosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addPagosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosEmpleadoRel)
    {
        $this->pagosEmpleadoRel[] = $pagosEmpleadoRel;

        return $this;
    }

    /**
     * Remove pagosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosEmpleadoRel
     */
    public function removePagosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosEmpleadoRel)
    {
        $this->pagosEmpleadoRel->removeElement($pagosEmpleadoRel);
    }

    /**
     * Get pagosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosEmpleadoRel()
    {
        return $this->pagosEmpleadoRel;
    }

    /**
     * Add pagosAdicionalesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addPagosAdicionalesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesEmpleadoRel)
    {
        $this->pagosAdicionalesEmpleadoRel[] = $pagosAdicionalesEmpleadoRel;

        return $this;
    }

    /**
     * Remove pagosAdicionalesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesEmpleadoRel
     */
    public function removePagosAdicionalesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesEmpleadoRel)
    {
        $this->pagosAdicionalesEmpleadoRel->removeElement($pagosAdicionalesEmpleadoRel);
    }

    /**
     * Get pagosAdicionalesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosAdicionalesEmpleadoRel()
    {
        return $this->pagosAdicionalesEmpleadoRel;
    }

    /**
     * Add creditosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addCreditosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosEmpleadoRel)
    {
        $this->creditosEmpleadoRel[] = $creditosEmpleadoRel;

        return $this;
    }

    /**
     * Remove creditosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosEmpleadoRel
     */
    public function removeCreditosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosEmpleadoRel)
    {
        $this->creditosEmpleadoRel->removeElement($creditosEmpleadoRel);
    }

    /**
     * Get creditosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreditosEmpleadoRel()
    {
        return $this->creditosEmpleadoRel;
    }

    /**
     * Add incapacidadesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addIncapacidadesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEmpleadoRel)
    {
        $this->incapacidadesEmpleadoRel[] = $incapacidadesEmpleadoRel;

        return $this;
    }

    /**
     * Remove incapacidadesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEmpleadoRel
     */
    public function removeIncapacidadesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEmpleadoRel)
    {
        $this->incapacidadesEmpleadoRel->removeElement($incapacidadesEmpleadoRel);
    }

    /**
     * Get incapacidadesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesEmpleadoRel()
    {
        return $this->incapacidadesEmpleadoRel;
    }

    /**
     * Add contratosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addContratosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel)
    {
        $this->contratosEmpleadoRel[] = $contratosEmpleadoRel;

        return $this;
    }

    /**
     * Remove contratosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel
     */
    public function removeContratosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel)
    {
        $this->contratosEmpleadoRel->removeElement($contratosEmpleadoRel);
    }

    /**
     * Get contratosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosEmpleadoRel()
    {
        return $this->contratosEmpleadoRel;
    }

    /**
     * Set fechaContrato
     *
     * @param \DateTime $fechaContrato
     *
     * @return RhuEmpleado
     */
    public function setFechaContrato($fechaContrato)
    {
        $this->fecha_contrato = $fechaContrato;

        return $this;
    }

    /**
     * Get fechaContrato
     *
     * @return \DateTime
     */
    public function getFechaContrato()
    {
        return $this->fecha_contrato;
    }

    /**
     * Set fechaFinalizaContrato
     *
     * @param \DateTime $fechaFinalizaContrato
     *
     * @return RhuEmpleado
     */
    public function setFechaFinalizaContrato($fechaFinalizaContrato)
    {
        $this->fecha_finaliza_contrato = $fechaFinalizaContrato;

        return $this;
    }

    /**
     * Get fechaFinalizaContrato
     *
     * @return \DateTime
     */
    public function getFechaFinalizaContrato()
    {
        return $this->fecha_finaliza_contrato;
    }

    /**
     * Set contratoIndefinido
     *
     * @param boolean $contratoIndefinido
     *
     * @return RhuEmpleado
     */
    public function setContratoIndefinido($contratoIndefinido)
    {
        $this->contratoIndefinido = $contratoIndefinido;

        return $this;
    }

    /**
     * Get contratoIndefinido
     *
     * @return boolean
     */
    public function getContratoIndefinido()
    {
        return $this->contratoIndefinido;
    }

    /**
     * Add descuentosAdicionalesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional $descuentosAdicionalesEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addDescuentosAdicionalesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional $descuentosAdicionalesEmpleadoRel)
    {
        $this->descuentosAdicionalesEmpleadoRel[] = $descuentosAdicionalesEmpleadoRel;

        return $this;
    }

    /**
     * Remove descuentosAdicionalesEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional $descuentosAdicionalesEmpleadoRel
     */
    public function removeDescuentosAdicionalesEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional $descuentosAdicionalesEmpleadoRel)
    {
        $this->descuentosAdicionalesEmpleadoRel->removeElement($descuentosAdicionalesEmpleadoRel);
    }

    /**
     * Get descuentosAdicionalesEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDescuentosAdicionalesEmpleadoRel()
    {
        return $this->descuentosAdicionalesEmpleadoRel;
    }

    /**
     * Add licenciasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addLicenciasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasEmpleadoRel)
    {
        $this->licenciasEmpleadoRel[] = $licenciasEmpleadoRel;

        return $this;
    }

    /**
     * Remove licenciasEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasEmpleadoRel
     */
    public function removeLicenciasEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasEmpleadoRel)
    {
        $this->licenciasEmpleadoRel->removeElement($licenciasEmpleadoRel);
    }

    /**
     * Get licenciasEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLicenciasEmpleadoRel()
    {
        return $this->licenciasEmpleadoRel;
    }

    /**
     * Set pagadoEntidadSalud
     *
     * @param boolean $pagadoEntidadSalud
     *
     * @return RhuEmpleado
     */
    public function setPagadoEntidadSalud($pagadoEntidadSalud)
    {
        $this->pagadoEntidadSalud = $pagadoEntidadSalud;

        return $this;
    }

    /**
     * Get pagadoEntidadSalud
     *
     * @return boolean
     */
    public function getPagadoEntidadSalud()
    {
        return $this->pagadoEntidadSalud;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuEmpleado
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
     * Set codigoTipoTiempoFk
     *
     * @param integer $codigoTipoTiempoFk
     *
     * @return RhuEmpleado
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
     * Set tipoTiempoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoTiempo $tipoTiempoRel
     *
     * @return RhuEmpleado
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
}
