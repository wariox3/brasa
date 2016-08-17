<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_centro_costo")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbCentroCostoRepository")
 */
class CtbCentroCosto
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_costo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoCentroCostoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;      
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;     
    
    /**
     * @ORM\OneToMany(targetEntity="CtbAsientoDetalle", mappedBy="centroCostoRel")
     */
    protected $asientosDetallesCentroCostoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="centroCostoContabilidadRel")
     */
    protected $rhuEmpleadosCentroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurPuesto", mappedBy="centroCostoContabilidadRel")
     */
    protected $turPuestosCentroCostoRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->asientosDetallesCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCentroCostoPk
     *
     * @return integer
     */
    public function getCodigoCentroCostoPk()
    {
        return $this->codigoCentroCostoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbCentroCosto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add asientosDetallesCentroCostoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel
     *
     * @return CtbCentroCosto
     */
    public function addAsientosDetallesCentroCostoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel)
    {
        $this->asientosDetallesCentroCostoRel[] = $asientosDetallesCentroCostoRel;

        return $this;
    }

    /**
     * Remove asientosDetallesCentroCostoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel
     */
    public function removeAsientosDetallesCentroCostoRel(\Brasa\ContabilidadBundle\Entity\CtbAsientoDetalle $asientosDetallesCentroCostoRel)
    {
        $this->asientosDetallesCentroCostoRel->removeElement($asientosDetallesCentroCostoRel);
    }

    /**
     * Get asientosDetallesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsientosDetallesCentroCostoRel()
    {
        return $this->asientosDetallesCentroCostoRel;
    }

    /**
     * Add rhuEmpleadosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCentroCostoRel
     *
     * @return CtbCentroCosto
     */
    public function addRhuEmpleadosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCentroCostoRel)
    {
        $this->rhuEmpleadosCentroCostoRel[] = $rhuEmpleadosCentroCostoRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCentroCostoRel
     */
    public function removeRhuEmpleadosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCentroCostoRel)
    {
        $this->rhuEmpleadosCentroCostoRel->removeElement($rhuEmpleadosCentroCostoRel);
    }

    /**
     * Get rhuEmpleadosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosCentroCostoRel()
    {
        return $this->rhuEmpleadosCentroCostoRel;
    }

    /**
     * Add turPuestosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $turPuestosCentroCostoRel
     *
     * @return CtbCentroCosto
     */
    public function addTurPuestosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurPuesto $turPuestosCentroCostoRel)
    {
        $this->turPuestosCentroCostoRel[] = $turPuestosCentroCostoRel;

        return $this;
    }

    /**
     * Remove turPuestosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $turPuestosCentroCostoRel
     */
    public function removeTurPuestosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurPuesto $turPuestosCentroCostoRel)
    {
        $this->turPuestosCentroCostoRel->removeElement($turPuestosCentroCostoRel);
    }

    /**
     * Get turPuestosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurPuestosCentroCostoRel()
    {
        return $this->turPuestosCentroCostoRel;
    }

    /**
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return CtbCentroCosto
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }
}
