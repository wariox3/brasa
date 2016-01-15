<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_horario_acceso")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuHorarioAccesoRepository")
 */
class RhuHorarioAcceso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_horario_acceso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoHorarioAccesoPk;                         
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="fecha_entrada", type="datetime", nullable=true)
     */    
    private $fechaEntrada;
    
    /**
     * @ORM\Column(name="fecha_salida", type="datetime", nullable=true)
     */    
    private $fechaSalida;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;
    
    /**
     * @ORM\Column(name="duracion_registro", type="datetime", nullable=true)
     */    
    private $duracionRegistro;
    
    /**     
     * @ORM\Column(name="estado", type="boolean")
     */    
    private $estado = 0;
   

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="horarioAccesoEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    

    /**
     * Get codigoHorarioAccesoPk
     *
     * @return integer
     */
    public function getCodigoHorarioAccesoPk()
    {
        return $this->codigoHorarioAccesoPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuHorarioAcceso
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
     * Set fechaEntrada
     *
     * @param \DateTime $fechaEntrada
     *
     * @return RhuHorarioAcceso
     */
    public function setFechaEntrada($fechaEntrada)
    {
        $this->fechaEntrada = $fechaEntrada;

        return $this;
    }

    /**
     * Get fechaEntrada
     *
     * @return \DateTime
     */
    public function getFechaEntrada()
    {
        return $this->fechaEntrada;
    }

    /**
     * Set fechaSalida
     *
     * @param \DateTime $fechaSalida
     *
     * @return RhuHorarioAcceso
     */
    public function setFechaSalida($fechaSalida)
    {
        $this->fechaSalida = $fechaSalida;

        return $this;
    }

    /**
     * Get fechaSalida
     *
     * @return \DateTime
     */
    public function getFechaSalida()
    {
        return $this->fechaSalida;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuHorarioAcceso
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
     * Set duracionRegistro
     *
     * @param \DateTime $duracionRegistro
     *
     * @return RhuHorarioAcceso
     */
    public function setDuracionRegistro($duracionRegistro)
    {
        $this->duracionRegistro = $duracionRegistro;

        return $this;
    }

    /**
     * Get duracionRegistro
     *
     * @return \DateTime
     */
    public function getDuracionRegistro()
    {
        return $this->duracionRegistro;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return RhuHorarioAcceso
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuHorarioAcceso
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
}
