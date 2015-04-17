<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_comprobante_contable")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbComprobanteContableRepository")
 */
class CtbComprobanteContable
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_comprobante_contable_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoComprobanteContablePk;
    
    /**
     * @ORM\Column(name="nombre_comprobante_contable", type="string", length=100, nullable=true)
     */    
    private $nombreComprobanteContable;      
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvDocumento", mappedBy="comprobanteContableRel")
     */
    protected $documentosRel;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documentosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoComprobanteContablePk
     *
     * @return integer
     */
    public function getCodigoComprobanteContablePk()
    {
        return $this->codigoComprobanteContablePk;
    }

    /**
     * Set nombreComprobanteContable
     *
     * @param string $nombreComprobanteContable
     *
     * @return CtbComprobanteContable
     */
    public function setNombreComprobanteContable($nombreComprobanteContable)
    {
        $this->nombreComprobanteContable = $nombreComprobanteContable;

        return $this;
    }

    /**
     * Get nombreComprobanteContable
     *
     * @return string
     */
    public function getNombreComprobanteContable()
    {
        return $this->nombreComprobanteContable;
    }

    /**
     * Add documentosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentosRel
     *
     * @return CtbComprobanteContable
     */
    public function addDocumentosRel(\Brasa\InventarioBundle\Entity\InvDocumento $documentosRel)
    {
        $this->documentosRel[] = $documentosRel;

        return $this;
    }

    /**
     * Remove documentosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentosRel
     */
    public function removeDocumentosRel(\Brasa\InventarioBundle\Entity\InvDocumento $documentosRel)
    {
        $this->documentosRel->removeElement($documentosRel);
    }

    /**
     * Get documentosRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentosRel()
    {
        return $this->documentosRel;
    }
}
