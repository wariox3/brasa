<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_examen")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoExamenRepository")
 */
class RhuEmpleadoExamen
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_examen_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoExamenPk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_examen_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoExamenTipoFk;     
    
    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */         
    private $fechaVencimiento;     
    
    /**     
     * @ORM\Column(name="validar_vencimiento", type="boolean")
     */    
    private $validarVencimiento = 0;        
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenTipo", inversedBy="empleadosExamenesExamenTipoRel")
     * @ORM\JoinColumn(name="codigo_examen_tipo_fk", referencedColumnName="codigo_examen_tipo_pk")
     */
    protected $examenTipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="empleadosExamenesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    


    /**
     * Get codigoEmpleadoExamenPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoExamenPk()
    {
        return $this->codigoEmpleadoExamenPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuEmpleadoExamen
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
     * Set codigoExamenTipoFk
     *
     * @param integer $codigoExamenTipoFk
     *
     * @return RhuEmpleadoExamen
     */
    public function setCodigoExamenTipoFk($codigoExamenTipoFk)
    {
        $this->codigoExamenTipoFk = $codigoExamenTipoFk;

        return $this;
    }

    /**
     * Get codigoExamenTipoFk
     *
     * @return integer
     */
    public function getCodigoExamenTipoFk()
    {
        return $this->codigoExamenTipoFk;
    }

    /**
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return RhuEmpleadoExamen
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
     * Set validarVencimiento
     *
     * @param boolean $validarVencimiento
     *
     * @return RhuEmpleadoExamen
     */
    public function setValidarVencimiento($validarVencimiento)
    {
        $this->validarVencimiento = $validarVencimiento;

        return $this;
    }

    /**
     * Get validarVencimiento
     *
     * @return boolean
     */
    public function getValidarVencimiento()
    {
        return $this->validarVencimiento;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuEmpleadoExamen
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
     * Set examenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenTipoRel
     *
     * @return RhuEmpleadoExamen
     */
    public function setExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenTipoRel = null)
    {
        $this->examenTipoRel = $examenTipoRel;

        return $this;
    }

    /**
     * Get examenTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo
     */
    public function getExamenTipoRel()
    {
        return $this->examenTipoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuEmpleadoExamen
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
