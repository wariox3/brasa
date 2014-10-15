<?php

namespace Brasa\LogisticaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log_despachos_tipos")
 * @ORM\Entity(repositoryClass="Brasa\LogisticaBundle\Repository\LogDespachosTiposRepository")
 */
class LogDespachosTipos
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_despacho_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDespachoTipoPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;   
    
    /**
     * @ORM\OneToMany(targetEntity="LogDespachos", mappedBy="despachoTipoRel")
     */
    protected $despachosRel;    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->despachosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDespachoTipoPk
     *
     * @return integer 
     */
    public function getCodigoDespachoTipoPk()
    {
        return $this->codigoDespachoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return LogDespachosTipos
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
     * Add despachosRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogDespachos $despachosRel
     * @return LogDespachosTipos
     */
    public function addDespachosRel(\Brasa\LogisticaBundle\Entity\LogDespachos $despachosRel)
    {
        $this->despachosRel[] = $despachosRel;

        return $this;
    }

    /**
     * Remove despachosRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogDespachos $despachosRel
     */
    public function removeDespachosRel(\Brasa\LogisticaBundle\Entity\LogDespachos $despachosRel)
    {
        $this->despachosRel->removeElement($despachosRel);
    }

    /**
     * Get despachosRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDespachosRel()
    {
        return $this->despachosRel;
    }
}
