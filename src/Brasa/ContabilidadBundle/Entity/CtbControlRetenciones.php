<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_control_retenciones")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbControlRetencionesRepository")
 */
class CtbControlRetenciones
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_control_retenciones_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoControlRetencionesPk;    
    
    /**
     * @ORM\Column(name="annio", type="integer")
     */
    private $annio;

    /**
     * @ORM\Column(name="mes", type="smallint")
     */
    private $mes;  
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     * @Assert\NotBlank
     */    
    private $codigoTerceroFk;   
    
    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0; 
    
    /**
     * @ORM\Column(name="estado_recibido", type="boolean")
     */    
    private $estadoRecibido = 0;     
    
    /**
     * @ORM\Column(name="codigo_usuario_recibe_fk", type="integer", nullable=true)
     */
    private $codigoUsuarioRecibeFk;    
    
    /**
     * @ORM\Column(name="fecha_recibido", type="datetime", nullable=true)
     */    
    private $fechaRecibido;     
    
    /**
     * @ORM\Column(name="fecha_ultimo_comentario", type="datetime", nullable=true)
     */    
    private $fechaUltimoComentario;
       

     /**
     * @ORM\ManyToOne(targetEntity="Brasa\SeguridadBundle\Entity\User", inversedBy="CtbControlRetenciones")
     * @ORM\JoinColumn(name="codigo_usuario_recibe_fk", referencedColumnName="id")
     */
    protected $usuarioRecibeRel;


    /**
     * Get codigoControlRetencionesPk
     *
     * @return integer 
     */
    public function getCodigoControlRetencionesPk()
    {
        return $this->codigoControlRetencionesPk;
    }

    /**
     * Set annio
     *
     * @param integer $annio
     */
    public function setAnnio($annio)
    {
        $this->annio = $annio;
    }

    /**
     * Get annio
     *
     * @return integer 
     */
    public function getAnnio()
    {
        return $this->annio;
    }

    /**
     * Set mes
     *
     * @param smallint $mes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    /**
     * Get mes
     *
     * @return smallint 
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
    }

    /**
     * Get codigoTerceroFk
     *
     * @return integer 
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * Set valor
     *
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Get valor
     *
     * @return float 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set estadoRecibido
     *
     * @param boolean $estadoRecibido
     */
    public function setEstadoRecibido($estadoRecibido)
    {
        $this->estadoRecibido = $estadoRecibido;
    }

    /**
     * Get estadoRecibido
     *
     * @return boolean 
     */
    public function getEstadoRecibido()
    {
        return $this->estadoRecibido;
    }

    /**
     * Set codigoUsuarioRecibeFk
     *
     * @param integer $codigoUsuarioRecibeFk
     */
    public function setCodigoUsuarioRecibeFk($codigoUsuarioRecibeFk)
    {
        $this->codigoUsuarioRecibeFk = $codigoUsuarioRecibeFk;
    }

    /**
     * Get codigoUsuarioRecibeFk
     *
     * @return integer 
     */
    public function getCodigoUsuarioRecibeFk()
    {
        return $this->codigoUsuarioRecibeFk;
    }

    /**
     * Set terceroRel
     *
     * @param Brasa\GeneralBundle\Entity\GenTerceros $terceroRel
     */
    public function setTerceroRel(\Brasa\GeneralBundle\Entity\GenTerceros $terceroRel)
    {
        $this->terceroRel = $terceroRel;
    }

    /**
     * Get terceroRel
     *
     * @return Brasa\GeneralBundle\Entity\GenTerceros 
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * Set usuarioRecibeRel
     *
     * @param Brasa\SeguridadBundle\Entity\User $usuarioRecibeRel
     */
    public function setUsuarioRecibeRel(\Brasa\SeguridadBundle\Entity\User $usuarioRecibeRel)
    {
        $this->usuarioRecibeRel = $usuarioRecibeRel;
    }

    /**
     * Get usuarioRecibeRel
     *
     * @return Brasa\SeguridadBundle\Entity\User 
     */
    public function getUsuarioRecibeRel()
    {
        return $this->usuarioRecibeRel;
    }

    /**
     * Set fechaRecibido
     *
     * @param date $fechaRecibido
     */
    public function setFechaRecibido($fechaRecibido)
    {
        $this->fechaRecibido = $fechaRecibido;
    }

    /**
     * Get fechaRecibido
     *
     * @return date 
     */
    public function getFechaRecibido()
    {
        return $this->fechaRecibido;
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
}
