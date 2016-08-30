<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_centro_costo")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbCentroCostoRepository")
 */
class CtbCentroCosto
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_costo_pk", type="string", length=20)
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoCentroCostoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;      
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;                 
    


    /**
     * Get codigoCentroCostoPk
     *
     * @return string
     */
    public function getCodigoCentroCostoPk()
    {
        return $this->codigoCentroCostoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbCentroCosto
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
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return CtbCentroCosto
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }
}
