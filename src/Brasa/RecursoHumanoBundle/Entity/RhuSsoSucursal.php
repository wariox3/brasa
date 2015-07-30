<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_sucursal")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoSucursalRepository")
 */
class RhuSsoSucursal
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sucursal_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSucursalPk;   
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre; 

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=10, nullable=true)
     */    
    private $codigoInterface;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSsoPeriodoDetalle", mappedBy="ssoSucursalRel")
     */
    protected $ssoPeriodosDetallesSsoSucursalRel;     

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ssoPeriodosDetallesSsoSucursalRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSucursalPk
     *
     * @return integer
     */
    public function getCodigoSucursalPk()
    {
        return $this->codigoSucursalPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSsoSucursal
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
     * @return RhuSsoSucursal
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
     * Add ssoPeriodosDetallesSsoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoSucursalRel
     *
     * @return RhuSsoSucursal
     */
    public function addSsoPeriodosDetallesSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoSucursalRel)
    {
        $this->ssoPeriodosDetallesSsoSucursalRel[] = $ssoPeriodosDetallesSsoSucursalRel;

        return $this;
    }

    /**
     * Remove ssoPeriodosDetallesSsoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoSucursalRel
     */
    public function removeSsoPeriodosDetallesSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoSucursalRel)
    {
        $this->ssoPeriodosDetallesSsoSucursalRel->removeElement($ssoPeriodosDetallesSsoSucursalRel);
    }

    /**
     * Get ssoPeriodosDetallesSsoSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoPeriodosDetallesSsoSucursalRel()
    {
        return $this->ssoPeriodosDetallesSsoSucursalRel;
    }
}
