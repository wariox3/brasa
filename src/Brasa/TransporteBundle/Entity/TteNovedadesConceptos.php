<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_novedades_conceptos")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteNovedadesConceptosRepository")
 */
class TteNovedadesConceptos
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNovedadConceptoPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteNovedades", mappedBy="novedadConceptoRel")
     */
    protected $novedadesRel;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->novedadesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoNovedadConceptoPk
     *
     * @return integer 
     */
    public function getCodigoNovedadConceptoPk()
    {
        return $this->codigoNovedadConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TteNovedadesConceptos
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
     * Add novedadesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteNovedades $novedadesRel
     * @return TteNovedadesConceptos
     */
    public function addNovedadesRel(\Brasa\TransporteBundle\Entity\TteNovedades $novedadesRel)
    {
        $this->novedadesRel[] = $novedadesRel;

        return $this;
    }

    /**
     * Remove novedadesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteNovedades $novedadesRel
     */
    public function removeNovedadesRel(\Brasa\TransporteBundle\Entity\TteNovedades $novedadesRel)
    {
        $this->novedadesRel->removeElement($novedadesRel);
    }

    /**
     * Get novedadesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNovedadesRel()
    {
        return $this->novedadesRel;
    }
}
