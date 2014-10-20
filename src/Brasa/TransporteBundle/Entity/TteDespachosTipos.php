<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_despachos_tipos")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteDespachosTiposRepository")
 */
class TteDespachosTipos
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
     * @ORM\OneToMany(targetEntity="TteDespachos", mappedBy="despachoTipoRel")
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
     * @return TteDespachosTipos
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
     * @param \Brasa\TransporteBundle\Entity\TteDespachos $despachosRel
     * @return TteDespachosTipos
     */
    public function addDespachosRel(\Brasa\TransporteBundle\Entity\TteDespachos $despachosRel)
    {
        $this->despachosRel[] = $despachosRel;

        return $this;
    }

    /**
     * Remove despachosRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespachos $despachosRel
     */
    public function removeDespachosRel(\Brasa\TransporteBundle\Entity\TteDespachos $despachosRel)
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
