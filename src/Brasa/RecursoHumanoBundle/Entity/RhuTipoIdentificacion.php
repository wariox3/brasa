<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_tipo_identificacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuTipoIdentificacionRepository")
 */
class RhuTipoIdentificacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_identificacion_pk", type="string", length=1)
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
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="tipoIdentificacionRel")
     */
    protected $empleadosTipoIdentificacionRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="tipoIdentificacionRel")
     */
    protected $seleccionesTipoIdentificacionRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTipoIdentificacionPk
     *
     * @return string
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
     * @return RhuTipoIdentificacion
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
     * Add empleadosTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoIdentificacionRel
     *
     * @return RhuTipoIdentificacion
     */
    public function addEmpleadosTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoIdentificacionRel)
    {
        $this->empleadosTipoIdentificacionRel[] = $empleadosTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove empleadosTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoIdentificacionRel
     */
    public function removeEmpleadosTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoIdentificacionRel)
    {
        $this->empleadosTipoIdentificacionRel->removeElement($empleadosTipoIdentificacionRel);
    }

    /**
     * Get empleadosTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosTipoIdentificacionRel()
    {
        return $this->empleadosTipoIdentificacionRel;
    }

    /**
     * Add seleccionesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesTipoIdentificacionRel
     *
     * @return RhuTipoIdentificacion
     */
    public function addSeleccionesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesTipoIdentificacionRel)
    {
        $this->seleccionesTipoIdentificacionRel[] = $seleccionesTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove seleccionesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesTipoIdentificacionRel
     */
    public function removeSeleccionesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesTipoIdentificacionRel)
    {
        $this->seleccionesTipoIdentificacionRel->removeElement($seleccionesTipoIdentificacionRel);
    }

    /**
     * Get seleccionesTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesTipoIdentificacionRel()
    {
        return $this->seleccionesTipoIdentificacionRel;
    }

    /**
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return RhuTipoIdentificacion
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
