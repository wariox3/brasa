<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_dotacion_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoDotacionDetalleRepository")
 */
class RhuEmpleadoDotacionDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_dotacion_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoDotacionDetallePk;                    
    
    /**
     * @ORM\Column(name="codigo_empleado_dotacion_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoDotacionFk;
    
    /**
     * @ORM\Column(name="codigo_dotacion_elemento_fk", type="integer", nullable=true)
     */    
    private $codigoDotacionElementoFk;
    
    /**
     * @ORM\Column(name="cantidad_asignada", type="integer", nullable=true)
     */    
    private $cantidadAsignada;
    
    /**
     * @ORM\Column(name="cantidad_devuelta", type="integer", nullable=false)
     */    
    private $cantidadDevuelta;
    
    /**
     * @ORM\Column(name="serie", type="string", nullable=false)
     */
    private $serie;
    
    /**
     * @ORM\Column(name="lote", type="string", nullable=false)
     */
    private $lote;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoDotacion", inversedBy="empleadosDotacionesDetallesEmpleadoDotacionRel")
     * @ORM\JoinColumn(name="codigo_empleado_dotacion_fk", referencedColumnName="codigo_empleado_dotacion_pk")
     */
    protected $empleadoDotacionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDotacionElemento", inversedBy="elementosDotacionesDetalleDotacionElementoRel")
     * @ORM\JoinColumn(name="codigo_dotacion_elemento_fk", referencedColumnName="codigo_dotacion_elemento_pk")
     */
    protected $dotacionElementoRel;
    

    /**
     * Get codigoEmpleadoDotacionDetallePk
     *
     * @return integer
     */
    public function getCodigoEmpleadoDotacionDetallePk()
    {
        return $this->codigoEmpleadoDotacionDetallePk;
    }

    /**
     * Set codigoEmpleadoDotacionFk
     *
     * @param integer $codigoEmpleadoDotacionFk
     *
     * @return RhuEmpleadoDotacionDetalle
     */
    public function setCodigoEmpleadoDotacionFk($codigoEmpleadoDotacionFk)
    {
        $this->codigoEmpleadoDotacionFk = $codigoEmpleadoDotacionFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoDotacionFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoDotacionFk()
    {
        return $this->codigoEmpleadoDotacionFk;
    }

    /**
     * Set codigoDotacionElementoFk
     *
     * @param integer $codigoDotacionElementoFk
     *
     * @return RhuEmpleadoDotacionDetalle
     */
    public function setCodigoDotacionElementoFk($codigoDotacionElementoFk)
    {
        $this->codigoDotacionElementoFk = $codigoDotacionElementoFk;

        return $this;
    }

    /**
     * Get codigoDotacionElementoFk
     *
     * @return integer
     */
    public function getCodigoDotacionElementoFk()
    {
        return $this->codigoDotacionElementoFk;
    }

    /**
     * Set cantidadAsignada
     *
     * @param integer $cantidadAsignada
     *
     * @return RhuEmpleadoDotacionDetalle
     */
    public function setCantidadAsignada($cantidadAsignada)
    {
        $this->cantidadAsignada = $cantidadAsignada;

        return $this;
    }

    /**
     * Get cantidadAsignada
     *
     * @return integer
     */
    public function getCantidadAsignada()
    {
        return $this->cantidadAsignada;
    }

    /**
     * Set cantidadDevuelta
     *
     * @param integer $cantidadDevuelta
     *
     * @return RhuEmpleadoDotacionDetalle
     */
    public function setCantidadDevuelta($cantidadDevuelta)
    {
        $this->cantidadDevuelta = $cantidadDevuelta;

        return $this;
    }

    /**
     * Get cantidadDevuelta
     *
     * @return integer
     */
    public function getCantidadDevuelta()
    {
        return $this->cantidadDevuelta;
    }

    /**
     * Set serie
     *
     * @param string $serie
     *
     * @return RhuEmpleadoDotacionDetalle
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return string
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set lote
     *
     * @param string $lote
     *
     * @return RhuEmpleadoDotacionDetalle
     */
    public function setLote($lote)
    {
        $this->lote = $lote;

        return $this;
    }

    /**
     * Get lote
     *
     * @return string
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set empleadoDotacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion $empleadoDotacionRel
     *
     * @return RhuEmpleadoDotacionDetalle
     */
    public function setEmpleadoDotacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion $empleadoDotacionRel = null)
    {
        $this->empleadoDotacionRel = $empleadoDotacionRel;

        return $this;
    }

    /**
     * Get empleadoDotacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion
     */
    public function getEmpleadoDotacionRel()
    {
        return $this->empleadoDotacionRel;
    }

    /**
     * Set dotacionElementoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento $dotacionElementoRel
     *
     * @return RhuEmpleadoDotacionDetalle
     */
    public function setDotacionElementoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento $dotacionElementoRel = null)
    {
        $this->dotacionElementoRel = $dotacionElementoRel;

        return $this;
    }

    /**
     * Get dotacionElementoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento
     */
    public function getDotacionElementoRel()
    {
        return $this->dotacionElementoRel;
    }
}
