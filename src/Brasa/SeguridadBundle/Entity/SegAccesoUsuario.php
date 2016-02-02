<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seg_acceso_usuario")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegAccesoUsuarioRepository")
 */
class SegAccesoUsuario
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_acceso_usuario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccesoUsuarioPk;
    
    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer", nullable=false)
     */    
    private $codigoUsuarioFk;
    
    /**
     * @ORM\Column(name="codigo_acceso_fk", type="integer", nullable=false)
     */    
    private $codigoAccesoFk;
    
    /**
     * @ORM\Column(name="permitir", type="boolean", nullable=false)
     */    
    private $permitir = 0;    

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userAccesoUsuarioRel")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
     */
    protected $usuarioRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="SegAcceso", inversedBy="accesoAccesoUsuarioRel")
     * @ORM\JoinColumn(name="codigo_acceso_fk", referencedColumnName="codigo_acceso_pk")
     */
    protected $accesoRel;

    


    /**
     * Get codigoAccesoUsuarioPk
     *
     * @return integer
     */
    public function getCodigoAccesoUsuarioPk()
    {
        return $this->codigoAccesoUsuarioPk;
    }

    /**
     * Set codigoUsuarioFk
     *
     * @param integer $codigoUsuarioFk
     *
     * @return SegAccesoUsuario
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
     * Set codigoAccesoFk
     *
     * @param integer $codigoAccesoFk
     *
     * @return SegAccesoUsuario
     */
    public function setCodigoAccesoFk($codigoAccesoFk)
    {
        $this->codigoAccesoFk = $codigoAccesoFk;

        return $this;
    }

    /**
     * Get codigoAccesoFk
     *
     * @return integer
     */
    public function getCodigoAccesoFk()
    {
        return $this->codigoAccesoFk;
    }

    /**
     * Set permitir
     *
     * @param boolean $permitir
     *
     * @return SegAccesoUsuario
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

    /**
     * Set usuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usuarioRel
     *
     * @return SegAccesoUsuario
     */
    public function setUsuarioRel(\Brasa\SeguridadBundle\Entity\User $usuarioRel = null)
    {
        $this->usuarioRel = $usuarioRel;

        return $this;
    }

    /**
     * Get usuarioRel
     *
     * @return \Brasa\SeguridadBundle\Entity\User
     */
    public function getUsuarioRel()
    {
        return $this->usuarioRel;
    }

    /**
     * Set accesoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegAcceso $accesoRel
     *
     * @return SegAccesoUsuario
     */
    public function setAccesoRel(\Brasa\SeguridadBundle\Entity\SegAcceso $accesoRel = null)
    {
        $this->accesoRel = $accesoRel;

        return $this;
    }

    /**
     * Get accesoRel
     *
     * @return \Brasa\SeguridadBundle\Entity\SegAcceso
     */
    public function getAccesoRel()
    {
        return $this->accesoRel;
    }
}
