<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seg_roles")
 * @ORM\Entity
 */
class SegRoles
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_rol_pk", type="string", length=50)     
     */
    private $codigoRolPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;   
    
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="rolRel")
     */
    protected $usersRolRel;    

    /**
     * Set codigoRolPk
     *
     * @param string $codigoRolPk
     *
     * @return SegRoles
     */
    public function setCodigoRolPk($codigoRolPk)
    {
        $this->codigoRolPk = $codigoRolPk;

        return $this;
    }

    /**
     * Get codigoRolPk
     *
     * @return string
     */
    public function getCodigoRolPk()
    {
        return $this->codigoRolPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return SegRoles
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
        $this->usersRolRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add usersRolRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usersRolRel
     *
     * @return SegRoles
     */
    public function addUsersRolRel(\Brasa\SeguridadBundle\Entity\User $usersRolRel)
    {
        $this->usersRolRel[] = $usersRolRel;

        return $this;
    }

    /**
     * Remove usersRolRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usersRolRel
     */
    public function removeUsersRolRel(\Brasa\SeguridadBundle\Entity\User $usersRolRel)
    {
        $this->usersRolRel->removeElement($usersRolRel);
    }

    /**
     * Get usersRolRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsersRolRel()
    {
        return $this->usersRolRel;
    }
}
