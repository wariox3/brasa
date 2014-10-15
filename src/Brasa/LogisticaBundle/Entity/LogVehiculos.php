<?php

namespace Brasa\LogisticaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log_vehiculos")
 * @ORM\Entity(repositoryClass="Brasa\LogisticaBundle\Repository\LogVehiculosRepository")
 */
class LogVehiculos
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_vehiculo_pk", type="string", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVehiculoPk;  
    
    /**
     * @ORM\Column(name="placa", type="string", length=10, nullable=true)
     */    
    private $placa;    

    /**
     * @ORM\OneToMany(targetEntity="LogDespachos", mappedBy="vehiculoRel")
     */
    protected $despachosRel; 

    /**
     * Get codigoVehiculoPk
     *
     * @return string 
     */
    public function getCodigoVehiculoPk()
    {
        return $this->codigoVehiculoPk;
    }

    /**
     * Set placa
     *
     * @param string $placa
     * @return LogVehiculos
     */
    public function setPlaca($placa)
    {
        $this->placa = $placa;

        return $this;
    }

    /**
     * Get placa
     *
     * @return string 
     */
    public function getPlaca()
    {
        return $this->placa;
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
     * @return LogVehiculos
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
