<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_control_retenciones_comentarios")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbControlRetencionesComentariosRepository")
 */
class CtbControlRetencionesComentarios
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_control_retenciones_comentario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoControlRetencionesComentarioPk;
    
    /**
     * @ORM\Column(name="codigo_control_retenciones_fk", type="integer")
     */     
    private $codigoControlRetencionesFk;    
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;    

    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer", nullable=true)
     */
    private $codigoUsuarioFk;    
    
    /**
     * @ORM\Column(name="comentario", type="string", length=200, nullable=true)
     */    
    private $comentario;        
    
     /**
     * @ORM\ManyToOne(targetEntity="CtbControlRetenciones", inversedBy="CtbControlRetencionesComentarios")
     * @ORM\JoinColumn(name="codigo_control_retenciones_fk", referencedColumnName="codigo_control_retenciones_pk")
     */
    protected $ctbControlRetencionesRel;      
    
     /**
     * @ORM\ManyToOne(targetEntity="Brasa\SeguridadBundle\Entity\User", inversedBy="CtbControlRetencionesComentarios")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
     */
    protected $usuarioRel;    
    

    /**
     * Get codigoControlRetencionesComentarioPk
     *
     * @return integer 
     */
    public function getCodigoControlRetencionesComentarioPk()
    {
        return $this->codigoControlRetencionesComentarioPk;
    }

    /**
     * Set codigoControlRetencionesFk
     *
     * @param integer $codigoControlRetencionesFk
     */
    public function setCodigoControlRetencionesFk($codigoControlRetencionesFk)
    {
        $this->codigoControlRetencionesFk = $codigoControlRetencionesFk;
    }

    /**
     * Get codigoControlRetencionesFk
     *
     * @return integer 
     */
    public function getCodigoControlRetencionesFk()
    {
        return $this->codigoControlRetencionesFk;
    }

    /**
     * Set fechaUltimoComentario
     *
     * @param datetime $fechaUltimoComentario
     */
    public function setFechaUltimoComentario($fechaUltimoComentario)
    {
        $this->fechaUltimoComentario = $fechaUltimoComentario;
    }

    /**
     * Get fechaUltimoComentario
     *
     * @return datetime 
     */
    public function getFechaUltimoComentario()
    {
        return $this->fechaUltimoComentario;
    }

    /**
     * Set codigoUsuarioFk
     *
     * @param integer $codigoUsuarioFk
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;
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
     * Set comentario
     *
     * @param string $comentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }

    /**
     * Get comentario
     *
     * @return string 
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set ctbControlRetencionesRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbControlRetenciones $ctbControlRetencionesRel
     */
    public function setCtbControlRetencionesRel(\Brasa\ContabilidadBundle\Entity\CtbControlRetenciones $ctbControlRetencionesRel)
    {
        $this->ctbControlRetencionesRel = $ctbControlRetencionesRel;
    }

    /**
     * Get ctbControlRetencionesRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbControlRetenciones 
     */
    public function getCtbControlRetencionesRel()
    {
        return $this->ctbControlRetencionesRel;
    }

    /**
     * Set usuarioRel
     *
     * @param Brasa\SeguridadBundle\Entity\User $usuarioRel
     */
    public function setUsuarioRel(\Brasa\SeguridadBundle\Entity\User $usuarioRel)
    {
        $this->usuarioRel = $usuarioRel;
    }

    /**
     * Get usuarioRel
     *
     * @return Brasa\SeguridadBundle\Entity\User 
     */
    public function getUsuarioRel()
    {
        return $this->usuarioRel;
    }

    /**
     * Set fecha
     *
     * @param datetime $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Get fecha
     *
     * @return datetime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}
