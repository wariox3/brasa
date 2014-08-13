<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_terceros")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenTercerosRepository")
 */
class GenTerceros
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tercero_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTerceroPk;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50)
     */
    private $nombreCorto;

    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvMovimientos", mappedBy="terceroRel")
     */
    protected $movimientosRel;     
    
    public function __construct()
    {        
        $this->movimientosRel = new ArrayCollection();
    }     

    /**
     * Get codigoTerceroPk
     *
     * @return integer 
     */
    public function getCodigoTerceroPk()
    {
        return $this->codigoTerceroPk;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     * @return GenTerceros
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
     * Add movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel
     * @return GenTerceros
     */
    public function addMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel)
    {
        $this->movimientosRel[] = $movimientosRel;

        return $this;
    }

    /**
     * Remove movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel
     */
    public function removeMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel)
    {
        $this->movimientosRel->removeElement($movimientosRel);
    }

    /**
     * Get movimientosRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovimientosRel()
    {
        return $this->movimientosRel;
    }
}
