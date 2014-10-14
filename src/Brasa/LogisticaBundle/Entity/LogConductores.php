<?php

namespace Brasa\LogisticaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log_conductores")
 * @ORM\Entity(repositoryClass="Brasa\LogisticaBundle\Repository\LogConductoresRepository")
 */
class LogConductores
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
     * @ORM\OneToMany(targetEntity="LogDespachos", mappedBy="conductorRel")
     */
    protected $despachosRel;    
    
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
     * @return LogConductores
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
     * Constructor
     */
    public function __construct()
    {
        $this->despachosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add despachosRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogDespachos $despachosRel
     * @return LogConductores
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
