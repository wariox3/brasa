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
     * @ORM\Column(name="codigo_turno_fk",  type="string", length=250, nullable=true)
     */    
    private $codigoTurnoFk;
    
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
     * @ORM\Column(name="duracion_registro", type="string", length=15, nullable=true)
     */    
    private $duracionRegistro;
    
    /**     
     * @ORM\Column(name="estado_entrada", type="boolean")
     */    
    private $estadoEntrada = FALSE;
    
    /**     
     * @ORM\Column(name="estado_salida", type="boolean")
     */    
    private $estadoSalida = FALSE;
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="hora_entrada_empleado", type="time", nullable=true)
     */    
    private $horaEntradaEmpleado;    

    /**
     * @ORM\Column(name="hora_salida_empleado", type="time", nullable=true)
     */    
    private $horaSalidaEmpleado;
   
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="horarioAccesoEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuTurno", inversedBy="horariosAccesosTurnoRel")
     * @ORM\JoinColumn(name="codigo_turno_fk", referencedColumnName="codigo_turno_pk")
     */
    protected $turnoRel;
    
    


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
     * Set codigoTurnoFk
     *
     * @param string $codigoTurnoFk
     *
     * @return RhuHorarioAcceso
     */
    public function setCodigoTurnoFk($codigoTurnoFk)
    {
        $this->codigoTurnoFk = $codigoTurnoFk;

        return $this;
    }

    /**
     * Get codigoTurnoFk
     *
     * @return string
     */
    public function getCodigoTurnoFk()
    {
        return $this->codigoTurnoFk;
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
     * @param string $duracionRegistro
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
     * @return string
     */
    public function getDuracionRegistro()
    {
        return $this->duracionRegistro;
    }

    /**
     * Set estadoEntrada
     *
     * @param boolean $estadoEntrada
     *
     * @return RhuHorarioAcceso
     */
    public function setEstadoEntrada($estadoEntrada)
    {
        $this->estadoEntrada = $estadoEntrada;

        return $this;
    }

    /**
     * Get estadoEntrada
     *
     * @return boolean
     */
    public function getEstadoEntrada()
    {
        return $this->estadoEntrada;
    }

    /**
     * Set estadoSalida
     *
     * @param boolean $estadoSalida
     *
     * @return RhuHorarioAcceso
     */
    public function setEstadoSalida($estadoSalida)
    {
        $this->estadoSalida = $estadoSalida;

        return $this;
    }

    /**
     * Get estadoSalida
     *
     * @return boolean
     */
    public function getEstadoSalida()
    {
        return $this->estadoSalida;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return RhuHorarioAcceso
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horaEntradaEmpleado
     *
     * @param \DateTime $horaEntradaEmpleado
     *
     * @return RhuHorarioAcceso
     */
    public function setHoraEntradaEmpleado($horaEntradaEmpleado)
    {
        $this->horaEntradaEmpleado = $horaEntradaEmpleado;

        return $this;
    }

    /**
     * Get horaEntradaEmpleado
     *
     * @return \DateTime
     */
    public function getHoraEntradaEmpleado()
    {
        return $this->horaEntradaEmpleado;
    }

    /**
     * Set horaSalidaEmpleado
     *
     * @param \DateTime $horaSalidaEmpleado
     *
     * @return RhuHorarioAcceso
     */
    public function setHoraSalidaEmpleado($horaSalidaEmpleado)
    {
        $this->horaSalidaEmpleado = $horaSalidaEmpleado;

        return $this;
    }

    /**
     * Get horaSalidaEmpleado
     *
     * @return \DateTime
     */
    public function getHoraSalidaEmpleado()
    {
        return $this->horaSalidaEmpleado;
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

    /**
     * Set turnoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTurno $turnoRel
     *
     * @return RhuHorarioAcceso
     */
    public function setTurnoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTurno $turnoRel = null)
    {
        $this->turnoRel = $turnoRel;

        return $this;
    }

    /**
     * Get turnoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuTurno
     */
    public function getTurnoRel()
    {
        return $this->turnoRel;
    }
}
