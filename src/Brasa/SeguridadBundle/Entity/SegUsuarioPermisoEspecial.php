<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seg_usuario_permiso_especial")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegUsuarioPermisoEspecialRepository")
 */
class SegUsuarioPermisoEspecial
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_usuario_permiso_especial_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoUsuarioPermisoEspecialPk;
    
    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer", nullable=false)
     */    
    private $codigoUsuarioFk;
    
    /**
     * @ORM\Column(name="codigo_permiso_especial_fk", type="integer", nullable=false)
     */    
    private $codigoPermisoEspecialFk;
    
    /**
     * @ORM\Column(name="permitir", type="boolean", nullable=false)
     */    
    private $permitir = 0;    


    /**
     * Get codigoUsuarioPermisoEspecialPk
     *
     * @return integer
     */
    public function getCodigoUsuarioPermisoEspecialPk()
    {
        return $this->codigoUsuarioPermisoEspecialPk;
    }

    /**
     * Set codigoUsuarioFk
     *
     * @param integer $codigoUsuarioFk
     *
     * @return SegUsuarioPermisoEspecial
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;

        return $this;
    }

    /**
     * Get codigoUsuarioFk
     *
     * @return integer
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * Set codigoPermisoEspecialFk
     *
     * @param integer $codigoPermisoEspecialFk
     *
     * @return SegUsuarioPermisoEspecial
     */
    public function setCodigoPermisoEspecialFk($codigoPermisoEspecialFk)
    {
        $this->codigoPermisoEspecialFk = $codigoPermisoEspecialFk;

        return $this;
    }

    /**
     * Get codigoPermisoEspecialFk
     *
     * @return integer
     */
    public function getCodigoPermisoEspecialFk()
    {
        return $this->codigoPermisoEspecialFk;
    }

    /**
     * Set permitir
     *
     * @param boolean $permitir
     *
     * @return SegUsuarioPermisoEspecial
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
