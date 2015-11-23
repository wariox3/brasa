<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_tipo_identificacion")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenTipoIdentificacionRepository")
 */
class GenTipoIdentificacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_identificacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoIdentificacionPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=2, nullable=true)
     */    
    private $codigoInterface;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="tipoIdentificacionRel")
     */
    protected $rhuEmpleadosTipoIdentificacionRel;    

    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuSeleccion", mappedBy="tipoIdentificacionRel")
     */
    protected $rhuSeleccionesTipoIdentificacionRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\ContabilidadBundle\Entity\CtbTercero", mappedBy="tipoIdentificacionRel")
     */
    protected $cbtTercerosTipoIdentificacionRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rhuEmpleadosTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhuSeleccionesTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cbtTercerosTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTipoIdentificacionPk
     *
     * @return integer
     */
    public function getCodigoTipoIdentificacionPk()
    {
        return $this->codigoTipoIdentificacionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenTipoIdentificacion
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
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return GenTipoIdentificacion
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

    /**
     * Add rhuEmpleadosTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhuEmpleadosTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel)
    {
        $this->rhuEmpleadosTipoIdentificacionRel[] = $rhuEmpleadosTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel
     */
    public function removeRhuEmpleadosTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel)
    {
        $this->rhuEmpleadosTipoIdentificacionRel->removeElement($rhuEmpleadosTipoIdentificacionRel);
    }

    /**
     * Get rhuEmpleadosTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosTipoIdentificacionRel()
    {
        return $this->rhuEmpleadosTipoIdentificacionRel;
    }

    /**
     * Add rhuSeleccionesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhuSeleccionesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel)
    {
        $this->rhuSeleccionesTipoIdentificacionRel[] = $rhuSeleccionesTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhuSeleccionesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel
     */
    public function removeRhuSeleccionesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel)
    {
        $this->rhuSeleccionesTipoIdentificacionRel->removeElement($rhuSeleccionesTipoIdentificacionRel);
    }

    /**
     * Get rhuSeleccionesTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuSeleccionesTipoIdentificacionRel()
    {
        return $this->rhuSeleccionesTipoIdentificacionRel;
    }

    /**
     * Add cbtTercerosTipoIdentificacionRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addCbtTercerosTipoIdentificacionRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel)
    {
        $this->cbtTercerosTipoIdentificacionRel[] = $cbtTercerosTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove cbtTercerosTipoIdentificacionRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel
     */
    public function removeCbtTercerosTipoIdentificacionRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel)
    {
        $this->cbtTercerosTipoIdentificacionRel->removeElement($cbtTercerosTipoIdentificacionRel);
    }

    /**
     * Get cbtTercerosTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCbtTercerosTipoIdentificacionRel()
    {
        return $this->cbtTercerosTipoIdentificacionRel;
    }
}