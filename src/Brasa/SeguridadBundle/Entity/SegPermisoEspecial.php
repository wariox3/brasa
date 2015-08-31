<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seg_permiso_especial")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegPermisoEspecialRepository")
 */
class SegPermisoEspecial
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_permiso_especial_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPermisoEspecialPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;   
    

    /**
     * Get codigoPermisoEspecialPk
     *
     * @return integer
     */
    public function getCodigoPermisoEspecialPk()
    {
        return $this->codigoPermisoEspecialPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return SegPermisoEspecial
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
     * Set permitir
     *
     * @param boolean $permitir
     *
     * @return SegPermisoEspecial
     */
    public function setPermitir($permitir)
    {
        $this->permitir = $permitir;

        return $this;
    }

    /**
     * Get permitir
     *
     * @return boolean
     */
    public function getPermitir()
    {
        return $this->permitir;
    }
}
