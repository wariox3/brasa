<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_requisito")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuRequisitoRepository")
 */
class RhuRequisito
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_requisito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRequisitoPk;        
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;        
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;             
    
    /**     
     * @ORM\Column(name="estado_aceptado", type="boolean")
     */    
    private $estadoAceptado = 0;       
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;       
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="requisitosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuRequisitoDetalle", mappedBy="requisitoRel")
     */
    protected $requisitosDetallesRequisitoRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->requisitosDetallesRequisitoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoRequisitoPk
     *
     * @return integer
     */
    public function getCodigoRequisitoPk()
    {
        return $this->codigoRequisitoPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuRequisito
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
     * @return RhuRequisito
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
     * Set estadoAceptado
     *
     * @param boolean $estadoAceptado
     *
     * @return RhuRequisito
     */
    public function setEstadoAceptado($estadoAceptado)
    {
        $this->estadoAceptado = $estadoAceptado;

        return $this;
    }

    /**
     * Get estadoAceptado
     *
     * @return boolean
     */
    public function getEstadoAceptado()
    {
        return $this->estadoAceptado;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuRequisito
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
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuRequisito
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
     * Add requisitosDetallesRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoRel
     *
     * @return RhuRequisito
     */
    public function addRequisitosDetallesRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoRel)
    {
        $this->requisitosDetallesRequisitoRel[] = $requisitosDetallesRequisitoRel;

        return $this;
    }

    /**
     * Remove requisitosDetallesRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoRel
     */
    public function removeRequisitosDetallesRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoRel)
    {
        $this->requisitosDetallesRequisitoRel->removeElement($requisitosDetallesRequisitoRel);
    }

    /**
     * Get requisitosDetallesRequisitoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitosDetallesRequisitoRel()
    {
        return $this->requisitosDetallesRequisitoRel;
    }
}
