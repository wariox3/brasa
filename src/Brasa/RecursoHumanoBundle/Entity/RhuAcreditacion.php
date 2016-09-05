<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_acreditacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuAcreditacionRepository")
 */
class RhuAcreditacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_acreditacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAcreditacionPk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;              
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */     
    
    private $fecha;
    
    /**
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */     
    
    private $fechaInicio;
    
    /**
     * @ORM\Column(name="fecha_terminacion", type="date", nullable=true)
     */     
    
    private $fechaTerminacion;
    
    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */     
    
    private $fechaVencimiento;            
    
    /**
     * @ORM\Column(name="codigo_academia_fk", type="integer", nullable=true)
     */    
    private $codigoAcademiaFk;    
    
    /**
     * @ORM\Column(name="numero_registro", type="string", length=20, nullable=true)
     */    
    private $numeroRegistro;
    
    /**
     * @ORM\Column(name="numero_acreditacion", type="string", length=20, nullable=true)
     */    
    private $numeroAcreditacion;
    
    /**
     * @ORM\Column(name="codigo_estudio_tipo_acreditacion_fk", type="integer", nullable=true)
     */    
    private $codigoEstudioTipoAcreditacionFk;
    
    /**
     * @ORM\Column(name="codigo_estudio_estado_fk", type="integer", nullable=true)
     */    
    private $codigoEstudioEstadoFk;
    
    /**
     * @ORM\Column(name="codigo_estudio_estado_invalido_fk", type="integer", nullable=true)
     */    
    private $codigoEstudioEstadoInvalidoFk;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\Column(name="fecha_estado", type="date", nullable=true)
     */     
    
    private $fechaEstado;
    
    /**
     * @ORM\Column(name="fecha_estado_invalido", type="date", nullable=true)
     */     
    
    private $fechaEstadoInvalido;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="acreditacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuAcademia", inversedBy="acreditacionesAcademiaRel")
     * @ORM\JoinColumn(name="codigo_academia_fk", referencedColumnName="codigo_academia_pk")
     */
    protected $academiaRel;        
   

    /**
     * Get codigoAcreditacionPk
     *
     * @return integer
     */
    public function getCodigoAcreditacionPk()
    {
        return $this->codigoAcreditacionPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuAcreditacion
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuAcreditacion
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return RhuAcreditacion
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaTerminacion
     *
     * @param \DateTime $fechaTerminacion
     *
     * @return RhuAcreditacion
     */
    public function setFechaTerminacion($fechaTerminacion)
    {
        $this->fechaTerminacion = $fechaTerminacion;

        return $this;
    }

    /**
     * Get fechaTerminacion
     *
     * @return \DateTime
     */
    public function getFechaTerminacion()
    {
        return $this->fechaTerminacion;
    }

    /**
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return RhuAcreditacion
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set codigoAcademiaFk
     *
     * @param integer $codigoAcademiaFk
     *
     * @return RhuAcreditacion
     */
    public function setCodigoAcademiaFk($codigoAcademiaFk)
    {
        $this->codigoAcademiaFk = $codigoAcademiaFk;

        return $this;
    }

    /**
     * Get codigoAcademiaFk
     *
     * @return integer
     */
    public function getCodigoAcademiaFk()
    {
        return $this->codigoAcademiaFk;
    }

    /**
     * Set numeroRegistro
     *
     * @param string $numeroRegistro
     *
     * @return RhuAcreditacion
     */
    public function setNumeroRegistro($numeroRegistro)
    {
        $this->numeroRegistro = $numeroRegistro;

        return $this;
    }

    /**
     * Get numeroRegistro
     *
     * @return string
     */
    public function getNumeroRegistro()
    {
        return $this->numeroRegistro;
    }

    /**
     * Set numeroAcreditacion
     *
     * @param string $numeroAcreditacion
     *
     * @return RhuAcreditacion
     */
    public function setNumeroAcreditacion($numeroAcreditacion)
    {
        $this->numeroAcreditacion = $numeroAcreditacion;

        return $this;
    }

    /**
     * Get numeroAcreditacion
     *
     * @return string
     */
    public function getNumeroAcreditacion()
    {
        return $this->numeroAcreditacion;
    }

    /**
     * Set codigoEstudioTipoAcreditacionFk
     *
     * @param integer $codigoEstudioTipoAcreditacionFk
     *
     * @return RhuAcreditacion
     */
    public function setCodigoEstudioTipoAcreditacionFk($codigoEstudioTipoAcreditacionFk)
    {
        $this->codigoEstudioTipoAcreditacionFk = $codigoEstudioTipoAcreditacionFk;

        return $this;
    }

    /**
     * Get codigoEstudioTipoAcreditacionFk
     *
     * @return integer
     */
    public function getCodigoEstudioTipoAcreditacionFk()
    {
        return $this->codigoEstudioTipoAcreditacionFk;
    }

    /**
     * Set codigoEstudioEstadoFk
     *
     * @param integer $codigoEstudioEstadoFk
     *
     * @return RhuAcreditacion
     */
    public function setCodigoEstudioEstadoFk($codigoEstudioEstadoFk)
    {
        $this->codigoEstudioEstadoFk = $codigoEstudioEstadoFk;

        return $this;
    }

    /**
     * Get codigoEstudioEstadoFk
     *
     * @return integer
     */
    public function getCodigoEstudioEstadoFk()
    {
        return $this->codigoEstudioEstadoFk;
    }

    /**
     * Set codigoEstudioEstadoInvalidoFk
     *
     * @param integer $codigoEstudioEstadoInvalidoFk
     *
     * @return RhuAcreditacion
     */
    public function setCodigoEstudioEstadoInvalidoFk($codigoEstudioEstadoInvalidoFk)
    {
        $this->codigoEstudioEstadoInvalidoFk = $codigoEstudioEstadoInvalidoFk;

        return $this;
    }

    /**
     * Get codigoEstudioEstadoInvalidoFk
     *
     * @return integer
     */
    public function getCodigoEstudioEstadoInvalidoFk()
    {
        return $this->codigoEstudioEstadoInvalidoFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuAcreditacion
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
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuAcreditacion
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
     * Set fechaEstado
     *
     * @param \DateTime $fechaEstado
     *
     * @return RhuAcreditacion
     */
    public function setFechaEstado($fechaEstado)
    {
        $this->fechaEstado = $fechaEstado;

        return $this;
    }

    /**
     * Get fechaEstado
     *
     * @return \DateTime
     */
    public function getFechaEstado()
    {
        return $this->fechaEstado;
    }

    /**
     * Set fechaEstadoInvalido
     *
     * @param \DateTime $fechaEstadoInvalido
     *
     * @return RhuAcreditacion
     */
    public function setFechaEstadoInvalido($fechaEstadoInvalido)
    {
        $this->fechaEstadoInvalido = $fechaEstadoInvalido;

        return $this;
    }

    /**
     * Get fechaEstadoInvalido
     *
     * @return \DateTime
     */
    public function getFechaEstadoInvalido()
    {
        return $this->fechaEstadoInvalido;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuAcreditacion
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
     * Set academiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAcademia $academiaRel
     *
     * @return RhuAcreditacion
     */
    public function setAcademiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuAcademia $academiaRel = null)
    {
        $this->academiaRel = $academiaRel;

        return $this;
    }

    /**
     * Get academiaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuAcademia
     */
    public function getAcademiaRel()
    {
        return $this->academiaRel;
    }
}
