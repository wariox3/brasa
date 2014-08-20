<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documentos_subtipos")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentosSubtiposRepository")
 */
class InvDocumentosSubtipos
{   
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_subtipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoDocumentoSubtipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */        
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="InvDocumentos", mappedBy="documentoSubtipoRel")
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
     * Get codigoDocumentoSubtipoPk
     *
     * @return integer 
     */
    public function getCodigoDocumentoSubtipoPk()
    {
        return $this->codigoDocumentoSubtipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return InvDocumentosSubtipos
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
     * Add documentosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosRel
     * @return InvDocumentosSubtipos
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
