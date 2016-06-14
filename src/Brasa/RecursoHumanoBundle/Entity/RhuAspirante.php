<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_aspirante")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuAspiranteRepository")
 */
class RhuAspirante
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aspirante_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAspirantePk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_tipo_identificacion_fk", type="integer")
     */
    private $codigoTipoIdentificacionFk;
    
    /**
     * @ORM\Column(name="codigo_seleccion_requisito_fk", type="integer", nullable=true)
     */
    private $codigoSeleccionRequisitoFk;

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
     * @ORM\Column(name="barrio", type="string", length=100, nullable=true)
     */
    private $barrio;

    /**
     * @ORM\Column(name="codigo_rh_fk", type="integer", nullable=true)
     */
    private $codigoRhFk;

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
    private $fechaNacimiento;

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
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */
    private $estadoAprobado = 0;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTipoIdentificacion", inversedBy="rhuAspirantesTipoIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_tipo_identificacion_fk", referencedColumnName="codigo_tipo_identificacion_pk")
     */
    protected $tipoIdentificacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEstadoCivil", inversedBy="aspirantesEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuAspiranteesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuAspiranteesCiudadNacimientoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_nacimiento_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadNacimientoRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuAspiranteesCiudadExpedicionRel")
     * @ORM\JoinColumn(name="codigo_ciudad_expedicion_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadExpedicionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuRh", inversedBy="aspirantesRhRel")
     * @ORM\JoinColumn(name="codigo_rh_fk", referencedColumnName="codigo_rh_pk")
     */
    protected $rhRel;

    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="aspirantesCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionRequisito", inversedBy="aspirantesSeleccionRequisitoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_requisito_fk", referencedColumnName="codigo_seleccion_requisito_pk")
     */
    protected $seleccionRequisitoRel;  

    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="aspirantesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;

    

    

    /**
     * Get codigoAspirantePk
     *
     * @return integer
     */
    public function getCodigoAspirantePk()
    {
        return $this->codigoAspirantePk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuAspirante
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
     * @param integer $codigoTipoIdentificacionFk
     *
     * @return RhuAspirante
     */
    public function setCodigoTipoIdentificacionFk($codigoTipoIdentificacionFk)
    {
        $this->codigoTipoIdentificacionFk = $codigoTipoIdentificacionFk;

        return $this;
    }

    /**
     * Get codigoTipoIdentificacionFk
     *
     * @return integer
     */
    public function getCodigoTipoIdentificacionFk()
    {
        return $this->codigoTipoIdentificacionFk;
    }

    /**
     * Set codigoSeleccionRequisitoFk
     *
     * @param integer $codigoSeleccionRequisitoFk
     *
     * @return RhuAspirante
     */
    public function setCodigoSeleccionRequisitoFk($codigoSeleccionRequisitoFk)
    {
        $this->codigoSeleccionRequisitoFk = $codigoSeleccionRequisitoFk;

        return $this;
    }

    /**
     * Get codigoSeleccionRequisitoFk
     *
     * @return integer
     */
    public function getCodigoSeleccionRequisitoFk()
    {
        return $this->codigoSeleccionRequisitoFk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @param integer $codigoRhFk
     *
     * @return RhuAspirante
     */
    public function setCodigoRhFk($codigoRhFk)
    {
        $this->codigoRhFk = $codigoRhFk;

        return $this;
    }

    /**
     * Get codigoRhFk
     *
     * @return integer
     */
    public function getCodigoRhFk()
    {
        return $this->codigoRhFk;
    }

    /**
     * Set codigoEstadoCivilFk
     *
     * @param string $codigoEstadoCivilFk
     *
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * @return RhuAspirante
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set codigoCiudadNacimientoFk
     *
     * @param integer $codigoCiudadNacimientoFk
     *
     * @return RhuAspirante
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
     * Set codigoCiudadExpedicionFk
     *
     * @param integer $codigoCiudadExpedicionFk
     *
     * @return RhuAspirante
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuAspirante
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
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuAspirante
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuAspirante
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
     * @return RhuAspirante
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuAspirante
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuAspirante
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuAspirante
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set tipoIdentificacionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTipoIdentificacion $tipoIdentificacionRel
     *
     * @return RhuAspirante
     */
    public function setTipoIdentificacionRel(\Brasa\GeneralBundle\Entity\GenTipoIdentificacion $tipoIdentificacionRel = null)
    {
        $this->tipoIdentificacionRel = $tipoIdentificacionRel;

        return $this;
    }

    /**
     * Get tipoIdentificacionRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenTipoIdentificacion
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
     * @return RhuAspirante
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
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuAspirante
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
     * Set ciudadNacimientoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadNacimientoRel
     *
     * @return RhuAspirante
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
     * Set ciudadExpedicionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadExpedicionRel
     *
     * @return RhuAspirante
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

    /**
     * Set rhRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRh $rhRel
     *
     * @return RhuAspirante
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
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuAspirante
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
     * Set seleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionRequisitoRel
     *
     * @return RhuAspirante
     */
    public function setSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionRequisitoRel = null)
    {
        $this->seleccionRequisitoRel = $seleccionRequisitoRel;

        return $this;
    }

    /**
     * Get seleccionRequisitoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito
     */
    public function getSeleccionRequisitoRel()
    {
        return $this->seleccionRequisitoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuAspirante
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
}
