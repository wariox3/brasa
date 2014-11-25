<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_vehiculos")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteVehiculosRepository")
 */
class TteVehiculos
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
     * @ORM\OneToMany(targetEntity="TteDespachos", mappedBy="vehiculoRel")
     */
    protected $despachosRel; 

    /**
     * @ORM\OneToMany(targetEntity="TtePlanesRecogidas", mappedBy="vehiculoRel")
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
     * @return TteVehiculos
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
     * @param \Brasa\TransporteBundle\Entity\TteDespachos $despachosRel
     * @return TteVehiculos
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

    /**
     * Add planesRecogidasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePlanesRecogidas $planesRecogidasRel
     * @return TteVehiculos
     */
    public function addPlanesRecogidasRel(\Brasa\TransporteBundle\Entity\TtePlanesRecogidas $planesRecogidasRel)
    {
        $this->planesRecogidasRel[] = $planesRecogidasRel;

        return $this;
    }

    /**
     * Remove planesRecogidasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePlanesRecogidas $planesRecogidasRel
     */
    public function removePlanesRecogidasRel(\Brasa\TransporteBundle\Entity\TtePlanesRecogidas $planesRecogidasRel)
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
