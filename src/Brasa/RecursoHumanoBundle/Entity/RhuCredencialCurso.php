<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_credencial_curso")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCredencialCursoRepository")
 */
class RhuCredencialCurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credencial_curso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCredencialCursoPk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="codigo_cargo_supervigilancia_fk", type="integer")
     */    
    private $codigoCargoSuperVigilanciaFk;
    
    /**
     * @ORM\Column(name="codigo_credencial_curso_tipo_estado_fk", type="integer")
     */    
    private $codigoCredencialCursoTipoEstadoFk;
    
    /**
     * @ORM\Column(name="codigo_credencial_curso_tipo_no_valido_fk", type="integer")
     */    
    private $codigoCredencialCursoTipoNoValidoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;
    
    /**
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */    
    private $fechaInicio;
    
    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */    
    private $fechaVencimiento;
    
    /**
     * @ORM\Column(name="numero_aprobacion", type="string", length=30, nullable=true)
     */    
    private $numeroAprobacion;
    
    /**
     * @ORM\Column(name="codigo_empleado_estudio_tipo_fk", type="integer", nullable=true)
     */    
    private $empleadoEstudioTipoFk;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;
    
    
    

    /**
     * Get codigoCredencialCursoPk
     *
     * @return integer
     */
    public function getCodigoCredencialCursoPk()
    {
        return $this->codigoCredencialCursoPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuCredencialCurso
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
     * Set codigoCargoSuperVigilanciaFk
     *
     * @param integer $codigoCargoSuperVigilanciaFk
     *
     * @return RhuCredencialCurso
     */
    public function setCodigoCargoSuperVigilanciaFk($codigoCargoSuperVigilanciaFk)
    {
        $this->codigoCargoSuperVigilanciaFk = $codigoCargoSuperVigilanciaFk;

        return $this;
    }

    /**
     * Get codigoCargoSuperVigilanciaFk
     *
     * @return integer
     */
    public function getCodigoCargoSuperVigilanciaFk()
    {
        return $this->codigoCargoSuperVigilanciaFk;
    }

    /**
     * Set codigoCredencialCursoTipoEstadoFk
     *
     * @param integer $codigoCredencialCursoTipoEstadoFk
     *
     * @return RhuCredencialCurso
     */
    public function setCodigoCredencialCursoTipoEstadoFk($codigoCredencialCursoTipoEstadoFk)
    {
        $this->codigoCredencialCursoTipoEstadoFk = $codigoCredencialCursoTipoEstadoFk;

        return $this;
    }

    /**
     * Get codigoCredencialCursoTipoEstadoFk
     *
     * @return integer
     */
    public function getCodigoCredencialCursoTipoEstadoFk()
    {
        return $this->codigoCredencialCursoTipoEstadoFk;
    }

    /**
     * Set codigoCredencialCursoTipoNoValidoFk
     *
     * @param integer $codigoCredencialCursoTipoNoValidoFk
     *
     * @return RhuCredencialCurso
     */
    public function setCodigoCredencialCursoTipoNoValidoFk($codigoCredencialCursoTipoNoValidoFk)
    {
        $this->codigoCredencialCursoTipoNoValidoFk = $codigoCredencialCursoTipoNoValidoFk;

        return $this;
    }

    /**
     * Get codigoCredencialCursoTipoNoValidoFk
     *
     * @return integer
     */
    public function getCodigoCredencialCursoTipoNoValidoFk()
    {
        return $this->codigoCredencialCursoTipoNoValidoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuCredencialCurso
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
     * @return RhuCredencialCurso
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
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return RhuCredencialCurso
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
     * Set numeroAprobacion
     *
     * @param string $numeroAprobacion
     *
     * @return RhuCredencialCurso
     */
    public function setNumeroAprobacion($numeroAprobacion)
    {
        $this->numeroAprobacion = $numeroAprobacion;

        return $this;
    }

    /**
     * Get numeroAprobacion
     *
     * @return string
     */
    public function getNumeroAprobacion()
    {
        return $this->numeroAprobacion;
    }

    /**
     * Set empleadoEstudioTipoFk
     *
     * @param integer $empleadoEstudioTipoFk
     *
     * @return RhuCredencialCurso
     */
    public function setEmpleadoEstudioTipoFk($empleadoEstudioTipoFk)
    {
        $this->empleadoEstudioTipoFk = $empleadoEstudioTipoFk;

        return $this;
    }

    /**
     * Get empleadoEstudioTipoFk
     *
     * @return integer
     */
    public function getEmpleadoEstudioTipoFk()
    {
        return $this->empleadoEstudioTipoFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuCredencialCurso
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
}
