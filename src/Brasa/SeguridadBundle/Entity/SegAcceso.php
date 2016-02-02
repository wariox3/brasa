<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seg_acceso")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegAccesoRepository")
 */
class SegAcceso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_acceso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccesoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;   
    

    /**
     * @ORM\OneToMany(targetEntity="SegAccesoUsuario", mappedBy="accesoRel")
     */
    protected $accesoAccesoUsuarioRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accesoAccesoUsuarioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAccesoPk
     *
     * @return integer
     */
    public function getCodigoAccesoPk()
    {
        return $this->codigoAccesoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return SegAcceso
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
     * Add accesoAccesoUsuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegAccesoUsuario $accesoAccesoUsuarioRel
     *
     * @return SegAcceso
     */
    public function addAccesoAccesoUsuarioRel(\Brasa\SeguridadBundle\Entity\SegAccesoUsuario $accesoAccesoUsuarioRel)
    {
        $this->accesoAccesoUsuarioRel[] = $accesoAccesoUsuarioRel;

        return $this;
    }

    /**
     * Remove accesoAccesoUsuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegAccesoUsuario $accesoAccesoUsuarioRel
     */
    public function removeAccesoAccesoUsuarioRel(\Brasa\SeguridadBundle\Entity\SegAccesoUsuario $accesoAccesoUsuarioRel)
    {
        $this->accesoAccesoUsuarioRel->removeElement($accesoAccesoUsuarioRel);
    }

    /**
     * Get accesoAccesoUsuarioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccesoAccesoUsuarioRel()
    {
        return $this->accesoAccesoUsuarioRel;
    }
}
