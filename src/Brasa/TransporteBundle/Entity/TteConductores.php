<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_conductores")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteConductoresRepository")
 */
class TteConductores
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_conductor_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoConductorPk;  
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50)
     */
    private $nombreCorto; 
    
    /**
     * @ORM\OneToMany(targetEntity="TteDespachos", mappedBy="conductorRel")
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
     * Get codigoConductorPk
     *
     * @return integer 
     */
    public function getCodigoConductorPk()
    {
        return $this->codigoConductorPk;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     * @return TteConductores
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string 
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Add despachosRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespachos $despachosRel
     * @return TteConductores
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
