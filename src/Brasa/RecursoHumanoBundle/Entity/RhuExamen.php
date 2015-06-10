<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenRepository")
 */
class RhuExamen
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenPk;
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_entidad_examen_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadExamenFk;
    
    /**
     * @ORM\Column(name="codigo_examen_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoExamenTipoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date")
     */    
    private $fecha;            
    
    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = 0;     
    
    /**
     * @ORM\Column(name="nombreCorto", type="string")
     */
    private $nombreCorto;        
    
    /**
     * @ORM\Column(name="identificacion", type="integer")
     */
    private $identificacion;  

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadExamen", inversedBy="examenesEntidadExamenRel")
     * @ORM\JoinColumn(name="codigo_entidad_examen_fk", referencedColumnName="codigo_entidad_examen_pk")
     */
    protected $entidadExamenRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenTipo", inversedBy="examenesExamenTipoRel")
     * @ORM\JoinColumn(name="codigo_examen_tipo_fk", referencedColumnName="codigo_examen_tipo_pk")
     */
    protected $examenTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="examenesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;
    

    /**
     * Get codigoExamenPk
     *
     * @return integer
     */
    public function getCodigoExamenPk()
    {
        return $this->codigoExamenPk;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuExamen
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
     * Set codigoEntidadExamenFk
     *
     * @param integer $codigoEntidadExamenFk
     *
     * @return RhuExamen
     */
    public function setCodigoEntidadExamenFk($codigoEntidadExamenFk)
    {
        $this->codigoEntidadExamenFk = $codigoEntidadExamenFk;

        return $this;
    }

    /**
     * Get codigoEntidadExamenFk
     *
     * @return integer
     */
    public function getCodigoEntidadExamenFk()
    {
        return $this->codigoEntidadExamenFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuExamen
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
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return RhuExamen
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuExamen
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
     * Set identificacion
     *
     * @param integer $identificacion
     *
     * @return RhuExamen
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;

        return $this;
    }

    /**
     * Get identificacion
     *
     * @return integer
     */
    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    /**
     * Set entidadExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $entidadExamenRel
     *
     * @return RhuExamen
     */
    public function setEntidadExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $entidadExamenRel = null)
    {
        $this->entidadExamenRel = $entidadExamenRel;

        return $this;
    }

    /**
     * Get entidadExamenRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen
     */
    public function getEntidadExamenRel()
    {
        return $this->entidadExamenRel;
    }

    /**
     * Set examenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenTipoRel
     *
     * @return RhuExamen
     */
    public function setExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenTipoRel = null)
    {
        $this->examenTipoRel = $examenTipoRel;

        return $this;
    }

    /**
     * Get examenTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo
     */
    public function getExamenTipoRel()
    {
        return $this->examenTipoRel;
    }

    /**
     * Set codigoExamenTipoFk
     *
     * @param integer $codigoExamenTipoFk
     *
     * @return RhuExamen
     */
    public function setCodigoExamenTipoFk($codigoExamenTipoFk)
    {
        $this->codigoExamenTipoFk = $codigoExamenTipoFk;

        return $this;
    }

    /**
     * Get codigoExamenTipoFk
     *
     * @return integer
     */
    public function getCodigoExamenTipoFk()
    {
        return $this->codigoExamenTipoFk;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuExamen
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
}
