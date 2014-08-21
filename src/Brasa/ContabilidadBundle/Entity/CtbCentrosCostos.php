<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_centros_costos")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbCentrosCostosRepository")
 */
class CtbCentrosCostos
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_costos_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoCentroCostosPk;
    
    /**
     * @ORM\Column(name="nombre_centro_costos", type="string", length=100, nullable=true)
     */    
    private $nombreCentroCostos;      
    


    /**
     * Get codigoCentroCostosPk
     *
     * @return integer 
     */
    public function getCodigoCentroCostosPk()
    {
        return $this->codigoCentroCostosPk;
    }

    /**
     * Set nombreCentroCostos
     *
     * @param string $nombreCentroCostos
     */
    public function setNombreCentroCostos($nombreCentroCostos)
    {
        $this->nombreCentroCostos = $nombreCentroCostos;
    }

    /**
     * Get nombreCentroCostos
     *
     * @return string 
     */
    public function getNombreCentroCostos()
    {
        return $this->nombreCentroCostos;
    }
}
