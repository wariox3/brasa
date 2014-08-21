<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_cierres_mes_items")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvCierresMesItemsRepository")
 */
class InvCierresMesItems
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_mes_item_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreMesItemPk;
    
    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer", nullable=true)
     */    
    private $codigoCierreMesFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="InvCierresMes", inversedBy="InvCierresMesItems")
     * @ORM\JoinColumn(name="codigo_cierre_mes_fk", referencedColumnName="codigo_cierre_mes_pk")
     */
    protected $cierreMesRel;    


    /**
     * Get codigoCierreMesItemPk
     *
     * @return integer 
     */
    public function getCodigoCierreMesItemPk()
    {
        return $this->codigoCierreMesItemPk;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     * @return InvCierresMesItems
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk)
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;

        return $this;
    }

    /**
     * Get codigoCierreMesFk
     *
     * @return integer 
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }

    /**
     * Set cierreMesRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvCierresMes $cierreMesRel
     * @return InvCierresMesItems
     */
    public function setCierreMesRel(\Brasa\InventarioBundle\Entity\InvCierresMes $cierreMesRel = null)
    {
        $this->cierreMesRel = $cierreMesRel;

        return $this;
    }

    /**
     * Get cierreMesRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvCierresMes 
     */
    public function getCierreMesRel()
    {
        return $this->cierreMesRel;
    }
}
