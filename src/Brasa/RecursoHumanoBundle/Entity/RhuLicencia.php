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
     * @ORM\Column(name="cantidadAfectada", type="float")
     */
    private $cantidadAfectada = 0;    
    
    /**
     * @ORM\Column(name="cantidadPendiente", type="float")
     */
    private $cantidadPendiente = 0;    
        
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;          
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
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
     * @ORM\OneToMany(targetEntity="RhuLicenciaRegistroPago", mappedBy="licenciaRel")
     */
    protected $licenciasRegistrosPagosLicenciaRel;    

  
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->licenciasRegistrosPagosLicenciaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    /**
     * Add licenciasRegistrosPagosLicenciaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicenciaRegistroPago $licenciasRegistrosPagosLicenciaRel
     *
     * @return RhuLicencia
     */
    public function addLicenciasRegistrosPagosLicenciaRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicenciaRegistroPago $licenciasRegistrosPagosLicenciaRel)
    {
        $this->licenciasRegistrosPagosLicenciaRel[] = $licenciasRegistrosPagosLicenciaRel;

        return $this;
    }

    /**
     * Remove licenciasRegistrosPagosLicenciaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicenciaRegistroPago $licenciasRegistrosPagosLicenciaRel
     */
    public function removeLicenciasRegistrosPagosLicenciaRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicenciaRegistroPago $licenciasRegistrosPagosLicenciaRel)
    {
        $this->licenciasRegistrosPagosLicenciaRel->removeElement($licenciasRegistrosPagosLicenciaRel);
    }

    /**
     * Get licenciasRegistrosPagosLicenciaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLicenciasRegistrosPagosLicenciaRel()
    {
        return $this->licenciasRegistrosPagosLicenciaRel;
    }
}
