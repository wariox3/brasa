<?php

namespace Brasa\LogisticaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log_tipos_pago")
 * @ORM\Entity(repositoryClass="Brasa\LogisticaBundle\Repository\LogTiposPagoRepository")
 */
class LogTiposPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoPagoPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
 
    /**
     * @ORM\OneToMany(targetEntity="LogGuias", mappedBy="tipoPagoRel")
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
     * Get codigoTipoPagoPk
     *
     * @return integer 
     */
    public function getCodigoTipoPagoPk()
    {
        return $this->codigoTipoPagoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return LogTiposPago
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
     * @return LogTiposPago
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
