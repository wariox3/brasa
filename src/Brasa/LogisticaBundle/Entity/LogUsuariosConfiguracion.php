<?php

namespace Brasa\LogisticaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log_usuarios_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\LogisticaBundle\Repository\LogUsuariosConfiguracionRepository")
 */
class LogUsuariosConfiguracion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_usuario_configuracion_pk", type="integer")
     */
    private $codigoUsuarioConfiguracionPk;  
    
    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer")
     */    
    private $codigoUsuarioFk;    
    
    /**
     * @ORM\Column(name="codigo_punto_operacion_fk", type="integer")
     */    
    private $codigoPuntoOperacionFk;        

     /**
     * @ORM\ManyToOne(targetEntity="Brasa\SeguridadBundle\Entity\User", inversedBy="usuariosConfiguracionRel")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
     */
    protected $usuarioRel;

     /**
     * @ORM\ManyToOne(targetEntity="LogPuntosOperacion", inversedBy="usuariosConfiguracionRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionRel;    
    


    /**
     * Get codigoUsuarioConfiguracionPk
     *
     * @return integer 
     */
    public function getCodigoUsuarioConfiguracionPk()
    {
        return $this->codigoUsuarioConfiguracionPk;
    }

    /**
     * Set codigoUsuarioFk
     *
     * @param integer $codigoUsuarioFk
     * @return LogUsuariosConfiguracion
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
     * Set codigoPuntoOperacionFk
     *
     * @param integer $codigoPuntoOperacionFk
     * @return LogUsuariosConfiguracion
     */
    public function setCodigoPuntoOperacionFk($codigoPuntoOperacionFk)
    {
        $this->codigoPuntoOperacionFk = $codigoPuntoOperacionFk;

        return $this;
    }

    /**
     * Get codigoPuntoOperacionFk
     *
     * @return integer 
     */
    public function getCodigoPuntoOperacionFk()
    {
        return $this->codigoPuntoOperacionFk;
    }

    /**
     * Set usuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usuarioRel
     * @return LogUsuariosConfiguracion
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
     * Set puntoOperacionRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogPuntosOperacion $puntoOperacionRel
     * @return LogUsuariosConfiguracion
     */
    public function setPuntoOperacionRel(\Brasa\LogisticaBundle\Entity\LogPuntosOperacion $puntoOperacionRel = null)
    {
        $this->puntoOperacionRel = $puntoOperacionRel;

        return $this;
    }

    /**
     * Get puntoOperacionRel
     *
     * @return \Brasa\LogisticaBundle\Entity\LogPuntosOperacion 
     */
    public function getPuntoOperacionRel()
    {
        return $this->puntoOperacionRel;
    }

    /**
     * Set codigoUsuarioConfiguracionPk
     *
     * @param integer $codigoUsuarioConfiguracionPk
     * @return LogUsuariosConfiguracion
     */
    public function setCodigoUsuarioConfiguracionPk($codigoUsuarioConfiguracionPk)
    {
        $this->codigoUsuarioConfiguracionPk = $codigoUsuarioConfiguracionPk;

        return $this;
    }
}
