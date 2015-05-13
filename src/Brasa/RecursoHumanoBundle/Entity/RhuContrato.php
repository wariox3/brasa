<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contrato")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContratoRepository")
 */
class RhuContrato
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoPk;        
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     
    
    /**
     * @ORM\Column(name="codigo_tipo_tiempo_fk", type="integer", nullable=true)
     */    
    private $codigoTipoTiempoFk;    
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta; 
    
    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */    
    private $numero;     
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;    
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = 0;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    
    
    /**     
     * @ORM\Column(name="indefinido", type="boolean")
     */    
    private $indefinido = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="contratosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoTiempo", inversedBy="contratosTipoTiempoRel")
     * @ORM\JoinColumn(name="codigo_tipo_tiempo_fk", referencedColumnName="codigo_tipo_tiempo_pk")
     */
    protected $tipoTiempoRel;     
    



    /**
     * Get codigoContratoPk
     *
     * @return integer
     */
    public function getCodigoContratoPk()
    {
        return $this->codigoContratoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuContrato
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
     * Set codigoTipoTiempoFk
     *
     * @param integer $codigoTipoTiempoFk
     *
     * @return RhuContrato
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuContrato
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuContrato
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuContrato
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return RhuContrato
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuContrato
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
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return RhuContrato
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuContrato
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
     * Set indefinido
     *
     * @param boolean $indefinido
     *
     * @return RhuContrato
     */
    public function setIndefinido($indefinido)
    {
        $this->indefinido = $indefinido;

        return $this;
    }

    /**
     * Get indefinido
     *
     * @return boolean
     */
    public function getIndefinido()
    {
        return $this->indefinido;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuContrato
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
     * Set tipoTiempoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoTiempo $tipoTiempoRel
     *
     * @return RhuContrato
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
