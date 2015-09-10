<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_subtipo_cotizante")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoSubtipoCotizanteRepository")
 */
class RhuSsoSubtipoCotizante
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_subtipo_cotizante_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSubtipoCotizantePk;   
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="ssoSubtipoCotizanteRel")
     */
    protected $empleadosSsoSubtipoCotizanteRel;     
    

    /**
     * Get codigoSubtipoCotizantePk
     *
     * @return integer
     */
    public function getCodigoSubtipoCotizantePk()
    {
        return $this->codigoSubtipoCotizantePk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSsoSubtipoCotizante
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
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosSsoSubtipoCotizanteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add empleadosSsoSubtipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoSubtipoCotizanteRel
     *
     * @return RhuSsoSubtipoCotizante
     */
    public function addEmpleadosSsoSubtipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoSubtipoCotizanteRel)
    {
        $this->empleadosSsoSubtipoCotizanteRel[] = $empleadosSsoSubtipoCotizanteRel;

        return $this;
    }

    /**
     * Remove empleadosSsoSubtipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoSubtipoCotizanteRel
     */
    public function removeEmpleadosSsoSubtipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoSubtipoCotizanteRel)
    {
        $this->empleadosSsoSubtipoCotizanteRel->removeElement($empleadosSsoSubtipoCotizanteRel);
    }

    /**
     * Get empleadosSsoSubtipoCotizanteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosSsoSubtipoCotizanteRel()
    {
        return $this->empleadosSsoSubtipoCotizanteRel;
    }
}
