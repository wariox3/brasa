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
     * @ORM\Column(name="codigo_tipo_acceso_fk", type="integer", nullable=true)
     */    
    private $codigoTipoAccesoFk;     
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;           
   
    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoAcceso", inversedBy="horarioAccesoTipoAccesoRel")
     * @ORM\JoinColumn(name="codigo_tipo_acceso_fk", referencedColumnName="codigo_tipo_acceso_pk")
     */
    protected $tipoAccesoRel;

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
     * Set codigoTipoAccesoFk
     *
     * @param integer $codigoTipoAccesoFk
     *
     * @return RhuHorarioAcceso
     */
    public function setCodigoTipoAccesoFk($codigoTipoAccesoFk)
    {
        $this->codigoTipoAccesoFk = $codigoTipoAccesoFk;

        return $this;
    }

    /**
     * Get codigoTipoAccesoFk
     *
     * @return integer
     */
    public function getCodigoTipoAccesoFk()
    {
        return $this->codigoTipoAccesoFk;
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuHorarioAcceso
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
     * Set tipoAccesoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoAcceso $tipoAccesoRel
     *
     * @return RhuHorarioAcceso
     */
    public function setTipoAccesoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoAcceso $tipoAccesoRel = null)
    {
        $this->tipoAccesoRel = $tipoAccesoRel;

        return $this;
    }

    /**
     * Get tipoAccesoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuTipoAcceso
     */
    public function getTipoAccesoRel()
    {
        return $this->tipoAccesoRel;
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
