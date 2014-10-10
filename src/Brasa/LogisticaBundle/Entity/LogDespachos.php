<?php

namespace Brasa\LogisticaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log_despachos")
 * @ORM\Entity(repositoryClass="Brasa\LogisticaBundle\Repository\LogDespachosRepository")
 */
class LogDespachos
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_despacho_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDespachoPk;
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;  

    /**
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = 0;      
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;     
    
    
    /**
     * @ORM\OneToMany(targetEntity="LogGuias", mappedBy="despachoRel")
     */
    protected $guiasDetallesRel;     

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->guiasDetallesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDespachoPk
     *
     * @return integer 
     */
    public function getCodigoDespachoPk()
    {
        return $this->codigoDespachoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return LogDespachos
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     * @return LogDespachos
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean 
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return LogDespachos
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Add guiasDetallesRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogGuias $guiasDetallesRel
     * @return LogDespachos
     */
    public function addGuiasDetallesRel(\Brasa\LogisticaBundle\Entity\LogGuias $guiasDetallesRel)
    {
        $this->guiasDetallesRel[] = $guiasDetallesRel;

        return $this;
    }

    /**
     * Remove guiasDetallesRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogGuias $guiasDetallesRel
     */
    public function removeGuiasDetallesRel(\Brasa\LogisticaBundle\Entity\LogGuias $guiasDetallesRel)
    {
        $this->guiasDetallesRel->removeElement($guiasDetallesRel);
    }

    /**
     * Get guiasDetallesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasDetallesRel()
    {
        return $this->guiasDetallesRel;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     * @return LogDespachos
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean 
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }
}
