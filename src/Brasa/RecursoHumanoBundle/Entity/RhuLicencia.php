<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_licencia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuLicenciaRepository")
 */
class RhuLicencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_licencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLicenciaPk;                    
    
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
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;            
    
    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;
    
    /**
     * @ORM\Column(name="cantidad_pendiente", type="float")
     */
    private $cantidadPendiente = 0;
    
    /**
     * @ORM\Column(name="cantidad_afectada", type="float")
     */
    private $cantidadAfectada = 0;
        
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;          
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**     
     * @ORM\Column(name="afecta_transporte", type="boolean")
     */    
    private $afectaTransporte = 0;     
    
    /**     
     * @ORM\Column(name="estado_cerrada", type="boolean")
     */    
    private $estadoCerrada = 0;    
    
    /**
     * @ORM\Column(name="codigo_pago_adicional_subtipo_fk", type="integer", nullable=true)
     */    
    private $codigoPagoAdicionalSubtipoFk;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoAdicionalSubtipo", inversedBy="licenciasPagoAdicionalSubtipoRel")
     * @ORM\JoinColumn(name="codigo_pago_adicional_subtipo_fk", referencedColumnName="codigo_pago_adicional_subtipo_pk")
     */
    protected $pagoAdicionalSubtipoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="licenciasCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;            
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="licenciasEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    

    /**
     * Get codigoLicenciaPk
     *
     * @return integer
     */
    public function getCodigoLicenciaPk()
    {
        return $this->codigoLicenciaPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuLicencia
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
     * @return RhuLicencia
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
     * @return RhuLicencia
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuLicencia
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
     * @param float $cantidad
     *
     * @return RhuLicencia
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set cantidadPendiente
     *
     * @param float $cantidadPendiente
     *
     * @return RhuLicencia
     */
    public function setCantidadPendiente($cantidadPendiente)
    {
        $this->cantidadPendiente = $cantidadPendiente;

        return $this;
    }

    /**
     * Get cantidadPendiente
     *
     * @return float
     */
    public function getCantidadPendiente()
    {
        return $this->cantidadPendiente;
    }

    /**
     * Set cantidadAfectada
     *
     * @param float $cantidadAfectada
     *
     * @return RhuLicencia
     */
    public function setCantidadAfectada($cantidadAfectada)
    {
        $this->cantidadAfectada = $cantidadAfectada;

        return $this;
    }

    /**
     * Get cantidadAfectada
     *
     * @return float
     */
    public function getCantidadAfectada()
    {
        return $this->cantidadAfectada;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuLicencia
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
     * @return RhuLicencia
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
     * Set afectaTransporte
     *
     * @param boolean $afectaTransporte
     *
     * @return RhuLicencia
     */
    public function setAfectaTransporte($afectaTransporte)
    {
        $this->afectaTransporte = $afectaTransporte;

        return $this;
    }

    /**
     * Get afectaTransporte
     *
     * @return boolean
     */
    public function getAfectaTransporte()
    {
        return $this->afectaTransporte;
    }

    /**
     * Set estadoCerrada
     *
     * @param boolean $estadoCerrada
     *
     * @return RhuLicencia
     */
    public function setEstadoCerrada($estadoCerrada)
    {
        $this->estadoCerrada = $estadoCerrada;

        return $this;
    }

    /**
     * Get estadoCerrada
     *
     * @return boolean
     */
    public function getEstadoCerrada()
    {
        return $this->estadoCerrada;
    }

    /**
     * Set codigoPagoAdicionalSubtipoFk
     *
     * @param integer $codigoPagoAdicionalSubtipoFk
     *
     * @return RhuLicencia
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
     * @return RhuLicencia
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
     * @return RhuLicencia
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
     * @return RhuLicencia
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
