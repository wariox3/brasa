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
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;        
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;             
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false)
     */         
    private $numeroIdentificacion;
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto; 
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;        
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;       
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;    
    
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
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="requisitosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuRequisitoDetalle", mappedBy="requisitoRel", cascade={"persist", "remove"})
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
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuRequisito
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuRequisito
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuRequisito
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuRequisito
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuRequisito
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
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuRequisito
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
