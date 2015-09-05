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
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;
    
    /**     
     * @ORM\Column(name="estado_pagado", type="boolean")
     */    
    private $estadoPagado = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadExamen", inversedBy="examenesEntidadExamenRel")
     * @ORM\JoinColumn(name="codigo_entidad_examen_fk", referencedColumnName="codigo_entidad_examen_pk")
     */
    protected $entidadExamenRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="examenesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenDetalle", mappedBy="examenRel")
     */
    protected $examenesExamenDetalleRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoExamenDetalle", mappedBy="examenRel")
     */
    protected $pagosExamenesDetallesExamenRel;

    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenesExamenDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosExamenesDetallesExamenRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuExamen
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set estadoPagado
     *
     * @param boolean $estadoPagado
     *
     * @return RhuExamen
     */
    public function setEstadoPagado($estadoPagado)
    {
        $this->estadoPagado = $estadoPagado;

        return $this;
    }

    /**
     * Get estadoPagado
     *
     * @return boolean
     */
    public function getEstadoPagado()
    {
        return $this->estadoPagado;
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

    /**
     * Add examenesExamenDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel
     *
     * @return RhuExamen
     */
    public function addExamenesExamenDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel)
    {
        $this->examenesExamenDetalleRel[] = $examenesExamenDetalleRel;

        return $this;
    }

    /**
     * Remove examenesExamenDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel
     */
    public function removeExamenesExamenDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel)
    {
        $this->examenesExamenDetalleRel->removeElement($examenesExamenDetalleRel);
    }

    /**
     * Get examenesExamenDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesExamenDetalleRel()
    {
        return $this->examenesExamenDetalleRel;
    }

    /**
     * Add pagosExamenesDetallesExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesExamenRel
     *
     * @return RhuExamen
     */
    public function addPagosExamenesDetallesExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesExamenRel)
    {
        $this->pagosExamenesDetallesExamenRel[] = $pagosExamenesDetallesExamenRel;

        return $this;
    }

    /**
     * Remove pagosExamenesDetallesExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesExamenRel
     */
    public function removePagosExamenesDetallesExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesExamenRel)
    {
        $this->pagosExamenesDetallesExamenRel->removeElement($pagosExamenesDetallesExamenRel);
    }

    /**
     * Get pagosExamenesDetallesExamenRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosExamenesDetallesExamenRel()
    {
        return $this->pagosExamenesDetallesExamenRel;
    }
}
