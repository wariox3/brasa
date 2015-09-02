<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_credito_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCreditoTipoRepository")
 */
class RhuCreditoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credito_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCreditoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="creditoTipoRel")
     */
    protected $creditosCreditoTipoRel;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creditosCreditoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCreditoTipoPk
     *
     * @return integer
     */
    public function getCodigoCreditoTipoPk()
    {
        return $this->codigoCreditoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCreditoTipo
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
     * Add creditosCreditoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoRel
     *
     * @return RhuCreditoTipo
     */
    public function addCreditosCreditoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoRel)
    {
        $this->creditosCreditoTipoRel[] = $creditosCreditoTipoRel;

        return $this;
    }

    /**
     * Remove creditosCreditoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoRel
     */
    public function removeCreditosCreditoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditosCreditoTipoRel)
    {
        $this->creditosCreditoTipoRel->removeElement($creditosCreditoTipoRel);
    }

    /**
     * Get creditosCreditoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreditosCreditoTipoRel()
    {
        return $this->creditosCreditoTipoRel;
    }
}
