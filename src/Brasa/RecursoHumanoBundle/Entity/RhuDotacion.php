<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_dotacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDotacionRepository")
 */
class RhuDotacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dotacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDotacionPk;                    
    
    /**
     * @ORM\Column(name="numero_interno_referencia", type="integer", nullable=true)
     */    
    private $codigoInternoReferencia;        
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;            
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="tipo_proceso", type="integer", nullable=true)
     */    
    private $tipoProceso;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;          
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="dotacionesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;            
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="dotacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDotacionDetalle", mappedBy="dotacionRel")
     */
    protected $dotacionesDetallesDotacionRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dotacionesDetallesDotacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDotacionPk
     *
     * @return integer
     */
    public function getCodigoDotacionPk()
    {
        return $this->codigoDotacionPk;
    }

    /**
     * Set codigoInternoReferencia
     *
     * @param integer $codigoInternoReferencia
     *
     * @return RhuDotacion
     */
    public function setCodigoInternoReferencia($codigoInternoReferencia)
    {
        $this->codigoInternoReferencia = $codigoInternoReferencia;

        return $this;
    }

    /**
     * Get codigoInternoReferencia
     *
     * @return integer
     */
    public function getCodigoInternoReferencia()
    {
        return $this->codigoInternoReferencia;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuDotacion
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuDotacion
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuDotacion
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
     * @return RhuDotacion
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
     * @return RhuDotacion
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
     * @return RhuDotacion
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
     * Add dotacionesDetallesDotacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $dotacionesDetallesDotacionRel
     *
     * @return RhuDotacion
     */
    public function addDotacionesDetallesDotacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $dotacionesDetallesDotacionRel)
    {
        $this->dotacionesDetallesDotacionRel[] = $dotacionesDetallesDotacionRel;

        return $this;
    }

    /**
     * Remove dotacionesDetallesDotacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $dotacionesDetallesDotacionRel
     */
    public function removeDotacionesDetallesDotacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $dotacionesDetallesDotacionRel)
    {
        $this->dotacionesDetallesDotacionRel->removeElement($dotacionesDetallesDotacionRel);
    }

    /**
     * Get dotacionesDetallesDotacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDotacionesDetallesDotacionRel()
    {
        return $this->dotacionesDetallesDotacionRel;
    }

    /**
     * Set tipoProceso
     *
     * @param integer $tipoProceso
     *
     * @return RhuDotacion
     */
    public function setTipoProceso($tipoProceso)
    {
        $this->tipoProceso = $tipoProceso;

        return $this;
    }

    /**
     * Get tipoProceso
     *
     * @return integer
     */
    public function getTipoProceso()
    {
        return $this->tipoProceso;
    }
}
