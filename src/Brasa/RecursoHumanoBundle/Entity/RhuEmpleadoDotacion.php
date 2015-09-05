<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_dotacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoDotacionRepository")
 */
class RhuEmpleadoDotacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_dotacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoDotacionPk;                    
    
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
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="empleadosDotacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoDotacionDetalle", mappedBy="empleadoDotacionRel")
     */
    protected $empleadosDotacionesDetallesEmpleadoDotacionRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosDotacionesEmpleadoDotacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEmpleadoDotacionPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoDotacionPk()
    {
        return $this->codigoEmpleadoDotacionPk;
    }

    /**
     * Set codigoInternoReferencia
     *
     * @param integer $codigoInternoReferencia
     *
     * @return RhuEmpleadoDotacion
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
     * @return RhuEmpleadoDotacion
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
     * @return RhuEmpleadoDotacion
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
     * @return RhuEmpleadoDotacion
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
     * @return RhuEmpleadoDotacion
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
     * @return RhuEmpleadoDotacion
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
     * @return RhuEmpleadoDotacion
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
     * Add empleadosDotacionesEmpleadoDotacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion $empleadosDotacionesEmpleadoDotacionRel
     *
     * @return RhuEmpleadoDotacion
     */
    public function addEmpleadosDotacionesEmpleadoDotacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion $empleadosDotacionesEmpleadoDotacionRel)
    {
        $this->empleadosDotacionesEmpleadoDotacionRel[] = $empleadosDotacionesEmpleadoDotacionRel;

        return $this;
    }

    /**
     * Remove empleadosDotacionesEmpleadoDotacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion $empleadosDotacionesEmpleadoDotacionRel
     */
    public function removeEmpleadosDotacionesEmpleadoDotacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion $empleadosDotacionesEmpleadoDotacionRel)
    {
        $this->empleadosDotacionesEmpleadoDotacionRel->removeElement($empleadosDotacionesEmpleadoDotacionRel);
    }

    /**
     * Get empleadosDotacionesEmpleadoDotacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosDotacionesEmpleadoDotacionRel()
    {
        return $this->empleadosDotacionesEmpleadoDotacionRel;
    }

    /**
     * Add empleadosDotacionesDetallesEmpleadoDotacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacionDetalle $empleadosDotacionesDetallesEmpleadoDotacionRel
     *
     * @return RhuEmpleadoDotacion
     */
    public function addEmpleadosDotacionesDetallesEmpleadoDotacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacionDetalle $empleadosDotacionesDetallesEmpleadoDotacionRel)
    {
        $this->empleadosDotacionesDetallesEmpleadoDotacionRel[] = $empleadosDotacionesDetallesEmpleadoDotacionRel;

        return $this;
    }

    /**
     * Remove empleadosDotacionesDetallesEmpleadoDotacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacionDetalle $empleadosDotacionesDetallesEmpleadoDotacionRel
     */
    public function removeEmpleadosDotacionesDetallesEmpleadoDotacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacionDetalle $empleadosDotacionesDetallesEmpleadoDotacionRel)
    {
        $this->empleadosDotacionesDetallesEmpleadoDotacionRel->removeElement($empleadosDotacionesDetallesEmpleadoDotacionRel);
    }

    /**
     * Get empleadosDotacionesDetallesEmpleadoDotacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosDotacionesDetallesEmpleadoDotacionRel()
    {
        return $this->empleadosDotacionesDetallesEmpleadoDotacionRel;
    }
}
