<?php

namespace Brasa\LogisticaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log_tipos_servicio")
 * @ORM\Entity(repositoryClass="Brasa\LogisticaBundle\Repository\LogTiposServicioRepository")
 */
class LogTiposServicio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_servicio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoServicioPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
 
    /**
     * @ORM\OneToMany(targetEntity="LogGuias", mappedBy="tipoServicioRel")
     */
    protected $guiasRel;     

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->guiasRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTipoServicioPk
     *
     * @return integer 
     */
    public function getCodigoTipoServicioPk()
    {
        return $this->codigoTipoServicioPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return LogTiposServicio
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
     * Add guiasRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogGuias $guiasRel
     * @return LogTiposServicio
     */
    public function addGuiasRel(\Brasa\LogisticaBundle\Entity\LogGuias $guiasRel)
    {
        $this->guiasRel[] = $guiasRel;

        return $this;
    }

    /**
     * Remove guiasRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogGuias $guiasRel
     */
    public function removeGuiasRel(\Brasa\LogisticaBundle\Entity\LogGuias $guiasRel)
    {
        $this->guiasRel->removeElement($guiasRel);
    }

    /**
     * Get guiasRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasRel()
    {
        return $this->guiasRel;
    }
}
