<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_comprobantes_contables")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbComprobantesContablesRepository")
 */
class CtbComprobantesContables
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
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvDocumentos", mappedBy="comprobanteContableRel")
     */
    protected $documentosRel;

    public function __construct()
    {
        $this->documentosRel = new ArrayCollection();
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
     */
    public function setNombreComprobanteContable($nombreComprobanteContable)
    {
        $this->nombreComprobanteContable = $nombreComprobanteContable;
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
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosRel
     * @return CtbComprobantesContables
     */
    public function addDocumentosRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosRel)
    {
        $this->documentosRel[] = $documentosRel;

        return $this;
    }

    /**
     * Remove documentosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosRel
     */
    public function removeDocumentosRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosRel)
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
