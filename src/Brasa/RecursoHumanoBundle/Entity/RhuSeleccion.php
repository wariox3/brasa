<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionRepository")
 */
class RhuSeleccion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionPk;        
    
    /**
     * @ORM\Column(name="codigo_seleccion_grupo_fk", type="integer")
     */    
    private $codigoSeleccionGrupoFk;
    
    /**
     * @ORM\Column(name="codigo_seleccion_tipo_fk", type="integer")
     */    
    private $codigoSeleccionTipoFk;     
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;     
    
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
     * @ORM\Column(name="codigo_barrio_fk", type="integer", length=80, nullable=true)
     */    
    private $codigoBarrioFk;
    
    /**
     * @ORM\Column(name="codigo_rh_fk", type="integer", nullable=true)
     */    
    private $codigoRhPk;
    
     /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */    
    private $codigoEstadoCivilFk;
    
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
     * @ORM\Column(name="codigo_ciudad_nacimiento_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadNacimientoFk;
    
    /**
     * @ORM\Column(name="codigo_ciudad_expedicion_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadExpedicionFk;
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;                   
        
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     

    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = 0;        
    
    /**     
     * @ORM\Column(name="estado_abierto", type="boolean")
     * @ORM\Column(type="integer", name="estado_abierto", options={"unsigned":true, "default":"1"})
     */    
    private $estadoAbierto = 1;    
    
    /**     
     * @ORM\Column(name="presenta_pruebas", type="boolean")
     */    
    private $presentaPruebas = 0;
    
    /**     
     * @ORM\Column(name="referencias_verificadas", type="boolean")
     */    
    private $referenciasVerificadas = 0;    
    
    /**
     * @ORM\Column(name="fecha_entrevista", type="datetime", nullable=true)
     */    
    private $fecha_entrevista;    
    
    /**
     * @ORM\Column(name="fecha_pruebas", type="datetime", nullable=true)
     */    
    private $fecha_pruebas;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionTipo", inversedBy="seleccionesSeleccionTipoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_tipo_fk", referencedColumnName="codigo_seleccion_tipo_pk")
     */
    protected $seleccionTipoRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoIdentificacion", inversedBy="seleccionesTipoIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_tipo_identificacion_fk", referencedColumnName="codigo_tipo_identificacion_pk")
     */
    protected $tipoIdentificacionRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEstadoCivil", inversedBy="seleccionesEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="seleccionesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;                              

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuSeleccionesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuSeleccionesCiudadNacimientoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_nacimiento_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadNacimientoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuSeleccionesCiudadExpedicionRel")
     * @ORM\JoinColumn(name="codigo_ciudad_expedicion_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadExpedicionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenBarrio", inversedBy="rhuSeleccionesBarrioRel")
     * @ORM\JoinColumn(name="codigo_barrio_fk", referencedColumnName="codigo_barrio_pk")
     */
    protected $barrioRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuRh", inversedBy="seleccionesRhRel")
     * @ORM\JoinColumn(name="codigo_rh_fk", referencedColumnName="codigo_rh_pk")
     */
    protected $rhRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionGrupo", inversedBy="seleccionesSeleccionGrupoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_grupo_fk", referencedColumnName="codigo_seleccion_grupo_pk")
     */
    protected $seleccionGrupoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionReferencia", mappedBy="seleccionRel")
     */
    protected $seleccionesReferenciasSeleccionRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionPrueba", mappedBy="seleccionRel")
     */
    protected $seleccionesPruebasSeleccionRel;    
   


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesReferenciasSeleccionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesPruebasSeleccionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSeleccionPk
     *
     * @return integer
     */
    public function getCodigoSeleccionPk()
    {
        return $this->codigoSeleccionPk;
    }

    /**
     * Set codigoSeleccionGrupoFk
     *
     * @param integer $codigoSeleccionGrupoFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoSeleccionGrupoFk($codigoSeleccionGrupoFk)
    {
        $this->codigoSeleccionGrupoFk = $codigoSeleccionGrupoFk;

        return $this;
    }

    /**
     * Get codigoSeleccionGrupoFk
     *
     * @return integer
     */
    public function getCodigoSeleccionGrupoFk()
    {
        return $this->codigoSeleccionGrupoFk;
    }

    /**
     * Set codigoSeleccionTipoFk
     *
     * @param integer $codigoSeleccionTipoFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoSeleccionTipoFk($codigoSeleccionTipoFk)
    {
        $this->codigoSeleccionTipoFk = $codigoSeleccionTipoFk;

        return $this;
    }

    /**
     * Get codigoSeleccionTipoFk
     *
     * @return integer
     */
    public function getCodigoSeleccionTipoFk()
    {
        return $this->codigoSeleccionTipoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuSeleccion
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
     * Set codigoTipoIdentificacionFk
     *
     * @param string $codigoTipoIdentificacionFk
     *
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * Set codigoBarrioFk
     *
     * @param integer $codigoBarrioFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoBarrioFk($codigoBarrioFk)
    {
        $this->codigoBarrioFk = $codigoBarrioFk;

        return $this;
    }

    /**
     * Get codigoBarrioFk
     *
     * @return integer
     */
    public function getCodigoBarrioFk()
    {
        return $this->codigoBarrioFk;
    }

    /**
     * Set codigoRhPk
     *
     * @param integer $codigoRhPk
     *
     * @return RhuSeleccion
     */
    public function setCodigoRhPk($codigoRhPk)
    {
        $this->codigoRhPk = $codigoRhPk;

        return $this;
    }

    /**
     * Get codigoRhPk
     *
     * @return integer
     */
    public function getCodigoRhPk()
    {
        return $this->codigoRhPk;
    }

    /**
     * Set codigoEstadoCivilFk
     *
     * @param string $codigoEstadoCivilFk
     *
     * @return RhuSeleccion
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
     * Set codigoSexoFk
     *
     * @param string $codigoSexoFk
     *
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuSeleccion
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuSeleccion
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
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return RhuSeleccion
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set estadoAbierto
     *
     * @param boolean $estadoAbierto
     *
     * @return RhuSeleccion
     */
    public function setEstadoAbierto($estadoAbierto)
    {
        $this->estadoAbierto = $estadoAbierto;

        return $this;
    }

    /**
     * Get estadoAbierto
     *
     * @return boolean
     */
    public function getEstadoAbierto()
    {
        return $this->estadoAbierto;
    }

    /**
     * Set presentaPruebas
     *
     * @param boolean $presentaPruebas
     *
     * @return RhuSeleccion
     */
    public function setPresentaPruebas($presentaPruebas)
    {
        $this->presentaPruebas = $presentaPruebas;

        return $this;
    }

    /**
     * Get presentaPruebas
     *
     * @return boolean
     */
    public function getPresentaPruebas()
    {
        return $this->presentaPruebas;
    }

    /**
     * Set referenciasVerificadas
     *
     * @param boolean $referenciasVerificadas
     *
     * @return RhuSeleccion
     */
    public function setReferenciasVerificadas($referenciasVerificadas)
    {
        $this->referenciasVerificadas = $referenciasVerificadas;

        return $this;
    }

    /**
     * Get referenciasVerificadas
     *
     * @return boolean
     */
    public function getReferenciasVerificadas()
    {
        return $this->referenciasVerificadas;
    }

    /**
     * Set fechaEntrevista
     *
     * @param \DateTime $fechaEntrevista
     *
     * @return RhuSeleccion
     */
    public function setFechaEntrevista($fechaEntrevista)
    {
        $this->fecha_entrevista = $fechaEntrevista;

        return $this;
    }

    /**
     * Get fechaEntrevista
     *
     * @return \DateTime
     */
    public function getFechaEntrevista()
    {
        return $this->fecha_entrevista;
    }

    /**
     * Set fechaPruebas
     *
     * @param \DateTime $fechaPruebas
     *
     * @return RhuSeleccion
     */
    public function setFechaPruebas($fechaPruebas)
    {
        $this->fecha_pruebas = $fechaPruebas;

        return $this;
    }

    /**
     * Get fechaPruebas
     *
     * @return \DateTime
     */
    public function getFechaPruebas()
    {
        return $this->fecha_pruebas;
    }

    /**
     * Set seleccionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionTipo $seleccionTipoRel
     *
     * @return RhuSeleccion
     */
    public function setSeleccionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionTipo $seleccionTipoRel = null)
    {
        $this->seleccionTipoRel = $seleccionTipoRel;

        return $this;
    }

    /**
     * Get seleccionTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionTipo
     */
    public function getSeleccionTipoRel()
    {
        return $this->seleccionTipoRel;
    }

    /**
     * Set tipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoIdentificacion $tipoIdentificacionRel
     *
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * @return RhuSeleccion
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
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuSeleccion
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
     * Set barrioRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenBarrio $barrioRel
     *
     * @return RhuSeleccion
     */
    public function setBarrioRel(\Brasa\GeneralBundle\Entity\GenBarrio $barrioRel = null)
    {
        $this->barrioRel = $barrioRel;

        return $this;
    }

    /**
     * Get barrioRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenBarrio
     */
    public function getBarrioRel()
    {
        return $this->barrioRel;
    }

    /**
     * Set rhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRh $rhRel
     *
     * @return RhuSeleccion
     */
    public function setRhRel(\Brasa\RecursoHumanoBundle\Entity\RhuRh $rhRel = null)
    {
        $this->rhRel = $rhRel;

        return $this;
    }

    /**
     * Get rhRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuRh
     */
    public function getRhRel()
    {
        return $this->rhRel;
    }

    /**
     * Set seleccionGrupoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo $seleccionGrupoRel
     *
     * @return RhuSeleccion
     */
    public function setSeleccionGrupoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo $seleccionGrupoRel = null)
    {
        $this->seleccionGrupoRel = $seleccionGrupoRel;

        return $this;
    }

    /**
     * Get seleccionGrupoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo
     */
    public function getSeleccionGrupoRel()
    {
        return $this->seleccionGrupoRel;
    }

    /**
     * Add seleccionesReferenciasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSeleccionRel
     *
     * @return RhuSeleccion
     */
    public function addSeleccionesReferenciasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSeleccionRel)
    {
        $this->seleccionesReferenciasSeleccionRel[] = $seleccionesReferenciasSeleccionRel;

        return $this;
    }

    /**
     * Remove seleccionesReferenciasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSeleccionRel
     */
    public function removeSeleccionesReferenciasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia $seleccionesReferenciasSeleccionRel)
    {
        $this->seleccionesReferenciasSeleccionRel->removeElement($seleccionesReferenciasSeleccionRel);
    }

    /**
     * Get seleccionesReferenciasSeleccionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesReferenciasSeleccionRel()
    {
        return $this->seleccionesReferenciasSeleccionRel;
    }

    /**
     * Add seleccionesPruebasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSeleccionRel
     *
     * @return RhuSeleccion
     */
    public function addSeleccionesPruebasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSeleccionRel)
    {
        $this->seleccionesPruebasSeleccionRel[] = $seleccionesPruebasSeleccionRel;

        return $this;
    }

    /**
     * Remove seleccionesPruebasSeleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSeleccionRel
     */
    public function removeSeleccionesPruebasSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba $seleccionesPruebasSeleccionRel)
    {
        $this->seleccionesPruebasSeleccionRel->removeElement($seleccionesPruebasSeleccionRel);
    }

    /**
     * Get seleccionesPruebasSeleccionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesPruebasSeleccionRel()
    {
        return $this->seleccionesPruebasSeleccionRel;
    }

    /**
     * Set codigoCiudadNacimientoFk
     *
     * @param integer $codigoCiudadNacimientoFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoCiudadNacimientoFk($codigoCiudadNacimientoFk)
    {
        $this->codigoCiudadNacimientoFk = $codigoCiudadNacimientoFk;

        return $this;
    }

    /**
     * Get codigoCiudadNacimientoFk
     *
     * @return integer
     */
    public function getCodigoCiudadNacimientoFk()
    {
        return $this->codigoCiudadNacimientoFk;
    }

    /**
     * Set ciudadNacimientoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadNacimientoRel
     *
     * @return RhuSeleccion
     */
    public function setCiudadNacimientoRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadNacimientoRel = null)
    {
        $this->ciudadNacimientoRel = $ciudadNacimientoRel;

        return $this;
    }

    /**
     * Get ciudadNacimientoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadNacimientoRel()
    {
        return $this->ciudadNacimientoRel;
    }

    /**
     * Set codigoCiudadExpedicionFk
     *
     * @param integer $codigoCiudadExpedicionFk
     *
     * @return RhuSeleccion
     */
    public function setCodigoCiudadExpedicionFk($codigoCiudadExpedicionFk)
    {
        $this->codigoCiudadExpedicionFk = $codigoCiudadExpedicionFk;

        return $this;
    }

    /**
     * Get codigoCiudadExpedicionFk
     *
     * @return integer
     */
    public function getCodigoCiudadExpedicionFk()
    {
        return $this->codigoCiudadExpedicionFk;
    }

    /**
     * Set ciudadExpedicionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadExpedicionRel
     *
     * @return RhuSeleccion
     */
    public function setCiudadExpedicionRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadExpedicionRel = null)
    {
        $this->ciudadExpedicionRel = $ciudadExpedicionRel;

        return $this;
    }

    /**
     * Get ciudadExpedicionRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadExpedicionRel()
    {
        return $this->ciudadExpedicionRel;
    }
}
