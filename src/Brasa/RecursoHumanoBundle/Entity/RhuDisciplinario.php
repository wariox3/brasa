<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_disciplinario")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDisciplinarioRepository")
 */
class RhuDisciplinario
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_disciplinario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDisciplinarioPk;        
    
    /**
     * @ORM\Column(name="codigo_disciplinario_tipo_fk", type="integer")
     */    
    private $codigoDisciplinarioTipoFk; 
    
    /**
     * @ORM\Column(name="codigo_disciplinario_motivo_fk", type="integer", nullable=true)
     */    
    private $codigoDisciplinarioMotivoFk;    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;         
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;             
    
    /**
     * @ORM\Column(name="asunto", type="string", length=500, nullable=true)
     */    
    private $asunto;     
    
    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */    
    private $comentarios;
    
    /**
     * @ORM\Column(name="fecha_incidente", type="string", length=100, nullable=true)
     */    
    private $fechaIncidente;
    
    /**
     * @ORM\Column(name="fecha_aplica_proceso", type="string", length=100, nullable=true)
     */    
    private $fechaAplicaProceso;
    
    /**
     * @ORM\Column(name="fecha_aplica_hasta_proceso", type="string", length=100, nullable=true)
     */    
    private $fechaAplicaHastaProceso;
    
    /**
     * @ORM\Column(name="fecha_ingreso_trabajo", type="string", length=100, nullable=true)
     */    
    private $fechaIngresoTrabajo;
    
    /**
     * @ORM\Column(name="dias_suspencion", type="string", length=100, nullable=true)
     */    
    private $diasSuspencion;
    
    /**     
     * @ORM\Column(name="reentrenamiento", type="boolean")
     */    
    private $reentrenamiento = false;
    
    /**
     * @ORM\Column(name="puesto", type="string", length=100, nullable=true)
     */    
    private $puesto;
    
    /**
     * @ORM\Column(name="zona", type="string", length=100, nullable=true)
     */    
    private $zona;
    
    /**
     * @ORM\Column(name="operacion", type="string", length=100, nullable=true)
     */    
    private $operacion;   
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer")
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;    
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="disciplinariosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;         

    /**
     * @ORM\ManyToOne(targetEntity="RhuDisciplinarioTipo", inversedBy="disciplinariosDisciplinarioTipoRel")
     * @ORM\JoinColumn(name="codigo_disciplinario_tipo_fk", referencedColumnName="codigo_disciplinario_tipo_pk")
     */
    protected $disciplinarioTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDisciplinarioMotivo", inversedBy="disciplinariosDisciplinarioMotivoRel")
     * @ORM\JoinColumn(name="codigo_disciplinario_motivo_fk", referencedColumnName="codigo_disciplinario_motivo_pk")
     */
    protected $disciplinarioMotivoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="disciplinariosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="disciplinariosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDisciplinarioDescargo", mappedBy="disciplinarioRel")
     */
    protected $disciplinariosDescargosDisciplinarioRel;     


    /**
     * Get codigoDisciplinarioPk
     *
     * @return integer
     */
    public function getCodigoDisciplinarioPk()
    {
        return $this->codigoDisciplinarioPk;
    }

    /**
     * Set codigoDisciplinarioTipoFk
     *
     * @param integer $codigoDisciplinarioTipoFk
     *
     * @return RhuDisciplinario
     */
    public function setCodigoDisciplinarioTipoFk($codigoDisciplinarioTipoFk)
    {
        $this->codigoDisciplinarioTipoFk = $codigoDisciplinarioTipoFk;

        return $this;
    }

    /**
     * Get codigoDisciplinarioTipoFk
     *
     * @return integer
     */
    public function getCodigoDisciplinarioTipoFk()
    {
        return $this->codigoDisciplinarioTipoFk;
    }

    /**
     * Set codigoDisciplinarioMotivoFk
     *
     * @param integer $codigoDisciplinarioMotivoFk
     *
     * @return RhuDisciplinario
     */
    public function setCodigoDisciplinarioMotivoFk($codigoDisciplinarioMotivoFk)
    {
        $this->codigoDisciplinarioMotivoFk = $codigoDisciplinarioMotivoFk;

        return $this;
    }

    /**
     * Get codigoDisciplinarioMotivoFk
     *
     * @return integer
     */
    public function getCodigoDisciplinarioMotivoFk()
    {
        return $this->codigoDisciplinarioMotivoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuDisciplinario
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuDisciplinario
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
     * Set asunto
     *
     * @param string $asunto
     *
     * @return RhuDisciplinario
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get asunto
     *
     * @return string
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuDisciplinario
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
     * Set fechaIncidente
     *
     * @param string $fechaIncidente
     *
     * @return RhuDisciplinario
     */
    public function setFechaIncidente($fechaIncidente)
    {
        $this->fechaIncidente = $fechaIncidente;

        return $this;
    }

    /**
     * Get fechaIncidente
     *
     * @return string
     */
    public function getFechaIncidente()
    {
        return $this->fechaIncidente;
    }

    /**
     * Set fechaAplicaProceso
     *
     * @param string $fechaAplicaProceso
     *
     * @return RhuDisciplinario
     */
    public function setFechaAplicaProceso($fechaAplicaProceso)
    {
        $this->fechaAplicaProceso = $fechaAplicaProceso;

        return $this;
    }

    /**
     * Get fechaAplicaProceso
     *
     * @return string
     */
    public function getFechaAplicaProceso()
    {
        return $this->fechaAplicaProceso;
    }

    /**
     * Set fechaAplicaHastaProceso
     *
     * @param string $fechaAplicaHastaProceso
     *
     * @return RhuDisciplinario
     */
    public function setFechaAplicaHastaProceso($fechaAplicaHastaProceso)
    {
        $this->fechaAplicaHastaProceso = $fechaAplicaHastaProceso;

        return $this;
    }

    /**
     * Get fechaAplicaHastaProceso
     *
     * @return string
     */
    public function getFechaAplicaHastaProceso()
    {
        return $this->fechaAplicaHastaProceso;
    }

    /**
     * Set fechaIngresoTrabajo
     *
     * @param string $fechaIngresoTrabajo
     *
     * @return RhuDisciplinario
     */
    public function setFechaIngresoTrabajo($fechaIngresoTrabajo)
    {
        $this->fechaIngresoTrabajo = $fechaIngresoTrabajo;

        return $this;
    }

    /**
     * Get fechaIngresoTrabajo
     *
     * @return string
     */
    public function getFechaIngresoTrabajo()
    {
        return $this->fechaIngresoTrabajo;
    }

    /**
     * Set diasSuspencion
     *
     * @param string $diasSuspencion
     *
     * @return RhuDisciplinario
     */
    public function setDiasSuspencion($diasSuspencion)
    {
        $this->diasSuspencion = $diasSuspencion;

        return $this;
    }

    /**
     * Get diasSuspencion
     *
     * @return string
     */
    public function getDiasSuspencion()
    {
        return $this->diasSuspencion;
    }

    /**
     * Set reentrenamiento
     *
     * @param boolean $reentrenamiento
     *
     * @return RhuDisciplinario
     */
    public function setReentrenamiento($reentrenamiento)
    {
        $this->reentrenamiento = $reentrenamiento;

        return $this;
    }

    /**
     * Get reentrenamiento
     *
     * @return boolean
     */
    public function getReentrenamiento()
    {
        return $this->reentrenamiento;
    }

    /**
     * Set puesto
     *
     * @param string $puesto
     *
     * @return RhuDisciplinario
     */
    public function setPuesto($puesto)
    {
        $this->puesto = $puesto;

        return $this;
    }

    /**
     * Get puesto
     *
     * @return string
     */
    public function getPuesto()
    {
        return $this->puesto;
    }

    /**
     * Set zona
     *
     * @param string $zona
     *
     * @return RhuDisciplinario
     */
    public function setZona($zona)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return string
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set operacion
     *
     * @param string $operacion
     *
     * @return RhuDisciplinario
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return string
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuDisciplinario
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuDisciplinario
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
     * @return RhuDisciplinario
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
     * Set estado
     *
     * @param boolean $estado
     *
     * @return RhuDisciplinario
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
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuDisciplinario
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
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuDisciplinario
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
     * Set disciplinarioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo $disciplinarioTipoRel
     *
     * @return RhuDisciplinario
     */
    public function setDisciplinarioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo $disciplinarioTipoRel = null)
    {
        $this->disciplinarioTipoRel = $disciplinarioTipoRel;

        return $this;
    }

    /**
     * Get disciplinarioTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo
     */
    public function getDisciplinarioTipoRel()
    {
        return $this->disciplinarioTipoRel;
    }

    /**
     * Set disciplinarioMotivoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioMotivo $disciplinarioMotivoRel
     *
     * @return RhuDisciplinario
     */
    public function setDisciplinarioMotivoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioMotivo $disciplinarioMotivoRel = null)
    {
        $this->disciplinarioMotivoRel = $disciplinarioMotivoRel;

        return $this;
    }

    /**
     * Get disciplinarioMotivoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioMotivo
     */
    public function getDisciplinarioMotivoRel()
    {
        return $this->disciplinarioMotivoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuDisciplinario
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
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuDisciplinario
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuDisciplinario
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
     * Constructor
     */
    public function __construct()
    {
        $this->disciplinariosDescargosDisciplinarioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add disciplinariosDescargosDisciplinarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo $disciplinariosDescargosDisciplinarioRel
     *
     * @return RhuDisciplinario
     */
    public function addDisciplinariosDescargosDisciplinarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo $disciplinariosDescargosDisciplinarioRel)
    {
        $this->disciplinariosDescargosDisciplinarioRel[] = $disciplinariosDescargosDisciplinarioRel;

        return $this;
    }

    /**
     * Remove disciplinariosDescargosDisciplinarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo $disciplinariosDescargosDisciplinarioRel
     */
    public function removeDisciplinariosDescargosDisciplinarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo $disciplinariosDescargosDisciplinarioRel)
    {
        $this->disciplinariosDescargosDisciplinarioRel->removeElement($disciplinariosDescargosDisciplinarioRel);
    }

    /**
     * Get disciplinariosDescargosDisciplinarioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinariosDescargosDisciplinarioRel()
    {
        return $this->disciplinariosDescargosDisciplinarioRel;
    }
}
