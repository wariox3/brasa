<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_vehiculo")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteVehiculoRepository")
 */
class TteVehiculo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_vehiculo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVehiculoPk;  
    
    /**
     * @ORM\Column(name="placa", type="string", length=10, nullable=true)
     */    
    private $placa;    

    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="vehiculoRel")
     */
    protected $despachosRel; 

    /**
     * @ORM\OneToMany(targetEntity="TtePlanRecogida", mappedBy="vehiculoRel")
     */
    protected $planesRecogidasRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->despachosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoVehiculoPk
     *
     * @return integer 
     */
    public function getCodigoVehiculoPk()
    {
        return $this->codigoVehiculoPk;
    }

    /**
     * Set placa
     *
     * @param string $placa
     * @return TteVehiculo
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
     * Add despachosRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosRel
     * @return TteVehiculo
     */
    public function addDespachosRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosRel)
    {
        $this->despachosRel[] = $despachosRel;

        return $this;
    }

    /**
     * Remove despachosRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosRel
     */
    public function removeDespachosRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosRel)
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

    /**
     * Add planesRecogidasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePlanRecogida $planesRecogidasRel
     * @return TteVehiculo
     */
    public function addPlanesRecogidasRel(\Brasa\TransporteBundle\Entity\TtePlanRecogida $planesRecogidasRel)
    {
        $this->planesRecogidasRel[] = $planesRecogidasRel;

        return $this;
    }

    /**
     * Remove planesRecogidasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePlanRecogida $planesRecogidasRel
     */
    public function removePlanesRecogidasRel(\Brasa\TransporteBundle\Entity\TtePlanRecogida $planesRecogidasRel)
    {
        $this->planesRecogidasRel->removeElement($planesRecogidasRel);
    }

    /**
     * Get planesRecogidasRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlanesRecogidasRel()
    {
        return $this->planesRecogidasRel;
    }
}
