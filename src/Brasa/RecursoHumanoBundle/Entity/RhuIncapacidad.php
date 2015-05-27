<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_incapacidad")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuIncapacidadRepository")
 */
class RhuIncapacidad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncapacidadPk;                    
    
    /**
     * @ORM\Column(name="numero", type="integer")
     */    
    private $numero = 0;     
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    
    
    /**
     * @ORM\Column(name="numero_eps", type="string", length=30, nullable=true)
     */    
    private $numeroEps;     
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;            
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;                

    /**
     * @ORM\Column(name="cantidad_pendiente", type="integer")
     */
    private $cantidadPendiente = 0;    
          
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;         
    
    /**
     * @ORM\Column(name="codigo_incapacidad_diagnostico_fk", type="integer", nullable=true)
     */    
    private $codigoIncapacidadDiagnosticoFk;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\Column(name="codigo_pago_adicional_subtipo_fk", type="integer", nullable=true)
     */    
    private $codigoPagoAdicionalSubtipoFk;
    
    /**     
     * @ORM\Column(name="estado_transcripcion", type="boolean")
     */    
    private $estadoTranscripcion = 0;      
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoAdicionalSubtipo", inversedBy="incapacidadesPagoAdicionalSubtipoRel")
     * @ORM\JoinColumn(name="codigo_pago_adicional_subtipo_fk", referencedColumnName="codigo_pago_adicional_subtipo_pk")
     */
    protected $pagoAdicionalSubtipoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="incapacidadesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="incapacidadesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuIncapacidadDiagnostico", inversedBy="incapacidadesIncapacidadDiagnosticoRel")
     * @ORM\JoinColumn(name="codigo_incapacidad_diagnostico_fk", referencedColumnName="codigo_incapacidad_diagnostico_pk")
     */
    protected $incapacidadDiagnosticoRel; 



    /**
     * Get codigoIncapacidadPk
     *
     * @return integer
     */
    public function getCodigoIncapacidadPk()
    {
        return $this->codigoIncapacidadPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuIncapacidad
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuIncapacidad
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
     * @return RhuIncapacidad
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
     * Set numeroEps
     *
     * @param string $numeroEps
     *
     * @return RhuIncapacidad
     */
    public function setNumeroEps($numeroEps)
    {
        $this->numeroEps = $numeroEps;

        return $this;
    }

    /**
     * Get numeroEps
     *
     * @return string
     */
    public function getNumeroEps()
    {
        return $this->numeroEps;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuIncapacidad
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return RhuIncapacidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set cantidadPendiente
     *
     * @param integer $cantidadPendiente
     *
     * @return RhuIncapacidad
     */
    public function setCantidadPendiente($cantidadPendiente)
    {
        $this->cantidadPendiente = $cantidadPendiente;

        return $this;
    }

    /**
     * Get cantidadPendiente
     *
     * @return integer
     */
    public function getCantidadPendiente()
    {
        return $this->cantidadPendiente;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuIncapacidad
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
     * @return RhuIncapacidad
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
     * Set codigoPagoAdicionalSubtipoFk
     *
     * @param integer $codigoPagoAdicionalSubtipoFk
     *
     * @return RhuIncapacidad
     */
    public function setCodigoPagoAdicionalSubtipoFk($codigoPagoAdicionalSubtipoFk)
    {
        $this->codigoPagoAdicionalSubtipoFk = $codigoPagoAdicionalSubtipoFk;

        return $this;
    }

    /**
     * Get codigoPagoAdicionalSubtipoFk
     *
     * @return integer
     */
    public function getCodigoPagoAdicionalSubtipoFk()
    {
        return $this->codigoPagoAdicionalSubtipoFk;
    }

    /**
     * Set pagoAdicionalSubtipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo $pagoAdicionalSubtipoRel
     *
     * @return RhuIncapacidad
     */
    public function setPagoAdicionalSubtipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo $pagoAdicionalSubtipoRel = null)
    {
        $this->pagoAdicionalSubtipoRel = $pagoAdicionalSubtipoRel;

        return $this;
    }

    /**
     * Get pagoAdicionalSubtipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo
     */
    public function getPagoAdicionalSubtipoRel()
    {
        return $this->pagoAdicionalSubtipoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuIncapacidad
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
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuIncapacidad
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
     * Set estadoTranscripcion
     *
     * @param boolean $estadoTranscripcion
     *
     * @return RhuIncapacidad
     */
    public function setEstadoTranscripcion($estadoTranscripcion)
    {
        $this->estadoTranscripcion = $estadoTranscripcion;

        return $this;
    }

    /**
     * Get estadoTranscripcion
     *
     * @return boolean
     */
    public function getEstadoTranscripcion()
    {
        return $this->estadoTranscripcion;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return RhuIncapacidad
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set codigoIncapacidadDiagnosticoFk
     *
     * @param integer $codigoIncapacidadDiagnosticoFk
     *
     * @return RhuIncapacidad
     */
    public function setCodigoIncapacidadDiagnosticoFk($codigoIncapacidadDiagnosticoFk)
    {
        $this->codigoIncapacidadDiagnosticoFk = $codigoIncapacidadDiagnosticoFk;

        return $this;
    }

    /**
     * Get codigoIncapacidadDiagnosticoFk
     *
     * @return integer
     */
    public function getCodigoIncapacidadDiagnosticoFk()
    {
        return $this->codigoIncapacidadDiagnosticoFk;
    }

    /**
     * Set incapacidadDiagnosticoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico $incapacidadDiagnosticoRel
     *
     * @return RhuIncapacidad
     */
    public function setIncapacidadDiagnosticoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico $incapacidadDiagnosticoRel = null)
    {
        $this->incapacidadDiagnosticoRel = $incapacidadDiagnosticoRel;

        return $this;
    }

    /**
     * Get incapacidadDiagnosticoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico
     */
    public function getIncapacidadDiagnosticoRel()
    {
        return $this->incapacidadDiagnosticoRel;
    }
}
