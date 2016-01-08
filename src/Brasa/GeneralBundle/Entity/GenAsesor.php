<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_asesor")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenAsesorRepository")
 */
class GenAsesor
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_asesor_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoAsesorPk;
    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */      
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="GenTercero", mappedBy="asesorRel")
     */
    protected $tercerosRel;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tercerosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAsesorPk
     *
     * @return integer
     */
    public function getCodigoAsesorPk()
    {
        return $this->codigoAsesorPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenAsesor
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
     * Add tercerosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $tercerosRel
     *
     * @return GenAsesor
     */
    public function addTercerosRel(\Brasa\GeneralBundle\Entity\GenTercero $tercerosRel)
    {
        $this->tercerosRel[] = $tercerosRel;

        return $this;
    }

    /**
     * Remove tercerosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $tercerosRel
     */
    public function removeTercerosRel(\Brasa\GeneralBundle\Entity\GenTercero $tercerosRel)
    {
        $this->tercerosRel->removeElement($tercerosRel);
    }

    /**
     * Get tercerosRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTercerosRel()
    {
        return $this->tercerosRel;
    }
}
